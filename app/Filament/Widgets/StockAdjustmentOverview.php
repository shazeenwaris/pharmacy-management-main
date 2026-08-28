<?php

namespace App\Filament\Widgets;

use App\Models\StockAdjustment;
use Filament\Widgets\Widget;

class StockAdjustmentOverview extends Widget
{
    protected string $view = 'filament.widgets.stock-adjustment-overview';

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $total = StockAdjustment::count();

        $thisMonth = StockAdjustment::whereMonth(
            'created_at',
            now()->month
        )->whereYear(
            'created_at',
            now()->year
        )->count();

        $increased = StockAdjustment::where('difference', '>', 0)->count();

        $decreased = StockAdjustment::where('difference', '<', 0)->count();

        return [
            'total' => $total,
            'thisMonth' => $thisMonth,
            'increased' => $increased,
            'decreased' => $decreased,
        ];
    }
}