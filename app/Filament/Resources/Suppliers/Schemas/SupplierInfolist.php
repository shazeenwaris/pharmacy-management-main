<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SupplierInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('supplier_name')
                            ->label('Supplier Name'),

                        TextEntry::make('company_name')
                            ->label('Company Name'),

                        TextEntry::make('contact_person')
                            ->label('Contact Person'),

                        TextEntry::make('phone'),

                        TextEntry::make('whatsapp'),

                        TextEntry::make('email'),
                    ]),

                Section::make('Address')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('address')
                            ->columnSpanFull(),

                        TextEntry::make('city'),

                        TextEntry::make('country'),
                    ]),

                Section::make('Financial Information')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('tax_ntn_number')
                            ->label('Tax / NTN Number'),

                        TextEntry::make('opening_balance')
                            ->money('PKR'),

                        TextEntry::make('credit_limit')
                            ->money('PKR'),

                        TextEntry::make('payment_terms')
                            ->columnSpanFull(),
                    ]),

                Section::make('Status & Notes')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'active' => 'success',
                                'inactive' => 'gray',
                                default => 'gray',
                            }),

                        TextEntry::make('notes')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}