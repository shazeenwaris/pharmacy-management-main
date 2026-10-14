<?php

namespace App\Filament\Cashier\Pages;

use App\Models\Customer;
use App\Models\HeldSale;
use App\Models\MedicineBatch;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SalePayment;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PosScreen extends Page
{
    protected static ?string $title = 'Sales / POS';

    protected static ?string $navigationLabel = 'Sales / POS';

    protected static \BackedEnum|string|null $navigationIcon =
        'heroicon-o-shopping-cart';

    protected static \UnitEnum|string|null $navigationGroup = 'Sales';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'pos';

    protected string $view = 'filament.cashier.pages.pos-screen';


    /*
    |--------------------------------------------------------------------------
    | POS STATE
    |--------------------------------------------------------------------------
    */

    public $search = '';

    public $customerId = null;

    public $paymentMethod = 'cash';

    public $discount = 0;

    public $tax = 0;

    public $paidAmount = 0;

    public $cart = [];

    /*
    |--------------------------------------------------------------------------
    | PRINT RECEIPT STATE
    |--------------------------------------------------------------------------
    */

    public ?int $completedSaleId = null;

    public ?int $editingSaleId = null;

    public bool $showPrintReceiptPrompt = false;


    /*
    |--------------------------------------------------------------------------
    | VIEW DATA
    |--------------------------------------------------------------------------
    */

    public function getViewData(): array
    {
        $searchResults = collect();

        if (trim((string) $this->search) !== '') {

            $term = trim((string) $this->search);

            $searchResults = MedicineBatch::query()
                ->with([
                    'medicine.unit',
                ])
                ->where('medicine_batches.status', true)
                ->where('medicine_batches.quantity', '>', 0)

                /*
                |--------------------------------------------------------------------------
                | EXPIRY
                |--------------------------------------------------------------------------
                */

                ->where(function (Builder $query) {

                    $query
                        ->whereHas(
                            'medicine',
                            function (Builder $medicineQuery) {
                                $medicineQuery->where(
                                    'has_expiry',
                                    false
                                );
                            }
                        )
                        ->orWhere(function (Builder $q) {
                            $q
                                ->whereNotNull(
                                    'medicine_batches.expiry_date'
                                )
                                ->whereDate(
                                    'medicine_batches.expiry_date',
                                    '>=',
                                    today()
                                );
                        });
                })

                /*
                |--------------------------------------------------------------------------
                | SEARCH
                |--------------------------------------------------------------------------
                */

                ->where(function (Builder $query) use ($term) {

                    $query
                        ->whereHas(
                            'medicine',
                            function (Builder $medicineQuery) use ($term) {

                                $medicineQuery->where(function (
                                    Builder $q
                                ) use ($term) {

                                    $q
                                        ->where(
                                            'name',
                                            'like',
                                            "%{$term}%"
                                        )
                                        ->orWhere(
                                            'generic_name',
                                            'like',
                                            "%{$term}%"
                                        )
                                        ->orWhere(
                                            'sku',
                                            'like',
                                            "%{$term}%"
                                        )
                                        ->orWhere(
                                            'barcode',
                                            'like',
                                            "%{$term}%"
                                        );
                                });
                            }
                        )
                        ->orWhere(
                            'batch_number',
                            'like',
                            "%{$term}%"
                        );
                })

                /*
                |--------------------------------------------------------------------------
                | FEFO
                |--------------------------------------------------------------------------
                */

                ->orderByRaw(
                    'CASE
                        WHEN expiry_date IS NULL THEN 1
                        ELSE 0
                     END'
                )
                ->orderBy('expiry_date')
                ->orderBy('id')
                ->limit(15)
                ->get();
        }

        return [

            /*
            |--------------------------------------------------------------------------
            | CUSTOMERS
            |--------------------------------------------------------------------------
            */

            'customers' => Customer::query()
                ->where('status', true)
                ->orderBy('name')
                ->get([
                    'id',
                    'name',
                    'phone',
                ]),

            /*
            |--------------------------------------------------------------------------
            | SEARCH RESULTS
            |--------------------------------------------------------------------------
            */

            'searchResults' => $searchResults,

            /*
            |--------------------------------------------------------------------------
            | HELD SALES
            |--------------------------------------------------------------------------
            */

            'heldSales' => HeldSale::query()
                ->where('status', 'held')
                ->where('user_id', auth()->id())
                ->with('customer')
                ->latest()
                ->get(),

            /*
            |--------------------------------------------------------------------------
            | TOTALS
            |--------------------------------------------------------------------------
            */

            'totals' => $this->calculateTotals(),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | APPLY SELLABLE BATCH RULES
    |--------------------------------------------------------------------------
    */

    protected function sellableBatchQuery(
        Builder $query
    ): Builder {

        return $query
            ->where('status', true)
            ->where('quantity', '>', 0)

            ->where(function (Builder $query) {

                /*
                |--------------------------------------------------------------------------
                | NON-EXPIRY PRODUCT
                |--------------------------------------------------------------------------
                */

                $query
                    ->whereHas(
                        'medicine',
                        function (Builder $medicineQuery) {
                            $medicineQuery->where(
                                'has_expiry',
                                false
                            );
                        }
                    )

                    /*
                    |--------------------------------------------------------------------------
                    | EXPIRY PRODUCT
                    |--------------------------------------------------------------------------
                    */

                    ->orWhere(function (Builder $q) {

                        $q
                            ->whereNotNull('expiry_date')
                            ->whereDate(
                                'expiry_date',
                                '>=',
                                today()
                            );
                    });
            });
    }


     /*
    |--------------------------------------------------------------------------
    | SCAN BARCODE
    |--------------------------------------------------------------------------
    */

    public function scanBarcode(): void
    {
        $term = trim((string) $this->search);

        if ($term === '') {
            return;
        }

        $query = MedicineBatch::query()
            ->with([
                'medicine.unit',
            ]);

        $this->sellableBatchQuery($query);

        $batch = $query
            ->where(function (Builder $query) use ($term) {

                $query
                    ->whereHas(
                        'medicine',
                        function (Builder $medicineQuery) use ($term) {

                            $medicineQuery
                                ->where(
                                    'barcode',
                                    $term
                                )
                                ->orWhere(
                                    'sku',
                                    $term
                                );
                        }
                    )
                    ->orWhere(
                        'batch_number',
                        $term
                    );
            })
            ->orderByRaw(
                'CASE
                    WHEN expiry_date IS NULL THEN 1
                    ELSE 0
                 END'
            )
            ->orderBy('expiry_date')
            ->orderBy('id')
            ->first();

        if (! $batch) {

            Notification::make()
                ->title('Product not available')
                ->body(
                    'No active, non-expired, in-stock batch was found.'
                )
                ->warning()
                ->send();

            return;
        }

        $this->addToCart(
            (int) $batch->id
        );
    }


    /*
    |--------------------------------------------------------------------------
    | BUILD SALE UNITS
    |--------------------------------------------------------------------------
    */

    protected function buildSaleUnits(
        MedicineBatch $batch
    ): array {

        $medicine = $batch->medicine;

        if (! $medicine) {
            return [];
        }

        /*
        |--------------------------------------------------------------------------
        | PRODUCT PACKAGING TYPE
        |--------------------------------------------------------------------------
        */

        $type = strtolower(
            trim(
                (string) (
                    $medicine->packaging_type
                    ?? 'single'
                )
            )
        );

        /*
        |--------------------------------------------------------------------------
        | NORMALIZE TYPE
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $type,
                [
                    'piece',
                    'unit',
                    'single',
                    'tablet',
                    'bottle',
                    'item',
                ],
                true
            )
        ) {
            $type = 'single';
        }

        if (
            in_array(
                $type,
                [
                    'box',
                    'strip',
                    'strips',
                ],
                true
            )
        ) {
            $type = 'strip';
        }

        if (
            in_array(
                $type,
                [
                    'pack',
                    'package',
                ],
                true
            )
        ) {
            $type = 'pack';
        }

        /*
        |--------------------------------------------------------------------------
        | BATCH VALUES
        |--------------------------------------------------------------------------
        */

        $boxesReceived = max(
            0,
            (int) (
                $batch->boxes_received ?? 0
            )
        );

        $stripsPerBox = max(
            1,
            (int) (
                $batch->strips_per_box ?? 1
            )
        );

        $unitsPerStrip = max(
            1,
            (int) (
                $batch->units_per_strip ?? 1
            )
        );

        /*
        |--------------------------------------------------------------------------
        | CURRENT BASE STOCK
        |--------------------------------------------------------------------------
        */

        $stock = max(
            0,
            (int) (
                $batch->quantity ?? 0
            )
        );

        /*
        |--------------------------------------------------------------------------
        | OUTER PRICE
        |--------------------------------------------------------------------------
        */

        $outerPrice = max(
            0,
            (float) (
                $batch->sale_price ?? 0
            )
        );

        /*
        |--------------------------------------------------------------------------
        | BASE UNIT NAME
        |--------------------------------------------------------------------------
        */

        $baseUnitName = trim(
            (string) (
                $medicine->unit?->name
                ?? 'Piece'
            )
        );

        if ($baseUnitName === '') {
            $baseUnitName = 'Piece';
        }

        $units = [];

        /*
        |--------------------------------------------------------------------------
        | STRIP PRODUCT
        |--------------------------------------------------------------------------
        */

        if ($type === 'strip') {

            /*
            |--------------------------------------------------------------------------
            | BOX CONVERSION
            |--------------------------------------------------------------------------
            */

            $boxConversion =
                $stripsPerBox *
                $unitsPerStrip;

            /*
            |--------------------------------------------------------------------------
            | AVAILABLE BOXES
            |--------------------------------------------------------------------------
            */

            $availableBoxes =
                $boxConversion > 0
                    ? (int) floor(
                        $stock /
                        $boxConversion
                    )
                    : 0;

            if ($availableBoxes > 0) {

                $units[] = [

                    'key' => 'box',

                    'name' => 'Box',

                    'conversion_factor' =>
                        $boxConversion,

                    'price' =>
                        round(
                            $outerPrice,
                            2
                        ),

                    'available_quantity' =>
                        $availableBoxes,
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | STRIP PRICE
            |--------------------------------------------------------------------------
            */

            $stripPrice =
                $stripsPerBox > 0
                    ? $outerPrice /
                        $stripsPerBox
                    : $outerPrice;

            /*
            |--------------------------------------------------------------------------
            | AVAILABLE STRIPS
            |--------------------------------------------------------------------------
            */

            $availableStrips =
                $unitsPerStrip > 0
                    ? (int) floor(
                        $stock /
                        $unitsPerStrip
                    )
                    : 0;

            if ($availableStrips > 0) {

                $units[] = [

                    'key' => 'strip',

                    'name' => 'Strip',

                    'conversion_factor' =>
                        $unitsPerStrip,

                    'price' =>
                        round(
                            $stripPrice,
                            2
                        ),

                    'available_quantity' =>
                        $availableStrips,
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | BASE UNIT PRICE
            |--------------------------------------------------------------------------
            */

            $basePrice =
                (
                    $stripsPerBox > 0 &&
                    $unitsPerStrip > 0
                )
                    ? $outerPrice /
                        (
                            $stripsPerBox *
                            $unitsPerStrip
                        )
                    : $outerPrice;

            /*
            |--------------------------------------------------------------------------
            | BASE UNIT
            |--------------------------------------------------------------------------
            */

            if ($stock > 0) {

                $units[] = [

                    'key' => 'base',

                    'name' =>
                        $baseUnitName,

                    'conversion_factor' => 1,

                    'price' =>
                        round(
                            $basePrice,
                            2
                        ),

                    'available_quantity' =>
                        $stock,
                ];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | PACK PRODUCT
        |--------------------------------------------------------------------------
        */

        elseif ($type === 'pack') {

            $packConversion =
                $unitsPerStrip;

            /*
            |--------------------------------------------------------------------------
            | AVAILABLE PACKS
            |--------------------------------------------------------------------------
            */

            $availablePacks =
                $packConversion > 0
                    ? (int) floor(
                        $stock /
                        $packConversion
                    )
                    : 0;

            /*
            |--------------------------------------------------------------------------
            | PACK
            |--------------------------------------------------------------------------
            */

            if ($availablePacks > 0) {

                $units[] = [

                    'key' => 'pack',

                    'name' => 'Pack',

                    'conversion_factor' =>
                        $packConversion,

                    'price' =>
                        round(
                            $outerPrice,
                            2
                        ),

                    'available_quantity' =>
                        $availablePacks,
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | PIECE
            |--------------------------------------------------------------------------
            */

            if ($stock > 0) {

                $piecePrice =
                    $packConversion > 0
                        ? $outerPrice /
                            $packConversion
                        : $outerPrice;

                $units[] = [

                    'key' => 'base',

                    'name' =>
                        $baseUnitName,

                    'conversion_factor' => 1,

                    'price' =>
                        round(
                            $piecePrice,
                            2
                        ),

                    'available_quantity' =>
                        $stock,
                ];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | SINGLE PRODUCT
        |--------------------------------------------------------------------------
        */

        else {

            if ($stock > 0) {

                $units[] = [

                    'key' => 'base',

                    'name' =>
                        $baseUnitName,

                    'conversion_factor' => 1,

                    'price' =>
                        round(
                            $outerPrice,
                            2
                        ),

                    'available_quantity' =>
                        $stock,
                ];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | FINAL FILTER
        |--------------------------------------------------------------------------
        */

        return collect($units)
            ->filter(function ($unit) {

                return
                    (float) (
                        $unit['conversion_factor']
                        ?? 0
                    ) > 0
                    &&
                    (int) (
                        $unit['available_quantity']
                        ?? 0
                    ) > 0;
            })
            ->values()
            ->all();
    }


    /*
    |--------------------------------------------------------------------------
    | ADD PRODUCT TO CART
    |--------------------------------------------------------------------------
    */

    public function addToCart(
        int $batchId
    ): void {

        $query = MedicineBatch::query()
            ->with([
                'medicine.unit',
            ])
            ->where('id', $batchId);

        $this->sellableBatchQuery($query);

        $batch = $query->first();

        if (! $batch) {

            Notification::make()
                ->title('Product unavailable')
                ->body(
                    'This batch is inactive, expired, or out of stock.'
                )
                ->danger()
                ->send();

            return;
        }

        if (! $batch->medicine) {

            Notification::make()
                ->title('Product not found')
                ->body(
                    'This batch is not connected to a valid product.'
                )
                ->danger()
                ->send();

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | SALE UNITS
        |--------------------------------------------------------------------------
        */

        $availableUnits =
            $this->buildSaleUnits($batch);

        if (empty($availableUnits)) {

            Notification::make()
                ->title('Sale units not available')
                ->body(
                    'This batch does not have a valid packaging configuration.'
                )
                ->danger()
                ->send();

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | DEFAULT UNIT
        |--------------------------------------------------------------------------
        */

        $selectedUnit =
            $availableUnits[0];

        /*
        |--------------------------------------------------------------------------
        | CART KEY
        |--------------------------------------------------------------------------
        */

        $key =
            $batch->id .
            '_' .
            $selectedUnit['key'];

        /*
        |--------------------------------------------------------------------------
        | ALREADY IN CART
        |--------------------------------------------------------------------------
        */

        if (isset($this->cart[$key])) {

            $currentQuantity =
                (int) (
                    $this->cart[$key]['quantity']
                    ?? 0
                );

            $conversionFactor =
                (float) (
                    $this->cart[$key]['conversion_factor']
                    ?? 1
                );

            $newQuantity =
                $currentQuantity + 1;

            $requiredBaseUnits =
                $newQuantity *
                $conversionFactor;

            if (
                $requiredBaseUnits >
                (float) $batch->quantity
            ) {

                $availableQuantity =
                    max(
                        0,
                        (int) floor(
                            $batch->quantity /
                            $conversionFactor
                        )
                    );

                Notification::make()
                    ->title('Stock limit reached')
                    ->body(
                        "Only {$availableQuantity} {$selectedUnit['name']} available."
                    )
                    ->warning()
                    ->send();

                return;
            }

            $this->cart[$key]['quantity'] =
                $newQuantity;

            $this->updateCartTotal($key);

            $this->search = '';

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | NEW CART ITEM
        |--------------------------------------------------------------------------
        */

        $this->cart[$key] = [

            'batch_id' =>
                (int) $batch->id,

            'medicine_id' =>
                (int) $batch->medicine_id,

            'name' =>
                $batch->medicine->name,

            'generic_name' =>
                $batch->medicine->generic_name,

            'batch_number' =>
                $batch->batch_number,

            'expiry_date' =>
                $batch->expiry_date,

            'stock_quantity' =>
                (float) $batch->quantity,

            'sale_unit' =>
                $selectedUnit['name'],

            'unit_key' =>
                $selectedUnit['key'],

            'conversion_factor' =>
                (float) $selectedUnit[
                    'conversion_factor'
                ],

            'price' =>
                (float) $selectedUnit['price'],

            'base_price' =>
                (float) $batch->sale_price,

            'quantity' => 1,

            'discount' => 0,

            'total' =>
                (float) $selectedUnit['price'],

            'available_units' =>
                $availableUnits,
        ];

        $this->search = '';
    }


    /*
    |--------------------------------------------------------------------------
    | CHANGE SALE UNIT
    |--------------------------------------------------------------------------
    */

    public function updateSaleUnit(
        string $key,
        $unitKey
    ): void {

        if (! isset($this->cart[$key])) {
            return;
        }

        $unitKey = (string) $unitKey;

        $batchId = (int) (
            $this->cart[$key]['batch_id']
            ?? 0
        );

        if ($batchId <= 0) {
            return;
        }

        $query = MedicineBatch::query()
            ->with('medicine.unit')
            ->where('id', $batchId);

        $this->sellableBatchQuery($query);

        $batch = $query->first();

        if (! $batch) {

            Notification::make()
                ->title('Batch unavailable')
                ->body(
                    'The selected batch is no longer available.'
                )
                ->danger()
                ->send();

            return;
        }

        $availableUnits =
            $this->buildSaleUnits($batch);

        $selectedUnit =
            collect($availableUnits)
                ->firstWhere(
                    'key',
                    $unitKey
                );

        if (! $selectedUnit) {

            Notification::make()
                ->title('Invalid sale unit')
                ->body(
                    'The selected sale unit is not available.'
                )
                ->danger()
                ->send();

            return;
        }

        $conversionFactor =
            (float) $selectedUnit[
                'conversion_factor'
            ];

        $stockQuantity =
            (float) $batch->quantity;

        $maxQuantity =
            (int) floor(
                $stockQuantity /
                $conversionFactor
            );

        if ($maxQuantity < 1) {

            Notification::make()
                ->title('Insufficient stock')
                ->body(
                    "No {$selectedUnit['name']} is currently available."
                )
                ->warning()
                ->send();

            return;
        }

        $currentQuantity =
            max(
                1,
                (int) (
                    $this->cart[$key]['quantity']
                    ?? 1
                )
            );

        if ($currentQuantity > $maxQuantity) {

            $currentQuantity =
                $maxQuantity;

            $this->cart[$key]['quantity'] =
                $currentQuantity;

            Notification::make()
                ->title('Quantity adjusted')
                ->body(
                    "Maximum available quantity is {$maxQuantity} {$selectedUnit['name']}."
                )
                ->warning()
                ->send();
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE CART
        |--------------------------------------------------------------------------
        */

        $this->cart[$key]['sale_unit'] =
            $selectedUnit['name'];

        $this->cart[$key]['unit_key'] =
            $selectedUnit['key'];

        $this->cart[$key]['conversion_factor'] =
            $conversionFactor;

        $this->cart[$key]['price'] =
            (float) $selectedUnit['price'];

        $this->cart[$key]['base_price'] =
            (float) $batch->sale_price;

        $this->cart[$key]['stock_quantity'] =
            $stockQuantity;

        $this->cart[$key]['available_units'] =
            $availableUnits;

        $this->updateCartTotal($key);
    }


    /*
    |--------------------------------------------------------------------------
    | INCREASE QUANTITY
    |--------------------------------------------------------------------------
    */

    public function increaseQuantity(
        string $key
    ): void {

        if (! isset($this->cart[$key])) {
            return;
        }

        $currentQuantity =
            (int) (
                $this->cart[$key]['quantity']
                ?? 0
            );

        $conversionFactor =
            (float) (
                $this->cart[$key]['conversion_factor']
                ?? 1
            );

        if ($conversionFactor <= 0) {
            $conversionFactor = 1;
        }

        $batchId = (int) (
            $this->cart[$key]['batch_id']
            ?? 0
        );

        $batch = MedicineBatch::find(
            $batchId
        );

        if (! $batch) {

            Notification::make()
                ->title('Batch unavailable')
                ->body(
                    'The selected batch no longer exists.'
                )
                ->danger()
                ->send();

            return;
        }

        $stockQuantity =
            (float) $batch->quantity;

        $newQuantity =
            $currentQuantity + 1;

        $requiredBaseUnits =
            $newQuantity *
            $conversionFactor;

        if (
            $requiredBaseUnits >
            $stockQuantity
        ) {

            $availableQuantity =
                max(
                    0,
                    (int) floor(
                        $stockQuantity /
                        $conversionFactor
                    )
                );

            Notification::make()
                ->title('Stock limit reached')
                ->body(
                    "Only {$availableQuantity} {$this->cart[$key]['sale_unit']} available."
                )
                ->warning()
                ->send();

            return;
        }

        $this->cart[$key]['quantity'] =
            $newQuantity;

        $this->cart[$key]['stock_quantity'] =
            $stockQuantity;

        $this->updateCartTotal($key);
    }


    /*
    |--------------------------------------------------------------------------
    | DECREASE QUANTITY
    |--------------------------------------------------------------------------
    */

    public function decreaseQuantity(
        string $key
    ): void {

        if (! isset($this->cart[$key])) {
            return;
        }

        if (
            (int) (
                $this->cart[$key]['quantity']
                ?? 0
            ) <= 1
        ) {

            unset(
                $this->cart[$key]
            );

            return;
        }

        $this->cart[$key]['quantity']--;

        $this->updateCartTotal($key);
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE QUANTITY
    |--------------------------------------------------------------------------
    */

    public function updateQuantity(
        string $key,
        $quantity
    ): void {

        if (! isset($this->cart[$key])) {
            return;
        }

        $quantity = (int) $quantity;

        if ($quantity < 1) {
            $quantity = 1;
        }

        $batchId = (int) (
            $this->cart[$key]['batch_id']
            ?? 0
        );

        $batch = MedicineBatch::find(
            $batchId
        );

        if (! $batch) {

            Notification::make()
                ->title('Batch unavailable')
                ->body(
                    'The selected batch no longer exists.'
                )
                ->danger()
                ->send();

            return;
        }

        $stockQuantity =
            (float) $batch->quantity;

        $conversionFactor =
            (float) (
                $this->cart[$key]['conversion_factor']
                ?? 1
            );

        if ($conversionFactor <= 0) {
            $conversionFactor = 1;
        }

        $maxQuantity =
            max(
                1,
                (int) floor(
                    $stockQuantity /
                    $conversionFactor
                )
            );

        if ($quantity > $maxQuantity) {

            $quantity =
                $maxQuantity;

            Notification::make()
                ->title('Stock limit reached')
                ->body(
                    "Only {$maxQuantity} {$this->cart[$key]['sale_unit']} available."
                )
                ->warning()
                ->send();
        }

        $this->cart[$key]['quantity'] =
            $quantity;

        $this->cart[$key]['stock_quantity'] =
            $stockQuantity;

        $this->updateCartTotal($key);
    }


    /*
    |--------------------------------------------------------------------------
    | REMOVE ITEM
    |--------------------------------------------------------------------------
    */

    public function removeFromCart(
        string $key
    ): void {

        unset(
            $this->cart[$key]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE ITEM TOTAL
    |--------------------------------------------------------------------------
    */

    protected function updateCartTotal(
        string $key
    ): void {

        if (! isset($this->cart[$key])) {
            return;
        }

        $quantity =
            (int) (
                $this->cart[$key]['quantity']
                ?? 0
            );

        $price =
            (float) (
                $this->cart[$key]['price']
                ?? 0
            );

        $itemDiscount =
            max(
                0,
                (float) (
                    $this->cart[$key]['discount']
                    ?? 0
                )
            );

        $this->cart[$key]['total'] =
            max(
                0,
                round(
                    (
                        $quantity *
                        $price
                    ) -
                    $itemDiscount,
                    2
                )
            );
    }


    /*
    |--------------------------------------------------------------------------
    | TOTALS
    |--------------------------------------------------------------------------
    */

    public function calculateTotals(): array
    {
        $subtotal = collect(
            $this->cart
        )->sum(function ($item) {

            return (float) (
                $item['total'] ?? 0
            );
        });

        $discount =
            max(
                0,
                (float) (
                    $this->discount ?? 0
                )
            );

        $tax =
            max(
                0,
                (float) (
                    $this->tax ?? 0
                )
            );

        $grandTotal =
            max(
                0,
                $subtotal -
                $discount +
                $tax
            );

        $paid =
            max(
                0,
                (float) (
                    $this->paidAmount ?? 0
                )
            );

        $change =
            max(
                0,
                $paid -
                $grandTotal
            );

        $due =
            max(
                0,
                $grandTotal -
                $paid
            );

        return [

            'subtotal' =>
                round(
                    $subtotal,
                    2
                ),

            'discount' =>
                round(
                    $discount,
                    2
                ),

            'tax' =>
                round(
                    $tax,
                    2
                ),

            'grandTotal' =>
                round(
                    $grandTotal,
                    2
                ),

            'paid' =>
                round(
                    $paid,
                    2
                ),

            'change' =>
                round(
                    $change,
                    2
                ),

            'due' =>
                round(
                    $due,
                    2
                ),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | HOLD SALE
    |--------------------------------------------------------------------------
    */

    public function holdSale(): void
    {
        if (empty($this->cart)) {

            Notification::make()
                ->title('Cart is empty')
                ->body(
                    'Add at least one product before holding the sale.'
                )
                ->warning()
                ->send();

            return;
        }

        $totals =
            $this->calculateTotals();

        $grandTotal =
            max(
                0,
                (float) (
                    $totals['grandTotal']
                    ?? 0
                )
            );

        if ($grandTotal <= 0) {

            Notification::make()
                ->title('Invalid sale')
                ->body(
                    'The sale total must be greater than zero.'
                )
                ->danger()
                ->send();

            return;
        }

        try {

            /*
            |--------------------------------------------------------------------------
            | HOLD NUMBER
            |--------------------------------------------------------------------------
            */

            do {

                $holdNumber =
                    'HOLD-' .
                    now()->format('YmdHis') .
                    '-' .
                    strtoupper(
                        Str::random(5)
                    );

            } while (
                HeldSale::where(
                    'hold_number',
                    $holdNumber
                )->exists()
            );

            /*
            |--------------------------------------------------------------------------
            | CREATE HELD SALE
            |--------------------------------------------------------------------------
            */

            $heldSale =
                HeldSale::create([

                    'hold_number' =>
                        $holdNumber,

                    'user_id' =>
                        auth()->id(),

                    'customer_id' =>
                        $this->customerId
                            ?: null,

                    'cart' =>
                        $this->cart,

                    'subtotal' =>
                        (float) (
                            $totals['subtotal']
                            ?? 0
                        ),

                    'discount' =>
                        (float) (
                            $totals['discount']
                            ?? 0
                        ),

                    'tax' =>
                        (float) (
                            $totals['tax']
                            ?? 0
                        ),

                    'grand_total' =>
                        $grandTotal,

                    'paid_amount' =>
                        max(
                            0,
                            (float) (
                                $this->paidAmount
                                ?? 0
                            )
                        ),

                    'payment_method' =>
                        $this->paymentMethod
                            ?: 'cash',

                    'status' =>
                        'held',
                ]);

            /*
            |--------------------------------------------------------------------------
            | RESET POS
            |--------------------------------------------------------------------------
            */

            $this->resetPos();

            Notification::make()
                ->title('Sale held successfully')
                ->body(
                    'Hold ' .
                    $heldSale->hold_number .
                    ' has been saved.'
                )
                ->success()
                ->send();

        } catch (\Throwable $e) {

            report($e);

            Notification::make()
                ->title('Could not hold sale')
                ->body(
                    $e->getMessage()
                )
                ->danger()
                ->send();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | RESUME HELD SALE
    |--------------------------------------------------------------------------
    */

    public function resumeHeldSale(
        int $heldSaleId
    ): void {

        if (! empty($this->cart)) {

            Notification::make()
                ->title('Current cart is not empty')
                ->body(
                    'Complete, clear, or hold the current sale before resuming another sale.'
                )
                ->warning()
                ->send();

            return;
        }

        try {

            // $heldSale =
            //     HeldSale::query()
            //         ->where(
            //             'id',
            //             $heldSaleId
            //         )
            //         ->where(
            //             'user_id',
            //             auth()->id()
            //         )
            //         ->where(
            //             'status',
            //             'held'
            //         )
            //         ->first();
                            $heldSale =
                HeldSale::query()
                    ->where('id', $heldSaleId)
                    ->where('status', 'held')
                    ->when(
                        ! auth()->user()?->hasAnyRole(['Admin', 'Pharmacist']),
                        fn ($query) => $query->where('user_id', auth()->id())
                    )
                    ->first();

            if (! $heldSale) {

                Notification::make()
                    ->title('Held sale not found')
                    ->body(
                        'This held sale is no longer available.'
                    )
                    ->danger()
                    ->send();

                return;
            }

            $restoredCart = [];

            /*
            |--------------------------------------------------------------------------
            | VALIDATE EVERY HELD ITEM
            |--------------------------------------------------------------------------
            */

            foreach (
                $heldSale->cart
                as $key => $item
            ) {

                $batchId =
                    (int) (
                        $item['batch_id']
                        ?? 0
                    );

                $quantity =
                    (int) (
                        $item['quantity']
                        ?? 0
                    );

                $unitKey =
                    (string) (
                        $item['unit_key']
                        ?? 'base'
                    );

                if (
                    $batchId <= 0 ||
                    $quantity <= 0
                ) {

                    throw new \RuntimeException(
                        'Invalid item found in held sale.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | CURRENT BATCH
                |--------------------------------------------------------------------------
                */

                $batch =
                    MedicineBatch::query()
                        ->with([
                            'medicine.unit',
                        ])
                        ->where(
                            'id',
                            $batchId
                        )
                        ->first();

                if (! $batch) {

                    throw new \RuntimeException(
                        'A product batch from this held sale no longer exists.'
                    );
                }

                if (! $batch->medicine) {

                    throw new \RuntimeException(
                        'A product from this held sale no longer exists.'
                    );
                }

                if (! $batch->status) {

                    throw new \RuntimeException(
                        'Batch ' .
                        (
                            $batch->batch_number
                            ?: '#' . $batch->id
                        ) .
                        ' is inactive.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | EXPIRY
                |--------------------------------------------------------------------------
                */

                if (
                    $batch->medicine->has_expiry
                ) {

                    if (
                        empty(
                            $batch->expiry_date
                        )
                    ) {

                        throw new \RuntimeException(
                            'Batch ' .
                            (
                                $batch->batch_number
                                ?: '#' . $batch->id
                            ) .
                            ' has no expiry date.'
                        );
                    }

                    $expiryDate =
                        Carbon::parse(
                            $batch->expiry_date
                        )->startOfDay();

                    if (
                        $expiryDate->lt(
                            today()->startOfDay()
                        )
                    ) {

                        throw new \RuntimeException(
                            'Batch ' .
                            (
                                $batch->batch_number
                                ?: '#' . $batch->id
                            ) .
                            ' has expired.'
                        );
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | REBUILD CURRENT SALE UNITS
                |--------------------------------------------------------------------------
                */

                $availableUnits =
                    $this->buildSaleUnits(
                        $batch
                    );

                $selectedUnit =
                    collect(
                        $availableUnits
                    )->firstWhere(
                        'key',
                        $unitKey
                    );

                if (! $selectedUnit) {

                    throw new \RuntimeException(
                        'The selected sale unit is no longer available for ' .
                        (
                            $batch->batch_number
                            ?: '#' . $batch->id
                        ) .
                        '.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | CONVERSION
                |--------------------------------------------------------------------------
                */

                $conversionFactor =
                    (float) (
                        $selectedUnit[
                            'conversion_factor'
                        ]
                    );

                /*
                |--------------------------------------------------------------------------
                | CURRENT STOCK
                |--------------------------------------------------------------------------
                */

                $stockQuantity =
                    (float) (
                        $batch->quantity
                        ?? 0
                    );

                $maxQuantity =
                    (int) floor(
                        $stockQuantity /
                        $conversionFactor
                    );

                if ($maxQuantity < 1) {

                    throw new \RuntimeException(
                        'No ' .
                        $selectedUnit['name'] .
                        ' is currently available for ' .
                        $batch->medicine->name .
                        '.'
                    );
                }

                if (
                    $quantity >
                    $maxQuantity
                ) {

                    throw new \RuntimeException(
                        $batch->medicine->name .
                        ' only has ' .
                        $maxQuantity .
                        ' ' .
                        $selectedUnit['name'] .
                        ' available now.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | REBUILD CART ITEM
                |--------------------------------------------------------------------------
                */

                $item['batch_id'] =
                    (int) $batch->id;

                $item['medicine_id'] =
                    (int) $batch->medicine_id;

                $item['name'] =
                    $batch->medicine->name;

                $item['generic_name'] =
                    $batch->medicine->generic_name;

                $item['batch_number'] =
                    $batch->batch_number;

                $item['expiry_date'] =
                    $batch->expiry_date;

                $item['stock_quantity'] =
                    $stockQuantity;

                $item['sale_unit'] =
                    $selectedUnit['name'];

                $item['unit_key'] =
                    $selectedUnit['key'];

                $item['base_price'] =
                    (float) $batch->sale_price;

                $item['conversion_factor'] =
                    $conversionFactor;

                $item['price'] =
                    (float) $selectedUnit['price'];

                $item['quantity'] =
                    $quantity;

                $item['total'] =
                    round(
                        $quantity *
                        (float) $selectedUnit['price'],
                        2
                    );

                $item['available_units'] =
                    $availableUnits;

                $restoredCart[
                    (string) $key
                ] = $item;
            }

            /*
            |--------------------------------------------------------------------------
            | RESTORE POS
            |--------------------------------------------------------------------------
            */

            $this->cart =
                $restoredCart;

            $this->customerId =
                $heldSale->customer_id;

            $this->discount =
                (float) $heldSale->discount;

            $this->tax =
                (float) $heldSale->tax;

            $this->paidAmount =
                (float) $heldSale->paid_amount;

            $this->paymentMethod =
                $heldSale->payment_method
                    ?: 'cash';

            /*
            |--------------------------------------------------------------------------
            | REMOVE HELD RECORD
            |--------------------------------------------------------------------------
            */

            $holdNumber =
                $heldSale->hold_number;

            $heldSale->delete();

            Notification::make()
                ->title('Sale resumed')
                ->body(
                    'Hold ' .
                    $holdNumber .
                    ' has been restored to the POS.'
                )
                ->success()
                ->send();

        } catch (\Throwable $e) {

            report($e);

            Notification::make()
                ->title('Could not resume sale')
                ->body(
                    $e->getMessage()
                )
                ->danger()
                ->send();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | CANCEL HELD SALE
    |--------------------------------------------------------------------------
    */

    public function cancelHeldSale(
        int $heldSaleId
    ): void {

        try {

            $heldSale =
                HeldSale::query()
                    ->where(
                        'id',
                        $heldSaleId
                    )
                    ->where(
                        'user_id',
                        auth()->id()
                    )
                    ->where(
                        'status',
                        'held'
                    )
                    ->first();

            if (! $heldSale) {

                Notification::make()
                    ->title('Held sale not found')
                    ->body(
                        'This held sale is no longer available.'
                    )
                    ->warning()
                    ->send();

                return;
            }

            $holdNumber =
                $heldSale->hold_number;

            $heldSale->delete();

            Notification::make()
                ->title('Held sale removed')
                ->body(
                    'Hold ' .
                    $holdNumber .
                    ' has been removed.'
                )
                ->success()
                ->send();

        } catch (\Throwable $e) {

            report($e);

            Notification::make()
                ->title('Could not remove held sale')
                ->body(
                    $e->getMessage()
                )
                ->danger()
                ->send();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | COMPLETE SALE
    |--------------------------------------------------------------------------
    */

    public function completeSale(): void
    {
        if (empty($this->cart)) {

            Notification::make()
                ->title('Cart is empty')
                ->body(
                    'Please add at least one product before completing the sale.'
                )
                ->danger()
                ->send();

            return;
        }

        $totals =
            $this->calculateTotals();

        $paidAmount =
            max(
                0,
                (float) (
                    $this->paidAmount
                    ?? 0
                )
            );

        $grandTotal =
            max(
                0,
                (float) (
                    $totals['grandTotal']
                    ?? 0
                )
            );

        if ($grandTotal <= 0) {

            Notification::make()
                ->title('Invalid sale amount')
                ->body(
                    'The sale total must be greater than zero.'
                )
                ->danger()
                ->send();

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | FULL PAYMENT REQUIRED
        |--------------------------------------------------------------------------
        */

        if (
            $paidAmount <
            $grandTotal
        ) {

            $dueAmount =
                $grandTotal -
                $paidAmount;

            Notification::make()
                ->title('Insufficient payment')
                ->body(
                    'Customer still has a due amount of ' .
                    $this->money(
                        $dueAmount
                    )
                )
                ->danger()
                ->send();

            return;
        }

        $changeAmount =
            max(
                0,
                $paidAmount -
                $grandTotal
            );

        try {

            $sale =
                DB::transaction(
                    function () use (
                        $totals,
                        $paidAmount,
                        $grandTotal,
                        $changeAmount
                    ) {

                        /*
                        |--------------------------------------------------------------------------
                        | INVOICE NUMBER
                        |--------------------------------------------------------------------------
                        */

                        do {

                            $invoiceNumber =
                                'INV-' .
                                now()->format(
                                    'YmdHis'
                                ) .
                                '-' .
                                strtoupper(
                                    Str::random(5)
                                );

                        } while (
                            Sale::where(
                                'invoice_number',
                                $invoiceNumber
                            )->exists()
                        );

                        /*
                        |--------------------------------------------------------------------------
                        | CREATE SALE
                        |--------------------------------------------------------------------------
                        */

                        $sale =
                            Sale::create([

                                'invoice_number' =>
                                    $invoiceNumber,

                                'customer_id' =>
                                    $this->customerId
                                        ?: null,

                                'subtotal' =>
                                    (float) (
                                        $totals[
                                            'subtotal'
                                        ] ?? 0
                                    ),

                                'discount' =>
                                    (float) (
                                        $totals[
                                            'discount'
                                        ] ?? 0
                                    ),

                                'tax' =>
                                    (float) (
                                        $totals[
                                            'tax'
                                        ] ?? 0
                                    ),

                                'grand_total' =>
                                    $grandTotal,

                                'paid_amount' =>
                                    $paidAmount,

                                'change_amount' =>
                                    $changeAmount,

                                'payment_method' =>
                                    $this->paymentMethod
                                        ?: 'cash',

                                'created_by' =>
                                    auth()->id(),
                            ]);

                        /*
                        |--------------------------------------------------------------------------
                        | SALE ITEMS
                        |--------------------------------------------------------------------------
                        */

                        foreach (
                            $this->cart
                            as $item
                        ) {

                            $batchId =
                                (int) (
                                    $item['batch_id']
                                    ?? 0
                                );

                            $quantity =
                                (int) (
                                    $item['quantity']
                                    ?? 0
                                );

                            $cartMedicineId =
                                (int) (
                                    $item['medicine_id']
                                    ?? 0
                                );

                            $unitKey =
                                (string) (
                                    $item['unit_key']
                                    ?? ''
                                );

                            if (
                                $batchId <= 0 ||
                                $quantity <= 0
                            ) {

                                throw new \RuntimeException(
                                    'Invalid product batch or quantity.'
                                );
                            }

                            if (
                                $cartMedicineId <= 0
                            ) {

                                throw new \RuntimeException(
                                    'Invalid product selected for sale.'
                                );
                            }

                            if (
                                $unitKey === ''
                            ) {

                                throw new \RuntimeException(
                                    'No valid sale unit was selected.'
                                );
                            }

                            /*
                            |--------------------------------------------------------------------------
                            | LOCK EXACT BATCH
                            |--------------------------------------------------------------------------
                            */

                            $batch =
                                MedicineBatch::query()
                                    ->with([
                                        'medicine.unit',
                                    ])
                                    ->lockForUpdate()
                                    ->find(
                                        $batchId
                                    );

                            if (! $batch) {

                                throw new \RuntimeException(
                                    'Product batch #' .
                                    $batchId .
                                    ' was not found.'
                                );
                            }

                            /*
                            |--------------------------------------------------------------------------
                            | PRODUCT MATCH
                            |--------------------------------------------------------------------------
                            */

                            if (
                                (int) $batch->medicine_id
                                !==
                                $cartMedicineId
                            ) {

                                throw new \RuntimeException(
                                    'Selected product does not belong to batch #' .
                                    $batch->id .
                                    '.'
                                );
                            }

                            /*
                            |--------------------------------------------------------------------------
                            | STATUS
                            |--------------------------------------------------------------------------
                            */

                            if (! $batch->status) {

                                throw new \RuntimeException(
                                    'The selected product batch is inactive.'
                                );
                            }

                            /*
                            |--------------------------------------------------------------------------
                            | EXPIRY
                            |--------------------------------------------------------------------------
                            */

                            if (
                                $batch->medicine
                                &&
                                $batch->medicine->has_expiry
                            ) {

                                if (
                                    empty(
                                        $batch->expiry_date
                                    )
                                ) {

                                    throw new \RuntimeException(
                                        'The selected product batch has no expiry date.'
                                    );
                                }

                                $expiryDate =
                                    Carbon::parse(
                                        $batch->expiry_date
                                    )->startOfDay();

                                if (
                                    $expiryDate->lt(
                                        today()->startOfDay()
                                    )
                                ) {

                                    throw new \RuntimeException(
                                        'Cannot sell expired product. Batch ' .
                                        (
                                            $batch->batch_number
                                            ?: '#' . $batch->id
                                        ) .
                                        ' expired on ' .
                                        $expiryDate->format(
                                            'd-m-Y'
                                        ) .
                                        '.'
                                    );
                                }
                            }

                            /*
                            |--------------------------------------------------------------------------
                            | REBUILD SALE UNITS
                            |--------------------------------------------------------------------------
                            */

                            $availableUnits =
                                $this->buildSaleUnits(
                                    $batch
                                );

                            $selectedUnit =
                                collect(
                                    $availableUnits
                                )->firstWhere(
                                    'key',
                                    $unitKey
                                );

                            if (! $selectedUnit) {

                                throw new \RuntimeException(
                                    'The selected sale unit is no longer available for ' .
                                    $batch->medicine->name .
                                    '.'
                                );
                            }

                            /*
                            |--------------------------------------------------------------------------
                            | CONVERSION FACTOR
                            |--------------------------------------------------------------------------
                            */

                            $conversionFactor =
                                (float) (
                                    $selectedUnit[
                                        'conversion_factor'
                                    ]
                                );

                            if (
                                $conversionFactor <= 0
                            ) {

                                throw new \RuntimeException(
                                    'Invalid conversion factor for sale unit.'
                                );
                            }

                            /*
                            |--------------------------------------------------------------------------
                            | SALE UNIT NAME
                            |--------------------------------------------------------------------------
                            */

                            $saleUnit =
                                trim(
                                    (string) (
                                        $selectedUnit[
                                            'name'
                                        ]
                                    )
                                );

                            if (
                                $saleUnit === ''
                            ) {

                                throw new \RuntimeException(
                                    'The selected sale unit name is invalid.'
                                );
                            }

                            /*
                            |--------------------------------------------------------------------------
                            | REQUIRED BASE STOCK
                            |--------------------------------------------------------------------------
                            */

                            $requiredQuantity =
                                $quantity *
                                $conversionFactor;

                            $availableStock =
                                (float) (
                                    $batch->quantity
                                    ?? 0
                                );

                            if (
                                $availableStock <
                                $requiredQuantity
                            ) {

                                throw new \RuntimeException(
                                    'Insufficient stock for batch ' .
                                    (
                                        $batch->batch_number
                                        ?: '#' . $batch->id
                                    ) .
                                    '. Available base stock: ' .
                                    $availableStock .
                                    ', Required base stock: ' .
                                    $requiredQuantity .
                                    '.'
                                );
                            }

                            /*
                            |--------------------------------------------------------------------------
                            | PRICE
                            |--------------------------------------------------------------------------
                            */

                            $unitPrice =
                                (float) (
                                    $selectedUnit[
                                        'price'
                                    ]
                                );

                            if (
                                $unitPrice <= 0
                            ) {

                                throw new \RuntimeException(
                                    'Invalid sale price for ' .
                                    $batch->medicine->name .
                                    '.'
                                );
                            }

                            /*
                            |--------------------------------------------------------------------------
                            | ITEM DISCOUNT
                            |--------------------------------------------------------------------------
                            */

                            $itemDiscount =
                                max(
                                    0,
                                    (float) (
                                        $item[
                                            'discount'
                                        ] ?? 0
                                    )
                                );

                            /*
                            |--------------------------------------------------------------------------
                            | ITEM TOTAL
                            |--------------------------------------------------------------------------
                            */

                            $itemTotal =
                                max(
                                    0,
                                    (
                                        $unitPrice *
                                        $quantity
                                    ) -
                                    $itemDiscount
                                );

                            /*
                            |--------------------------------------------------------------------------
                            | CREATE SALE ITEM
                            |--------------------------------------------------------------------------
                            */

                            SaleItem::create([

                                'sale_id' =>
                                    $sale->id,

                                'medicine_id' =>
                                    $batch->medicine_id,

                                'medicine_batch_id' =>
                                    $batch->id,

                                'sale_unit' =>
                                    $saleUnit,

                                'conversion_factor' =>
                                    $conversionFactor,

                                'quantity' =>
                                    $quantity,

                                'unit_price' =>
                                    $unitPrice,

                                'discount' =>
                                    $itemDiscount,

                                'total' =>
                                    $itemTotal,
                            ]);

                            /*
                            |--------------------------------------------------------------------------
                            | IMPORTANT
                            |--------------------------------------------------------------------------
                            |
                            | DO NOT MANUALLY DECREASE STOCK HERE.
                            |
                            | SaleItemObserver handles:
                            |
                            | quantity × conversion_factor
                            |
                            */
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | PAYMENT
                        |--------------------------------------------------------------------------
                        */

                        if ($paidAmount > 0) {

                            SalePayment::create([

                                'sale_id' =>
                                    $sale->id,

                                'amount' =>
                                    $paidAmount,

                                'payment_method' =>
                                    $this->paymentMethod
                                        ?: 'cash',

                                'reference_number' =>
                                    null,

                                'notes' =>
                                    'POS payment',

                                'received_by' =>
                                    auth()->id(),

                                'paid_at' =>
                                    now(),
                            ]);
                        }

                        return $sale;
                    }
                );

            /*
            |--------------------------------------------------------------------------
            | RESET POS
            |--------------------------------------------------------------------------
            */

            $this->resetPos();

            /*
            |--------------------------------------------------------------------------
            | SUCCESS NOTIFICATION
            |--------------------------------------------------------------------------
            */

            Notification::make()
                ->title('Sale completed successfully')
                ->body(
                    'Invoice ' .
                    $sale->invoice_number .
                    ' created. Total: ' .
                    $this->money(
                        (float) $sale->grand_total
                    ) .
                    ' | Change: ' .
                    $this->money(
                        (float) $sale->change_amount
                    )
                )
                ->success()
                ->send();

            /*
            |--------------------------------------------------------------------------
            | PRINT RECEIPT PROMPT
            |--------------------------------------------------------------------------
            */

            $this->completedSaleId =
                (int) $sale->id;

            $this->showPrintReceiptPrompt =
                true;

        } catch (\Throwable $e) {

            report($e);

            Notification::make()
                ->title(
                    'Sale could not be completed'
                )
                ->body(
                    $e->getMessage()
                )
                ->danger()
                ->send();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | OPEN COMPLETED SALE RECEIPT
    |--------------------------------------------------------------------------
    */

    public function openCompletedSaleReceipt(): void
    {
        if (! $this->completedSaleId) {
            return;
        }

        $saleId =
            $this->completedSaleId;

        $this->showPrintReceiptPrompt =
            false;

        $this->completedSaleId =
            null;

        $url =
            route(
                'filament.cashier.pages.sale-history',
                [
                    'openSale' => $saleId,
                ]
            );

        $this->dispatch(
            'open-completed-receipt',
            url: $url
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CLOSE PRINT RECEIPT PROMPT
    |--------------------------------------------------------------------------
    */

    public function closePrintReceiptPrompt(): void
    {
        $this->showPrintReceiptPrompt =
            false;

        $this->completedSaleId =
            null;
    }


    /*
    |--------------------------------------------------------------------------
    | RESET POS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | EDIT EXISTING SALE
    |--------------------------------------------------------------------------
    */

    public function mount(): void
    {
        $id = (int) request()->query('editSale');

        if ($id > 0) {
            $this->loadSaleForEdit($id);
        }
    }

    protected function loadSaleForEdit(int $saleId): void
    {
        $query = Sale::query()->with(['items.batch.medicine.unit']);

        if (! auth()->user()?->hasAnyRole(['Admin', 'Pharmacist'])) {
            $query->where('created_by', auth()->id());
        }

        $sale = $query->find($saleId);

        if (! $sale) {
            Notification::make()->title('Sale not found')->danger()->send();
            return;
        }

        if ($sale->returns()->exists()) {
            Notification::make()
                ->title('This sale has returns')
                ->body('A sale with returned items cannot be edited.')
                ->danger()
                ->send();
            return;
        }

        $cart = [];
        $itemDiscountSum = 0;

        foreach ($sale->items as $item) {

            $batch = $item->batch;

            if (! $batch || ! $batch->medicine) {
                continue;
            }

            $oldBase = (float) $item->quantity * (float) ($item->conversion_factor ?: 1);

            // Current stock + this sale's own quantity (in memory only)
            $batch->quantity = (float) $batch->quantity + $oldBase;

            $units = $this->buildSaleUnits($batch);

            $unit = collect($units)->first(
                fn ($u) => strcasecmp((string) $u['name'], (string) $item->sale_unit) === 0
            ) ?? collect($units)->first(
                fn ($u) => abs((float) $u['conversion_factor'] - (float) $item->conversion_factor) < 0.0001
            );

            if (! $unit) {
                continue;
            }

            $key = $batch->id . '_' . $unit['key'];
            $qty = (int) $item->quantity;
            $discount = (float) $item->discount;
            $price = (float) $unit['price'];

            if (isset($cart[$key])) {
                $cart[$key]['quantity'] += $qty;
                $cart[$key]['total'] = max(0, ($price * $cart[$key]['quantity']) - $cart[$key]['discount']);
                continue;
            }

            $itemDiscountSum += $discount;

            $cart[$key] = [
                'batch_id'          => $batch->id,
                'medicine_id'       => $batch->medicine_id,
                'name'              => $batch->medicine->name,
                'generic_name'      => $batch->medicine->generic_name,
                'batch_number'      => $batch->batch_number,
                'expiry_date'       => $batch->expiry_date,
                'stock_quantity'    => (float) $batch->quantity,
                'sale_unit'         => $unit['name'],
                'unit_key'          => $unit['key'],
                'conversion_factor' => (float) $unit['conversion_factor'],
                'price'             => $price,
                'base_price'        => $price,
                'quantity'          => $qty,
                'discount'          => $discount,
                'total'             => max(0, ($price * $qty) - $discount),
                'available_units'   => $units,
            ];
        }

        $this->cart = $cart;
        $this->customerId = $sale->customer_id;
        $this->paymentMethod = $sale->payment_method ?: 'cash';
        $this->discount = max(0, (float) $sale->discount - $itemDiscountSum);
        $this->tax = (float) $sale->tax;
        $this->paidAmount = (float) $sale->paid_amount;
        $this->editingSaleId = $sale->id;
    }

    public function cancelEdit(): void
    {
        $this->resetPos();
        $this->editingSaleId = null;

        $this->redirect(url('/cashier/sale-history'));
    }

    public function updateSale(): void
    {
        if (! $this->editingSaleId) {
            return;
        }

        if (empty($this->cart)) {
            Notification::make()
                ->title('Cart is empty')
                ->body('Add at least one product before updating the sale.')
                ->danger()
                ->send();
            return;
        }

        $totals = $this->calculateTotals();
        $paidAmount = max(0, (float) ($this->paidAmount ?? 0));
        $grandTotal = max(0, (float) ($totals['grandTotal'] ?? 0));

        if ($grandTotal <= 0) {
            Notification::make()
                ->title('Invalid sale amount')
                ->body('The sale total must be greater than zero.')
                ->danger()
                ->send();
            return;
        }

        $changeAmount = max(0, $paidAmount - $grandTotal);

        try {

            $sale = DB::transaction(function () use ($totals, $paidAmount, $grandTotal, $changeAmount) {

                $sale = Sale::query()->lockForUpdate()->findOrFail($this->editingSaleId);

                if ($sale->returns()->exists()) {
                    throw new \RuntimeException('A sale with returned items cannot be edited.');
                }

                // Delete old items one by one so the observer restores stock
                foreach ($sale->items()->get() as $oldItem) {
                    $oldItem->delete();
                }

                foreach ($this->cart as $item) {

                    $batchId = (int) ($item['batch_id'] ?? 0);
                    $quantity = (int) ($item['quantity'] ?? 0);
                    $unitKey = (string) ($item['unit_key'] ?? '');

                    if ($batchId <= 0 || $quantity <= 0 || $unitKey === '') {
                        throw new \RuntimeException('Invalid product, quantity or unit in cart.');
                    }

                    $batch = MedicineBatch::query()
                        ->with(['medicine.unit'])
                        ->lockForUpdate()
                        ->find($batchId);

                    if (! $batch || ! $batch->medicine) {
                        throw new \RuntimeException('Product batch #' . $batchId . ' was not found.');
                    }

                    if (! $batch->status) {
                        throw new \RuntimeException('Batch ' . ($batch->batch_number ?: '#' . $batch->id) . ' is inactive.');
                    }

                    if ($batch->medicine->has_expiry) {
                        if (empty($batch->expiry_date)) {
                            throw new \RuntimeException('Batch ' . ($batch->batch_number ?: '#' . $batch->id) . ' has no expiry date.');
                        }

                        if (Carbon::parse($batch->expiry_date)->startOfDay()->lt(today()->startOfDay())) {
                            throw new \RuntimeException('Batch ' . ($batch->batch_number ?: '#' . $batch->id) . ' has expired.');
                        }
                    }

                    $selectedUnit = collect($this->buildSaleUnits($batch))->firstWhere('key', $unitKey);

                    if (! $selectedUnit) {
                        throw new \RuntimeException('Sale unit is no longer available for ' . $batch->medicine->name . '.');
                    }

                    $conversionFactor = (float) $selectedUnit['conversion_factor'];
                    $unitPrice = (float) $selectedUnit['price'];
                    $saleUnit = trim((string) $selectedUnit['name']);

                    if ($conversionFactor <= 0 || $unitPrice <= 0 || $saleUnit === '') {
                        throw new \RuntimeException('Invalid unit or price for ' . $batch->medicine->name . '.');
                    }

                    if ((float) $batch->quantity < ($quantity * $conversionFactor)) {
                        throw new \RuntimeException(
                            'Insufficient stock for ' . $batch->medicine->name .
                            '. Available base stock: ' . $batch->quantity .
                            ', required: ' . ($quantity * $conversionFactor) . '.'
                        );
                    }

                    $itemDiscount = max(0, (float) ($item['discount'] ?? 0));

                    // Observer decrements stock on create
                    SaleItem::create([
                        'sale_id'           => $sale->id,
                        'medicine_id'       => $batch->medicine_id,
                        'medicine_batch_id' => $batch->id,
                        'sale_unit'         => $saleUnit,
                        'conversion_factor' => $conversionFactor,
                        'quantity'          => $quantity,
                        'unit_price'        => $unitPrice,
                        'discount'          => $itemDiscount,
                        'total'             => max(0, ($unitPrice * $quantity) - $itemDiscount),
                    ]);
                }

                $sale->update([
                    'customer_id'    => $this->customerId ?: null,
                    'subtotal'       => (float) ($totals['subtotal'] ?? 0),
                    'discount'       => (float) ($totals['discount'] ?? 0),
                    'tax'            => (float) ($totals['tax'] ?? 0),
                    'grand_total'    => $grandTotal,
                    'paid_amount'    => $paidAmount,
                    'change_amount'  => $changeAmount,
                    'payment_method' => $this->paymentMethod ?: 'cash',
                ]);

                SalePayment::where('sale_id', $sale->id)->delete();

                if ($paidAmount > 0) {
                    SalePayment::create([
                        'sale_id'          => $sale->id,
                        'amount'           => $paidAmount,
                        'payment_method'   => $this->paymentMethod ?: 'cash',
                        'reference_number' => null,
                        'notes'            => 'Updated from POS edit',
                        'received_by'      => auth()->id(),
                        'paid_at'          => now(),
                    ]);
                }

                return $sale;
            });

            $this->resetPos();
            $this->editingSaleId = null;

            Notification::make()
                ->title('Sale updated successfully')
                ->body('Invoice ' . $sale->invoice_number . ' updated. Total: ' . $this->money($grandTotal))
                ->success()
                ->send();

            $this->redirect(url('/cashier/sale-history'));

        } catch (\Throwable $e) {

            report($e);

            Notification::make()
                ->title('Sale could not be updated')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
    protected function resetPos(): void
    {
        $this->cart = [];

        $this->search = '';

        $this->customerId = null;

        $this->discount = 0;

        $this->tax = 0;

        $this->paidAmount = 0;

        $this->paymentMethod = 'cash';
    }


    /*
    |--------------------------------------------------------------------------
    | MONEY
    |--------------------------------------------------------------------------
    */

    protected function money(
        float $amount
    ): string {

        $symbol =
            function_exists(
                'currency_symbol'
            )
                ? currency_symbol()
                : '£';

        return $symbol .
            number_format(
                $amount,
                2
            );
    }
}