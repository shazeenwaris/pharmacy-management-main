<?php

namespace App\Filament\Pharmacist\Resources\Customers\Pages;

use App\Filament\Pharmacist\Resources\Customers\CustomerResource;
use App\Filament\Pharmacist\Widgets\CustomerOverview;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->extraAttributes(['class' => 'pms-hide-header-action']),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CustomerOverview::class,
        ];
    }
}