<?php

namespace App\Filament\Pharmacist\Resources\MedicineBatches\Pages;

use App\Filament\Pharmacist\Resources\MedicineBatches\MedicineBatchResource;
use App\Filament\Pharmacist\Widgets\StockBanner;
use App\Filament\Pharmacist\Widgets\StockStats;
use Filament\Resources\Pages\ListRecords;

class ListMedicineBatches extends ListRecords
{
    protected static string $resource = MedicineBatchResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            StockBanner::class,
            StockStats::class,
        ];
    }
}