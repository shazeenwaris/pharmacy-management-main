<?php

namespace App\Filament\Resources\MedicineBatches\Pages;

use App\Filament\Resources\MedicineBatches\MedicineBatchResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMedicineBatch extends CreateRecord
{
    protected static string $resource = MedicineBatchResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
