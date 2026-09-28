<?php

namespace App\Filament\Pharmacist\Widgets;

use App\Models\MedicineBatch;
use Filament\Widgets\Widget;

class StockStats extends Widget
{
    protected string $view = 'filament.pharmacist.widgets.stock-stats';

    protected static bool $isLazy = false;

   protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $lowStock = MedicineBatch::where('quantity', '>', 0)
            ->whereHas('medicine', function ($q) {
                $q->whereColumn('medicine_batches.quantity', '<=', 'medicines.minimum_stock_level');
            })->count();

        $outOfStock = MedicineBatch::where('quantity', '<=', 0)->count();

        $expiringSoon = MedicineBatch::where('quantity', '>', 0)
            ->whereBetween('expiry_date', [now(), now()->addDays(30)])
            ->count();

        return [
            'lowStock' => $lowStock,
            'outOfStock' => $outOfStock,
            'expiringSoon' => $expiringSoon,
        ];
    }
}