<?php

namespace App\Filament\Widgets;

use App\Models\Unit;
use Filament\Widgets\Widget;

class UnitOverview extends Widget
{
    protected string $view = 'filament.widgets.unit-overview';

     protected static bool $isLazy = false;

    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        return [
            'totalUnits'  => Unit::count(),
            'activeUnits' => Unit::where('status', true)->count(),
            'totalMedicines' => Unit::withCount('medicines')->get()->sum('medicines_count'),
        ];
    }
}