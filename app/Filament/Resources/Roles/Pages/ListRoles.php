<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RoleResource;
use App\Filament\Widgets\RoleOverview;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRoles extends ListRecords
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Role')
                ->icon('heroicon-m-plus')
                ->extraAttributes([
                    'class' => 'pms-hide-header-action',
                ]),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            RoleOverview::class,
        ];
    }
}