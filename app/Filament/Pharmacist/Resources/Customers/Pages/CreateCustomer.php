<?php
namespace App\Filament\Pharmacist\Resources\Customers\Pages;
use App\Filament\Pharmacist\Resources\Customers\CustomerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;
    protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
}