<?php

namespace App\Filament\Resources\FinancialTransactions\Widgets;

use App\Models\FinancialTransaction;
use Filament\Widgets\Widget;

class FinancialTransactionStats extends Widget
{
    protected string $view = 'filament.resources.financial-transactions.widgets.financial-transaction-stats';
    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    public function getStatsData(): array
    {
        return [
            'total'   => FinancialTransaction::count(),
            'income'  => FinancialTransaction::where('transaction_type', 'income')->sum('amount'),
            'expense' => FinancialTransaction::where('transaction_type', 'expense')->sum('amount'),
        ];
    }
}