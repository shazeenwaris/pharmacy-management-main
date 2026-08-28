<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use Filament\Widgets\Widget;

class CategoryOverview extends Widget
{
    protected string $view = 'filament.widgets.category-overview';

     protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        return [
            'totalCategories' => Category::count(),

            'activeCategories' => Category::where('status', true)->count(),

            'totalMedicines' => Category::withCount('medicines')
                ->get()
                ->sum('medicines_count'),
        ];
    }
}