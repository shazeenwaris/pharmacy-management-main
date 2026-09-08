<?php

namespace App\Filament\Resources\Accounts\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AccountsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('account_name')
                    ->label('Account')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('account_type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(
                        fn (string $state): string =>
                            match ($state) {
                                'cash' => 'Cash',
                                'bank' => 'Bank',
                                'mobile_wallet' => 'Mobile Wallet',
                                'other' => 'Other',
                                default => ucfirst($state),
                            }
                    ),

                TextColumn::make('account_number')
                    ->label('Account Number')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('bank_name')
                    ->label('Bank')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('opening_balance')
                    ->label('Opening Balance')
                    ->formatStateUsing(fn ($state) => format_currency($state))
                    ->sortable(),

                TextColumn::make('current_balance')
                    ->label('Current Balance')
                    ->formatStateUsing(fn ($state) => format_currency($state))
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string =>
                        $state === 'active'
                            ? 'success'
                            : 'danger'
                    ),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                SelectFilter::make('account_type')
                    ->label('Account Type')
                    ->options([
                        'cash' => 'Cash',
                        'bank' => 'Bank',
                        'mobile_wallet' => 'Mobile Wallet',
                        'other' => 'Other',
                    ]),

                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ]),
            ])

            ->actions([
                \Filament\Actions\EditAction::make(),
            ])

            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->recordActionsColumnLabel('Actions')

            ->defaultSort('created_at', 'desc');
    }
}