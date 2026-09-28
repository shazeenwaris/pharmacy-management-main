<?php

namespace App\Filament\Pharmacist\Resources\SaleReturns\Pages;

use App\Filament\Pharmacist\Resources\SaleReturns\SaleReturnResource;
use App\Filament\Pharmacist\Widgets\SaleReturnOverview;
use Filament\Resources\Pages\ListRecords;

class ListSaleReturns extends ListRecords
{
    protected static string $resource = SaleReturnResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            SaleReturnOverview::class,
        ];
    }
}

