<?php

namespace App\Filament\Resources\Sales\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SaleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Sale Details')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('invoice_number')
                            ->label('Invoice #'),
                        TextEntry::make('creator.name')
                            ->label('Cashier'),
                        TextEntry::make('created_at')
                            ->label('Date')
                            ->dateTime('d M Y, h:i A'),
                        TextEntry::make('payment_method')
                            ->badge(),
                        TextEntry::make('paid_amount')
                            ->money('PKR'),
                        TextEntry::make('change_amount')
                            ->label('Change')
                            ->money('PKR'),
                    ]),

                Section::make('Items')
                    ->schema([
                        RepeatableEntry::make('items')
                            ->schema([
                                TextEntry::make('medicine.name')
                                    ->label('Medicine'),
                                TextEntry::make('quantity'),
                                TextEntry::make('unit_price')
                                    ->money('PKR'),
                                TextEntry::make('total')
                                    ->money('PKR'),
                            ])
                            ->columns(4),
                    ]),

                Section::make('Totals')
                    ->columns(4)
                    ->schema([
                        TextEntry::make('subtotal')
                            ->money('PKR'),
                        TextEntry::make('discount')
                            ->money('PKR'),
                        TextEntry::make('tax')
                            ->money('PKR'),
                        TextEntry::make('grand_total')
    ->formatStateUsing(fn ($state) => format_currency($state))
    ->weight('bold'),
                    ]),
            ]);
    }
}