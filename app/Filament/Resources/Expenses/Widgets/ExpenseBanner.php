<?php

namespace App\Filament\Resources\Expenses\Widgets;

use Filament\Widgets\Widget;

class ExpenseBanner extends Widget
{
    protected string $view = 'filament.resources.expenses.widgets.expense-banner';
    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';
}