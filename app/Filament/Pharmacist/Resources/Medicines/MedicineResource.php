<?php

namespace App\Filament\Pharmacist\Resources\Medicines;

use App\Filament\Pharmacist\Resources\Medicines\Pages\ListMedicines;
use App\Filament\Pharmacist\Resources\Medicines\Pages\ViewMedicine;
use App\Models\Medicine;
use BackedEnum;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MedicineResource extends Resource
{
    protected static ?string $model = Medicine::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBeaker;

    protected static ?string $navigationLabel = 'Medicine Search';

    protected static ?int $navigationSort = 2;

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
                Section::make('Basic Information')
                    ->columnSpanFull()
                    ->columns(4)
                    ->schema([
                        TextEntry::make('name')->label('Medicine Name'),
                        TextEntry::make('generic_name')->label('Generic Name'),
                        TextEntry::make('brand_name')->label('Brand'),
                 TextEntry::make('sku')->label('SKU'),
                        TextEntry::make('barcode'),
                        TextEntry::make('category.name')->label('Category')->badge(),
                        TextEntry::make('manufacturer.name')->label('Manufacturer'),
                        TextEntry::make('dosageForm.name')->label('Dosage Form'),
                        TextEntry::make('strength'),
                        TextEntry::make('pack_size')->label('Pack Size'),
                        IconEntry::make('prescription_required')->label('Prescription Required')->boolean(),
                        IconEntry::make('status')->label('Active')->boolean(),
                    ]),

                Section::make('Pricing & Stock')
                    ->columnSpanFull()
                    ->columns(4)
                    ->schema([
                        TextEntry::make('sale_price')
                            ->label('Sale Price')
                            ->formatStateUsing(fn ($state) => format_currency($state)),
                        TextEntry::make('minimum_stock_level')->label('Minimum Stock Level'),
                        TextEntry::make('reorder_level')->label('Reorder Level'),
                        TextEntry::make('total_stock')
                            ->label('Available Stock')
                            ->state(fn (Medicine $record) => $record->batches()->where('status', true)->sum('quantity'))
                            ->badge()
                            ->color(fn ($state) => $state > 0 ? 'success' : 'danger'),
                    ]),

                Section::make('Batches')
                    ->description('All stock batches for this medicine, sorted by expiry')
                    ->columnSpanFull()
                    ->schema([
                        RepeatableEntry::make('batches')
                            ->label('')
                            ->schema([
                                TextEntry::make('batch_number')->label('Batch #'),
                                TextEntry::make('quantity')->label('Quantity'),
                                TextEntry::make('expiry_date')->label('Expiry Date')->date('d M Y'),
                                TextEntry::make('purchase_price')
                                    ->label('Purchase Price')
                                    ->formatStateUsing(fn ($state) => format_currency($state)),
                                TextEntry::make('sale_price')
                                    ->label('Sale Price')
                                    ->formatStateUsing(fn ($state) => format_currency($state)),
                            ])
                            ->columns(5),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Medicine Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('generic_name')
                    ->searchable(),
               
                TextColumn::make('barcode')
                    ->searchable(),
                TextColumn::make('category.name')
                    ->badge(),
                
                TextColumn::make('stock')
                    ->label('Available Stock')
                    ->state(fn (Medicine $record) => $record->batches()->where('status', true)->sum('quantity'))
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'danger'),
                
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Category'),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMedicines::route('/'),
            'view' => ViewMedicine::route('/{record}'),
        ];
    }
}