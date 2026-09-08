<?php

namespace App\Filament\Resources\FinancialTransactions\Pages;

use App\Filament\Resources\FinancialTransactions\FinancialTransactionResource;
use App\Filament\Resources\FinancialTransactions\Widgets\FinancialTransactionBanner;
use App\Filament\Resources\FinancialTransactions\Widgets\FinancialTransactionStats;
use Filament\Resources\Pages\ListRecords;

class ListFinancialTransactions extends ListRecords
{
    protected static string $resource = FinancialTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            FinancialTransactionBanner::class,
            FinancialTransactionStats::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return 1;
    }
}