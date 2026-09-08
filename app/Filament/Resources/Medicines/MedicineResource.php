<?php

namespace App\Filament\Resources\Medicines;
use App\Filament\Resources\Medicines\Pages\ListMedicines;
use App\Filament\Resources\Medicines\Pages\CreateMedicine;
use App\Filament\Resources\Medicines\Pages\EditMedicine;
use App\Models\Medicine;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
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
                Section::make('Medicine Photo')
                    ->description('Upload a photo for this medicine')
                    ->icon(Heroicon::OutlinedPhoto)
                    ->columnSpanFull()
                    ->schema([
                        FileUpload::make('image')
                            ->label('Medicine Photo')
                            ->image()
                            ->directory('medicines')
                            ->imageEditor()
                            ->avatar()
                            ->columnSpanFull(),
                    ]),

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
                    ->description('Category, manufacturer, dosage form and unit')
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
                    ->columns(4),

                

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
                        
                    ])
                    ->columns(3),

                Section::make('Other')
                    ->description('Prescription requirement, status and additional notes')
                    ->icon(Heroicon::OutlinedDocumentText)
                    ->columnSpanFull()
                    ->schema([
                        // Toggle::make('prescription_required')
                        //     ->label('Prescription Required'),
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
        return \App\Filament\Resources\Medicines\Tables\MedicinesTable::configure($table)
            ->recordUrl(null);
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