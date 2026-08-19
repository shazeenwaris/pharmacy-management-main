<?php

namespace App\Filament\Resources\SupplierPayments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SupplierPaymentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Payment Details')
                    ->columns(2)
                    ->schema([

                        TextEntry::make('supplier.supplier_name')
                            ->label('Supplier')
                            ->weight('bold'),

                        TextEntry::make('payment_date')
                            ->label('Payment Date')
                            ->date('d M Y'),

                        TextEntry::make('amount')
                            ->label('Amount')
                            ->money('PKR')
                            ->weight('bold'),

                        TextEntry::make('payment_method')
                            ->label('Payment Method')
                            ->badge(),

                        TextEntry::make('reference_number')
                            ->label('Reference Number'),

                        TextEntry::make('account')
                            ->label('Paid From Account'),

                    ]),

                Section::make('Notes')
                    ->schema([

                        TextEntry::make('notes')
                            ->label('Notes')
                            ->placeholder('No notes added.')
                            ->columnSpanFull(),

                    ]),

                Section::make('System Information')
                    ->columns(2)
                    ->schema([

                        TextEntry::make('created_at')
                            ->label('Created')
                            ->dateTime('d M Y, h:i A'),

                        TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime('d M Y, h:i A'),

                    ]),

            ]);
    }
}