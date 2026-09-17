<?php

namespace App\Filament\Cashier\Pages;

use App\Models\Sale;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Collection;

class DailySalesReport extends Page
{
    protected string $view = 'filament.cashier.pages.daily-sales-report';

    protected static ?string $title = 'Daily Sales';

    protected static ?string $navigationLabel = 'Daily Sales';

    protected static string|\UnitEnum|null $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'daily-sales';

    public string $selectedDate;

    public function mount(): void
    {
        $this->selectedDate = now()->format('Y-m-d');
    }

    /**
     * Sales for selected date.
     */
    public function getSalesProperty(): Collection
    {
        return Sale::query()
            ->with('customer')
            ->whereDate('created_at', $this->selectedDate)
            ->latest()
            ->get();
    }

    /**
     * Total sales amount.
     */
    public function getTotalSalesProperty(): float
    {
        return (float) $this->sales->sum('grand_total');
    }

    /**
     * Total amount paid.
     */
    public function getTotalPaidProperty(): float
    {
        return (float) $this->sales->sum('paid_amount');
    }

    /**
     * Total outstanding amount.
     */
    public function getTotalDueProperty(): float
    {
        return (float) $this->sales->sum('due_amount');
    }

    /**
     * Number of invoices.
     */
    public function getTotalInvoicesProperty(): int
    {
        return $this->sales->count();
    }

    /**
     * Cash payments.
     */
    public function getCashSalesProperty(): float
    {
        return (float) $this->sales
            ->where('payment_method', 'cash')
            ->sum('paid_amount');
    }

    /**
     * Card payments.
     */
    public function getCardSalesProperty(): float
    {
        return (float) $this->sales
            ->where('payment_method', 'card')
            ->sum('paid_amount');
    }

    /**
     * Bank transfer payments.
     */
    public function getBankSalesProperty(): float
    {
        return (float) $this->sales
            ->where('payment_method', 'bank_transfer')
            ->sum('paid_amount');
    }

    /**
     * Other payment methods.
     */
    public function getOtherSalesProperty(): float
    {
        return (float) $this->sales
            ->whereNotIn('payment_method', [
                'cash',
                'card',
                'bank_transfer',
            ])
            ->sum('paid_amount');
    }
    protected function getHeaderActions(): array
{
    return [];
}

public function getHeading(): string
{
    return '';
}

public function getSubheading(): ?string
{
    return null;
}
}