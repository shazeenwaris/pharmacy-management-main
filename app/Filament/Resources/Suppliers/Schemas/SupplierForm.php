<?php

namespace App\Filament\Resources\Suppliers\Schemas;

<<<<<<< HEAD
use Filament\Infolists\Components\TextEntry;
=======
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
>>>>>>> d06efcbf243a71ca1589c114591841d520a8f34f
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SupplierInfolist
{
    public static function configure(Schema $schema): Schema
    {
<<<<<<< HEAD
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
                            ->formatStateUsing(fn ($state) => format_currency($state)),

                        TextEntry::make('credit_limit')
                            ->formatStateUsing(fn ($state) => format_currency($state)),

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
=======
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
>>>>>>> d06efcbf243a71ca1589c114591841d520a8f34f
    }
}