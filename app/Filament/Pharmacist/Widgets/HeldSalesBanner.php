<?php

namespace App\Filament\Pharmacist\Widgets;

use Filament\Widgets\Widget;

class HeldSalesBanner extends Widget
{
    protected string $view = 'filament.pharmacist.widgets.held-sales-banner';

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';
}