<?php

namespace App\Filament\Pharmacist\Resources\Medicines;

use App\Filament\Pharmacist\Resources\Medicines\Pages\ListMedicines;
use App\Filament\Pharmacist\Resources\Medicines\Pages\ViewMedicine;
use App\Filament\Resources\Medicines\MedicineResource as AdminMedicineResource;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MedicineResource extends Resource
{
    protected static ?string $model = Medicine::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBeaker;

    protected static ?string $navigationLabel = 'Medicine Search';

    protected static ?int $navigationSort = 2;

    // Pharmacist panel is read-only — searching and viewing stock only.
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
                        TextEntry::make('product_type')
                            ->label('Type')
                            ->badge()
                            ->color('info')
                            ->formatStateUsing(fn ($state) => AdminMedicineResource::productTypes()[$state] ?? $state),
                        TextEntry::make('generic_name')
                            ->label('Generic Name')
                            ->visible(fn ($record) => $record->product_type === 'medicine')
                            ->placeholder('—'),
                        TextEntry::make('sku')->label('SKU'),
                        TextEntry::make('barcode')->placeholder('—'),
                        TextEntry::make('category.name')->label('Category')->badge(),
                        TextEntry::make('manufacturer.name')->label('Manufacturer'),
                        TextEntry::make('dosageForm.name')
                            ->label('Dosage Form')
                            ->visible(fn ($record) => $record->product_type === 'medicine')
                            ->placeholder('—'),
                        TextEntry::make('strength')
                            ->visible(fn ($record) => $record->product_type === 'medicine')
                            ->placeholder('—'),
                        TextEntry::make('unit.name')->label('Unit')->placeholder('—'),
                        IconEntry::make('prescription_required')->label('Prescription Required')->boolean(),
                        IconEntry::make('has_expiry')->label('Has Expiry')->boolean(),
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
                            ->color(fn ($state, $record) => match (true) {
                                (int) $state <= 0 => 'danger',
                                (int) $state <= $record->minimum_stock_level => 'warning',
                                default => 'success',
                            })
                            ->formatStateUsing(fn ($state, $record) => match (true) {
                                (int) $state <= 0 => 'Out of Stock',
                                (int) $state <= $record->minimum_stock_level => (int) $state . ' (Low)',
                                default => (int) $state . ' units',
                            }),
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
                                TextEntry::make('expiry_date')
                                    ->label('Expiry Date')
                                    ->date('d M Y')
                                    ->placeholder('No expiry'),
                                TextEntry::make('sale_price')
                                    ->label('Sale Price')
                                    ->formatStateUsing(fn ($state) => format_currency($state)),
                                TextEntry::make('expiry_status')
                                    ->label('Status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'expired' => 'danger',
                                        'expiring_soon' => 'warning',
                                        'no_expiry' => 'gray',
                                        default => 'success',
                                    })
                                    ->formatStateUsing(fn (string $state): string => match ($state) {
                                        'expired' => 'Expired',
                                        'expiring_soon' => 'Expiring Soon',
                                        'no_expiry' => 'No Expiry',
                                        default => 'Active',
                                    }),
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
                    ->sortable()
                    ->description(fn ($record) => $record->generic_name),

                TextColumn::make('product_type')
                    ->label('Type')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn ($state) => AdminMedicineResource::productTypes()[$state] ?? $state),

                TextColumn::make('packaging_type')
                    ->label('Packaging')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn ($state) => AdminMedicineResource::packagingTypes()[$state] ?? $state)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('barcode')
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('category.name')
                    ->label('Category')
                    ->badge(),

                TextColumn::make('sale_price')
                    ->label('Sale Price')
                    ->formatStateUsing(fn ($state) => format_currency($state))
                    ->sortable(),

                TextColumn::make('stock')
                    ->label('Available Stock')
                    ->state(fn (Medicine $record) => $record->batches()->where('status', true)->sum('quantity'))
                    ->badge()
                    ->color(fn ($state, $record) => match (true) {
                        (int) $state <= 0 => 'danger',
                        (int) $state <= $record->minimum_stock_level => 'warning',
                        default => 'success',
                    })
                    ->formatStateUsing(fn ($state, $record) => match (true) {
                        (int) $state <= 0 => 'Out of Stock',
                        (int) $state <= $record->minimum_stock_level => (int) $state . ' (Low)',
                        default => (int) $state . ' units',
                    }),
            ])
            ->filters([
                SelectFilter::make('product_type')
                    ->label('Type')
                    ->options(fn () => AdminMedicineResource::productTypes()),

                SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Category'),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->recordActionsColumnLabel('Actions');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMedicines::route('/'),
            'view' => ViewMedicine::route('/{record}'),
        ];
    }
}