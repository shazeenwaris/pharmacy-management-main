<?php

namespace App\Filament\Widgets;

use App\Models\Sale;
use Carbon\Carbon;
use Filament\Widgets\Widget;

class SaleOverview extends Widget
{
    protected string $view = 'filament.widgets.sale-overview';

     protected static bool $isLazy = false;

    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        return [
            'totalSales'  => Sale::count(),
            'todaySales'  => Sale::whereDate('created_at', Carbon::today())->sum('grand_total'),
            'totalRevenue' => Sale::sum('grand_total'),
        ];
    }
}