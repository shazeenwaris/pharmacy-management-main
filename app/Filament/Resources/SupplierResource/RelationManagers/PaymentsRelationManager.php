<?php

namespace App\Filament\Resources\SupplierResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $title = 'Supplier Payments';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            DatePicker::make('payment_date')
                ->required()
                ->default(now()),

            TextInput::make('amount')
                ->numeric()
                ->prefix('PKR')
                ->required(),

            Select::make('payment_method')
                ->options([
                    'cash' => 'Cash',
                    'bank_transfer' => 'Bank Transfer',
                    'cheque' => 'Cheque',
                    'online' => 'Online',
                ]),

            TextInput::make('reference_number'),

            TextInput::make('account')
                ->placeholder('Cash / Bank account used'),

            Textarea::make('notes')
                ->rows(2)
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('reference_number')
            ->columns([
                TextColumn::make('payment_date')
                    ->date()
                    ->sortable(),

                TextColumn::make('amount')
                    ->money('PKR')
                    ->sortable(),

                TextColumn::make('payment_method')
                    ->badge(),

                TextColumn::make('reference_number'),

                TextColumn::make('account'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('payment_date', 'desc');
    }
}