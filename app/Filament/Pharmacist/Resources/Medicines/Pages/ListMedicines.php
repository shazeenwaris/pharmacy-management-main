<?php

namespace App\Filament\Pharmacist\Resources\Medicines\Pages;

use App\Filament\Pharmacist\Resources\Medicines\MedicineResource;
use App\Filament\Pharmacist\Widgets\MedicineBanner;
use App\Filament\Pharmacist\Widgets\MedicineStats;
use Filament\Resources\Pages\ListRecords;

class ListMedicines extends ListRecords
{
    protected static string $resource = MedicineResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            MedicineBanner::class,
            MedicineStats::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int | array
{
    return 1;
}
}