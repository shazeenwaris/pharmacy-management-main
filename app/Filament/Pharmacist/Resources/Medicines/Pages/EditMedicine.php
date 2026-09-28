<?php

namespace App\Filament\Pharmacist\Resources\Medicines\Pages;

use App\Filament\Pharmacist\Resources\Medicines\MedicineResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMedicine extends EditRecord
{
    protected static string $resource = MedicineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
