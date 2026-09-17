<?php

namespace App\Filament\Cashier\Resources\Customers\Pages;

use App\Filament\Cashier\Resources\Customers\CustomerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;
}
