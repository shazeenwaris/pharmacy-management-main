<?php


namespace App\Filament\Resources\Medicines\Schemas;


use App\Models\Category;
use App\Models\DosageForm;
use App\Models\Manufacturer;
use App\Models\Supplier;
use App\Models\Unit;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;


class MedicineForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->description('Core identification details for this medicine')
                    ->icon(Heroicon::OutlinedIdentification)
                    ->columnSpanFull()
                    ->columns(4)
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
                            ->maxLength(255),


                        TextInput::make('brand_name')
                            ->label('Brand Name')
                            ->maxLength(255),


                        TextInput::make('sku')
                            ->label('Medicine Code / SKU')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),


                        TextInput::make('barcode')
                            ->label('Barcode')
                            ->maxLength(255),


                        TextInput::make('strength')
                            ->label('Strength')
                            ->placeholder('e.g. 500mg')
                            ->maxLength(255),


                        TextInput::make('pack_size')
                            ->label('Pack Size')
                            ->maxLength(255),
                    ]),


                Section::make('Classification')
                    ->description('Category, manufacturer and supplier links')
                    ->icon(Heroicon::OutlinedTag)
                    ->columnSpanFull()
                    ->columns(4)
                    ->schema([
                        Select::make('category_id')
                            ->label('Category')
                            ->relationship('category', 'name')
                            ->options(fn () => Category::query()->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(),


                        Select::make('manufacturer_id')
                            ->label('Manufacturer')
                            ->relationship('manufacturer', 'name')
                            ->options(fn () => Manufacturer::query()->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(),


                        Select::make('supplier_id')
                            ->label('Supplier')
                            ->relationship('supplier', 'supplier_name')
                            ->options(fn () => Supplier::query()->pluck('supplier_name', 'id'))
                            ->searchable()
                            ->preload(),


                        Select::make('dosage_form_id')
                            ->label('Dosage Form')
                            ->relationship('dosageForm', 'name')
                            ->options(fn () => DosageForm::query()->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(),


                        Select::make('unit_id')
                            ->label('Unit')
                            ->relationship('unit', 'name')
                            ->options(fn () => Unit::query()->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(),
                    ]),


                Section::make('Pricing')
                    ->description('Purchase and sale pricing for this medicine')
                    ->icon(Heroicon::OutlinedBanknotes)
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        TextInput::make('purchase_price')
                            ->label('Purchase Price')
                            ->numeric()
                            ->required()
                            ->prefix(fn () => currency_symbol()),


                        TextInput::make('sale_price')
                            ->label('Sale Price')
                            ->numeric()
                            ->required()
                            ->prefix(fn () => currency_symbol()),


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
                            ->default(0)
                            ->suffix('%'),


                        TextInput::make('discount_percent')
                            ->label('Discount Percent')
                            ->numeric()
                            ->default(0)
                            ->suffix('%'),
                    ]),


                Section::make('Stock Settings')
                    ->description('Reorder thresholds and opening stock')
                    ->icon(Heroicon::OutlinedCube)
                    ->columnSpanFull()
                    ->columns(4)
                    ->schema([
                        TextInput::make('minimum_stock_level')
                            ->label('Minimum Stock Level')
                            ->numeric()
                            ->default(0)
                            ->required(),


                        TextInput::make('maximum_stock_level')
                            ->label('Maximum Stock Level')
                            ->numeric(),


                        TextInput::make('reorder_level')
                            ->label('Reorder Level')
                            ->numeric(),


                        // TextInput::make('opening_stock')
                        //     ->label('Opening Stock')
                        //     ->numeric()
                        //     ->default(0),
                    ]),


                Section::make('Additional Details')
                    ->description('Prescription requirement, description and status')
                    ->icon(Heroicon::OutlinedDocumentText)
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Toggle::make('prescription_required')
                            ->label('Prescription Required')
                            ->onColor('danger')
                            ->offColor('success'),


                        Toggle::make('status')
                            ->label('Active')
                            ->default(true)
                            ->onColor('success')
                            ->offColor('danger'),


                        Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),


                        FileUpload::make('image')
                            ->label('Medicine Image')
                            ->image()
                            ->directory('medicines')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
