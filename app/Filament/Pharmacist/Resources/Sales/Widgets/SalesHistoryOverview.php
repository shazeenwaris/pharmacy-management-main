<?php

namespace App\Filament\Pharmacist\Widgets;

use App\Models\Sale;
use Filament\Widgets\Widget;

class SalesHistoryOverview extends Widget
{
    // protected string $view = 'filament.pharmacist.resources.sales.widgets.sales-history-overview';

    protected string $view = 'filament.widgets.pharmacist-sales-history-overview';

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    public int $totalSales = 0;
    public float $todaysSales = 0;
    public float $totalRevenue = 0;

    public function mount(): void
    {
        $this->totalSales = Sale::count();

        $this->todaysSales = Sale::whereDate('created_at', today())
            ->sum('grand_total');

        $this->totalRevenue = Sale::sum('grand_total');
    }
}