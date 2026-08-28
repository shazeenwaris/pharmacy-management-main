<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Spatie\Activitylog\Models\Activity;

class ActivityOverview extends Widget
{
    protected string $view = 'filament.widgets.activity-overview';

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $total = Activity::count();

        $today = Activity::whereDate(
            'created_at',
            today()
        )->count();

        $created = Activity::where('event', 'created')->count();

        $updated = Activity::where('event', 'updated')->count();

        $deleted = Activity::where('event', 'deleted')->count();

        return [
            'total' => $total,
            'today' => $today,
            'created' => $created,
            'updated' => $updated,
            'deleted' => $deleted,
        ];
    }
}