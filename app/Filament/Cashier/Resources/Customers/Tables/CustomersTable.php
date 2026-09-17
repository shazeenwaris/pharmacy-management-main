<?php

namespace App\Filament\Cashier\Resources\Customers\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                // =====================================================
                // CUSTOMER NAME
                // =====================================================
                TextColumn::make('name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-o-user')
                    ->iconColor('primary')
                    ->description(fn ($record) => $record->email ?? 'Registered customer'),

                // =====================================================
                // PHONE
                // =====================================================
                TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-phone')
                    ->iconColor('gray'),

                // =====================================================
                // TOTAL PURCHASES
                // =====================================================
                TextColumn::make('total_purchases')
                    ->label('Total Purchases')
                    ->state(function ($record): float {
                        return (float) $record->sales()
                            ->sum('grand_total');
                    })
                    ->formatStateUsing(fn ($state) => format_currency($state))
                    ->sortable()
                    ->weight('bold')
                    ->color('primary'),

                // =====================================================
                // TOTAL PAID
                // =====================================================
                TextColumn::make('total_paid')
                    ->label('Total Paid')
                    ->state(function ($record): float {
                        return (float) $record->sales()
                            ->sum('paid_amount');
                    })
                    ->formatStateUsing(fn ($state) => format_currency($state))
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),

                // =====================================================
                // TOTAL DUE
                // =====================================================
                TextColumn::make('total_due')
                    ->label('Total Due')
                    ->state(function ($record): float {
                        return (float) $record->due_balance;
                    })
                    ->formatStateUsing(fn ($state) => format_currency($state))
                    ->sortable()
                    ->weight('bold')
                    ->color(fn ($state) => (float) $state > 0 ? 'danger' : 'success'),

                // =====================================================
                // LAST PURCHASE
                // =====================================================
                TextColumn::make('last_purchase')
                    ->label('Last Purchase')
                    ->state(function ($record) {
                        return $record->sales()
                            ->latest('created_at')
                            ->value('created_at');
                    })
                    ->dateTime('d M Y')
                    ->sortable()
                    ->icon('heroicon-o-calendar-days')
                    ->iconColor('gray'),

            ])

            // =========================================================
            // FILTERS
            // =========================================================
            ->filters([])

            // =========================================================
            // RECORD ACTIONS
            // =========================================================
            ->recordActions([
                ViewAction::make()
                    ->label('View')
                    ->icon('heroicon-o-eye'),
            ])

            // =========================================================
            // TOOLBAR
            // =========================================================
            ->toolbarActions([])

            // =========================================================
            // TABLE STYLING
            // =========================================================
            ->striped(false);
    }
}
