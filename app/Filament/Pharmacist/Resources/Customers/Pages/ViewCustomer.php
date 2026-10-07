<?php

namespace App\Filament\Pharmacist\Resources\Customers\Pages;

use App\Filament\Pharmacist\Resources\Customers\CustomerResource;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

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
                        TextEntry::make('phone')->placeholder('—'),
                        TextEntry::make('email')->placeholder('—'),
                        TextEntry::make('address')->placeholder('—'),
                        TextEntry::make('date_of_birth')
                            ->label('Date of Birth')
                            ->date('d M Y')
                            ->placeholder('—'),
                    ]),

                Section::make('Purchase History')
                    ->schema([
                        RepeatableEntry::make('sales')
                            ->label('')
                            ->placeholder('Koi purchase nahi hui')
                            ->schema([
                                TextEntry::make('invoice_number')->label('Invoice'),
                                TextEntry::make('created_at')
                                    ->label('Date')
                                    ->dateTime('d M Y, h:i A')
                                    ->placeholder('—'),
                                TextEntry::make('grand_total')
                                    ->label('Amount')
                                    ->formatStateUsing(fn ($state) => format_currency($state)),
                            ])
                            ->columns(3),
                    ]),
            ]);
    }
}