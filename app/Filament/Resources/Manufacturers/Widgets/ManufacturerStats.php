<?php

namespace App\Filament\Resources\Manufacturers\Widgets;

use App\Models\Manufacturer;
use Filament\Widgets\Widget;

class ManufacturerStats extends Widget
{
    protected string $view = 'filament.resources.manufacturers.widgets.manufacturer-stats';
     protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    public function getStatsData(): array
    {
        return [
            'total' => Manufacturer::count(),
            'active' => Manufacturer::where('status', true)->count(),
            'inactive' => Manufacturer::where('status', false)->count(),
        ];
    }
}