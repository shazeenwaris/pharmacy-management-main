<?php

namespace App\Filament\Resources\CustomerPayments\Pages;

use App\Filament\Resources\CustomerPayments\CustomerPaymentResource;
use App\Filament\Widgets\CustomerPaymentOverview;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCustomerPayments extends ListRecords
{
    protected static string $resource = CustomerPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                 ->extraAttributes(['style' => 'display:none !important;']),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CustomerPaymentOverview::class,
        ];
    }
}