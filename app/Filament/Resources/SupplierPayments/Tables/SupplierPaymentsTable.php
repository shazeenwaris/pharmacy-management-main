<?php

namespace App\Filament\Resources\SupplierPayments\Tables;

use Filament\Actions\BulkActionGroup;

use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SupplierPaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table

            /*
            |--------------------------------------------------------------------------
            | TABLE HEADER ACTION
            |--------------------------------------------------------------------------
            */

           

            /*
            |--------------------------------------------------------------------------
            | COLUMNS
            |--------------------------------------------------------------------------
            */

            ->columns([

                TextColumn::make('supplier.supplier_name')
                    ->label('Supplier')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),

                TextColumn::make('payment_date')
                    ->label('Date')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('amount')
                    ->label('Amount')
                    ->money('USD')
                    ->sortable()
                    ->weight('semibold'),

                TextColumn::make('payment_method')
                    ->label('Method')
                    ->badge()
                    ->formatStateUsing(
                        fn (?string $state): string =>
                            match ($state) {
                                'cash' => 'Cash',
                                'bank_transfer' => 'Bank Transfer',
                                'cheque' => 'Cheque',
                                'online' => 'Online',
                                'card' => 'Card',
                                'other' => 'Other',
                                default => ucfirst($state ?? 'N/A'),
                            }
                    )
                    ->color(
                        fn (?string $state): string =>
                            match ($state) {
                                'cash' => 'success',
                                'bank_transfer' => 'info',
                                'cheque' => 'warning',
                                'online' => 'primary',
                                'card' => 'gray',
                                default => 'gray',
                            }
                    ),

                TextColumn::make('reference_number')
                    ->label('Reference')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('account')
                    ->label('Account')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])

            /*
            |--------------------------------------------------------------------------
            | FILTERS
            |--------------------------------------------------------------------------
            */

            ->filters([

                SelectFilter::make('supplier_id')
                    ->label('Supplier')
                    ->relationship('supplier', 'supplier_name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('payment_method')
                    ->label('Payment Method')
                    ->options([
                        'cash' => 'Cash',
                        'bank_transfer' => 'Bank Transfer',
                        'cheque' => 'Cheque',
                        'online' => 'Online Payment',
                        'card' => 'Card',
                        'other' => 'Other',
                    ]),

            ])

            /*
            |--------------------------------------------------------------------------
            | RECORD ACTIONS
            |--------------------------------------------------------------------------
            */

            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])

            /*
            |--------------------------------------------------------------------------
            | BULK ACTIONS
            |--------------------------------------------------------------------------
            */

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])

            /*
            |--------------------------------------------------------------------------
            | DEFAULT SORT
            |--------------------------------------------------------------------------
            */

            ->defaultSort('payment_date', 'desc');
    }
}