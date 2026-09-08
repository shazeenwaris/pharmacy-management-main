<?php

namespace App\Filament\Resources\PurchaseReturns\Pages;

use App\Filament\Resources\PurchaseReturns\PurchaseReturnResource;
use App\Filament\Widgets\PurchaseReturnOverview;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPurchaseReturns extends ListRecords
{
    protected static string $resource = PurchaseReturnResource::class;

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
            PurchaseReturnOverview::class,
        ];
    }
}