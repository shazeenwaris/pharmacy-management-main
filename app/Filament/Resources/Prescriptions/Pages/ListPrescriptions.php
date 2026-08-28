<?php

namespace App\Filament\Resources\Prescriptions\Pages;

use App\Filament\Resources\Prescriptions\PrescriptionResource;
use App\Filament\Widgets\PrescriptionOverview;
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

    public function mount(): void
    {
        parent::mount();

        if (request()->has('status')) {
            $this->tableFilters['status']['value'] = request()->get('status');
        }
    }
}