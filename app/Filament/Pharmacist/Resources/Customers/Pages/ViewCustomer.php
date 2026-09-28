<?php

namespace App\Filament\Pharmacist\Resources\Customers\Pages;

use App\Filament\Pharmacist\Resources\Customers\CustomerResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;

class ViewCustomer extends ViewRecord
{
    protected static string $resource = CustomerResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Customer Details')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('phone'),
                        TextEntry::make('email')->default('—'),
                        TextEntry::make('address')->default('—'),
                        TextEntry::make('date_of_birth')->date()->default('—'),
                    ]),

                Section::make('Purchase History')
                    ->schema([
                        RepeatableEntry::make('sales')
                            ->label('')
                            ->schema([
                                TextEntry::make('invoice_number')->label('Invoice'),
                                TextEntry::make('created_at')->label('Date')->date(),
                                TextEntry::make('grand_total')
                                    ->label('Amount')
                                    ->formatStateUsing(fn ($state) => format_currency($state)),
                            ])
                            ->columns(3),
                    ]),
            ]);
    }
}