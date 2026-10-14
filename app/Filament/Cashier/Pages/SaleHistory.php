<?php

namespace App\Filament\Cashier\Pages;

use App\Models\Sale;
use Filament\Pages\Page;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\WithPagination;

class SaleHistory extends Page
{
    use WithPagination;

   protected string $view = 'filament.cashier.pages.sale-history';

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'Sale History';

    protected static ?string $title = 'Sale History';

    protected static string|\UnitEnum|null $navigationGroup = 'Sales';

    protected static ?int $navigationSort = 2;

    public string $search = '';

    public string $dateFrom = '';

    public string $dateTo = '';

    public string $paymentStatus = '';

    public string $paymentMethod = '';

    public ?int $selectedSaleId = null;

public bool $showSaleModal = false;

public ?int $openSale = null;

protected $queryString = [
    'search' => [
        'except' => '',
    ],
    'dateFrom' => [
        'except' => '',
    ],
    'dateTo' => [
        'except' => '',
    ],
    'paymentStatus' => [
        'except' => '',
    ],
    'paymentMethod' => [
        'except' => '',
    ],
    'openSale' => [
        'except' => null,
    ],
];
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatingDateTo(): void
    {
        $this->resetPage();
    }

    public function updatingPaymentStatus(): void
    {
        $this->resetPage();
    }

    public function updatingPaymentMethod(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset([
            'search',
            'dateFrom',
            'dateTo',
            'paymentStatus',
            'paymentMethod',
        ]);

        $this->resetPage();
    }

    /**
     * Only show sales created by the logged-in cashier.
     */
    protected function baseQuery()
    {
        return Sale::query()
            ->with([
                'customer',
                'creator',
            ])
            ->where('created_by', auth()->id())
            ->latest('created_at');
    }

    /**
     * Filtered sales.
     */
    public function getSalesProperty(): LengthAwarePaginator
    {
        $query = $this->baseQuery();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if (filled($this->search)) {
            $search = trim($this->search);

            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($customerQuery) use ($search) {
                        $customerQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Date From
        |--------------------------------------------------------------------------
        */

        if (filled($this->dateFrom)) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        /*
        |--------------------------------------------------------------------------
        | Date To
        |--------------------------------------------------------------------------
        */

        if (filled($this->dateTo)) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        /*
        |--------------------------------------------------------------------------
        | Payment Method
        |--------------------------------------------------------------------------
        */

        if (filled($this->paymentMethod)) {
            $query->where('payment_method', $this->paymentMethod);
        }

        /*
        |--------------------------------------------------------------------------
        | Payment Status
        |--------------------------------------------------------------------------
        |
        | paymentStatus is an accessor on Sale model, not a database column.
        | Therefore status filtering is done after retrieving the relevant IDs.
        |
        */

        if (filled($this->paymentStatus)) {
            $status = $this->paymentStatus;

            $ids = (clone $query)
                ->get()
                ->filter(function (Sale $sale) use ($status) {
                    return $sale->payment_status === $status;
                })
                ->pluck('id');

            $query->whereIn('id', $ids);
        }

        return $query->paginate(15);
    }

    /**
     * Summary statistics.
     */
    public function getStatsProperty(): array
    {
        $query = $this->baseQuery();

        /*
        |--------------------------------------------------------------------------
        | Apply same filters as table
        |--------------------------------------------------------------------------
        */

        if (filled($this->search)) {
            $search = trim($this->search);

            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($customerQuery) use ($search) {
                        $customerQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        if (filled($this->dateFrom)) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if (filled($this->dateTo)) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        if (filled($this->paymentMethod)) {
            $query->where('payment_method', $this->paymentMethod);
        }

        $sales = $query->get();

        if (filled($this->paymentStatus)) {
            $sales = $sales->filter(function (Sale $sale) {
                return $sale->payment_status === $this->paymentStatus;
            });
        }

        return [
            'sales' => $sales->count(),

            'total' => (float) $sales->sum(function (Sale $sale) {
                return (float) $sale->grand_total;
            }),

            'paid' => (float) $sales->sum(function (Sale $sale) {
                return (float) $sale->paid_amount;
            }),

            'due' => (float) $sales->sum(function (Sale $sale) {
                return $sale->due_amount;
            }),
        ];
    }

    /**
     * Open sale details.
     */
    public function viewSale(int $saleId): void
    {
        $sale = Sale::query()
            ->where('created_by', auth()->id())
            ->findOrFail($saleId);

        $this->selectedSaleId = $sale->id;

        $this->showSaleModal = true;
    }

    /**
     * Close details modal.
     */
    public ?int $editSaleId = null;
    public $editCustomerId = null;
    public string $editPaymentMethod = 'cash';
    public $editPaidAmount = 0;

    public function editSale(int $saleId): void
    {
        $sale = $this->baseQuery()->findOrFail($saleId);

        $this->editSaleId = $sale->id;
        $this->editCustomerId = $sale->customer_id;
        $this->editPaymentMethod = (string) $sale->payment_method;
        $this->editPaidAmount = (float) $sale->paid_amount;
    }

    public function closeEdit(): void
    {
        $this->editSaleId = null;
    }

    public function saveEdit(): void
    {
        $this->validate([
            'editPaymentMethod' => 'required|in:cash,card,bank,other',
            'editPaidAmount' => 'required|numeric|min:0',
        ]);

        $sale = $this->baseQuery()->findOrFail($this->editSaleId);

        $paid = (float) $this->editPaidAmount;
        $grand = (float) $sale->grand_total;

        $sale->update([
            'customer_id' => $this->editCustomerId ?: null,
            'payment_method' => $this->editPaymentMethod,
            'paid_amount' => $paid,
            'change_amount' => max(0, $paid - $grand),
        ]);

        \Filament\Notifications\Notification::make()
            ->title('Sale updated')
            ->success()
            ->send();

        $this->editSaleId = null;
    }
    public function closeSale(): void
    {
        $this->showSaleModal = false;

        $this->selectedSaleId = null;
    }

    /**
     * Selected sale.
     */
    public function getSelectedSaleProperty(): ?Sale
    {
        if (!$this->selectedSaleId) {
            return null;
        }

        return Sale::query()
            ->with([
                'customer',
                'creator',
                'items.medicine',
                'items.batch',
                'items.returns',
                'payments.receiver',
            ])
            ->where('created_by', auth()->id())
            ->find($this->selectedSaleId);
    }
public function mount(): void
{
    if ($this->openSale) {
        $this->viewSale($this->openSale);
    }
}
    /**
     * Navigation access.
     */
    public static function canAccess(): bool
    {
        return auth()->check()
            && (
                method_exists(auth()->user(), 'isCashier')
                    ? auth()->user()->isCashier()
                    : true
            );
    }
}