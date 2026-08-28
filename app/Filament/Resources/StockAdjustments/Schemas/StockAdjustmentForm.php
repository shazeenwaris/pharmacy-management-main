<?php
// app/Filament/Resources/StockAdjustments/Schemas/StockAdjustmentForm.php

namespace App\Filament\Resources\StockAdjustments\Schemas;

use App\Models\Medicine;
use App\Models\MedicineBatch;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class StockAdjustmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Stock Adjustment')
                ->description('Correct system stock to match actual physical count')
                ->icon(Heroicon::OutlinedAdjustmentsHorizontal)
                ->columnSpanFull()
                ->columns(2)
                ->components([
                    Select::make('medicine_id')
                        ->label('Medicine')
                        ->options(fn () => Medicine::query()->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->required()
                        ->live()
                        ->afterStateUpdated(fn ($set) => $set('medicine_batch_id', null)),

                    Select::make('medicine_batch_id')
                        ->label('Batch')
                        ->options(function ($get) {
                            $medicineId = $get('medicine_id');

                            if (! $medicineId) {
                                return [];
                            }

                            return MedicineBatch::query()
                                ->where('medicine_id', $medicineId)
                                ->pluck('batch_number', 'id');
                        })
                        ->searchable()
                        ->preload()
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($state, $set) {
                            $batch = MedicineBatch::find($state);
                            $set('old_quantity', $batch?->quantity ?? 0);
                        }),

                    TextInput::make('old_quantity')
                        ->label('Current (System) Quantity')
                        ->numeric()
                        ->disabled()
                        ->dehydrated()
                        ->default(0)
                        ->required(),

                    TextInput::make('new_quantity')
                        ->label('Actual (New) Quantity')
                        ->numeric()
                        ->minValue(0)
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, $get, $set) {
                            $set('difference', (int) $state - (int) $get('old_quantity'));
                        }),

                    TextInput::make('difference')
                        ->numeric()
                        ->disabled()
                        ->dehydrated()
                        ->default(0)
                        ->required(),

                    Select::make('reason')
                        ->options([
                            'damaged' => 'Damaged Medicine',
                            'expired' => 'Expired Medicine',
                            'lost' => 'Lost Stock',
                            'correction' => 'Manual Correction',
                            'returned' => 'Returned Stock',
                            'other' => 'Other',
                        ])
                        ->required(),

                    Textarea::make('notes')
                        ->rows(3)
                        ->columnSpanFull(),

                    Hidden::make('user_id')
                        ->default(fn () => Auth::id()),
                ]),
        ]);
    }
}