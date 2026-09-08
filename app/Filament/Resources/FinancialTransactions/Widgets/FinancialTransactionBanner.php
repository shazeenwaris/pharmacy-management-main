<?php

namespace App\Filament\Resources\FinancialTransactions\Widgets;

use Filament\Widgets\Widget;

class FinancialTransactionBanner extends Widget
{
    protected string $view = 'filament.resources.financial-transactions.widgets.financial-transaction-banner';
    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';
}