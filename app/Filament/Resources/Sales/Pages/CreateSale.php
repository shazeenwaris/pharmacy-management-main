<?php

namespace App\Filament\Resources\Sales\Pages;

use App\Filament\Resources\Sales\SaleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSale extends CreateRecord
{
    protected static string $resource = SaleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['invoice_number'])) {
            $data['invoice_number'] = 'INV-' . now()->format('Ymd') . '-' . str_pad(
                (string) (\App\Models\Sale::whereDate('created_at', now())->count() + 1),
                4,
                '0',
                STR_PAD_LEFT
            );
        }

        return $data;
    }
}