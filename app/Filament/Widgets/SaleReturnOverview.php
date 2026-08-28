<?php

namespace App\Filament\Widgets;

use App\Models\SaleReturn;
use Carbon\Carbon;
use Filament\Widgets\Widget;

class SaleReturnOverview extends Widget
{
    protected string $view = 'filament.widgets.sale-return-overview';

    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        return [
            'totalReturns'   => SaleReturn::count(),
            'totalRefund'    => SaleReturn::sum('refund_amount'),
            'thisMonthCount' => SaleReturn::whereMonth('return_date', Carbon::now()->month)
                ->whereYear('return_date', Carbon::now()->year)
                ->count(),
        ];
    }
}