<?php

namespace App\Filament\Pharmacist\Widgets;

use App\Models\Medicine;
use App\Models\MedicineBatch;
use Filament\Widgets\Widget;

class MedicineStats extends Widget
{
    protected string $view = 'filament.pharmacist.widgets.medicine-stats';

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        return [
            'total' => Medicine::count(),
            'lowStock' => Medicine::whereColumn('opening_stock', '<=', 'minimum_stock_level')->count(),
            'expiringSoon' => MedicineBatch::where('expiry_date', '<=', now()->addDays(30))
                ->where('expiry_date', '>', now())
                ->where('quantity', '>', 0)
                ->count(),
        ];
    }
}