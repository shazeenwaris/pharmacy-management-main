<?php

namespace App\Filament\Widgets;

use App\Models\Purchase;
use Filament\Widgets\Widget;

class PurchaseOverview extends Widget
{
    protected string $view = 'filament.widgets.purchase-overview';

     protected static bool $isLazy = false;

    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        return [
            'totalPurchases'     => Purchase::count(),
            'pendingPurchases'   => Purchase::where('status', 'pending')->count(),
            'completedPurchases' => Purchase::where('status', 'completed')->count(),
            'totalAmount'        => Purchase::sum('total_amount'),
        ];
    }
}