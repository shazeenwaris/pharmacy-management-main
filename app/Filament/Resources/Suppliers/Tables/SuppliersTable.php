<?php

namespace App\Filament\Resources\Suppliers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SuppliersTable
{
    public static function configure(Table $table): Table
    {
        return $table

            ->columns([

                TextColumn::make('supplier_name')
                    ->label('Supplier')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-o-building-storefront')
                    ->iconColor('primary')
                    ->description(
                        fn ($record) => $record->company_name
                    ),

                TextColumn::make('phone')
                    ->label('Contact')
                    ->searchable()
                    ->icon('heroicon-o-phone')
                    ->iconColor('gray'),

                TextColumn::make('city')
                    ->label('Location')
                    ->searchable()
                    ->icon('heroicon-o-map-pin')
                    ->iconColor('gray'),

                TextColumn::make('payable_balance')
<<<<<<< HEAD
                    ->label('Payable Balance')
                    ->formatStateUsing(fn ($state) => format_currency($state))
=======
                    ->label('Payable')
                    ->money('USD')
                    ->sortable()
                    ->weight('bold')
>>>>>>> d06efcbf243a71ca1589c114591841d520a8f34f
                    ->color(
                        fn ($record): string =>
                            $record->payable_balance > 0
                                ? 'danger'
                                : 'success'
                    ),

                TextColumn::make('credit_limit')
<<<<<<< HEAD
                    ->formatStateUsing(fn ($state) => format_currency($state))
=======
                    ->label('Credit Limit')
                    ->money('USD')
>>>>>>> d06efcbf243a71ca1589c114591841d520a8f34f
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(
                        fn (?string $state): string =>
                            ucfirst($state ?? 'Unknown')
                    )
                    ->color(
                        fn (?string $state): string =>
                            match ($state) {
                                'active' => 'success',
                                'inactive' => 'gray',
                                default => 'gray',
                            }
                    ),

            ])

            ->filters([

                SelectFilter::make('status')
                    ->label('Supplier Status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ]),

            ])

            ->recordActions([

                ViewAction::make()
                    ->color('gray'),

                EditAction::make()
                    ->color('primary'),

                DeleteAction::make(),

            ])

            ->toolbarActions([

                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),

            ])

            ->searchPlaceholder('Search suppliers...')

            ->defaultSort('supplier_name');

    }
}