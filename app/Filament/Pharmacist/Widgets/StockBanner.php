<?php

namespace App\Filament\Pharmacist\Widgets;

use Filament\Widgets\Widget;

class StockBanner extends Widget
{
    protected string $view = 'filament.pharmacist.widgets.stock-banner';

    protected static bool $isLazy = false;

   protected int|string|array $columnSpan = 'full';
}