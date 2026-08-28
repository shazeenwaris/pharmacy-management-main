<?php
// app/Filament/Resources/StockAdjustments/Tables/StockAdjustmentsTable.php

namespace App\Filament\Resources\StockAdjustments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class StockAdjustmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('medicine.name')->label('Medicine')->searchable()->sortable(),
                TextColumn::make('batch.batch_number')->label('Batch')->searchable(),
                TextColumn::make('old_quantity')->label('Old Qty')->sortable(),
                TextColumn::make('new_quantity')->label('New Qty')->sortable(),

                BadgeColumn::make('difference')
                    ->color(fn ($state) => $state > 0 ? 'success' : ($state < 0 ? 'danger' : 'gray'))
                    ->formatStateUsing(fn ($state) => $state > 0 ? "+{$state}" : $state),

                BadgeColumn::make('reason')
                    ->colors([
                        'danger' => ['damaged', 'expired', 'lost'],
                        'warning' => 'correction',
                        'success' => 'returned',
                        'gray' => 'other',
                    ]),

                TextColumn::make('user.name')->label('Adjusted By')->sortable(),
                TextColumn::make('created_at')->label('Date/Time')->dateTime('d M Y, h:i A')->sortable(),
            ])
            ->filters([
                SelectFilter::make('reason')->options([
                    'damaged' => 'Damaged',
                    'expired' => 'Expired',
                    'lost' => 'Lost',
                    'correction' => 'Correction',
                    'returned' => 'Returned',
                    'other' => 'Other',
                ]),
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
            ->defaultSort('created_at', 'desc');
    }
}