<?php

namespace App\Filament\Resources\Purchases\Pages;

use App\Filament\Resources\Purchases\PurchaseResource;
use App\Filament\Widgets\PurchaseOverview;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPurchases extends ListRecords
{
    protected static string $resource = PurchaseResource::class;

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
            PurchaseOverview::class,
        ];
    }
}