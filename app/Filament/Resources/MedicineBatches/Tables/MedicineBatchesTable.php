<?php

namespace App\Filament\Resources\MedicineBatches\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Carbon\Carbon;

class MedicineBatchesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('medicine.name')
                    ->label('Medicine')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),

                TextColumn::make('batch_number')
                    ->label('Batch #')
                    ->searchable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('quantity')
                    ->label('Quantity')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        (int) $state <= 0 => 'danger',
                        (int) $state <= 10 => 'warning',
                        default => 'success',
                    }),

                TextColumn::make('purchase_price')
                    ->label('Purchase Price')
                    ->formatStateUsing(fn ($state) => format_currency($state))
                    ->sortable(),

                TextColumn::make('sale_price')
                    ->label('Sale Price')
                    ->formatStateUsing(fn ($state) => format_currency($state))
                    ->sortable(),

                TextColumn::make('manufacturing_date')
                    ->label('Mfg Date')
                    ->date('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('expiry_date')
                    ->label('Expiry Date')
                    ->date('d M Y')
                    ->sortable()
                    ->color(fn ($record) => Carbon::parse($record->expiry_date)->isPast() ? 'danger' : (Carbon::parse($record->expiry_date)->diffInDays(now()) <= 30 ? 'warning' : 'success')),

                TextColumn::make('supplier.supplier_name')
                    ->label('Supplier')
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('status')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Added')
                    ->dateTime('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->color('gray'),
            ])
            ->filters([
                SelectFilter::make('medicine_id')
                    ->label('Medicine')
                    ->relationship('medicine', 'name')
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('status')
                    ->label('Status')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->striped()
            ->defaultSort('expiry_date');
    }
}