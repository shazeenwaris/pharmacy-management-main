<?php

namespace App\Filament\Pharmacist\Widgets;

use App\Models\Prescription;
use App\Models\Sale;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TodaySalesWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $todaySales = Sale::whereDate('created_at', today())->sum('grand_total');
        $todayCount = Sale::whereDate('created_at', today())->count();
        $pendingPrescriptions = Prescription::where('status', 'active')->count();

        return [
            Stat::make('Today\'s Sales', format_currency($todaySales))
                ->description($todayCount . ' transactions today')
                ->color('success'),

            Stat::make('Pending Prescriptions', $pendingPrescriptions)
                ->description('Awaiting verification/dispensing')
                ->color($pendingPrescriptions > 0 ? 'warning' : 'success'),
        ];
    }
}