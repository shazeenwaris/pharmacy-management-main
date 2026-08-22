<?php

namespace App\Filament\Resources\SaleReturns\Pages;

use App\Filament\Resources\SaleReturns\SaleReturnResource;
use App\Models\MedicineBatch;
use Filament\Resources\Pages\CreateRecord;

class CreateSaleReturn extends CreateRecord
{
    protected static string $resource = SaleReturnResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['processed_by'] = auth()->id();

        return $data;
    }

    protected function afterCreate(): void
    {
        $batch = MedicineBatch::find($this->record->medicine_batch_id);

        if ($batch) {
            $batch->increment('quantity', $this->record->quantity);
        }
    }
}