<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SalePayment;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PosScreen extends Component
{
    /*
    |--------------------------------------------------------------------------
    | POS Properties
    |--------------------------------------------------------------------------
    */

   public string $search = '';

public array $cart = [];

public ?int $customerId = null;

public float $discount = 0;

public float $tax = 0;

public string $paymentMethod = 'cash';

public $paidAmount = 0;
    
    


    /*
    |--------------------------------------------------------------------------
    | Customers
    |--------------------------------------------------------------------------
    */

    public function getCustomersProperty()
    {
        return Customer::query()
            ->where('status', true)
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'phone',
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Medicine Search Results
    |--------------------------------------------------------------------------
    */

    public function getMedicineSearchResults()
    {
        $search = trim($this->search);

        if ($search === '') {
            return collect();
        }

        return Medicine::query()
            ->where('status', true)

            ->where(function ($query) use ($search) {
                $query
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('generic_name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('barcode', $search);
            })

            ->whereHas('batches', function ($query) {
                $query
                    ->where('status', true)
                    ->where('quantity', '>', 0)
                    ->whereDate('expiry_date', '>=', today());
            })

            ->with([
                'batches' => function ($query) {
                    $query
                        ->where('status', true)
                        ->where('quantity', '>', 0)
                        ->whereDate('expiry_date', '>=', today())
                        ->orderBy('expiry_date', 'asc');
                },
            ])

            ->limit(10)
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | Add Medicine To Cart
    |--------------------------------------------------------------------------
    |
    | FEFO:
    | First Expire, First Out
    |
    */

    public function addToCart(int $medicineId): void
    {
        $medicine = Medicine::query()
            ->where('status', true)
            ->find($medicineId);

        if (! $medicine) {
            $this->notify(
                'Medicine not found.',
                'error'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Prescription Warning
        |--------------------------------------------------------------------------
        */

        if ($medicine->prescription_required) {
            $this->notify(
                'Prescription required for ' . $medicine->name . '.',
                'warning'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FEFO Batch
        |--------------------------------------------------------------------------
        */

        $batch = MedicineBatch::query()
            ->where('medicine_id', $medicine->id)
            ->where('status', true)
            ->where('quantity', '>', 0)
            ->whereDate('expiry_date', '>', today())
            ->orderBy('expiry_date', 'asc')
            ->first();

        if (! $batch) {
            $this->notify(
                'No available non-expired stock for ' . $medicine->name . '.',
                'error'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Existing Batch Already In Cart
        |--------------------------------------------------------------------------
        */

        if (isset($this->cart[$batch->id])) {

            $newQuantity =
                (int) $this->cart[$batch->id]['quantity'] + 1;

            if ($newQuantity > (int) $batch->quantity) {
                $this->notify(
                    'Only ' . $batch->quantity .
                    ' units available in this batch.',
                    'warning'
                );

                return;
            }

            $this->cart[$batch->id]['quantity'] = $newQuantity;

            $this->cart[$batch->id]['available_qty'] =
                (int) $batch->quantity;

            $this->recalculateCartItem($batch->id);
        }


        /*
        |--------------------------------------------------------------------------
        | New Cart Item
        |--------------------------------------------------------------------------
        */

        else {

            $unitPrice = (float) $batch->sale_price;

            $this->cart[$batch->id] = [
                'medicine_id' => (int) $medicine->id,

                'medicine_name' => $medicine->name,

                'batch_id' => (int) $batch->id,

                'batch_number' => $batch->batch_number,

                'expiry_date' => $batch->expiry_date
                    ? $batch->expiry_date->format('d M Y')
                    : '',

                'available_qty' => (int) $batch->quantity,

                'quantity' => 1,
                'unit_price' => (float) $medicine->sale_price,
                'discount' => 0,

                'total' => $unitPrice,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Clear Search
        |--------------------------------------------------------------------------
        */

        $this->search = '';

        $this->dispatch('focus-search');
    }


    /*
    |--------------------------------------------------------------------------
    | Barcode Scan
    |--------------------------------------------------------------------------
    */

    public function scanBarcode(): void
    {
        $barcode = trim($this->search);

        if ($barcode === '') {
            return;
        }

        $medicine = Medicine::query()
            ->where('status', true)
            ->where('barcode', $barcode)
            ->first();

        if (! $medicine) {
            $this->notify(
                'No medicine found for barcode: ' . $barcode,
                'error'
            );

            $this->search = '';

            $this->dispatch('focus-search');

            return;
        }

        $this->addToCart($medicine->id);
    }


    /*
    |--------------------------------------------------------------------------
    | Remove Cart Item
    |--------------------------------------------------------------------------
    */

    public function removeFromCart(int $batchId): void
    {
        unset($this->cart[$batchId]);

        /*
        |--------------------------------------------------------------------------
        | IMPORTANT
        |--------------------------------------------------------------------------
        |
        | Do NOT use array_values() here.
        |
        | Cart is indexed by batch ID and other methods depend
        | on the batch ID remaining as the array key.
        |
        */
    }


    /*
    |--------------------------------------------------------------------------
    | Update Quantity
    |--------------------------------------------------------------------------
    */

    public function updateQuantity(int $batchId, $qty): void
    {
        if (! isset($this->cart[$batchId])) {
            return;
        }

        $qty = (int) $qty;

        if ($qty < 1) {
            $qty = 1;
        }


        /*
        |--------------------------------------------------------------------------
        | Get Current Batch Stock
        |--------------------------------------------------------------------------
        */

        $batch = MedicineBatch::query()
            ->find($batchId);

        if (! $batch) {
            unset($this->cart[$batchId]);

            $this->notify(
                'Selected batch no longer exists.',
                'error'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Check Active Status
        |--------------------------------------------------------------------------
        */

        if (! $batch->status) {
            unset($this->cart[$batchId]);

            $this->notify(
                'This batch is inactive.',
                'error'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Check Expiry
        |--------------------------------------------------------------------------
        */

        if (
            ! $batch->expiry_date ||
            $batch->expiry_date->lte(today())
        ) {
            unset($this->cart[$batchId]);

            $this->notify(
                'This batch is expired.',
                'error'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Check Stock
        |--------------------------------------------------------------------------
        */

        if ((int) $batch->quantity <= 0) {
            unset($this->cart[$batchId]);

            $this->notify(
                'This batch is out of stock.',
                'error'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Quantity Cannot Exceed Available Stock
        |--------------------------------------------------------------------------
        */

        if ($qty > (int) $batch->quantity) {
            $qty = (int) $batch->quantity;

            $this->notify(
                'Only ' . $batch->quantity .
                ' units available.',
                'warning'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Update Cart
        |--------------------------------------------------------------------------
        */

        $this->cart[$batchId]['quantity'] = $qty;

        $this->cart[$batchId]['available_qty'] =
            (int) $batch->quantity;

        $this->recalculateCartItem($batchId);
    }


    /*
    |--------------------------------------------------------------------------
    | Recalculate Single Cart Item
    |--------------------------------------------------------------------------
    */

    protected function recalculateCartItem(int $batchId): void
    {
        if (! isset($this->cart[$batchId])) {
            return;
        }

        $item = $this->cart[$batchId];

        $quantity = max(
            1,
            (int) ($item['quantity'] ?? 1)
        );

        $unitPrice = max(
            0,
            (float) ($item['unit_price'] ?? 0)
        );

        $gross = $unitPrice * $quantity;

        $discount = min(
            max(
                0,
                (float) ($item['discount'] ?? 0)
            ),
            $gross
        );

        $this->cart[$batchId]['quantity'] = $quantity;

        $this->cart[$batchId]['discount'] = $discount;

        $this->cart[$batchId]['total'] =
            max(
                0,
                $gross - $discount
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Subtotal
    |--------------------------------------------------------------------------
    */

    public function getSubtotalProperty(): float
    {
        $subtotal = collect($this->cart)
            ->sum(function ($item) {

                $unitPrice = max(
                    0,
                    (float) ($item['unit_price'] ?? 0)
                );

                $quantity = max(
                    0,
                    (int) ($item['quantity'] ?? 0)
                );

                $discount = max(
                    0,
                    (float) ($item['discount'] ?? 0)
                );

                $gross = $unitPrice * $quantity;

                return max(
                    0,
                    $gross - min($discount, $gross)
                );
            });

        return round(
            max(0, $subtotal),
            2
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Grand Total
    |--------------------------------------------------------------------------
    */

    public function getGrandTotalProperty(): float
    {
        $subtotal = max(
            0,
            (float) $this->subtotal
        );

        $tax = max(
            0,
            (float) $this->tax
        );

        $discount = max(
            0,
            (float) $this->discount
        );

        return round(
            max(
                0,
                $subtotal + $tax - $discount
            ),
            2
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Change
    |--------------------------------------------------------------------------
    */

    public function getChangeProperty(): float
    {
        $paidAmount = max(
            0,
            (float) $this->paidAmount
        );

        $grandTotal = max(
            0,
            (float) $this->grandTotal
        );

        return round(
            max(
                0,
                $paidAmount - $grandTotal
            ),
            2
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Due Amount
    |--------------------------------------------------------------------------
    */

    public function getDueAmountProperty(): float
    {
        $paidAmount = max(
            0,
            (float) $this->paidAmount
        );

        $grandTotal = max(
            0,
            (float) $this->grandTotal
        );

        return round(
            max(
                0,
                $grandTotal - $paidAmount
            ),
            2
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Complete Sale
    |--------------------------------------------------------------------------
    */

    public function completeSale()
    {
        /*
        |--------------------------------------------------------------------------
        | Cart Validation
        |--------------------------------------------------------------------------
        */

        if (empty($this->cart)) {
            $this->notify(
                'Cart is empty.',
                'error'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Sanitize Financial Values
        |--------------------------------------------------------------------------
        */

        $this->discount = max(
            0,
            (float) $this->discount
        );

        $this->tax = max(
            0,
            (float) $this->tax
        );

        $this->paidAmount = max(
            0,
            (float) $this->paidAmount
        );


        /*
        |--------------------------------------------------------------------------
        | Calculate Final Total Before Transaction
        |--------------------------------------------------------------------------
        */

        $grandTotal = $this->grandTotal;


        /*
        |--------------------------------------------------------------------------
        | Payment Validation
        |--------------------------------------------------------------------------
        |
        | Current POS requires full payment.
        |
        */

        if ($this->paidAmount < $grandTotal) {
            $this->notify(
                'Paid amount is less than the grand total.',
                'error'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Payment Method
        |--------------------------------------------------------------------------
        */

        $allowedPaymentMethods = [
            'cash',
            'card',
            'bank',
            'other',
        ];

        if (! in_array(
            $this->paymentMethod,
            $allowedPaymentMethods,
            true
        )) {
            $this->paymentMethod = 'cash';
        }


        /*
        |--------------------------------------------------------------------------
        | Sale ID
        |--------------------------------------------------------------------------
        */

        $saleId = null;


        /*
        |--------------------------------------------------------------------------
        | EVERYTHING INSIDE DATABASE TRANSACTION
        |--------------------------------------------------------------------------
        */

        try {

            DB::transaction(function () use (
                &$saleId,
                $grandTotal
            ) {

                /*
                |--------------------------------------------------------------------------
                | Generate Invoice Number
                |--------------------------------------------------------------------------
                */

                $invoiceNumber =
                    $this->generateInvoiceNumber();


                /*
                |--------------------------------------------------------------------------
                | Calculate Change
                |--------------------------------------------------------------------------
                */

                $changeAmount = round(
                    max(
                        0,
                        $this->paidAmount - $grandTotal
                    ),
                    2
                );


                /*
                |--------------------------------------------------------------------------
                | Create Sale
                |--------------------------------------------------------------------------
                */

                $sale = Sale::create([
                    'invoice_number' => $invoiceNumber,

                    'customer_id' =>
                        $this->customerId ?: null,

                    'subtotal' =>
                        $this->subtotal,

                    'discount' =>
                        $this->discount,

                    'tax' =>
                        $this->tax,

                    'grand_total' =>
                        $grandTotal,

                    /*
                    |--------------------------------------------------------------------------
                    | Amount Applied To Invoice
                    |--------------------------------------------------------------------------
                    */

                    'paid_amount' =>
                        $grandTotal,

                    /*
                    |--------------------------------------------------------------------------
                    | Extra Money Returned To Customer
                    |--------------------------------------------------------------------------
                    */

                    'change_amount' =>
                        $changeAmount,

                    'payment_method' =>
                        $this->paymentMethod,

                    'created_by' =>
                        auth()->id(),
                ]);


                /*
                |--------------------------------------------------------------------------
                | Sale Items + Stock Reduction
                |--------------------------------------------------------------------------
                */

                foreach ($this->cart as $item) {

                    /*
                    |--------------------------------------------------------------------------
                    | Validate Cart Item Structure
                    |--------------------------------------------------------------------------
                    */

                    if (
                        empty($item['batch_id']) ||
                        empty($item['medicine_id']) ||
                        empty($item['quantity'])
                    ) {
                        throw new \RuntimeException(
                            'Invalid cart item.'
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Lock Batch Row
                    |--------------------------------------------------------------------------
                    */

                    $batch = MedicineBatch::query()
                        ->whereKey($item['batch_id'])
                        ->lockForUpdate()
                        ->first();

                    if (! $batch) {
                        throw new \RuntimeException(
                            'Selected batch no longer exists.'
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Re-check Batch Status
                    |--------------------------------------------------------------------------
                    */

                    if (! $batch->status) {
                        throw new \RuntimeException(
                            'Batch ' .
                            $batch->batch_number .
                            ' is inactive.'
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Re-check Expiry
                    |--------------------------------------------------------------------------
                    */

                    if (
                        ! $batch->expiry_date ||
                        $batch->expiry_date->lte(now())
                    ) {
                        throw new \RuntimeException(
                            'Batch ' .
                            $batch->batch_number .
                            ' is expired.'
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Re-check Stock
                    |--------------------------------------------------------------------------
                    */

                    $quantity = (int) $item['quantity'];

                    if (
                        $quantity < 1 ||
                        $batch->quantity < $quantity
                    ) {
                        throw new \RuntimeException(
                            $item['medicine_name'] .
                            ' has only ' .
                            $batch->quantity .
                            ' units available.'
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Price Snapshot
                    |--------------------------------------------------------------------------
                    */

                    $unitPrice = max(
                        0,
                        (float) $item['unit_price']
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Item Gross
                    |--------------------------------------------------------------------------
                    */

                    $gross =
                        $unitPrice * $quantity;


                    /*
                    |--------------------------------------------------------------------------
                    | Item Discount
                    |--------------------------------------------------------------------------
                    */

                    $itemDiscount = min(
                        max(
                            0,
                            (float) ($item['discount'] ?? 0)
                        ),
                        $gross
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Item Total
                    |--------------------------------------------------------------------------
                    */

                    $itemTotal = max(
                        0,
                        $gross - $itemDiscount
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Create Sale Item
                    |--------------------------------------------------------------------------
                    */

                    SaleItem::create([
                        'sale_id' =>
                            $sale->id,

                        'medicine_id' =>
                            $item['medicine_id'],

                        'medicine_batch_id' =>
                            $batch->id,

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
                    | Reduce Batch Stock
                    |--------------------------------------------------------------------------
                    */

                    $batch->decrement(
                        'quantity',
                        $quantity
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Create Sale Payment
                |--------------------------------------------------------------------------
                |
                | Only the invoice amount is recorded as payment.
                |
                | Example:
                |
                | Grand Total = 800
                | Received = 1000
                | Payment = 800
                | Change = 200
                |
                */

                SalePayment::create([
                    'sale_id' =>
                        $sale->id,

                    'amount' =>
                        $grandTotal,

                    'payment_method' =>
                        $this->paymentMethod,

                    'reference_number' =>
                        null,

                    'notes' =>
                        null,

                    'received_by' =>
                        auth()->id(),

                    'paid_at' =>
                        now(),
                ]);


                /*
                |--------------------------------------------------------------------------
                | Store Sale ID
                |--------------------------------------------------------------------------
                */

                $saleId = $sale->id;
            });

        } catch (\Throwable $e) {

            report($e);

            $this->notify(
                $e->getMessage() ?: 'Unable to complete sale.',
                'error'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Success Message
        |--------------------------------------------------------------------------
        */

        session()->flash(
            'success',
            'Sale completed successfully.'
        );


        /*
        |--------------------------------------------------------------------------
        | Reset POS
        |--------------------------------------------------------------------------
        */

        $this->resetCart();


        /*
        |--------------------------------------------------------------------------
        | Redirect To Receipt
        |--------------------------------------------------------------------------
        */

       return redirect()->route(
    'filament.admin.resources.sales.view',
    [
        'record' => $saleId,
    ]
);
    }


    /*
    |--------------------------------------------------------------------------
    | Generate Invoice Number
    |--------------------------------------------------------------------------
    */

    protected function generateInvoiceNumber(): string
    {
        do {

            $invoice =
                'INV-' .
                now()->format('Ymd') .
                '-' .
                strtoupper(
                    substr(
                        bin2hex(
                            random_bytes(4)
                        ),
                        0,
                        6
                    )
                );

        } while (
            Sale::where(
                'invoice_number',
                $invoice
            )->exists()
        );

        return $invoice;
    }


    /*
    |--------------------------------------------------------------------------
    | Reset POS
    |--------------------------------------------------------------------------
    */

    protected function resetCart(): void
    {
        $this->search = '';

        $this->cart = [];

        $this->customerId = null;

        $this->discount = 0;

        $this->tax = 0;

        $this->paidAmount = 0;

        $this->paymentMethod = 'cash';
    }


    /*
    |--------------------------------------------------------------------------
    | Notification Helper
    |--------------------------------------------------------------------------
    */

    protected function notify(
        string $message,
        string $type = 'info'
    ): void {
        $this->dispatch(
            'notify',
            message: $message,
            type: $type
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        return view(
            'livewire.pos-screen',
            [
                'results' =>
                    $this->getMedicineSearchResults(),
            ]
        );
    }
}

