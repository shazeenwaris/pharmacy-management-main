<?php

namespace App\Filament\Widgets;

use App\Models\MedicineBatch;
use Carbon\Carbon;
use Filament\Widgets\Widget;

class MedicineBatchOverview extends Widget
{
    protected string $view = 'filament.widgets.medicine-batch-overview';

     protected static bool $isLazy = false;

    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        return [
            'totalBatches'   => MedicineBatch::count(),
            'activeBatches'  => MedicineBatch::where('quantity', '>', 0)
                ->whereDate('expiry_date', '>=', Carbon::today())
                ->count(),
            'expiredBatches' => MedicineBatch::whereDate('expiry_date', '<', Carbon::today())->count(),
        ];
    }
}