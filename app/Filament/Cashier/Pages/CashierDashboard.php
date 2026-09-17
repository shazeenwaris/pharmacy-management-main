<?php

namespace App\Filament\Cashier\Pages;

use App\Models\Sale;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Database\Eloquent\Collection;

class CashierDashboard extends BaseDashboard
{
    protected static ?string $title = 'Cashier Dashboard';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $slug = 'dashboard';

    protected string $view = 'filament.cashier.pages.cashier-dashboard';

    /**
     * Today's sales.
     */
    public function getTodaySalesProperty(): Collection
    {
        return Sale::query()
            ->with('customer')
            ->whereDate('created_at', today())
            ->latest('created_at')
            ->get();
    }

    /**
     * Today's total sales.
     */
    public function getTodayTotalProperty(): float
    {
        return (float) $this->todaySales->sum('grand_total');
    }

    /**
     * Today's paid amount.
     */
    public function getTodayPaidProperty(): float
    {
        return (float) $this->todaySales->sum('paid_amount');
    }

    /**
     * Today's due amount.
     */
    public function getTodayDueProperty(): float
    {
        return (float) $this->todaySales->sum('due_amount');
    }

    /**
     * Today's invoice count.
     */
    public function getTodayInvoicesProperty(): int
    {
        return $this->todaySales->count();
    }

    /**
     * Today's cash collection.
     */
    public function getTodayCashProperty(): float
    {
        return (float) $this->todaySales
            ->where('payment_method', 'cash')
            ->sum('paid_amount');
    }

    /**
     * Today's card collection.
     */
    public function getTodayCardProperty(): float
    {
        return (float) $this->todaySales
            ->where('payment_method', 'card')
            ->sum('paid_amount');
    }

    /**
     * Today's bank transfer collection.
     */
    public function getTodayBankProperty(): float
    {
        return (float) $this->todaySales
            ->where('payment_method', 'bank_transfer')
            ->sum('paid_amount');
    }

    /**
     * Today's other payment collection.
     */
    public function getTodayOtherProperty(): float
    {
        return (float) $this->todaySales
            ->whereNotIn('payment_method', [
                'cash',
                'card',
                'bank_transfer',
            ])
            ->sum('paid_amount');
    }

    /**
     * Five most recent sales.
     */
    public function getRecentSalesProperty(): Collection
    {
        return $this->todaySales->take(5);
    }
   public function getHeading(): string
{
    return '';
}
public function getBreadcrumbs(): array
{
    return [];
}
}