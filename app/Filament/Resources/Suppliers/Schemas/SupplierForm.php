<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SupplierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // ================================
            // SUPPLIER PROFILE
            // ================================

            Section::make('Supplier Profile')
                ->icon('heroicon-o-building-office-2')
                ->columns(3)
                ->schema([

                    TextInput::make('supplier_name')
                        ->label('Supplier Name')
                        ->placeholder('Ahmed Traders')
                        ->prefixIcon('heroicon-o-user')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('company_name')
                        ->label('Company')
                        ->placeholder('ABC Pharmaceutical')
                        ->prefixIcon('heroicon-o-building-office')
                        ->maxLength(255),

                    TextInput::make('contact_person')
                        ->label('Contact Person')
                        ->placeholder('Muhammad Ahmed')
                        ->prefixIcon('heroicon-o-user-circle')
                        ->maxLength(255),

                    TextInput::make('phone')
                        ->label('Phone')
                        ->placeholder('+92 300 1234567')
                        ->prefixIcon('heroicon-o-phone')
                        ->tel()
                        ->maxLength(30),

                    TextInput::make('whatsapp')
                        ->label('WhatsApp')
                        ->placeholder('+92 300 1234567')
                        ->prefixIcon('heroicon-o-chat-bubble-left-right')
                        ->tel()
                        ->maxLength(30),

                    TextInput::make('email')
                        ->label('Email')
                        ->placeholder('supplier@example.com')
                        ->prefixIcon('heroicon-o-envelope')
                        ->email()
                        ->maxLength(255),

                ])
                ->columnSpanFull(),


            // ================================
            // BUSINESS INFORMATION
            // ================================

            Section::make('Business Information')
                ->icon('heroicon-o-map-pin')
                ->columns(3)
                ->schema([

                    TextInput::make('city')
                        ->label('City')
                        ->placeholder('Lahore')
                        ->prefixIcon('heroicon-o-building-office-2')
                        ->maxLength(100),

                    TextInput::make('country')
                        ->label('Country')
                        ->placeholder('Pakistan')
                        ->prefixIcon('heroicon-o-globe-alt')
                        ->default('Pakistan')
                        ->maxLength(100),

                    TextInput::make('tax_ntn_number')
                        ->label('Tax / NTN')
                        ->placeholder('1234567-8')
                        ->prefixIcon('heroicon-o-document-text')
                        ->maxLength(100),

                    Textarea::make('address')
                        ->label('Business Address')
                        ->placeholder('Office / Street / Area')
                        ->rows(2)
                        ->columnSpan(2)
                        ->maxLength(1000),

                    TextInput::make('payment_terms')
                        ->label('Payment Terms')
                        ->placeholder('Net 30 Days')
                        ->prefixIcon('heroicon-o-calendar-days')
                        ->maxLength(255),

                ])
                ->columnSpanFull(),


            // ================================
            // FINANCIAL
            // ================================

            Section::make('Financial Settings')
                ->icon('heroicon-o-banknotes')
                ->columns(3)
                ->schema([

                    TextInput::make('opening_balance')
                        ->label('Opening Balance')
                        ->numeric()
                        ->prefix('$')
                        ->default(0)
                        ->minValue(0)
                        ->step(0.01)
                        ->prefixIcon('heroicon-o-wallet'),

                    TextInput::make('credit_limit')
                        ->label('Credit Limit')
                        ->numeric()
                        ->prefix('$')
                        ->default(0)
                        ->minValue(0)
                        ->step(0.01)
                        ->prefixIcon('heroicon-o-credit-card'),

                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'active' => 'Active',
                            'inactive' => 'Inactive',
                        ])
                        ->default('active')
                        ->required()
                        ->native(false)
                        ->prefixIcon('heroicon-o-check-circle'),

                ])
                ->columnSpanFull(),


            // ================================
            // NOTES
            // ================================

            Section::make('Notes')
                ->icon('heroicon-o-document-text')
                ->schema([

                    Textarea::make('notes')
                        ->label('Internal Notes')
                        ->placeholder('Add any additional information...')
                        ->rows(2)
                        ->maxLength(2000),

                ])
                ->columnSpanFull(),

        ]);
    }
}