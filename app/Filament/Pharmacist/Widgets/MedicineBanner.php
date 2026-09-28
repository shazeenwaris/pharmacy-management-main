<?php

namespace App\Filament\Pharmacist\Widgets;

use Filament\Widgets\Widget;

class MedicineBanner extends Widget
{
    protected string $view = 'filament.pharmacist.widgets.medicine-banner';

    protected static bool $isLazy = false;

    protected int | string | array $columnSpan = 'full';
}