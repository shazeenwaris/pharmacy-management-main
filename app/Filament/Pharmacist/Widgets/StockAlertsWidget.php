<?php

namespace App\Filament\Pharmacist\Widgets;

use App\Models\MedicineBatch;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StockAlertsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $expiringSoon = MedicineBatch::where('status', true)
            ->where('quantity', '>', 0)
            ->whereBetween('expiry_date', [now(), now()->addDays(30)])
            ->count();

        $expired = MedicineBatch::where('status', true)
            ->where('quantity', '>', 0)
            ->where('expiry_date', '<', now())
            ->count();

        return [
            Stat::make('Expiring Soon (30 days)', $expiringSoon)
                ->color($expiringSoon > 0 ? 'warning' : 'success'),

            Stat::make('Expired Batches', $expired)
                ->color($expired > 0 ? 'danger' : 'success'),
        ];
    }
}