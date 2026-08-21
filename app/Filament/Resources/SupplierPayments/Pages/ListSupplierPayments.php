<?php

namespace App\Filament\Resources\SupplierPayments\Pages;

use App\Filament\Resources\SupplierPayments\SupplierPaymentResource;
use Filament\Resources\Pages\ListRecords;

class ListSupplierPayments extends ListRecords
{
    protected static string $resource = SupplierPaymentResource::class;

    protected string $view = 'filament.resources.supplier-payments.pages.supplier-payments-page';

    protected function getHeaderActions(): array
    {
        return [];
    }
}