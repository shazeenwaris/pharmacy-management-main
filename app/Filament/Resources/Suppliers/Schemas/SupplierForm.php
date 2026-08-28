<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SupplierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // =====================================================
            // SUPPLIER OVERVIEW
            // =====================================================

            Section::make()
                ->schema([

                    TextEntry::make('supplier_name')
                        ->label('Supplier Name')
                        ->weight('bold')
                        ->icon('heroicon-m-user'),

                    TextEntry::make('company_name')
                        ->label('Company')
                        ->icon('heroicon-m-building-office-2'),

                    TextEntry::make('status')
                        ->label('Status')
                        ->badge()
                        ->formatStateUsing(
                            fn ($state) => $state
                                ? ucfirst($state)
                                : 'Unknown'
                        )
                        ->color(
                            fn ($state): string => match ($state) {
                                'active' => 'success',
                                'inactive' => 'danger',
                                default => 'gray',
                            }
                        ),

                    TextEntry::make('phone')
                        ->label('Phone')
                        ->icon('heroicon-m-phone'),

                    TextEntry::make('email')
                        ->label('Email')
                        ->icon('heroicon-m-envelope'),

                    TextEntry::make('whatsapp')
                        ->label('WhatsApp')
                        ->icon('heroicon-m-chat-bubble-left-right'),

                    TextEntry::make('payment_terms')
                        ->label('Payment Terms')
                        ->icon('heroicon-m-calendar-days'),

                ])
                ->columns(4)
                ->columnSpanFull()
                ->extraAttributes([
                    'class' => 'supplier-overview',
                ]),


            // =====================================================
            // CONTACT INFORMATION
            // =====================================================

            Section::make('Contact Information')
                ->description('Supplier contact and business location')
                ->icon('heroicon-m-identification')
                ->schema([

                    TextEntry::make('contact_person')
                        ->label('Contact Person')
                        ->icon('heroicon-m-user'),

                    TextEntry::make('phone')
                        ->label('Phone')
                        ->icon('heroicon-m-phone'),

                    TextEntry::make('email')
                        ->label('Email')
                        ->icon('heroicon-m-envelope'),

                    TextEntry::make('whatsapp')
                        ->label('WhatsApp')
                        ->icon('heroicon-m-chat-bubble-left-right'),

                    TextEntry::make('address')
                        ->label('Business Address')
                        ->icon('heroicon-m-map-pin')
                        ->columnSpan(2),

                    TextEntry::make('city')
                        ->label('City')
                        ->icon('heroicon-m-building-office'),

                    TextEntry::make('country')
                        ->label('Country')
                        ->icon('heroicon-m-globe-alt'),

                ])
                ->columns(4)
                ->columnSpan(2)
                ->extraAttributes([
                    'class' => 'supplier-modern-card supplier-contact-card',
                ]),


            // =====================================================
            // FINANCIAL INFORMATION
            // =====================================================

            Section::make('Financial Overview')
                ->description('Supplier payment and credit information')
                ->icon('heroicon-m-banknotes')
                ->schema([

                    TextEntry::make('opening_balance')
                        ->label('Opening Balance')
                        ->money('PKR')
                        ->icon('heroicon-m-arrow-trending-up'),

                    TextEntry::make('credit_limit')
                        ->label('Credit Limit')
                        ->money('PKR')
                        ->icon('heroicon-m-credit-card'),

                    TextEntry::make('tax_ntn_number')
                        ->label('Tax / NTN Number')
                        ->icon('heroicon-m-document-check'),

                ])
                ->columns(1)
                ->columnSpan(2)
                ->extraAttributes([
                    'class' => 'supplier-modern-card supplier-financial-card',
                ]),


            // =====================================================
            // SUPPLIER NOTES
            // =====================================================

            Section::make('Supplier Notes')
                ->description('Additional information about this supplier')
                ->icon('heroicon-m-document-text')
                ->schema([

                    TextEntry::make('notes')
                        ->label('')
                        ->placeholder('No additional notes have been added.')
                        ->columnSpanFull(),

                ])
                ->columnSpanFull()
                ->extraAttributes([
                    'class' => 'supplier-modern-card supplier-notes-card',
                ]),

        ]);
    }
}