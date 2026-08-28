<?php
// app/Filament/Resources/StockAdjustments/Pages/CreateStockAdjustment.php

namespace App\Filament\Resources\StockAdjustments\Pages;

use App\Filament\Resources\StockAdjustments\StockAdjustmentResource;
use App\Models\MedicineBatch;
use Filament\Resources\Pages\CreateRecord;

class CreateStockAdjustment extends CreateRecord
{
    protected static string $resource = StockAdjustmentResource::class;

    // Adjustment save hote hi batch ka actual quantity update ho jayega
    protected function afterCreate(): void
    {
        $batch = MedicineBatch::find($this->record->medicine_batch_id);

        if ($batch) {
            $batch->update([
                'quantity' => $this->record->new_quantity,
            ]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}