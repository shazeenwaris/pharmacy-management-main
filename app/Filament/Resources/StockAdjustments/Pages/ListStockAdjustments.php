<?php

namespace App\Filament\Resources\StockAdjustments\Pages;

use App\Filament\Resources\StockAdjustments\StockAdjustmentResource;
use App\Filament\Widgets\StockAdjustmentOverview;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStockAdjustments extends ListRecords
{
    protected static string $resource = StockAdjustmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Adjustment')
                ->icon('heroicon-m-plus')
                ->extraAttributes([
                    'class' => 'pms-hide-header-action',
                ]),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StockAdjustmentOverview::class,
        ];
    }
}