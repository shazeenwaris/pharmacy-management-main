<?php

namespace App\Filament\Resources\Manufacturers\Widgets;

use Filament\Widgets\Widget;

class ManufacturerBanner extends Widget
{
    protected string $view = 'filament.resources.manufacturers.widgets.manufacturer-banner';
     protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';
}