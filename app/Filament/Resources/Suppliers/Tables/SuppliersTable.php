<?php

namespace App\Filament\Resources\Suppliers\Tables;

use App\Models\Supplier;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
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
                    ->weight('semibold')
                    ->description(
                        fn (Supplier $record): ?string =>
                            $record->company_name
                    ),

                TextColumn::make('phone')
                    ->searchable(),

                TextColumn::make('city')
                    ->toggleable(),

                TextColumn::make('payable_balance')
                    ->label('Payable Balance')
                    ->formatStateUsing(fn ($state) => format_currency($state))
                    ->color(
                        fn (Supplier $record): string =>
                            $record->payable_balance > 0
                                ? 'danger'
                                : 'success'
                    ),

                TextColumn::make('credit_limit')
                    ->formatStateUsing(fn ($state) => format_currency($state))
                    ->toggleable(),

                BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'gray' => 'inactive',
                    ]),
            ])

            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ]),
            ])

            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])

            ->defaultSort('supplier_name');
    }
}