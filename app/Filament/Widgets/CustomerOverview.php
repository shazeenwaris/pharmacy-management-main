<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use Filament\Widgets\Widget;

class CustomerOverview extends Widget
{
    protected string $view = 'filament.widgets.customer-overview';
     protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    public int $totalCustomers = 0;
    public int $newThisMonth = 0;
    public float $totalDueBalance = 0;

    public function mount(): void
    {
        $this->totalCustomers = Customer::count();

        $this->newThisMonth = Customer::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // due_balance is a computed accessor, not a DB column,
        // so it has to be summed in PHP rather than via ->sum().
        $this->totalDueBalance = (float) Customer::all()->sum(fn ($customer) => $customer->due_balance);
    }
}