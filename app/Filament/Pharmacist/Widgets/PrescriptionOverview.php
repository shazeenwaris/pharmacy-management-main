<?php

namespace App\Filament\Pharmacist\Widgets;

use App\Models\Prescription;
use Filament\Widgets\Widget;

class PrescriptionOverview extends Widget
{
    protected string $view = 'filament.widgets.pharmacist-prescription-overview';

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    public int $totalPrescriptions = 0;
    public int $thisMonthCount = 0;
    public int $totalPatients = 0;

    public function mount(): void
    {
        $this->totalPrescriptions = Prescription::count();

        $this->thisMonthCount = Prescription::whereMonth('prescription_date', now()->month)
            ->whereYear('prescription_date', now()->year)
            ->count();

        $this->totalPatients = Prescription::distinct('patient_name')->count('patient_name');
    }
}