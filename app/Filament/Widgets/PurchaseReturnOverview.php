<?php

namespace App\Filament\Widgets;

use App\Models\PurchaseReturn;
use Carbon\Carbon;
use Filament\Widgets\Widget;

class PurchaseReturnOverview extends Widget
{
    protected string $view = 'filament.widgets.purchase-return-overview';

     protected static bool $isLazy = false;

    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        return [
            'totalReturns'   => PurchaseReturn::count(),
            'totalRefund'    => PurchaseReturn::sum('refund_amount'),
            'thisMonthCount' => PurchaseReturn::whereMonth('return_date', Carbon::now()->month)
                ->whereYear('return_date', Carbon::now()->year)
                ->count(),
        ];
    }
}