<?php

namespace App\Filament\Widgets;

use App\Models\CustomerPayment;
use Filament\Widgets\Widget;

class CustomerPaymentOverview extends Widget
{
    protected string $view = 'filament.widgets.customer-payment-overview';
    
     protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    public int $totalPayments = 0;
    public int $thisMonthCount = 0;
    public float $totalCollected = 0;

    public function mount(): void
    {
        $this->totalPayments = CustomerPayment::count();

        $this->thisMonthCount = CustomerPayment::whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->count();

        $this->totalCollected = (float) CustomerPayment::sum('amount');
    }
}