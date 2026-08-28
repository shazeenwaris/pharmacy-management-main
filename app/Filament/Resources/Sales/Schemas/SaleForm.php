<?php

namespace App\Filament\Resources\Sales\Schemas;

use App\Models\Customer;
use App\Models\Medicine;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class SaleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Sale Info')
                    ->description('Invoice reference, customer and cashier details')
                    ->icon(Heroicon::OutlinedDocumentText)
                    ->columnSpanFull()
                    ->columns(4)
                    ->schema([
                        TextInput::make('invoice_number')
                            ->label('Invoice #')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->default(fn () => 'INV-' . now()->format('Ymd') . '-' . str_pad((string) rand(1, 9999), 4, '0', STR_PAD_LEFT))
                            ->disabled()
                            ->dehydrated(),

                        Select::make('customer_id')
                            ->label('Customer (optional)')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload(),

                        DatePicker::make('sale_date')
                            ->label('Date')
                            ->default(now())
                            ->required(),

                        Select::make('payment_method')
                            ->label('Payment Method')
                            ->options([
                                'cash' => 'Cash',
                                'card' => 'Card',
                                'bank_transfer' => 'Bank Transfer',
                                'other' => 'Other',
                            ])
                            ->default('cash')
                            ->required(),
                    ]),

                Section::make('Medicines')
                    ->description('Search and add medicines to this sale')
                    ->icon(Heroicon::OutlinedShoppingCart)
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('items')
                            ->relationship('items')
                            ->schema([
                                Select::make('medicine_id')
                                    ->label('Medicine')
                                    ->relationship('medicine', 'name')
                                    ->options(fn () => Medicine::query()->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, $set) {
                                        $medicine = Medicine::find($state);
                                        $set('price', $medicine?->sale_price ?? 0);
                                    })
                                    ->columnSpan(2),

                                TextInput::make('quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, $get, $set) {
                                        $total = ((float) $state * (float) $get('price')) - (float) $get('discount');
                                        $set('total', max($total, 0));
                                    }),

                                TextInput::make('price')
                                    ->numeric()
                                    ->required()
                                    ->prefix(fn () => currency_symbol())
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, $get, $set) {
                                        $total = ((float) $get('quantity') * (float) $state) - (float) $get('discount');
                                        $set('total', max($total, 0));
                                    }),

                                TextInput::make('discount')
                                    ->numeric()
                                    ->default(0)
                                    ->prefix(fn () => currency_symbol())
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, $get, $set) {
                                        $total = ((float) $get('quantity') * (float) $get('price')) - (float) $state;
                                        $set('total', max($total, 0));
                                    }),

                                TextInput::make('total')
                                    ->numeric()
                                    ->prefix(fn () => currency_symbol())
                                    ->disabled()
                                    ->dehydrated(),
                            ])
                            ->columns(6)
                            ->columnSpanFull()
                            ->addActionLabel('Add Medicine')
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                $subtotal = collect($state)->sum('total');
                                $set('../../subtotal', $subtotal);
                            }),
                    ]),

                Section::make('Payment Summary')
                    ->icon(Heroicon::OutlinedBanknotes)
                    ->columnSpanFull()
                    ->columns(4)
                    ->schema([
                        TextInput::make('subtotal')
                            ->numeric()
                            ->default(0)
                            ->prefix(fn () => currency_symbol())
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('discount')
                            ->label('Overall Discount')
                            ->numeric()
                            ->default(0)
                            ->prefix(fn () => currency_symbol()),

                        TextInput::make('tax')
                            ->label('Tax')
                            ->numeric()
                            ->default(0)
                            ->prefix(fn () => currency_symbol()),

                        TextInput::make('grand_total')
                            ->label('Grand Total')
                            ->numeric()
                            ->required()
                            ->prefix(fn () => currency_symbol()),
                    ]),
            ]);
    }
}