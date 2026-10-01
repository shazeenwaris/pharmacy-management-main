<?php

namespace App\Filament\Cashier\Pages;

use App\Models\MedicineBatch;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SaleReturn;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
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
    | Page
    |--------------------------------------------------------------------------
    */

    public function getMaxContentWidth(): Width
    {
        return Width::Full;
    }

    /*
    |--------------------------------------------------------------------------
    | Modal
    |--------------------------------------------------------------------------
    */

    public bool $showReturnModal = false;

    /*
    |--------------------------------------------------------------------------
    | Invoice
    |--------------------------------------------------------------------------
    */

    public string $invoiceSearch = '';

public bool $showInvoiceDropdown = false;

public ?int $selectedSaleId = null;

    

    public array $saleData = [];

    /*
    |--------------------------------------------------------------------------
    | Return
    |--------------------------------------------------------------------------
    */

    public array $returnQuantities = [];

    public array $reasons = [];

    public bool $processing = false;

    /*
    |--------------------------------------------------------------------------
    | History
    |--------------------------------------------------------------------------
    */

    public string $returnSearch = '';

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

    /*
    |--------------------------------------------------------------------------
    | Open Modal
    |--------------------------------------------------------------------------
    */

    public function openReturnModal(): void
    {
        $this->resetReturnForm();

        $this->showReturnModal = true;
    }

    /*
    |--------------------------------------------------------------------------
    | Close Modal
    |--------------------------------------------------------------------------
    */

    public function closeReturnModal(): void
    {
        if ($this->processing) {
            return;
        }

        $this->resetReturnForm();

        $this->showReturnModal = false;
    }

    /*
    |--------------------------------------------------------------------------
    | Reset Return Form
    |--------------------------------------------------------------------------
    */

   protected function resetReturnForm(): void
{
    $this->invoiceSearch = '';
    $this->selectedSaleId = null;
    $this->showInvoiceDropdown = false;

    $this->saleData = [];
    $this->returnQuantities = [];
    $this->reasons = [];
}
    /*
    |--------------------------------------------------------------------------
    | Invoice Search
    |--------------------------------------------------------------------------
    */

 public function updatedInvoiceSearch(): void
{
    $this->selectedSaleId = null;

    $this->saleData = [];
    $this->returnQuantities = [];
    $this->reasons = [];

    $this->showInvoiceDropdown = true;
}
public function selectInvoice(int $saleId): void
{
    $sale = Sale::query()
        ->with([
            'customer',
            'creator',
            'items.medicine',
            'items.batch',
        ])
        ->whereKey($saleId)
        ->where('created_by', auth()->id())
        ->first();

    if (! $sale) {
        Notification::make()
            ->danger()
            ->title('Invoice not found')
            ->body('The selected invoice could not be found.')
            ->send();

        return;
    }

    // Load invoice
    $this->loadSale($sale);

    // Put selected invoice in input
    $this->invoiceSearch =
        $sale->invoice_number ?: 'INV-' . $sale->id;

    // Close dropdown
    $this->showInvoiceDropdown = false;
}
#[Computed]
public function invoiceOptions(): array
{
    $search = trim($this->invoiceSearch);

    return Sale::query()
        ->with('customer')
        ->where('created_by', auth()->id())
        ->when($search !== '', function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where(
                    'invoice_number',
                    'like',
                    "%{$search}%"
                );

                if (is_numeric($search)) {
                    $q->orWhereKey((int) $search);
                }

                $q->orWhereHas('customer', function ($customerQuery) use ($search) {
                    $customerQuery->where(
                        'name',
                        'like',
                        "%{$search}%"
                    );
                });
            });
        })
        ->latest('id')
        ->limit(20)
        ->get()
        ->map(fn ($sale) => [
            'id' => $sale->id,
            'invoice_number' => $sale->invoice_number ?: 'INV-' . $sale->id,
            'customer' => $sale->customer?->name ?? 'Walk-in Customer',
            'date' => $sale->created_at?->format('d M Y h:i A'),
            'total' => (float) ($sale->grand_total ?? 0),
        ])
        ->toArray();
}

public function openInvoiceDropdown(): void
{
    $this->showInvoiceDropdown = true;
}

public function toggleInvoiceDropdown(): void
{
    $this->showInvoiceDropdown = ! $this->showInvoiceDropdown;
}

public function closeInvoiceDropdown(): void
{
    $this->showInvoiceDropdown = false;
}
    /*
    |--------------------------------------------------------------------------
    | Load Sale
    |--------------------------------------------------------------------------
    */

   protected function loadSale(Sale $sale): void
{
    $this->selectedSaleId = $sale->id;

    $this->saleData = [
        'id' => $sale->id,

        'invoice_number' =>
            $sale->invoice_number ?: 'INV-' . $sale->id,

        'date' =>
            $sale->created_at?->format('d M Y h:i A'),

        'customer' =>
            $sale->customer?->name ?? 'Walk-in Customer',

        'phone' =>
            $sale->customer?->phone ?? null,

        'cashier' =>
            $sale->creator?->name ?? 'Unknown',

        'grand_total' =>
            (float) ($sale->grand_total ?? 0),

        'paid_amount' =>
            (float) ($sale->paid_amount ?? 0),

        'items' => [],
    ];

    $this->returnQuantities = [];
    $this->reasons = [];

    foreach ($sale->items as $item) {

        $soldQuantity = (float) $item->quantity;

        /*
        |--------------------------------------------------------------------------
        | Already Returned Quantity
        |--------------------------------------------------------------------------
        */

        $returnedQuantity = (float) SaleReturn::query()
            ->where('sale_id', $sale->id)
            ->where('sale_item_id', $item->id)
            ->sum('quantity');

        /*
        |--------------------------------------------------------------------------
        | Remaining Returnable Quantity
        |--------------------------------------------------------------------------
        */

        $returnableQuantity = max(
            0,
            $soldQuantity - $returnedQuantity
        );

        /*
        |--------------------------------------------------------------------------
        | Sale Item Data
        |--------------------------------------------------------------------------
        */

        $this->saleData['items'][] = [

            'id' =>
                $item->id,

            'medicine_id' =>
                $item->medicine_id,

            'medicine_name' =>
                $item->medicine?->name ?? 'Unknown Medicine',

            'batch_number' =>
                $item->batch?->batch_number ?? '-',

            'expiry_date' =>
                $item->batch?->expiry_date
                    ? $item->batch->expiry_date->format('d M Y')
                    : null,

            'sold_quantity' =>
                $soldQuantity,

            'returned_quantity' =>
                $returnedQuantity,

            'returnable_quantity' =>
                $returnableQuantity,

            'unit_price' =>
                (float) ($item->unit_price ?? 0),

            'line_total' =>
                (float) ($item->total ?? 0),
        ];

        /*
        |--------------------------------------------------------------------------
        | Start Return Quantity From 0
        |--------------------------------------------------------------------------
        */

        $this->returnQuantities[$item->id] = 0;

        $this->reasons[$item->id] = '';
    }
}
    /*
    |--------------------------------------------------------------------------
    | Return Quantity
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
    | Return Total
    |--------------------------------------------------------------------------
    */

    #[Computed]
    public function returnTotal(): float
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

            $effectiveUnitPrice =
                (float) $item['line_total'] / $soldQuantity;

            $total += $effectiveUnitPrice * $quantity;
        }

        return round($total, 2);
    }

    /*
    |--------------------------------------------------------------------------
    | Selected Return Count
    |--------------------------------------------------------------------------
    */

    #[Computed]
    public function selectedReturnCount(): int
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

    #[Computed]
    public function returnRows(): array
    {
        $query = DB::table('sale_returns as sr')
            ->leftJoin('sales as s', 's.id', '=', 'sr.sale_id')
            ->leftJoin(
                'sale_items as si',
                'si.id',
                '=',
                'sr.sale_item_id'
            )
            ->leftJoin(
                'medicines as m',
                'm.id',
                '=',
                'sr.medicine_id'
            )
            ->leftJoin(
                'users as u',
                'u.id',
                '=',
                'sr.processed_by'
            )
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
                $q->where(
                    's.invoice_number',
                    'like',
                    "%{$search}%"
                )
                    ->orWhere(
                        'm.name',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'sr.reason',
                        'like',
                        "%{$search}%"
                    );
            });
        }

        return $query
            ->limit(100)
            ->get()
            ->map(function ($row) {
                return [
                    'id' => $row->id,

                    'invoice_number' =>
                        $row->invoice_number ?: '-',

                    'medicine_name' =>
                        $row->medicine_name ?: '-',

                    'quantity' => (int) $row->quantity,

                    'refund_amount' =>
                        (float) $row->refund_amount,

                    'reason' =>
                        $this->formatReason($row->reason),

                    'return_date' =>
                        $row->return_date
                            ? date(
                                'd M Y',
                                strtotime($row->return_date)
                            )
                            : '-',

                    'processed_by' =>
                        $row->processed_by ?: 'Unknown',
                ];
            })
            ->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | Statistics
    |--------------------------------------------------------------------------
    */

    #[Computed]
    public function totalReturns(): int
    {
        return SaleReturn::query()
            ->whereHas('sale', function ($query) {
                $query->where(
                    'created_by',
                    auth()->id()
                );
            })
            ->count();
    }

    #[Computed]
    public function monthlyReturns(): int
    {
        return SaleReturn::query()
            ->whereHas('sale', function ($query) {
                $query->where(
                    'created_by',
                    auth()->id()
                );
            })
            ->whereBetween('return_date', [
                now()->startOfMonth()->toDateString(),
                now()->endOfMonth()->toDateString(),
            ])
            ->count();
    }

    #[Computed]
    public function totalRefundAmount(): float
    {
        return round(
            (float) SaleReturn::query()
                ->whereHas('sale', function ($query) {
                    $query->where(
                        'created_by',
                        auth()->id()
                    );
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
                ->body('Please search for an invoice first.')
                ->send();

            return;
        }

        if ($this->selectedReturnCount <= 0) {
            Notification::make()
                ->warning()
                ->title('No quantity selected')
                ->body(
                    'Please enter a return quantity for at least one medicine.'
                )
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

                foreach (
                    $this->returnQuantities
                    as $saleItemId => $requestedQuantity
                ) {
                    $requestedQuantity = (int) $requestedQuantity;

                    if ($requestedQuantity <= 0) {
                        continue;
                    }

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

                    $alreadyReturned = (int) SaleReturn::query()
                        ->where(
                            'sale_item_id',
                            $saleItem->id
                        )
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

                    $batch = null;

                    if ($saleItem->medicine_batch_id) {
                        $batch = MedicineBatch::query()
                            ->whereKey(
                                $saleItem->medicine_batch_id
                            )
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
                    |--------------------------------------------------------------------------
                    | Refund
                    |--------------------------------------------------------------------------
                    */

                    $soldQuantity = max(
                        1,
                        (int) $saleItem->quantity
                    );

                    $lineTotal =
                        (float) ($saleItem->total ?? 0);

                    $effectiveUnitPrice =
                        $lineTotal / $soldQuantity;

                    $refundAmount = round(
                        $effectiveUnitPrice *
                        $requestedQuantity,
                        2
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Create Return
                    |--------------------------------------------------------------------------
                    */

                    SaleReturn::create([
                        'sale_id' => $sale->id,

                        'sale_item_id' =>
                            $saleItem->id,

                        'medicine_id' =>
                            $saleItem->medicine_id,

                        'medicine_batch_id' =>
                            $saleItem->medicine_batch_id,

                        'quantity' =>
                            $requestedQuantity,

                        'refund_amount' =>
                            $refundAmount,

                        'reason' =>
                            $this->reasons[$saleItem->id]
                            ?? 'other',

                        'return_date' =>
                            today(),

                        'processed_by' =>
                            auth()->id(),
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | Restore Original Batch Stock
                    |--------------------------------------------------------------------------
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

                    'refund' =>
                        round($refundTotal, 2),

                    'invoice' =>
                        $sale->invoice_number
                        ?: '#' . $sale->id,
                ];
            });

            Notification::make()
                ->success()
                ->title('Sale return completed')
                ->body(
                    $result['count'] .
                    ' item line(s) returned successfully. Refund: ' .
                    currency_symbol() .
                    number_format(
                        $result['refund'],
                        2
                    )
                )
                ->send();

            /*
            |--------------------------------------------------------------------------
            | Close Modal
            |--------------------------------------------------------------------------
            */

            $this->showReturnModal = false;

            $this->resetReturnForm();

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
    | Clear
    |--------------------------------------------------------------------------
    */

    public function clearSearch(): void
    {
        $this->resetReturnForm();
    }

    /*
    |--------------------------------------------------------------------------
    | Reason
    |--------------------------------------------------------------------------
    */

    protected function formatReason(?string $reason): string
    {
        return match ($reason) {
            'defective' =>
                'Defective / Damaged',

            'wrong_item' =>
                'Wrong Item Sold',

            'customer_request' =>
                'Customer Changed Mind',

            'expired' =>
                'Expired',

            'other' =>
                'Other',

            default =>
                $reason
                    ? ucwords(
                        str_replace(
                            '_',
                            ' ',
                            $reason
                        )
                    )
                    : '-',
        };
    }
}