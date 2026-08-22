<?php
// app/Filament/Pages/InventoryOverview.php

namespace App\Filament\Pages;

use App\Models\Medicine;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class InventoryOverview extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cube';

    protected static string|UnitEnum|null $navigationGroup = 'Stock';

    protected static ?string $navigationLabel = 'Inventory Overview';

    protected static ?int $navigationSort = 0;

    protected static ?string $slug = 'stock/overview';

    protected string $view = 'filament.pages.inventory-overview';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Medicine::query()->withSum('batches as stock_quantity', 'quantity')
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Medicine')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('stock_quantity')
                    ->label('Current Stock')
                    ->default(0)
                    ->sortable(),

                TextColumn::make('minimum_stock_level')
                    ->label('Minimum Level')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->state(function (Medicine $record) {
                        $stock = $record->stock_quantity ?? 0;

                        if ($stock <= 0) {
                            return 'Out of Stock';
                        }

                        if ($stock <= $record->minimum_stock_level) {
                            return 'Low';
                        }

                        return 'Good';
                    })
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'Out of Stock' => 'danger',
                        'Low' => 'warning',
                        default => 'success',
                    }),
            ])
            ->filters([
                Filter::make('low_stock')
                    ->label('Low Stock Only')
                    ->query(fn (Builder $query) => $query->whereRaw(
                        '(select coalesce(sum(quantity),0) from medicine_batches where medicine_batches.medicine_id = medicines.id) <= medicines.minimum_stock_level'
                    )),

                Filter::make('out_of_stock')
                    ->label('Out of Stock Only')
                    ->query(fn (Builder $query) => $query->whereRaw(
                        '(select coalesce(sum(quantity),0) from medicine_batches where medicine_batches.medicine_id = medicines.id) <= 0'
                    )),
            ])
            ->defaultSort('name');
    }
}