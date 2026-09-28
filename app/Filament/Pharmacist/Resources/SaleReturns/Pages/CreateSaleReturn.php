<?php

namespace App\Filament\Pharmacist\Resources\SaleReturns\Pages;

use App\Filament\Pharmacist\Resources\SaleReturns\SaleReturnResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSaleReturn extends CreateRecord
{
    protected static string $resource = SaleReturnResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['processed_by'] = auth()->id();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}