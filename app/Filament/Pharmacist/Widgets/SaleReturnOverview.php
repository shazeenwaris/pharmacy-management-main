<?php

namespace App\Filament\Pharmacist\Widgets;

use App\Models\SaleReturn;
use Filament\Widgets\Widget;

class SaleReturnOverview extends Widget
{
    protected string $view = 'filament.widgets.pharmacist-sale-return-overview';

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    public int $totalReturns = 0;
    public int $todaysReturns = 0;
    public float $totalRefunded = 0;

    public function mount(): void
    {
        $this->totalReturns = SaleReturn::count();

        $this->todaysReturns = SaleReturn::whereDate('return_date', today())->count();

        $this->totalRefunded = SaleReturn::sum('refund_amount');
    }
}