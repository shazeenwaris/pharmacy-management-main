<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Widgets\CategoryOverview;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCategories extends ManageRecords
{
    protected static string $resource = CategoryResource::class;

    
        protected function getHeaderActions(): array
{
    return [
        CreateAction::make()
            ->label('New Category')
            ->icon('heroicon-m-plus')
            ->extraAttributes(['class' => 'pms-hide-header-action']),
    ];
}

    protected function getHeaderWidgets(): array
    {
        return [
            CategoryOverview::class,
        ];
    }
}