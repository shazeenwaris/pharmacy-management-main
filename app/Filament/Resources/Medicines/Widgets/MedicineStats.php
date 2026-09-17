<?php

namespace App\Filament\Resources\Medicines\Widgets;

use App\Models\Medicine;
use App\Models\MedicineBatch;
use Filament\Widgets\Widget;

class MedicineStats extends Widget
{
    protected string $view = 'filament.resources.medicines.widgets.medicine-stats';

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    public function getStatsData(): array
    {
        $lowStock = Medicine::whereHas('batches')
            ->get()
            ->filter(function ($medicine) {
                return $medicine->batches->sum('quantity')
                    <= $medicine->minimum_stock_level;
            })
            ->count();

        return [
            'total' => Medicine::count(),

            'lowStock' => $lowStock,

            'expiringSoon' => MedicineBatch::where('expiry_date', '<=', now()->addDays(30))
                ->where('expiry_date', '>', now())
                ->count(),
        ];
    }
}