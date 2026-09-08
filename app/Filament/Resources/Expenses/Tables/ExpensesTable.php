<?php

namespace App\Filament\Resources\Expenses\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ExpensesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('expense_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),

                 TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('account.account_name')
                    ->label('Account')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('amount')
    ->label('Amount')
    ->formatStateUsing(fn ($state) => format_currency($state))
    ->sortable(),

                TextColumn::make('payment_method')
                    ->label('Payment')
                    ->formatStateUsing(
                        fn (?string $state) =>
                            $state ? ucfirst($state) : '-'
                    )
                    ->badge(),

                TextColumn::make('reference')
                    ->label('Reference')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->defaultSort('expense_date', 'desc');
    }
}