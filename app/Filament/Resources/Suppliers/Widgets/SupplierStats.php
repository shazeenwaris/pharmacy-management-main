<?php

namespace App\Filament\Resources\Suppliers\Widgets;

use App\Models\Supplier;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SupplierStats extends StatsOverviewWidget
{
    protected function getExtraAttributes(): array
    {
        return [
            'class' => 'supplier-stats-widget',
        ];
    }

    protected function getStats(): array
    {
        $suppliers = Supplier::withSum('payments', 'amount')->get();

        $total = $suppliers->count();

        $active = $suppliers
            ->where('status', 'active')
            ->count();

        $inactive = $suppliers
            ->where('status', 'inactive')
            ->count();

        $payable = $suppliers->sum(function ($supplier) {
            return (float) $supplier->opening_balance
                - (float) ($supplier->payments_sum_amount ?? 0);
        });

        return [
            Stat::make(
                'Total Suppliers',
                number_format($total)
            )
                ->description('All registered suppliers')
                ->descriptionIcon('heroicon-o-truck')
                ->icon('heroicon-o-building-storefront')
                ->color('primary')
                ->extraAttributes([
                    'class' => 'supplier-stat-card supplier-stat-total',
                ]),

            Stat::make(
                'Active Suppliers',
                number_format($active)
            )
                ->description('Currently active suppliers')
                ->descriptionIcon('heroicon-o-check-circle')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->extraAttributes([
                    'class' => 'supplier-stat-card supplier-stat-active',
                ]),

            Stat::make(
                'Outstanding Payable',
                '$ ' . number_format($payable, 2)
            )
                ->description('Total amount payable')
                ->descriptionIcon('heroicon-o-banknotes')
                ->icon('heroicon-o-currency-dollar')
                ->color('warning')
                ->extraAttributes([
                    'class' => 'supplier-stat-card supplier-stat-payable',
                ]),

            Stat::make(
                'Inactive Suppliers',
                number_format($inactive)
            )
                ->description('Currently inactive suppliers')
                ->descriptionIcon('heroicon-o-x-circle')
                ->icon('heroicon-o-user-minus')
                ->color('danger')
                ->extraAttributes([
                    'class' => 'supplier-stat-card supplier-stat-inactive',
                ]),
        ];
    }
}