<?php

namespace App\Filament\Widgets;

use App\Models\MedicineBatch;
use Filament\Widgets\Widget;

class ExpiryTrackerOverview extends Widget
{
    protected string $view = 'filament.widgets.expiry-tracker-overview';

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $today = now()->startOfDay();

        $expired = MedicineBatch::query()
            ->where('expiry_date', '<', $today)
            ->count();

        $within30 = MedicineBatch::query()
            ->whereBetween('expiry_date', [
                $today,
                $today->copy()->addDays(30),
            ])
            ->count();

        $safe = MedicineBatch::query()
            ->where('expiry_date', '>', $today->copy()->addDays(30))
            ->count();

        $total = MedicineBatch::query()->count();

        return [
            'expired' => $expired,
            'within30' => $within30,
            'safe' => $safe,
            'total' => $total,
        ];
    }
}