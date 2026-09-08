<?php

namespace App\Filament\Resources\ExpenseCategories\Widgets;

use Filament\Widgets\Widget;

class ExpenseCategoryBanner extends Widget
{
    protected string $view = 'filament.resources.expense-categories.widgets.expense-category-banner';
    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';
}