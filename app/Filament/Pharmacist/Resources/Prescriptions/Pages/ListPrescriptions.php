<?php

namespace App\Filament\Pharmacist\Resources\Prescriptions\Pages;

use App\Filament\Pharmacist\Resources\Prescriptions\PrescriptionResource;
use App\Filament\Pharmacist\Widgets\PrescriptionOverview;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPrescriptions extends ListRecords
{
    protected static string $resource = PrescriptionResource::class;

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
            PrescriptionOverview::class,
        ];
    }
}