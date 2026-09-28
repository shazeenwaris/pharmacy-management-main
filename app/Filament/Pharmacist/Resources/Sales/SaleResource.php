<?php

namespace App\Filament\Pharmacist\Resources\Sales;

use App\Filament\Pharmacist\Resources\Sales\Pages\ListSales;
use App\Filament\Pharmacist\Resources\Sales\Pages\ViewSale;
use App\Models\Sale;
use BackedEnum;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Sales History';

    protected static string|\UnitEnum|null $navigationGroup = 'Sales / POS';

    // Explicit slug so this never collides with the PosScreen page
    // (which owns the /pharmacist/sales URL for "New Sale")
    protected static ?string $slug = 'sales-history';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Sale Details')
                    ->description('Basic information about this sale')
                    ->icon(Heroicon::OutlinedDocumentText)
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        TextEntry::make('invoice_number')
                            ->label('Invoice #'),
                        TextEntry::make('customer.name')
                            ->label('Customer')
                            ->default('Walk-in'),
                        TextEntry::make('creator.name')
                            ->label('Cashier'),
                        TextEntry::make('created_at')
                            ->label('Date')
                            ->dateTime('d M Y, h:i A'),
                        TextEntry::make('payment_method')
                            ->badge(),
                        TextEntry::make('paid_amount')
                            ->label('Paid Amount')
                            ->formatStateUsing(fn ($state) => format_currency($state)),
                    ]),

                Section::make('Items')
                    ->description('Medicines included in this sale')
                    ->icon(Heroicon::OutlinedBeaker)
                    ->columnSpanFull()
                    ->schema([
                        RepeatableEntry::make('items')
                            ->label('')
                            ->schema([
                                TextEntry::make('medicine.name')
                                    ->label('Medicine'),
                                TextEntry::make('quantity'),
                                TextEntry::make('unit_price')
                                    ->label('Unit Price')
                                    ->formatStateUsing(fn ($state) => format_currency($state)),
                                TextEntry::make('total')
                                    ->formatStateUsing(fn ($state) => format_currency($state)),
                            ])
                            ->columns(4),
                    ]),

                Section::make('Totals')
                    ->description('Overall amounts for this sale')
                    ->icon(Heroicon::OutlinedCalculator)
                    ->columnSpanFull()
                    ->columns(4)
                    ->schema([
                        TextEntry::make('subtotal')
                            ->formatStateUsing(fn ($state) => format_currency($state)),
                        TextEntry::make('discount')
                            ->formatStateUsing(fn ($state) => format_currency($state)),
                        TextEntry::make('tax')
                            ->formatStateUsing(fn ($state) => format_currency($state)),
                        TextEntry::make('grand_total')
                            ->label('Grand Total')
                            ->formatStateUsing(fn ($state) => format_currency($state))
                            ->weight('bold'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')
                    ->label('Invoice #')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),
                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->default('Walk-in')
                    ->searchable(),
                TextColumn::make('creator.name')
                    ->label('Cashier'),
                TextColumn::make('items_count')
                    ->counts('items')
                    ->label('Items'),
                TextColumn::make('grand_total')
                    ->label('Total')
                    ->formatStateUsing(fn ($state) => format_currency($state))
                    ->sortable()
                    ->weight('semibold'),
                TextColumn::make('payment_method')
                    ->label('Payment')
                    ->badge(),
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('payment_method')
                    ->label('Payment Method')
                    ->options([
                        'cash' => 'Cash',
                        'card' => 'Card',
                        'bank' => 'Bank Transfer',
                        'other' => 'Other',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSales::route('/'),
            'view' => ViewSale::route('/{record}'),
        ];
    }
}