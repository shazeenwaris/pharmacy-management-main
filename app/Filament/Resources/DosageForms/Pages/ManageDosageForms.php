<?php

namespace App\Filament\Resources\DosageForms\Pages;

use App\Filament\Resources\DosageForms\DosageFormResource;
use App\Filament\Widgets\DosageFormOverview;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageDosageForms extends ManageRecords
{
    protected static string $resource = DosageFormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Dosage Form')
                ->icon('heroicon-m-plus')
                ->extraAttributes(['style' => 'display:none !important;']),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            DosageFormOverview::class,
        ];
    }
}