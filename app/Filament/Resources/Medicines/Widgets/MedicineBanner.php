<?php

namespace App\Filament\Resources\Medicines\Widgets;

use Filament\Widgets\Widget;

class MedicineBanner extends Widget
{
    protected string $view = 'filament.resources.medicines.widgets.medicine-banner';

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';
}