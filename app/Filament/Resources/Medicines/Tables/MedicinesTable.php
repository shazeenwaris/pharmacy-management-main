<?php

namespace App\Filament\Resources\Medicines\Tables;

use App\Models\Category;
use App\Models\DosageForm;
use App\Models\Manufacturer;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MedicinesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('')
                    ->circular()
                    ->defaultImageUrl(fn () => 'https://ui-avatars.com/api/?background=0D9488&color=fff&name=Rx')
                    ->size(40),

                TextColumn::make('name')
                    ->label('Medicine Name')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold')
                    ->description(fn ($record) => $record->generic_name),

                TextColumn::make('sku')
                    ->label('SKU')
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('category.name')
                    ->label('Category')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('manufacturer.name')
                    ->label('Manufacturer')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('dosageForm.name')
                    ->label('Form')
                    ->badge()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('strength')
                    ->label('Strength')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('opening_stock')
                    ->label('Stock')
                    ->badge()
                    ->color(fn ($state, $record) => match (true) {
                        (int) $state <= 0 => 'danger',
                        (int) $state <= $record->minimum_stock_level => 'warning',
                        default => 'success',
                    })
                    ->formatStateUsing(fn ($state, $record) => match (true) {
                        (int) $state <= 0 => 'Out of Stock',
                        (int) $state <= $record->minimum_stock_level => (int) $state . ' (Low)',
                        default => (string) (int) $state,
                    })
                    ->sortable(),

                // IconColumn::make('prescription_required')
                //     ->label('Rx')
                //     ->boolean()
                //     ->trueIcon('heroicon-o-exclamation-triangle')
                //     ->falseIcon('heroicon-o-check-circle')
                //     ->trueColor('danger')
                //     ->falseColor('success')
                //     ->tooltip(fn ($state) => $state ? 'Prescription Required' : 'No Prescription Needed'),

                IconColumn::make('status')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Added')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->color('gray'),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->options(fn () => Category::query()->pluck('name', 'id'))
                    ->searchable()
                    ->preload(),

                SelectFilter::make('manufacturer_id')
                    ->label('Manufacturer')
                    ->options(fn () => Manufacturer::query()->pluck('name', 'id'))
                    ->searchable()
                    ->preload(),

                SelectFilter::make('dosage_form_id')
                    ->label('Dosage Form')
                    ->options(fn () => DosageForm::query()->pluck('name', 'id'))
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('prescription_required')
                    ->label('Rx Required')
                    ->trueLabel('Prescription only')
                    ->falseLabel('No prescription needed'),

                TernaryFilter::make('status')
                    ->label('Status')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),

                Filter::make('low_stock')
                    ->label('Low / Out of Stock')
                    ->query(fn (Builder $query): Builder => $query
                        ->whereColumn('opening_stock', '<=', 'minimum_stock_level')),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->recordActionsColumnLabel('Actions')
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->striped()
            ->defaultSort('name');
    }
}