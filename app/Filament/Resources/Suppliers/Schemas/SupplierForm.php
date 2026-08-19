<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SupplierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->columns(2)
                    ->schema([
                        TextInput::make('supplier_name')
                            ->label('Supplier Name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('company_name')
                            ->label('Company Name')
                            ->maxLength(255),

                        TextInput::make('contact_person')
                            ->label('Contact Person')
                            ->maxLength(255),

                        TextInput::make('phone')
                            ->tel(),

                        TextInput::make('whatsapp')
                            ->tel(),

                        TextInput::make('email')
                            ->email(),
                    ]),

                Section::make('Address')
                    ->columns(3)
                    ->schema([
                        Textarea::make('address')
                            ->columnSpanFull()
                            ->rows(2),

                        TextInput::make('city'),

                        TextInput::make('country'),
                    ]),

                Section::make('Financial Information')
                    ->columns(3)
                    ->schema([
                        TextInput::make('tax_ntn_number')
                            ->label('Tax / NTN Number'),

                        TextInput::make('opening_balance')
                            ->numeric()
                            ->prefix('PKR')
                            ->default(0),

                        TextInput::make('credit_limit')
                            ->numeric()
                            ->prefix('PKR')
                            ->default(0),

                        TextInput::make('payment_terms')
                            ->placeholder('Net 30, Cash on Delivery...')
                            ->columnSpanFull(),
                    ]),

                Section::make('Status & Notes')
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                            ])
                            ->default('active')
                            ->required(),

                        Textarea::make('notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}