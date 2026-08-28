<?php

namespace App\Filament\Resources\Medicines;
use App\Filament\Resources\Medicines\Pages\ListMedicines;
use App\Filament\Resources\Medicines\Pages\CreateMedicine;
use App\Filament\Resources\Medicines\Pages\EditMedicine;
use App\Models\Medicine;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class MedicineResource extends Resource
{
    protected static ?string $model = Medicine::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBeaker;

    protected static string|\UnitEnum|null $navigationGroup = 'Medicines';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'All Medicines';

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('manage medicines') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('manage medicines') ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->can('manage medicines') ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->can('manage medicines') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->description('Core identification details for this medicine')
                    ->icon(Heroicon::OutlinedIdentification)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')
                            ->label('Medicine Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('generic_name')
                            ->label('Generic Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('medicine_type')
                            ->label('Medicine Type')
                            ->placeholder('e.g. Tablet, Syrup, Injection')
                            ->maxLength(255),
                        TextInput::make('brand_name')
                            ->label('Brand Name')
                            ->maxLength(255),
                        TextInput::make('sku')
                            ->label('Medicine Code / SKU')
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('barcode')
                            ->label('Barcode')
                            ->unique(ignoreRecord: true),
                        TextInput::make('strength')
                            ->label('Strength')
                            ->required()
                            ->placeholder('e.g. 500mg'),
                        TextInput::make('pack_size')
                            ->label('Pack Size')
                            ->placeholder('e.g. 10 tablets/strip'),
                    ])
                    ->columns(4),

                Section::make('Classification')
                    ->description('Category, manufacturer and supplier links')
                    ->icon(Heroicon::OutlinedTag)
                    ->columnSpanFull()
                    ->schema([
                        Select::make('category_id')
                            ->label('Category')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('manufacturer_id')
                            ->label('Manufacturer')
                            ->relationship('manufacturer', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('supplier_id')
                            ->label('Supplier')
                            ->relationship('supplier', 'supplier_name')
                            ->searchable()
                            ->preload(),
                        Select::make('dosage_form_id')
                            ->label('Dosage Form')
                            ->relationship('dosageForm', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('unit_id')
                            ->label('Unit')
                            ->relationship('unit', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])
                    ->columns(3),

                Section::make('Pricing')
                    ->description('Purchase and sale pricing for this medicine')
                    ->icon(Heroicon::OutlinedCalculator)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('purchase_price')
                            ->label('Purchase Price')
                            ->numeric()
                            ->prefix(fn () => currency_symbol())
                            ->required(),
                        TextInput::make('sale_price')
                            ->label('Sale Price')
                            ->numeric()
                            ->prefix(fn () => currency_symbol())
                            ->required(),
                        TextInput::make('wholesale_price')
                            ->label('Wholesale Price')
                            ->numeric()
                            ->prefix(fn () => currency_symbol()),
                        TextInput::make('minimum_sale_price')
                            ->label('Minimum Sale Price')
                            ->numeric()
                            ->prefix(fn () => currency_symbol()),
                        TextInput::make('tax_percent')
                            ->label('Tax Percent')
                            ->numeric()
                            ->suffix('%')
                            ->default(0),
                        TextInput::make('discount_percent')
                            ->label('Discount Percent')
                            ->numeric()
                            ->suffix('%')
                            ->default(0),
                    ])
                    ->columns(3),

                Section::make('Stock Settings')
                    ->description('Reorder thresholds and opening stock')
                    ->icon(Heroicon::OutlinedArchiveBox)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('minimum_stock_level')
                            ->label('Minimum Stock Level')
                            ->numeric()
                            ->default(0),
                        TextInput::make('maximum_stock_level')
                            ->label('Maximum Stock Level')
                            ->numeric(),
                        TextInput::make('reorder_level')
                            ->label('Reorder Level')
                            ->numeric()
                            ->default(0),
                        TextInput::make('opening_stock')
                            ->label('Opening Stock')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(4),

                Section::make('Other')
                    ->description('Prescription requirement, status and additional notes')
                    ->icon(Heroicon::OutlinedDocumentText)
                    ->columnSpanFull()
                    ->schema([
                        Toggle::make('prescription_required')
                            ->label('Prescription Required'),
                        Toggle::make('status')
                            ->label('Active')
                            ->default(true),
                        Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->columnSpanFull(),
                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
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
                TextColumn::make('medicine_type')
                    ->label('Type')
                    ->badge(),
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                TextColumn::make('category.name')
                    ->badge(),
                TextColumn::make('manufacturer.name'),
                TextColumn::make('sale_price')
                    ->formatStateUsing(fn ($state) => format_currency($state))
                    ->sortable(),
                IconColumn::make('prescription_required')
                    ->boolean()
                    ->label('Rx'),
                IconColumn::make('status')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->relationship('category', 'name'),
                SelectFilter::make('manufacturer_id')
                    ->relationship('manufacturer', 'name'),
                TernaryFilter::make('prescription_required'),
                TernaryFilter::make('status'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMedicines::route('/'),
            'create' => CreateMedicine::route('/create'),
            'edit' => EditMedicine::route('/{record}/edit'),
        ];
    }
    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'sku', 'generic_name', 'brand_name'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->name;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'SKU' => $record->sku,
        ];
    }

    public static function getWidgets(): array
{
    return [
        \App\Filament\Resources\Medicines\Widgets\MedicineBanner::class,
        \App\Filament\Resources\Medicines\Widgets\MedicineStats::class,
    ];
}
}