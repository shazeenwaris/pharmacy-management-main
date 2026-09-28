<?php

namespace App\Filament\Pharmacist\Resources\Sales\Pages;

use App\Filament\Pharmacist\Resources\Sales\SaleResource;
use App\Filament\Pharmacist\Widgets\SalesHistoryOverview;
use Filament\Resources\Pages\ListRecords;

class ListSales extends ListRecords
{
    protected static string $resource = SaleResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            SalesHistoryOverview::class,
        ];
    }
}

