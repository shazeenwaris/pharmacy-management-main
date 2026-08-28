<?php

namespace App\Filament\Widgets;

use App\Models\DosageForm;
use Filament\Widgets\Widget;

class DosageFormOverview extends Widget
{
    protected string $view = 'filament.widgets.dosage-form-overview';

     protected static bool $isLazy = false;

    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        return [
            'totalForms'  => DosageForm::count(),
            'activeForms' => DosageForm::where('status', true)->count(),
            'totalMedicines' => DosageForm::withCount('medicines')->get()->sum('medicines_count'),
        ];
    }
}