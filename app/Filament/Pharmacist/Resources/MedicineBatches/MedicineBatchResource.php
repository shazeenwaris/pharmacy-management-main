<?php

namespace App\Filament\Pharmacist\Resources\MedicineBatches;

use App\Filament\Pharmacist\Resources\MedicineBatches\Pages\ListMedicineBatches;
use App\Models\MedicineBatch;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MedicineBatchResource extends Resource
{
    protected static ?string $model = MedicineBatch::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBox;

    protected static ?string $navigationLabel = 'Stock';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('medicine.name')
                    ->label('Medicine')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('batch_number')
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label('Current Stock')
                    ->sortable(),
                TextColumn::make('medicine.minimum_stock_level')
                    ->label('Minimum Level'),
                TextColumn::make('expiry_date')
                    ->date()
                    ->sortable()
                    ->color(fn ($record) => match (true) {
                        $record->expiry_date < now() => 'danger',
                        $record->expiry_date <= now()->addDays(30) => 'warning',
                        default => 'success',
                    }),
                TextColumn::make('status_label')
                    ->label('Status')
                    ->state(function ($record) {
                        if ($record->quantity <= 0) {
                            return 'Out of Stock';
                        }
                        if ($record->expiry_date < now()) {
                            return 'Expired';
                        }
                        if ($record->quantity <= $record->medicine->minimum_stock_level) {
                            return 'Low Stock';
                        }
                        return 'Good';
                    })
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'Good' => 'success',
                        'Low Stock' => 'warning',
                        'Out of Stock', 'Expired' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('medicine_id')
                    ->relationship('medicine', 'name')
                    ->label('Medicine'),

                Filter::make('low_stock')
                    ->label('Low Stock')
                    ->query(fn (Builder $query): Builder => $query
                        ->whereHas('medicine', fn ($q) => $q->whereColumn('medicine_batches.quantity', '<=', 'medicines.minimum_stock_level'))),

                Filter::make('expiring_soon')
                    ->label('Expiring Soon (30 days)')
                    ->query(fn (Builder $query): Builder => $query
                        ->whereBetween('expiry_date', [now(), now()->addDays(30)])),
            ])
            ->recordActions([])
            ->defaultSort('expiry_date', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMedicineBatches::route('/'),
        ];
    }
}