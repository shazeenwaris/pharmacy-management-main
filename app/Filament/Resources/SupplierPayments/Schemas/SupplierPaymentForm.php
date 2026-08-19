<?php

namespace App\Filament\Resources\SupplierPayments\Schemas;

use App\Models\Supplier;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SupplierPaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Payment Information')
                    ->description('Record a payment made to a supplier.')
                    ->icon('heroicon-o-banknotes')
                    ->columns(2)
                    ->schema([

                        Select::make('supplier_id')
                            ->label('Supplier')
                            ->options(
                                Supplier::query()
                                    ->orderBy('supplier_name')
                                    ->pluck('supplier_name', 'id')
                            )
                            ->searchable()
                            ->preload()
                            ->required(),

                        DatePicker::make('payment_date')
                            ->label('Payment Date')
                            ->default(now())
                            ->required(),

                        TextInput::make('amount')
                            ->label('Payment Amount')
                            ->numeric()
                            ->prefix('PKR')
                            ->minValue(0.01)
                            ->required(),

                        Select::make('payment_method')
                            ->label('Payment Method')
                            ->options([
                                'cash' => 'Cash',
                                'bank_transfer' => 'Bank Transfer',
                                'cheque' => 'Cheque',
                                'online' => 'Online Payment',
                                'card' => 'Card',
                                'other' => 'Other',
                            ])
                            ->searchable()
                            ->required(),

                        TextInput::make('reference_number')
                            ->label('Reference / Receipt Number')
                            ->placeholder('e.g. PAY-0001'),

                        TextInput::make('account')
                            ->label('Paid From Account')
                            ->placeholder('Cash Account / Meezan Bank / HBL...'),

                    ]),

                Section::make('Notes')
                    ->schema([

                        Textarea::make('notes')
                            ->label('Payment Notes')
                            ->rows(4)
                            ->placeholder('Add any additional information...'),

                    ]),
            ]);
    }
}