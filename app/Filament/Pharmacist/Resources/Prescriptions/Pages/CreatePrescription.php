<?php
namespace App\Filament\Pharmacist\Resources\Prescriptions\Pages;
use App\Filament\Pharmacist\Resources\Prescriptions\PrescriptionResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePrescription extends CreateRecord
{
    protected static string $resource = PrescriptionResource::class;
    protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
}