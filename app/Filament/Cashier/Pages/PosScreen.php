<?php

namespace App\Filament\Cashier\Pages;

use App\Models\Customer;
use App\Models\HeldSale;
use App\Models\MedicineBatch;
use App\Models\MedicineBatchPackaging;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SalePayment;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
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
                'medicine',
                'batchPackagings' => function ($query) {
                    $query
                        ->where('can_sell', true)
                        ->with('unit');
                },
            ])
            ->where('medicine_batches.status', true)
            ->where('medicine_batches.quantity', '>', 0)
            ->whereNotNull('medicine_batches.expiry_date')
            ->whereDate(
                'medicine_batches.expiry_date',
                '>=',
                today()
            )
            ->where(function ($query) use ($term) {
                $query
                    ->whereHas('medicine', function ($medicineQuery) use ($term) {
                        $medicineQuery->where(function ($q) use ($term) {
                            $q
                                ->where('name', 'like', "%{$term}%")
                                ->orWhere('generic_name', 'like', "%{$term}%")
                                ->orWhere('sku', 'like', "%{$term}%")
                                ->orWhere('barcode', 'like', "%{$term}%");
                        });
                    })
                    ->orWhere(
                        'batch_number',
                        'like',
                        "%{$term}%"
                    );
            })
            ->orderBy('expiry_date')
            ->limit(15)
            ->get();
    }

    return [
        'customers' => Customer::query()
            ->where('status', true)
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'phone',
            ]),

        'searchResults' => $searchResults,
// 'heldSales' => HeldSale::query()
//     ->where('status', 'held')
//     ->where('user_id', auth()->id())
//     ->with('customer')
//     ->latest()
//     ->get(),
'heldSales' => HeldSale::query()
    ->where('held_by', auth()->id())
    ->with('customer')
    ->latest()
    ->get(),
        'totals' => $this->calculateTotals(),
    ];
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

        $batch = MedicineBatch::query()
            ->with([
                'medicine',
                'batchPackagings' => function ($query) {
                    $query
                        ->where('can_sell', true)
                        ->with('unit');
                },
            ])
            ->where('status', true)
            ->where('quantity', '>', 0)
            ->whereNotNull('expiry_date')
            ->whereDate(
                'expiry_date',
                '>=',
                today()
            )
            ->where(function ($query) use ($term) {
                $query
                    ->whereHas('medicine', function ($medicineQuery) use ($term) {
                        $medicineQuery
                            ->where('barcode', $term)
                            ->orWhere('sku', $term);
                    })
                    ->orWhere(
                        'batch_number',
                        $term
                    );
            })
            ->orderBy('expiry_date')
            ->first();

        if (! $batch) {
            Notification::make()
                ->title('Medicine not available')
                ->body(
                    'No active, non-expired, in-stock batch was found.'
                )
                ->warning()
                ->send();

            return;
        }

        $this->addToCart((int) $batch->id);
    }

    /*
    |--------------------------------------------------------------------------
    | ADD MEDICINE TO CART
    |--------------------------------------------------------------------------
    */

    public function addToCart(int $batchId): void
    {
        $batch = MedicineBatch::query()
            ->with([
                'medicine',
                'batchPackagings' => function ($query) {
                    $query
                        ->where('can_sell', true)
                        ->with('unit');
                },
            ])
            ->where('id', $batchId)
            ->where('status', true)
            ->where('quantity', '>', 0)
            ->whereNotNull('expiry_date')
            ->whereDate(
                'expiry_date',
                '>=',
                today()
            )
            ->first();

        if (! $batch) {
            Notification::make()
                ->title('Medicine unavailable')
                ->body(
                    'This batch is expired, inactive, or out of stock.'
                )
                ->danger()
                ->send();

            return;
        }

        if (! $batch->medicine) {
            Notification::make()
                ->title('Medicine not found')
                ->body(
                    'The selected batch is not connected to a valid medicine.'
                )
                ->danger()
                ->send();

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | BATCH BASE PRICE
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | Only medicine_batches.sale_price is used.
        |
        | This is the price of ONE BASE UNIT.
        |
        */

        $basePrice = (float) ($batch->sale_price ?? 0);

        if ($basePrice <= 0) {
            Notification::make()
                ->title('Invalid sale price')
                ->body(
                    'This batch does not have a valid base-unit sale price.'
                )
                ->danger()
                ->send();

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | SALE UNITS
        |--------------------------------------------------------------------------
        |
        | Units come from medicine_batch_packagings.
        |
        */

       $packagings = $batch->batchPackagings
    ->filter(function ($packaging) {
        return $packaging->can_sell
            && $packaging->unit
            && (float) $packaging->conversion_factor > 0;
    })
    ->sortBy('sort_order')
    ->values();
        if ($packagings->isEmpty()) {
            Notification::make()
                ->title('Sale units not configured')
                ->body(
                    'Please configure at least one sellable unit for this batch.'
                )
                ->danger()
                ->send();

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | DEFAULT SALE UNIT
        |--------------------------------------------------------------------------
        |
        | First packaging according to sort_order.
        |
        */

        $selectedPackaging = $packagings->first();

        $unitId = (int) $selectedPackaging->unit_id;

        $unitName = trim(
            (string) $selectedPackaging->unit->name
        );

        $conversionFactor = (float) $selectedPackaging->conversion_factor;

        if (
            $unitId <= 0 ||
            $unitName === '' ||
            $conversionFactor <= 0
        ) {
            Notification::make()
                ->title('Invalid sale unit')
                ->body(
                    'The selected batch has an invalid sale-unit configuration.'
                )
                ->danger()
                ->send();

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | UNIT PRICE
        |--------------------------------------------------------------------------
        |
        | Base price × conversion factor.
        |
        | Example:
        |
        | Base price = 30
        | Strip = 10
        |
        | Strip price = 30 × 10 = 300
        |
        */

        $unitPrice = round(
            $basePrice * $conversionFactor,
            2
        );

        /*
        |--------------------------------------------------------------------------
        | AVAILABLE UNITS FOR CART
        |--------------------------------------------------------------------------
        */

        $availableUnits = $packagings
            ->map(function ($packaging) use ($basePrice) {
                $conversionFactor =
                    (float) $packaging->conversion_factor;

                return [
                    'packaging_id' =>
                        (int) $packaging->id,

                    'unit_id' =>
                        (int) $packaging->unit_id,

                    'name' =>
                        trim((string) $packaging->unit->name),

                    'conversion_factor' =>
                        $conversionFactor,

                    /*
                     * Price is ALWAYS calculated
                     * from batch base price.
                     */
                    'price' =>
                        round(
                            $basePrice * $conversionFactor,
                            2
                        ),
                ];
            })
            ->values()
            ->all();

        /*
        |--------------------------------------------------------------------------
        | CART KEY
        |--------------------------------------------------------------------------
        */

        $key = (string) $batch->id;

        /*
        |--------------------------------------------------------------------------
        | EXISTING CART ITEM
        |--------------------------------------------------------------------------
        */

        if (isset($this->cart[$key])) {
            $currentQuantity = (int) (
                $this->cart[$key]['quantity'] ?? 0
            );

            $conversionFactor = (float) (
                $this->cart[$key]['conversion_factor'] ?? 1
            );

            $stockQuantity = (float) $batch->quantity;

            $newQuantity =
                $currentQuantity + 1;

            $requiredBaseUnits =
                $newQuantity * $conversionFactor;

            if ($requiredBaseUnits > $stockQuantity) {
                $availableQuantity = max(
                    0,
                    (int) floor(
                        $stockQuantity / $conversionFactor
                    )
                );

                Notification::make()
                    ->title('Stock limit reached')
                    ->body(
                        "Only {$availableQuantity} {$this->cart[$key]['sale_unit']} can be sold from this batch."
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

            'unit_id' =>
                $unitId,

            'name' =>
                $batch->medicine->name,

            'generic_name' =>
                $batch->medicine->generic_name,

            'batch_number' =>
                $batch->batch_number,

            'expiry_date' =>
                $batch->expiry_date,

            /*
             * Stock is always stored in BASE units.
             */
            'stock_quantity' =>
                (float) $batch->quantity,

            /*
             * Selected POS unit.
             */
            'sale_unit' =>
                $unitName,

            /*
             * Selected packaging ID.
             */
            'packaging_id' =>
                (int) $selectedPackaging->id,

            /*
             * Base-unit price from batch.
             */
            'base_price' =>
                $basePrice,

            /*
             * Conversion factor.
             */
            'conversion_factor' =>
                $conversionFactor,

            /*
             * Price of ONE selected sale unit.
             */
            'price' =>
                $unitPrice,

            'quantity' =>
                1,

            'discount' =>
                0,

            'total' =>
                $unitPrice,

            /*
             * All available units for dropdown.
             */
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
        $unitId
    ): void {
        if (! isset($this->cart[$key])) {
            return;
        }

        $unitId = (int) $unitId;

        if ($unitId <= 0) {
            return;
        }

        $batchId = (int) (
            $this->cart[$key]['batch_id'] ?? 0
        );

        if ($batchId <= 0) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Load exact batch packaging
        |--------------------------------------------------------------------------
        */

        $packaging = MedicineBatchPackaging::query()
            ->with('unit')
            ->where('medicine_batch_id', $batchId)
            ->where('unit_id', $unitId)
            ->where('can_sell', true)
            ->first();

        if (! $packaging || ! $packaging->unit) {
            Notification::make()
                ->title('Invalid sale unit')
                ->body(
                    'The selected unit is not configured for this batch.'
                )
                ->danger()
                ->send();

            return;
        }

        $conversionFactor =
            (float) $packaging->conversion_factor;

        if ($conversionFactor <= 0) {
            Notification::make()
                ->title('Invalid conversion')
                ->body(
                    'The selected unit has an invalid conversion factor.'
                )
                ->danger()
                ->send();

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Get current batch price
        |--------------------------------------------------------------------------
        |
        | Do NOT use packaging.sale_price.
        |
        */

        $batch = MedicineBatch::query()
            ->find($batchId);

        if (! $batch) {
            return;
        }

        $basePrice =
            (float) ($batch->sale_price ?? 0);

        if ($basePrice <= 0) {
            Notification::make()
                ->title('Invalid sale price')
                ->body(
                    'This batch does not have a valid base-unit sale price.'
                )
                ->danger()
                ->send();

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Check current quantity against new unit
        |--------------------------------------------------------------------------
        */

        $currentQuantity = max(
            1,
            (int) (
                $this->cart[$key]['quantity'] ?? 1
            )
        );

        $stockQuantity =
            (float) $batch->quantity;

        $requiredBaseUnits =
            $currentQuantity * $conversionFactor;

        if ($requiredBaseUnits > $stockQuantity) {
            $maxQuantity = max(
                1,
                (int) floor(
                    $stockQuantity / $conversionFactor
                )
            );

            $this->cart[$key]['quantity'] =
                $maxQuantity;

            Notification::make()
                ->title('Quantity adjusted')
                ->body(
                    "Maximum available quantity for {$packaging->unit->name} is {$maxQuantity}."
                )
                ->warning()
                ->send();
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate selected unit price
        |--------------------------------------------------------------------------
        */

        $unitPrice = round(
            $basePrice * $conversionFactor,
            2
        );

        /*
        |--------------------------------------------------------------------------
        | Update cart
        |--------------------------------------------------------------------------
        */

        $this->cart[$key]['unit_id'] =
            (int) $packaging->unit_id;

        $this->cart[$key]['packaging_id'] =
            (int) $packaging->id;

        $this->cart[$key]['sale_unit'] =
            trim((string) $packaging->unit->name);

        $this->cart[$key]['conversion_factor'] =
            $conversionFactor;

        $this->cart[$key]['base_price'] =
            $basePrice;

        $this->cart[$key]['price'] =
            $unitPrice;

        $this->updateCartTotal($key);
    }

    /*
    |--------------------------------------------------------------------------
    | INCREASE QUANTITY
    |--------------------------------------------------------------------------
    */

    public function increaseQuantity(string $key): void
    {
        if (! isset($this->cart[$key])) {
            return;
        }

        $currentQuantity = (int) (
            $this->cart[$key]['quantity'] ?? 0
        );

        $conversionFactor = (float) (
            $this->cart[$key]['conversion_factor'] ?? 1
        );

        if ($conversionFactor <= 0) {
            $conversionFactor = 1;
        }

        $stockQuantity = (float) (
            $this->cart[$key]['stock_quantity'] ?? 0
        );

        $newQuantity =
            $currentQuantity + 1;

        /*
        |--------------------------------------------------------------------------
        | IMPORTANT
        |--------------------------------------------------------------------------
        |
        | Stock is BASE units.
        |
        | Required = sale quantity × conversion.
        |
        */

        $requiredBaseUnits =
            $newQuantity * $conversionFactor;

        if ($requiredBaseUnits > $stockQuantity) {
            $availableQuantity = max(
                0,
                (int) floor(
                    $stockQuantity / $conversionFactor
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

        $this->updateCartTotal($key);
    }

    /*
    |--------------------------------------------------------------------------
    | DECREASE QUANTITY
    |--------------------------------------------------------------------------
    */

    public function decreaseQuantity(string $key): void
    {
        if (! isset($this->cart[$key])) {
            return;
        }

        if (
            (int) $this->cart[$key]['quantity'] <= 1
        ) {
            unset($this->cart[$key]);

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

        $stockQuantity = (float) (
            $this->cart[$key]['stock_quantity'] ?? 0
        );

        $conversionFactor = (float) (
            $this->cart[$key]['conversion_factor'] ?? 1
        );

        if ($conversionFactor <= 0) {
            $conversionFactor = 1;
        }

        /*
        |--------------------------------------------------------------------------
        | Maximum SALE quantity
        |--------------------------------------------------------------------------
        */

        $maxQuantity = max(
            1,
            (int) floor(
                $stockQuantity / $conversionFactor
            )
        );

        if ($quantity > $maxQuantity) {
            $quantity = $maxQuantity;

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

        $this->updateCartTotal($key);
    }

    /*
    |--------------------------------------------------------------------------
    | REMOVE
    |--------------------------------------------------------------------------
    */

    public function removeFromCart(string $key): void
    {
        unset($this->cart[$key]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE ITEM TOTAL
    |--------------------------------------------------------------------------
    */

    protected function updateCartTotal(string $key): void
    {
        if (! isset($this->cart[$key])) {
            return;
        }

        $quantity = (int) (
            $this->cart[$key]['quantity'] ?? 0
        );

        $price = (float) (
            $this->cart[$key]['price'] ?? 0
        );

        $this->cart[$key]['total'] =
            round(
                $quantity * $price,
                2
            );
    }

    /*
    |--------------------------------------------------------------------------
    | TOTALS
    |--------------------------------------------------------------------------
    */

    public function calculateTotals(): array
    {
        $subtotal = collect($this->cart)
            ->sum(function ($item) {
                return (float) (
                    $item['total'] ?? 0
                );
            });

        $discount = max(
            0,
            (float) ($this->discount ?? 0)
        );

        $tax = max(
            0,
            (float) ($this->tax ?? 0)
        );

        $grandTotal = max(
            0,
            $subtotal -
            $discount +
            $tax
        );

        $paid = max(
            0,
            (float) ($this->paidAmount ?? 0)
        );

        $change = max(
            0,
            $paid - $grandTotal
        );

        $due = max(
            0,
            $grandTotal - $paid
        );

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'grandTotal' => $grandTotal,
            'paid' => $paid,
            'change' => $change,
            'due' => $due,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | HOLD SALE
    |--------------------------------------------------------------------------
    */

    public function holdSale(): void
{
    /*
    |--------------------------------------------------------------------------
    | CART CHECK
    |--------------------------------------------------------------------------
    */

    if (empty($this->cart)) {
        Notification::make()
            ->title('Cart is empty')
            ->body(
                'Add at least one medicine before holding the sale.'
            )
            ->warning()
            ->send();

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | CALCULATE TOTALS
    |--------------------------------------------------------------------------
    */

    $totals = $this->calculateTotals();

    $grandTotal = max(
        0,
        (float) ($totals['grandTotal'] ?? 0)
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
        | CREATE UNIQUE HOLD NUMBER
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
        | SAVE HELD SALE
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | We only save the POS snapshot.
        |
        | No Sale is created.
        | No SaleItem is created.
        | Therefore stock is NOT deducted.
        |
        */

        $heldSale = HeldSale::create([
            'hold_number' => $holdNumber,

            'user_id' => auth()->id(),

            'customer_id' =>
                $this->customerId ?: null,

            'cart' => $this->cart,

            'subtotal' =>
                (float) $totals['subtotal'],

            'discount' =>
                (float) $totals['discount'],

            'tax' =>
                (float) $totals['tax'],

            'grand_total' =>
                $grandTotal,

            'paid_amount' =>
                max(
                    0,
                    (float) ($this->paidAmount ?? 0)
                ),

            'payment_method' =>
                $this->paymentMethod ?: 'cash',

            'status' => 'held',
        ]);

        /*
        |--------------------------------------------------------------------------
        | CLEAR CURRENT POS
        |--------------------------------------------------------------------------
        */

        $this->cart = [];

        $this->search = '';

        $this->customerId = null;

        $this->discount = 0;

        $this->tax = 0;

        $this->paidAmount = 0;

        $this->paymentMethod = 'cash';

        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        Notification::make()
            ->title('Sale held successfully')
            ->body(
                'Hold ' .
                $heldSale->hold_number .
                ' has been saved. You can resume it later.'
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

public function resumeHeldSale(int $heldSaleId): void
{
    /*
    |--------------------------------------------------------------------------
    | DO NOT RESUME IF CURRENT CART HAS ITEMS
    |--------------------------------------------------------------------------
    */

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

        $heldSale = HeldSale::query()
            ->where('id', $heldSaleId)
            ->where('user_id', auth()->id())
            ->where('status', 'held')
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
        | VALIDATE EVERY CART ITEM AGAINST CURRENT STOCK
        |--------------------------------------------------------------------------
        */

        foreach ($heldSale->cart as $key => $item) {

            $batchId = (int) (
                $item['batch_id'] ?? 0
            );

            $packagingId = (int) (
                $item['packaging_id'] ?? 0
            );

            $quantity = (int) (
                $item['quantity'] ?? 0
            );

            if (
                $batchId <= 0 ||
                $packagingId <= 0 ||
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

            $batch = MedicineBatch::query()
                ->with('medicine')
                ->find($batchId);

            if (! $batch) {
                throw new \RuntimeException(
                    'A medicine batch from this held sale no longer exists.'
                );
            }

            if (! $batch->medicine) {
                throw new \RuntimeException(
                    'A medicine from this held sale no longer exists.'
                );
            }

            if (! $batch->status) {
                throw new \RuntimeException(
                    'Medicine batch ' .
                    ($batch->batch_number ?: '#' . $batch->id) .
                    ' is inactive.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | EXPIRY
            |--------------------------------------------------------------------------
            */

            if (empty($batch->expiry_date)) {
                throw new \RuntimeException(
                    'Medicine batch ' .
                    ($batch->batch_number ?: '#' . $batch->id) .
                    ' has no expiry date.'
                );
            }

            $expiryDate = Carbon::parse(
                $batch->expiry_date
            )->startOfDay();

            if ($expiryDate->lt(today()->startOfDay())) {
                throw new \RuntimeException(
                    'Medicine batch ' .
                    ($batch->batch_number ?: '#' . $batch->id) .
                    ' has expired.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | PACKAGING
            |--------------------------------------------------------------------------
            */

            $packaging = MedicineBatchPackaging::query()
                ->with('unit')
                ->where('id', $packagingId)
                ->where(
                    'medicine_batch_id',
                    $batch->id
                )
                ->where('can_sell', true)
                ->first();

            if (! $packaging || ! $packaging->unit) {
                throw new \RuntimeException(
                    'The selected sale unit is no longer available for ' .
                    ($batch->batch_number ?: '#' . $batch->id) .
                    '.'
                );
            }

            $conversionFactor =
                (float) $packaging->conversion_factor;

            if ($conversionFactor <= 0) {
                throw new \RuntimeException(
                    'Invalid conversion factor in held sale.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | CURRENT STOCK
            |--------------------------------------------------------------------------
            */

            $stockQuantity =
                (float) ($batch->quantity ?? 0);

            $maxQuantity = (int) floor(
                $stockQuantity /
                $conversionFactor
            );

            if ($maxQuantity < 1) {
                throw new \RuntimeException(
                    'No stock is currently available for ' .
                    $batch->medicine->name .
                    '.'
                );
            }

            if ($quantity > $maxQuantity) {
                throw new \RuntimeException(
                    $batch->medicine->name .
                    ' only has ' .
                    $maxQuantity .
                    ' ' .
                    $packaging->unit->name .
                    ' available now.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | CURRENT BASE PRICE
            |--------------------------------------------------------------------------
            */

            $basePrice =
                (float) ($batch->sale_price ?? 0);

            if ($basePrice <= 0) {
                throw new \RuntimeException(
                    'Invalid sale price for ' .
                    $batch->medicine->name .
                    '.'
                );
            }

            $unitPrice = round(
                $basePrice *
                $conversionFactor,
                2
            );

            /*
            |--------------------------------------------------------------------------
            | AVAILABLE SALE UNITS
            |--------------------------------------------------------------------------
            */

            $availableUnits =
                MedicineBatchPackaging::query()
                    ->with('unit')
                    ->where(
                        'medicine_batch_id',
                        $batch->id
                    )
                    ->where('can_sell', true)
                    ->get()
                    ->filter(function ($packaging) {
                        return
                            $packaging->unit &&
                            (float) $packaging->conversion_factor > 0;
                    })
                    ->sortBy('sort_order')
                    ->values()
                    ->map(function ($packaging) use (
                        $batch,
                        $stockQuantity
                    ) {
                        $conversion =
                            (float) $packaging->conversion_factor;

                        $availableQuantity =
                            (int) floor(
                                $stockQuantity /
                                $conversion
                            );

                        return [
                            'packaging_id' =>
                                (int) $packaging->id,

                            'unit_id' =>
                                (int) $packaging->unit_id,

                            'name' =>
                                trim(
                                    (string) $packaging->unit->name
                                ),

                            'conversion_factor' =>
                                $conversion,

                            'price' =>
                                round(
                                    (float) $batch->sale_price *
                                    $conversion,
                                    2
                                ),

                            'available_quantity' =>
                                $availableQuantity,

                            'available' =>
                                $availableQuantity > 0,
                        ];
                    })
                    ->toArray();

            /*
            |--------------------------------------------------------------------------
            | REBUILD CART ITEM
            |--------------------------------------------------------------------------
            */

            $item['batch_id'] =
                (int) $batch->id;

            $item['medicine_id'] =
                (int) $batch->medicine_id;

            $item['unit_id'] =
                (int) $packaging->unit_id;

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
                trim(
                    (string) $packaging->unit->name
                );

            $item['packaging_id'] =
                (int) $packaging->id;

            $item['base_price'] =
                $basePrice;

            $item['conversion_factor'] =
                $conversionFactor;

            $item['price'] =
                $unitPrice;

            $item['quantity'] =
                $quantity;

            $item['total'] =
                round(
                    $quantity * $unitPrice,
                    2
                );

            $item['available_units'] =
                $availableUnits;

            $restoredCart[(string) $key] =
                $item;
        }

        /*
        |--------------------------------------------------------------------------
        | RESTORE POS DATA
        |--------------------------------------------------------------------------
        */

        $this->cart = $restoredCart;

        $this->customerId =
            $heldSale->customer_id;

        $this->discount =
            (float) $heldSale->discount;

        $this->tax =
            (float) $heldSale->tax;

        $this->paidAmount =
            (float) $heldSale->paid_amount;

        $this->paymentMethod =
            $heldSale->payment_method ?: 'cash';

        /*
        |--------------------------------------------------------------------------
        | DELETE HELD RECORD
        |--------------------------------------------------------------------------
        |
        | Once resumed, it is removed from the active held list.
        |
        */

        $heldSale->delete();

        Notification::make()
            ->title('Sale resumed')
            ->body(
                'Hold ' .
                $heldSale->hold_number .
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

public function cancelHeldSale(int $heldSaleId): void
{
    try {

        $heldSale = HeldSale::query()
            ->where('id', $heldSaleId)
            ->where('user_id', auth()->id())
            ->where('status', 'held')
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
                    'Please add at least one medicine before completing the sale.'
                )
                ->danger()
                ->send();

            return;
        }

        $totals = $this->calculateTotals();

        $paidAmount = max(
            0,
            (float) ($this->paidAmount ?? 0)
        );

        $grandTotal = max(
            0,
            (float) ($totals['grandTotal'] ?? 0)
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

        if ($paidAmount < $grandTotal) {
            $dueAmount =
                $grandTotal - $paidAmount;

            Notification::make()
                ->title('Insufficient payment')
                ->body(
                    'Customer still has a due amount of ' .
                    $this->money($dueAmount)
                )
                ->danger()
                ->send();

            return;
        }

        $changeAmount = max(
            0,
            $paidAmount - $grandTotal
        );

        try {
            $sale = DB::transaction(
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
                            now()->format('YmdHis') .
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

                    $sale = Sale::create([
                        'invoice_number' =>
                            $invoiceNumber,

                        'customer_id' =>
                            $this->customerId ?: null,

                        'subtotal' =>
                            (float) $totals['subtotal'],

                        'discount' =>
                            (float) $totals['discount'],

                        'tax' =>
                            (float) $totals['tax'],

                        'grand_total' =>
                            $grandTotal,

                        'paid_amount' =>
                            $paidAmount,

                        'change_amount' =>
                            $changeAmount,

                        'payment_method' =>
                            $this->paymentMethod ?: 'cash',

                        'created_by' =>
                            auth()->id(),
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | SALE ITEMS
                    |--------------------------------------------------------------------------
                    */

                    foreach ($this->cart as $item) {
                        $batchId = (int) (
                            $item['batch_id'] ?? 0
                        );

                        $quantity = (int) (
                            $item['quantity'] ?? 0
                        );

                        $cartMedicineId = (int) (
                            $item['medicine_id'] ?? 0
                        );

                        $packagingId = (int) (
                            $item['packaging_id'] ?? 0
                        );

                        if (
                            $batchId <= 0 ||
                            $quantity <= 0
                        ) {
                            throw new \RuntimeException(
                                'Invalid medicine batch or quantity.'
                            );
                        }

                        if ($cartMedicineId <= 0) {
                            throw new \RuntimeException(
                                'Invalid medicine selected for sale.'
                            );
                        }

                        if ($packagingId <= 0) {
                            throw new \RuntimeException(
                                'No valid sale unit was selected.'
                            );
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | LOCK EXACT BATCH
                        |--------------------------------------------------------------------------
                        */

                        $batch = MedicineBatch::query()
                            ->lockForUpdate()
                            ->find($batchId);

                        if (! $batch) {
                            throw new \RuntimeException(
                                'Medicine batch #' .
                                $batchId .
                                ' was not found.'
                            );
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | MEDICINE MATCH
                        |--------------------------------------------------------------------------
                        */

                        if (
                            (int) $batch->medicine_id !==
                            $cartMedicineId
                        ) {
                            throw new \RuntimeException(
                                'Selected medicine does not belong to batch #' .
                                $batch->id .
                                '.'
                            );
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | VERIFY PACKAGING
                        |--------------------------------------------------------------------------
                        */

                        $packaging = \App\Models\MedicineBatchPackaging::query()
                            ->with('unit')
                            ->where('id', $packagingId)
                            ->where(
                                'medicine_batch_id',
                                $batch->id
                            )
                            ->where('can_sell', true)
                            ->first();

                        if (! $packaging || ! $packaging->unit) {
                            throw new \RuntimeException(
                                'The selected sale unit is no longer available for this batch.'
                            );
                        }

                        $conversionFactor =
                            (float) $packaging->conversion_factor;

                        if ($conversionFactor <= 0) {
                            throw new \RuntimeException(
                                'Invalid conversion factor for sale unit.'
                            );
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | SALE UNIT
                        |--------------------------------------------------------------------------
                        */

                        $realSaleUnit = trim(
                            (string) $packaging->unit->name
                        );

                        if ($realSaleUnit === '') {
                            throw new \RuntimeException(
                                'The selected sale unit name is invalid.'
                            );
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | STATUS
                        |--------------------------------------------------------------------------
                        */

                        if (! $batch->status) {
                            throw new \RuntimeException(
                                'The selected medicine batch is inactive.'
                            );
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | EXPIRY
                        |--------------------------------------------------------------------------
                        */

                        if (empty($batch->expiry_date)) {
                            throw new \RuntimeException(
                                'The selected medicine batch has no expiry date and cannot be sold.'
                            );
                        }

                        $expiryDate = Carbon::parse(
                            $batch->expiry_date
                        )->startOfDay();

                        $today =
                            today()->startOfDay();

                        if ($expiryDate->lt($today)) {
                            throw new \RuntimeException(
                                'Cannot sell expired medicine. Batch ' .
                                (
                                    $batch->batch_number
                                    ?: '#' . $batch->id
                                ) .
                                ' expired on ' .
                                $expiryDate->format('d-m-Y') .
                                '.'
                            );
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | STOCK
                        |--------------------------------------------------------------------------
                        |
                        | IMPORTANT:
                        |
                        | Batch stock is stored in BASE units.
                        |
                        | Example:
                        |
                        | Strip conversion = 10
                        | Qty = 2
                        |
                        | Required base stock = 20
                        |
                        */

                        $requiredQuantity =
                            $quantity * $conversionFactor;

                        $availableStock =
                            (float) ($batch->quantity ?? 0);

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
                        | BASE PRICE
                        |--------------------------------------------------------------------------
                        |
                        | ONLY batch sale_price is used.
                        |
                        */

                        $basePrice =
                            (float) ($batch->sale_price ?? 0);

                        if ($basePrice <= 0) {
                            throw new \RuntimeException(
                                'Invalid base-unit sale price for batch #' .
                                $batch->id .
                                '.'
                            );
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | SELECTED UNIT PRICE
                        |--------------------------------------------------------------------------
                        */

                        $unitPrice = round(
                            $basePrice *
                            $conversionFactor,
                            2
                        );

                        /*
                        |--------------------------------------------------------------------------
                        | ITEM DISCOUNT
                        |--------------------------------------------------------------------------
                        */

                        $itemDiscount = max(
                            0,
                            (float) (
                                $item['discount'] ?? 0
                            )
                        );

                        $itemTotal = max(
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
                                $realSaleUnit,

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
                        | Stock is NOT manually decreased here.
                        |
                        | SaleItemObserver should deduct:
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

            $this->cart = [];

            $this->search = '';

            $this->customerId = null;

            $this->discount = 0;

            $this->tax = 0;

            $this->paidAmount = 0;

            $this->paymentMethod = 'cash';

            Notification::make()
                ->title(
                    'Sale completed successfully'
                )
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
    | MONEY
    |--------------------------------------------------------------------------
    */

    protected function money(float $amount): string
    {
        $symbol = function_exists('currency_symbol')
            ? currency_symbol()
            : '£';

        return $symbol .
            number_format(
                $amount,
                2
            );
    }
}