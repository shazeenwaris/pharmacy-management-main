<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SalePayment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SaleController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | New Sale Screen
    |--------------------------------------------------------------------------
    */
    public function create(): View
    {
        $customers = Customer::query()
            ->where('status', true)
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'phone',
            ]);

        return view('cashier.sales.create', compact('customers'));
    }


    /*
    |--------------------------------------------------------------------------
    | Search Medicines
    |--------------------------------------------------------------------------
    |
    | Search by:
    | - medicine name
    | - generic name
    | - SKU
    | - exact barcode
    |
    | Only medicines having usable FEFO stock are returned.
    |--------------------------------------------------------------------------
    */
  public function searchMedicines(Request $request): JsonResponse
{
    $search = trim((string) $request->query('search', ''));

    if ($search === '') {
        return response()->json([]);
    }

    try {
        $medicines = Medicine::query()
            ->where(function ($query) use ($search) {
                $query
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('generic_name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('barcode', $search);
            })
            ->whereHas('batches', function ($query) {
                $query
                    ->where('quantity', '>', 0)
                    ->whereDate('expiry_date', '>=', today());
            })
            ->with([
                'batches' => function ($query) {
                    $query
                        ->where('quantity', '>', 0)
                        ->whereDate('expiry_date', '>=', today())
                        ->orderBy('expiry_date', 'asc');
                }
            ])
            ->limit(10)
            ->get();

        $results = $medicines->map(function ($medicine) {

            $batches = $medicine->batches
                ->map(function ($batch) {
                    return [
                        'id' => (int) $batch->id,
                        'batch_number' => $batch->batch_number,
                        'expiry_date' => $batch->expiry_date
                            ? $batch->expiry_date->format('d M Y')
                            : null,
                        'quantity' => (int) $batch->quantity,
                        'sale_price' => (float) ($batch->sale_price ?? 0),
                    ];
                })
                ->values();

            return [
                'id' => (int) $medicine->id,
                'name' => $medicine->name,
                'generic_name' => $medicine->generic_name,
                'sku' => $medicine->sku,
                'barcode' => $medicine->barcode,

                'sale_price' => (float) ($medicine->sale_price ?? 0),

                'discount_percent' => (float) (
                    $medicine->discount_percent ?? 0
                ),

                'tax_percent' => (float) (
                    $medicine->tax_percent ?? 0
                ),

                'total_stock' => (int) $batches->sum('quantity'),

                'batches' => $batches,
            ];
        })->values();

        return response()->json($results);

    } catch (\Throwable $e) {

        report($e);

        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}
    /*
    |--------------------------------------------------------------------------
    | Complete Sale
    |--------------------------------------------------------------------------
    */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'customer_id' => [
                'nullable',
                'integer',
                'exists:customers,id',
            ],

            'discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'payment_method' => [
                'required',
                'string',
                'in:cash,card,bank_transfer,easypaisa,jazzcash',
            ],

            'paid_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.medicine_id' => [
                'required',
                'integer',
                'exists:medicines,id',
            ],

            'items.*.medicine_batch_id' => [
                'required',
                'integer',
                'exists:medicine_batches,id',
            ],

            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],

            'items.*.unit_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'items.*.discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],
        ]);


        $saleId = DB::transaction(function () use ($data) {

            $subtotal = 0;
            $itemData = [];


            /*
            |--------------------------------------------------------------------------
            | FIRST:
            | Lock and validate every batch.
            |--------------------------------------------------------------------------
            */
            foreach ($data['items'] as $item) {

                $batch = MedicineBatch::query()
                    ->whereKey($item['medicine_batch_id'])
                    ->lockForUpdate()
                    ->first();

                if (! $batch) {
                    throw new \RuntimeException(
                        'Selected batch no longer exists.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Verify medicine/batch relationship
                |--------------------------------------------------------------------------
                */
                if (
                    (int) $batch->medicine_id
                    !== (int) $item['medicine_id']
                ) {
                    throw new \RuntimeException(
                        'Invalid medicine batch selected.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Expiry check
                |--------------------------------------------------------------------------
                */
                if (
                    ! $batch->status
                    || ! $batch->expiry_date
                    || $batch->expiry_date <= now()
                ) {
                    throw new \RuntimeException(
                        'Batch ' .
                        $batch->batch_number .
                        ' is expired or inactive.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Stock check
                |--------------------------------------------------------------------------
                */
                if (
                    (int) $batch->quantity
                    < (int) $item['quantity']
                ) {
                    throw new \RuntimeException(
                        'Insufficient stock for batch ' .
                        $batch->batch_number .
                        '. Available: ' .
                        $batch->quantity
                    );
                }


                $quantity = (int) $item['quantity'];

                $unitPrice = (float) $item['unit_price'];

                $discount = (float) (
                    $item['discount'] ?? 0
                );


                $gross = $quantity * $unitPrice;

                $discount = min(
                    max(0, $discount),
                    $gross
                );

                $lineTotal = max(
                    0,
                    $gross - $discount
                );


                $subtotal += $lineTotal;


                $itemData[] = [
                    'medicine_id' => $item['medicine_id'],
                    'medicine_batch_id' => $batch->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'discount' => $discount,
                    'total' => $lineTotal,
                ];
            }


            /*
            |--------------------------------------------------------------------------
            | Order level discount
            |--------------------------------------------------------------------------
            */
            $discount = (float) (
                $data['discount'] ?? 0
            );

            $discount = min(
                max(0, $discount),
                $subtotal
            );


            /*
            |--------------------------------------------------------------------------
            | IMPORTANT
            |--------------------------------------------------------------------------
            |
            | Tax is already calculated client-side from item tax_percent.
            | We don't currently have tax fields in sale_items.
            |
            | Therefore we calculate tax again from Medicine here
            | to keep the server authoritative.
            |--------------------------------------------------------------------------
            */
            $tax = 0;


            foreach ($itemData as $index => $item) {

                $medicine = Medicine::find(
                    $item['medicine_id']
                );

                $taxPercent = (float) (
                    $medicine?->tax_percent ?? 0
                );

                $taxableAmount = max(
                    0,
                    $item['total']
                );

                $tax += (
                    $taxableAmount
                    * $taxPercent
                    / 100
                );
            }


            $grandTotal = max(
                0,
                $subtotal
                + $tax
                - $discount
            );


            $paidAmount = (float) $data['paid_amount'];


            /*
            |--------------------------------------------------------------------------
            | No partial payment in normal cashier sale
            |--------------------------------------------------------------------------
            */
            if ($paidAmount < $grandTotal) {
                throw new \RuntimeException(
                    'Received amount is less than the grand total.'
                );
            }


            $changeAmount = max(
                0,
                $paidAmount - $grandTotal
            );


            /*
            |--------------------------------------------------------------------------
            | Invoice Number
            |--------------------------------------------------------------------------
            */
            $invoiceNumber = $this->generateInvoiceNumber();


            /*
            |--------------------------------------------------------------------------
            | CREATE SALE
            |--------------------------------------------------------------------------
            */
            $sale = Sale::create([
                'invoice_number' => $invoiceNumber,

                'customer_id' => $data['customer_id'] ?? null,

                'subtotal' => round($subtotal, 2),

                'discount' => round($discount, 2),

                'tax' => round($tax, 2),

                'grand_total' => round($grandTotal, 2),

                /*
                |--------------------------------------------------------------------------
                | Amount applied to bill
                |--------------------------------------------------------------------------
                */
                'paid_amount' => round($grandTotal, 2),

                'change_amount' => round($changeAmount, 2),

                'payment_method' => $data['payment_method'],

                'created_by' => auth()->id(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | CREATE SALE ITEMS + REDUCE STOCK
            |--------------------------------------------------------------------------
            */
            foreach ($itemData as $item) {

                SaleItem::create([
                    'sale_id' => $sale->id,

                    'medicine_id' => $item['medicine_id'],

                    'medicine_batch_id' =>
                        $item['medicine_batch_id'],

                    'quantity' => $item['quantity'],

                    'unit_price' => $item['unit_price'],

                    'discount' => $item['discount'],

                    'total' => $item['total'],
                ]);


                /*
                |--------------------------------------------------------------------------
                | Lock again and reduce stock
                |--------------------------------------------------------------------------
                */
                $batch = MedicineBatch::query()
                    ->whereKey($item['medicine_batch_id'])
                    ->lockForUpdate()
                    ->firstOrFail();


                $batch->decrement(
                    'quantity',
                    $item['quantity']
                );
            }


            /*
            |--------------------------------------------------------------------------
            | CREATE PAYMENT
            |--------------------------------------------------------------------------
            */
            SalePayment::create([
                'sale_id' => $sale->id,

                'amount' => round(
                    $grandTotal,
                    2
                ),

                'payment_method' =>
                    $data['payment_method'],

                'reference_number' => null,

                'notes' => null,

                'received_by' => auth()->id(),

                'paid_at' => now(),
            ]);


            return $sale->id;
        });


        return response()->json([
            'success' => true,

            'message' => 'Sale completed successfully.',

            'sale_id' => $saleId,

            'redirect' => route(
                'cashier.sales.receipt',
                $saleId
            ),
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Receipt
    |--------------------------------------------------------------------------
    */
    public function receipt(Sale $sale): View
    {
        $sale->load([
            'customer',
            'creator',
            'items.medicine',
            'items.batch',
            'payments',
        ]);

        return view(
            'cashier.sales.receipt',
            compact('sale')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Invoice Number
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
}