<?php

namespace App\Filament\Resources\Expenses\Widgets;

use App\Models\Expense;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class ExpenseStats extends Widget
{
    protected string $view = 'filament.resources.expenses.widgets.expense-stats';
    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    public function getStatsData(): array
    {
        return [
            'total'       => Expense::count(),
            'totalAmount' => Expense::sum('amount'),
            'thisMonth'   => Expense::whereMonth('expense_date', Carbon::now()->month)
                                ->whereYear('expense_date', Carbon::now()->year)
                                ->sum('amount'),
        ];
    }
}