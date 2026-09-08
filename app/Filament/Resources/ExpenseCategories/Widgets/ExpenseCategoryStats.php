<?php

namespace App\Filament\Resources\ExpenseCategories\Widgets;

use App\Models\ExpenseCategory;
use Filament\Widgets\Widget;

class ExpenseCategoryStats extends Widget
{
    protected string $view = 'filament.resources.expense-categories.widgets.expense-category-stats';
    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    public function getStatsData(): array
    {
        return [
            'total'    => ExpenseCategory::count(),
            'active'   => ExpenseCategory::where('status', true)->count(),
            'inactive' => ExpenseCategory::where('status', false)->count(),
        ];
    }
}