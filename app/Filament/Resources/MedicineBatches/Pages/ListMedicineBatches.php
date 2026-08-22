<?php

namespace App\Filament\Resources\MedicineBatches\Pages;

use App\Filament\Resources\MedicineBatches\MedicineBatchResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMedicineBatches extends ListRecords
{
    protected static string $resource = MedicineBatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
