<?php

namespace App\Filament\Resources\Manufacturers\Pages;

use App\Filament\Resources\Manufacturers\ManufacturerResource;
use App\Filament\Resources\Manufacturers\Widgets\ManufacturerBanner;
use App\Filament\Resources\Manufacturers\Widgets\ManufacturerStats;
use Filament\Resources\Pages\ListRecords;

class ListManufacturers extends ListRecords
{
    protected static string $resource = ManufacturerResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ManufacturerBanner::class,
            ManufacturerStats::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return 1;
    }
}