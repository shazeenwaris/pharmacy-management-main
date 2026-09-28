<?php

namespace App\Filament\Pharmacist\Resources\MedicineBatches\Pages;

use App\Filament\Pharmacist\Resources\MedicineBatches\MedicineBatchResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMedicineBatch extends EditRecord
{
    protected static string $resource = MedicineBatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
