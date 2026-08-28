<?php

namespace App\Filament\Resources\Units\Pages;

use App\Filament\Resources\Units\UnitResource;
use App\Filament\Widgets\UnitOverview;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageUnits extends ManageRecords
{
    protected static string $resource = UnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Unit')
                ->icon('heroicon-m-plus')
                ->extraAttributes(['class' => 'pms-hide-header-action']),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            UnitOverview::class,
        ];
    }
}