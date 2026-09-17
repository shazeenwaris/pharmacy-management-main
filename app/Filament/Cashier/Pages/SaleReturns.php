<?php

namespace App\Filament\Cashier\Pages;


use Livewire\Attributes\Computed;
use App\Models\MedicineBatch;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SaleReturn;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\DB;
use Throwable;

class SaleReturns extends Page
{
    protected string $view = 'filament.cashier.pages.sale-returns';

    protected static \BackedEnum|string|null $navigationIcon =
        'heroicon-o-arrow-uturn-left';

    protected static ?string $navigationLabel = 'Sale Returns';

    protected static ?string $title = 'Sale Returns';

    protected static string|\UnitEnum|null $navigationGroup = 'Sales';

    protected static ?int $navigationSort = 3;

    /*
    |--------------------------------------------------------------------------
    | Page Layout
    |--------------------------------------------------------------------------
    */

    public function getMaxContentWidth(): Width
    {
        return Width::Full;
    }

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

   public string $invoiceSearch = '';

public ?int $selectedSaleId = null;

public ?int $selectedSaleItemId = null;

public array $saleData = [];

public array $returnQuantities = [];

public array $reasons = [];

public bool $processing = false;
    /*
    |--------------------------------------------------------------------------
    | Access
    |--------------------------------------------------------------------------
    */

    public static function canAccess(): bool
    {
        return auth()->check()
            && auth()->user()->can('handle returns');
    }
public function getInvoiceOptionsProperty(): array
{
    return Sale::query()
        ->where('created_by', auth()->id())
        ->latest('id')
        ->get()
        ->mapWithKeys(function ($sale) {
            $invoice = $sale->invoice_number ?: 'INV-' . $sale->id;

            $customer = $sale->customer?->name ?? 'Walk-in Customer';

            return [
                $sale->id => $invoice . ' — ' . $customer,
            ];
        })
        ->toArray();
}
public function updatedSelectedSaleId($saleId): void
{
    $this->selectedSaleItemId = null;

    $this->returnQuantities = [];
    $this->reasons = [];

    if (! $saleId) {
        $this->saleData = [];

        return;
    }

    $sale = Sale::query()
        ->with([
            'customer',
            'creator',
            'items.medicine',
            'items.batch',
        ])
        ->whereKey((int) $saleId)
        ->where('created_by', auth()->id())
        ->first();

    if (! $sale) {
        $this->resetSale();

        Notification::make()
            ->danger()
            ->title('Invoice not found')
            ->body('The selected invoice could not be found.')
            ->send();

        return;
    }

    $this->loadSale($sale);

    Notification::make()
        ->success()
        ->title('Invoice selected')
        ->body(
            'Invoice ' .
            ($sale->invoice_number ?: '#' . $sale->id) .
            ' loaded successfully.'
        )
        ->send();
}
public function updatedSelectedSaleItemId($saleItemId): void
{
    if (! $saleItemId) {
        return;
    }

    $item = collect($this->saleData['items'] ?? [])
        ->firstWhere('id', (int) $saleItemId);

    if (! $item) {
        return;
    }

    if ((int) $item['returnable_quantity'] <= 0) {
        $this->selectedSaleItemId = null;

        Notification::make()
            ->warning()
            ->title('Medicine already returned')
            ->body('There is no remaining quantity available for return.')
            ->send();

        return;
    }

    $this->returnQuantities = [
        $item['id'] => 1,
    ];

    $this->reasons = [
        $item['id'] => 'customer_request',
    ];
}
    /*
    |--------------------------------------------------------------------------
    | Search Invoice
    |--------------------------------------------------------------------------
    */

    public function searchInvoice(): void
    {
        $search = trim($this->invoiceSearch);

        if ($search === '') {
            $this->resetSale();

            Notification::make()
                ->warning()
                ->title('Enter invoice number')
                ->body('Please enter an invoice number to search.')
                ->send();

            return;
        }

        $this->resetSale();

        $sale = Sale::query()
            ->with([
                'customer',
                'creator',
                'items.medicine',
                'items.batch',
            ])
            ->where('created_by', auth()->id())
            ->where(function ($query) use ($search) {
                $query
                    ->where('invoice_number', 'like', "%{$search}%")
                    ->when(
                        is_numeric($search),
                        fn ($q) => $q->orWhereKey((int) $search)
                    );
            })
            ->latest('id')
            ->first();

        if (! $sale) {
            Notification::make()
                ->danger()
                ->title('Sale not found')
                ->body('No sale belonging to your cashier account was found.')
                ->send();

            return;
        }

        $this->loadSale($sale);

        Notification::make()
            ->success()
            ->title('Invoice found')
            ->body(
                'Invoice ' .
                ($sale->invoice_number ?: '#' . $sale->id) .
                ' loaded successfully.'
            )
            ->send();
    }

    /*
    |--------------------------------------------------------------------------
    | Load Sale
    |--------------------------------------------------------------------------
    */

   protected function loadSale(Sale $sale): void
{
    $this->selectedSaleId = $sale->id;
    $this->selectedSaleItemId = null;

    
        $this->saleData = [
            'id' => $sale->id,
            'invoice_number' => $sale->invoice_number ?: 'N/A',
            'date' => $sale->created_at?->format('d M Y h:i A'),
            'customer' => $sale->customer?->name ?? 'Walk-in Customer',
            'phone' => $sale->customer?->phone ?? null,
            'cashier' => $sale->creator?->name ?? 'Current Cashier',
            'grand_total' => (float) ($sale->grand_total ?? 0),
            'paid_amount' => (float) ($sale->paid_amount ?? 0),
            'items' => [],
        ];

        $this->returnQuantities = [];
        $this->reasons = [];

        foreach ($sale->items as $item) {
            $returnedQuantity = (int) SaleReturn::query()
                ->where('sale_item_id', $item->id)
                ->sum('quantity');

            $returnableQuantity = max(
                0,
                (int) $item->quantity - $returnedQuantity
            );

            $this->saleData['items'][] = [
                'id' => $item->id,
                'medicine_id' => $item->medicine_id,
                'medicine_name' => $item->medicine?->name ?? 'Unknown Medicine',

                'batch_id' => $item->medicine_batch_id,

                'batch_number' => $item->batch?->batch_number ?? 'N/A',

                'expiry_date' => $item->batch?->expiry_date
                    ? $item->batch->expiry_date->format('d M Y')
                    : null,

                'sold_quantity' => (int) $item->quantity,

                'returned_quantity' => $returnedQuantity,

                'returnable_quantity' => $returnableQuantity,

                'unit_price' => (float) ($item->unit_price ?? 0),

                'line_total' => (float) ($item->total ?? 0),
            ];

            $this->returnQuantities[$item->id] = 0;

            $this->reasons[$item->id] = 'customer_request';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Return Quantity Validation
    |--------------------------------------------------------------------------
    */

    public function updatedReturnQuantities($value, $key): void
    {
        $quantity = max(0, (int) $value);

        foreach ($this->saleData['items'] ?? [] as $item) {
            if ((string) $item['id'] === (string) $key) {
                $quantity = min(
                    $quantity,
                    (int) $item['returnable_quantity']
                );

                break;
            }
        }

        $this->returnQuantities[$key] = $quantity;
    }

    /*
    |--------------------------------------------------------------------------
    | Refund Total
    |--------------------------------------------------------------------------
    */

    public function getReturnTotalProperty(): float
    {
        $total = 0;

        foreach ($this->saleData['items'] ?? [] as $item) {
            $quantity = (int) (
                $this->returnQuantities[$item['id']] ?? 0
            );

            if ($quantity <= 0) {
                continue;
            }

            $soldQuantity = max(
                1,
                (int) $item['sold_quantity']
            );

            /*
             * Use actual line total.
             * This preserves item-level discount.
             */
            $effectiveUnitPrice =
                (float) $item['line_total'] / $soldQuantity;

            $total += $effectiveUnitPrice * $quantity;
        }

        return round($total, 2);
    }

    /*
    |--------------------------------------------------------------------------
    | Selected Return Lines
    |--------------------------------------------------------------------------
    */

    public function getSelectedReturnCountProperty(): int
    {
        return collect($this->returnQuantities)
            ->map(fn ($quantity) => (int) $quantity)
            ->filter(fn ($quantity) => $quantity > 0)
            ->count();
    }

    /*
    |--------------------------------------------------------------------------
    | Return History
    |--------------------------------------------------------------------------
    */

    public function getReturnRowsProperty(): array
    {
        $query = DB::table('sale_returns as sr')
            ->leftJoin('sales as s', 's.id', '=', 'sr.sale_id')
            ->leftJoin('sale_items as si', 'si.id', '=', 'sr.sale_item_id')
            ->leftJoin('medicines as m', 'm.id', '=', 'sr.medicine_id')
            ->leftJoin('users as u', 'u.id', '=', 'sr.processed_by')
            ->where('s.created_by', auth()->id())
            ->select([
                'sr.id',
                's.invoice_number',
                'm.name as medicine_name',
                'sr.quantity',
                'sr.refund_amount',
                'sr.reason',
                'sr.return_date',
                'u.name as processed_by',
            ])
            ->orderByDesc('sr.id');

        $search = trim($this->returnSearch);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('s.invoice_number', 'like', "%{$search}%")
                    ->orWhere('m.name', 'like', "%{$search}%")
                    ->orWhere('sr.reason', 'like', "%{$search}%");
            });
        }

        return $query
            ->limit(100)
            ->get()
            ->map(function ($row) {
                return [
                    'id' => $row->id,
                    'invoice_number' => $row->invoice_number ?: '-',
                    'medicine_name' => $row->medicine_name ?: '-',
                    'quantity' => (int) $row->quantity,
                    'refund_amount' => (float) $row->refund_amount,
                    'reason' => $this->formatReason($row->reason),
                    'return_date' => $row->return_date
                        ? date('d M Y', strtotime($row->return_date))
                        : '-',
                    'processed_by' => $row->processed_by ?: 'Unknown',
                ];
            })
            ->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | Search Property
    |--------------------------------------------------------------------------
    */

    public string $returnSearch = '';

    /*
    |--------------------------------------------------------------------------
    | Total Returns
    |--------------------------------------------------------------------------
    */

    public function getTotalReturnsProperty(): int
    {
        return SaleReturn::query()
            ->whereHas('sale', function ($query) {
                $query->where('created_by', auth()->id());
            })
            ->count();
    }

    /*
    |--------------------------------------------------------------------------
    | Monthly Returns
    |--------------------------------------------------------------------------
    */

    public function getMonthlyReturnsProperty(): int
    {
        return SaleReturn::query()
            ->whereHas('sale', function ($query) {
                $query->where('created_by', auth()->id());
            })
            ->whereBetween('return_date', [
                now()->startOfMonth()->toDateString(),
                now()->endOfMonth()->toDateString(),
            ])
            ->count();
    }

    /*
    |--------------------------------------------------------------------------
    | Total Refund Amount
    |--------------------------------------------------------------------------
    */

    public function getTotalRefundAmountProperty(): float
    {
        return round(
            (float) SaleReturn::query()
                ->whereHas('sale', function ($query) {
                    $query->where('created_by', auth()->id());
                })
                ->sum('refund_amount'),
            2
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Process Return
    |--------------------------------------------------------------------------
    */

    public function processReturn(): void
    {
        if ($this->processing) {
            return;
        }

        if (! $this->selectedSaleId) {
            Notification::make()
                ->warning()
                ->title('No invoice selected')
                ->body('Please search and select an invoice first.')
                ->send();

            return;
        }

        $this->processing = true;

        try {
            $result = DB::transaction(function () {

                $sale = Sale::query()
                    ->whereKey($this->selectedSaleId)
                    ->where('created_by', auth()->id())
                    ->lockForUpdate()
                    ->first();

                if (! $sale) {
                    throw new \RuntimeException(
                        'Sale not found or you are not allowed to return this sale.'
                    );
                }

                $returnCount = 0;
                $refundTotal = 0;

                foreach ($this->returnQuantities as $saleItemId => $requestedQuantity) {

                    $requestedQuantity = (int) $requestedQuantity;

                    if ($requestedQuantity <= 0) {
                        continue;
                    }

                    /*
                     * Lock original sale item.
                     */
                    $saleItem = SaleItem::query()
                        ->whereKey((int) $saleItemId)
                        ->where('sale_id', $sale->id)
                        ->lockForUpdate()
                        ->first();

                    if (! $saleItem) {
                        throw new \RuntimeException(
                            'One of the selected sale items no longer exists.'
                        );
                    }

                    /*
                     * Calculate already returned quantity.
                     */
                    $alreadyReturned = (int) SaleReturn::query()
                        ->where('sale_item_id', $saleItem->id)
                        ->sum('quantity');

                    $returnableQuantity = max(
                        0,
                        (int) $saleItem->quantity - $alreadyReturned
                    );

                    if ($requestedQuantity > $returnableQuantity) {
                        throw new \RuntimeException(
                            'Cannot return more than the remaining quantity for this medicine. ' .
                            'Returnable quantity: ' .
                            $returnableQuantity
                        );
                    }

                    /*
                     * Original batch.
                     */
                    $batch = null;

                    if ($saleItem->medicine_batch_id) {

                        $batch = MedicineBatch::query()
                            ->whereKey($saleItem->medicine_batch_id)
                            ->lockForUpdate()
                            ->first();

                        if (! $batch) {
                            throw new \RuntimeException(
                                'Original medicine batch no longer exists.'
                            );
                        }

                        if (
                            (int) $batch->medicine_id !==
                            (int) $saleItem->medicine_id
                        ) {
                            throw new \RuntimeException(
                                'Original medicine batch does not match the sale item.'
                            );
                        }
                    }

                    /*
                     * Refund calculation.
                     */
                    $soldQuantity = max(
                        1,
                        (int) $saleItem->quantity
                    );

                    $lineTotal = (float) ($saleItem->total ?? 0);

                    $effectiveUnitPrice =
                        $lineTotal / $soldQuantity;

                    $refundAmount = round(
                        $effectiveUnitPrice * $requestedQuantity,
                        2
                    );

                    /*
                     * Create return.
                     */
                    SaleReturn::create([
                        'sale_id' => $sale->id,
                        'sale_item_id' => $saleItem->id,
                        'medicine_id' => $saleItem->medicine_id,
                        'medicine_batch_id' => $saleItem->medicine_batch_id,
                        'quantity' => $requestedQuantity,
                        'refund_amount' => $refundAmount,
                        'reason' =>
                            $this->reasons[$saleItem->id]
                            ?? 'other',
                        'return_date' => today(),
                        'processed_by' => auth()->id(),
                    ]);

                    /*
                     * Restore stock to ORIGINAL batch.
                     */
                    if ($batch) {
                        $batch->increment(
                            'quantity',
                            $requestedQuantity
                        );
                    }

                    $returnCount++;
                    $refundTotal += $refundAmount;
                }

                if ($returnCount === 0) {
                    throw new \RuntimeException(
                        'Please select at least one quantity to return.'
                    );
                }

                return [
                    'count' => $returnCount,
                    'refund' => round($refundTotal, 2),
                    'invoice' => $sale->invoice_number,
                ];
            });

            Notification::make()
                ->success()
                ->title('Sale return completed')
                ->body(
                    $result['count'] .
                    ' item line(s) returned successfully. Refund: ' .
                    currency_symbol() .
                    number_format($result['refund'], 2)
                )
                ->send();

            /*
             * Reload current invoice.
             */
            $sale = Sale::query()
                ->with([
                    'customer',
                    'creator',
                    'items.medicine',
                    'items.batch',
                ])
                ->find($this->selectedSaleId);

            if ($sale) {
                $this->loadSale($sale);
            }

            /*
             * Reset search/history state.
             */
            $this->returnSearch = '';

        } catch (Throwable $e) {

            report($e);

            Notification::make()
                ->danger()
                ->title('Return failed')
                ->body($e->getMessage())
                ->send();

        } finally {
            $this->processing = false;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Reset Sale
    |--------------------------------------------------------------------------
    */

  public function resetSale(): void
{
    $this->selectedSaleId = null;

    $this->selectedSaleItemId = null;

    $this->saleData = [];

    $this->returnQuantities = [];

    $this->reasons = [];
}
    /*
    |--------------------------------------------------------------------------
    | New Return
    |--------------------------------------------------------------------------
    */
/*
|--------------------------------------------------------------------------
| Clear Search / New Return
|--------------------------------------------------------------------------
*/

public function clearSearch(): void
{
    $this->invoiceSearch = '';

    $this->returnSearch = '';

    $this->resetSale();

    $this->processing = false;
}
   
    /*
    |--------------------------------------------------------------------------
    | Reason Formatter
    |--------------------------------------------------------------------------
    */

    protected function formatReason(?string $reason): string
    {
        return match ($reason) {
            'defective' => 'Defective / Damaged',
            'wrong_item' => 'Wrong Item Sold',
            'customer_request' => 'Customer Changed Mind',
            'expired' => 'Expired',
            'other' => 'Other',
            default => $reason
                ? ucwords(str_replace('_', ' ', $reason))
                : '-',
        };
    }

    #[Computed]
public function filteredInvoiceOptions(): array
{
    $search = trim((string) $this->invoiceSearch);

    $options = $this->invoiceOptions;

    if ($search === '') {
        return $options;
    }

    return collect($options)
        ->filter(function ($label) use ($search) {

            return str_contains(
                strtolower((string) $label),
                strtolower($search)
            );

        })
        ->toArray();
}
}