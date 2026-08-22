<?php

namespace App\Filament\Widgets;

use App\Models\SupplierPayment;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class SupplierPaymentStats extends StatsOverviewWidget
{
    protected function getExtraAttributes(): array
    {
        return [
            'class' => 'supplier-payment-stats-widget',
        ];
    }

    protected function getStats(): array
    {
        $totalPaid = SupplierPayment::sum('amount');

        $thisMonth = SupplierPayment::whereBetween('payment_date', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth(),
        ])->sum('amount');

        $today = SupplierPayment::whereDate(
            'payment_date',
            Carbon::today()
        )->sum('amount');

        $totalTransactions = SupplierPayment::count();

        return [

            Stat::make(
                'Total Paid',
                '$ ' . number_format($totalPaid, 2)
            )
                ->description('Total payments to suppliers')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->extraAttributes([
                    'class' => 'supplier-payment-stat-card supplier-payment-total',
                ]),

            Stat::make(
                'This Month',
                '$ ' . number_format($thisMonth, 2)
            )
                ->description('Payments made this month')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary')
                ->extraAttributes([
                    'class' => 'supplier-payment-stat-card supplier-payment-month',
                ]),

            Stat::make(
                'Today',
                '$ ' . number_format($today, 2)
            )
                ->description('Payments made today')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning')
                ->extraAttributes([
                    'class' => 'supplier-payment-stat-card supplier-payment-today',
                ]),

            Stat::make(
                'Transactions',
                number_format($totalTransactions)
            )
                ->description('Total payment transactions')
                ->descriptionIcon('heroicon-m-receipt-percent')
                ->color('info')
                ->extraAttributes([
                    'class' => 'supplier-payment-stat-card supplier-payment-transactions',
                ]),
        ];
    }
}