<?php

namespace App\Filament\Resources\Medicines\Tables;

use App\Filament\Resources\Medicines\MedicineResource;
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
                    ->label('Product Name')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold')
                    ->description(fn ($record) => $record->generic_name),

                TextColumn::make('product_type')
                    ->label('Type')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn ($state) => MedicineResource::productTypes()[$state] ?? $state)
                    ->toggleable(),

                TextColumn::make('brand_name')
                    ->label('Brand')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('barcode')
                    ->label('Barcode')
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

                TextColumn::make('pack_size')
                    ->label('Pack Size')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('unit.name')
                    ->label('Unit')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('current_stock')
                    ->label('Stock')
                    ->state(fn ($record) => $record->batches()->sum('quantity'))
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
                    }),

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
                SelectFilter::make('product_type')
                    ->label('Product Type')
                    ->options(fn () => MedicineResource::productTypes()),

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
                        ->whereRaw('(select coalesce(sum(quantity), 0) from medicine_batches where medicine_batches.medicine_id = medicines.id) <= medicines.minimum_stock_level')),
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