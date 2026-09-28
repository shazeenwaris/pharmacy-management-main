<?php

namespace App\Filament\Resources\Medicines;

use App\Filament\Resources\Medicines\Pages\ListMedicines;
use App\Filament\Resources\Medicines\Pages\CreateMedicine;
use App\Filament\Resources\Medicines\Pages\ViewMedicine;
use App\Filament\Resources\Medicines\Pages\EditMedicine;
use App\Filament\Resources\MedicineBatches\MedicineBatchResource;
use App\Models\Medicine;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class MedicineResource extends Resource
{
    protected static ?string $model = Medicine::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBeaker;

    protected static string|\UnitEnum|null $navigationGroup = 'Medicines';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'All Products';

    protected static ?string $modelLabel = 'Product';

    protected static ?string $pluralModelLabel = 'Products';

    // ==================================================================
    // Shared option lists (also used by the table)
    // ==================================================================

    public static function productTypes(): array
    {
        return [
            'medicine' => 'Medicine',
            'personal_care' => 'Personal Care',
            'baby_care' => 'Baby Care',
            'medical_device' => 'Medical Device',
            'other' => 'Other',
        ];
    }

    public static function packagingTypes(): array
    {
        return [
            'strip' => 'Strip (Box → Strips → Tablets)',
            'pack' => 'Pack (Packs → Pieces)',
            'single' => 'Single Unit',
        ];
    }

    protected static function isMedicine(Get $get): bool
    {
        return ($get('product_type') ?? 'medicine') === 'medicine';
    }

    // ==================================================================
    // Permissions
    // ==================================================================

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('manage medicines') ?? false;
    }

    public static function canView(Model $record): bool
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

    // ==================================================================
    // FORM (Create / Edit)
    // ==================================================================

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([

           
                // Section::make('Product Type & Packaging')
                //     ->description('What kind of product is this, and how is it packed?')
                //     ->icon(Heroicon::OutlinedSquares2x2)
                //     ->columnSpanFull()
                //     ->columns(3)
                //     ->schema([
                //         Select::make('product_type')
                //             ->label('Product Type')
                //             ->options(fn () => self::productTypes())
                //             ->default('medicine')
                //             ->required()
                //             ->native(false)
                //             ->live()
                //             ->afterStateUpdated(function ($state, Set $set) {
                //                 // Sensible defaults per type (user can still change them)
                //                 [$packaging, $expiry] = match ($state) {
                //                     'medicine' => ['strip', true],
                //                     'personal_care', 'baby_care' => ['pack', true],
                //                     default => ['single', false],
                //                 };

                //                 $set('packaging_type', $packaging);
                //                 $set('has_expiry', $expiry);
                //             }),

                //         Select::make('packaging_type')
                //             ->label('Packaging Type')
                //             ->options(fn () => self::packagingTypes())
                //             ->default('strip')
                //             ->required()
                //             ->native(false)
                //             ->live(),

                //         Toggle::make('has_expiry')
                //             ->label('Has Expiry Date')
                //             ->default(true)
                //             ->onColor('success')
                //             ->offColor('gray'),
                //     ]),

                Section::make('Product Type & Packaging')
    ->description('What kind of product is this, and how is it packed?')
    ->icon(Heroicon::OutlinedSquares2x2)
    ->columnSpanFull()
    ->columns(3)
    ->schema([
        Select::make('product_type')
            ->label('Product Type')
            ->options(fn () => self::productTypes())
            ->default('medicine')
            ->required()
            ->native(false)
            ->live()
            ->afterStateUpdated(function ($state, Set $set) {
                // Sensible defaults per type (user can still change them)
                [$packaging, $expiry] = match ($state) {
                    'medicine' => ['strip', true],
                    'personal_care', 'baby_care' => ['pack', true],
                    default => ['single', false],
                };

                $set('packaging_type', $packaging);
                $set('has_expiry', $expiry);
            }),

        Select::make('packaging_type')
            ->label('Packaging Type')
            ->options(fn () => self::packagingTypes())
            ->default('strip')
            ->required()
            ->native(false)
            ->live(),

        Group::make([
            Toggle::make('has_expiry')
                ->label('Has Expiry Date')
                ->default(true)
                ->inline(false)
                ->onColor('success')
                ->offColor('gray'),
        ])
            ->extraAttributes([
                'class' => 'flex flex-col justify-center h-full',
            ]),
    ]),

                Section::make('Basic Information')
                    ->description('Core identification details for this product')
                    ->icon(Heroicon::OutlinedIdentification)
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                Group::make([
                                    TextInput::make('name')
                                        ->label('Product Name')
                                        ->required()
                                        ->maxLength(255)
                                        ->unique(ignoreRecord: true)
                                        ->placeholder('e.g. Panadol, Baby Lotion')
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(function ($state, $livewire) {
                                            if (! $livewire instanceof \Filament\Resources\Pages\CreateRecord) {
                                                return;
                                            }

                                            if (blank($state)) {
                                                return;
                                            }

                                            $existing = Medicine::where('name', $state)->latest('id')->first();

                                            if (! $existing) {
                                                return;
                                            }

                                            $currentStock = $existing->batches()->sum('quantity');
                                            $addBatchUrl = MedicineBatchResource::getUrl('create', ['medicine_id' => $existing->id]);

                                            Notification::make()
                                                ->warning()
                                                ->title('This product already exists')
                                                ->body("\"{$existing->name}\" currently has {$currentStock} units in stock. To add new stock, use \"Batches & Expiry\" instead of adding it again here.")
                                                ->persistent()
                                                ->actions([
                                                    \Filament\Actions\Action::make('addBatch')
                                                        ->label('Add New Batch')
                                                        ->url($addBatchUrl)
                                                        ->button(),
                                                ])
                                                ->send();
                                        }),

                                    TextInput::make('generic_name')
                                        ->label('Generic Name')
                                        ->required(fn (Get $get) => self::isMedicine($get))
                                        ->visible(fn (Get $get) => self::isMedicine($get))
                                        ->maxLength(255)
                                        ->placeholder('e.g. Paracetamol'),

                                    TextInput::make('sku')
                                        ->label('SKU')
                                        ->required()
                                        ->unique(ignoreRecord: true)
                                        ->maxLength(100)
                                        ->default(fn () => 'MED-' . strtoupper(substr(uniqid(), -6))),

                                    TextInput::make('barcode')
                                        ->label('Barcode')
                                        ->unique(ignoreRecord: true)
                                        ->placeholder('Scan or enter barcode'),

                                    TextInput::make('strength')
                                        ->label('Strength')
                                        ->required(fn (Get $get) => self::isMedicine($get))
                                        ->visible(fn (Get $get) => self::isMedicine($get))
                                        ->placeholder('e.g. 500mg'),

                                    TextInput::make('pack_size')
                                        ->label('Pack Size')
                                        ->placeholder('e.g. 10 tablets/strip, 200ml bottle'),
                                ])
                                    ->columns(3)
                                    ->columnSpan(3),

                                Group::make([
                                    FileUpload::make('image')
                                        ->label('Product Photo')
                                        ->image()
                                        ->directory('medicines')
                                        ->imageEditor()
                                        ->avatar()
                                        ->alignCenter()
                                        ->extraAttributes(['class' => 'flex flex-col items-center justify-start h-full']),
                                ])
                                    ->columnSpan(1)
                                    ->extraAttributes(['class' => 'flex items-center justify-center']),
                            ]),
                    ]),

                Section::make('Classification')
                    ->description('Category, manufacturer, dosage form and unit')
                    ->icon(Heroicon::OutlinedTag)
                    ->columnSpanFull()
                    ->schema([

                        // ---------- 1. CATEGORY ----------
                        Select::make('category_id')
                            ->label('Category')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder('Select a category')
                            ->createOptionModalHeading('Add Category')
                            ->createOptionForm([
                                Section::make('Category Details')
                                    ->description('Add or update a product category')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Name')
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpan(1),
                                        Toggle::make('status')
                                            ->label('Active')
                                            ->default(true)
                                            ->onColor('success')
                                            ->offColor('danger')
                                            ->columnSpan(1),
                                        Textarea::make('description')
                                            ->label('Description')
                                            ->rows(4)
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                            ]),

                        // ---------- 2. MANUFACTURER ----------
                        Select::make('manufacturer_id')
                            ->label('Manufacturer')
                            ->relationship('manufacturer', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder('Select a manufacturer')
                            ->createOptionModalHeading('Add Manufacturer')
                            ->createOptionForm([
                                Section::make('Manufacturer Details')
                                    ->description('Basic identity and contact information')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Name')
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpan(1),
                                        Toggle::make('status')
                                            ->label('Active')
                                            ->default(true)
                                            ->onColor('success')
                                            ->offColor('danger')
                                            ->columnSpan(1),
                                        TextInput::make('company_code')
                                            ->label('Company Code')
                                            ->maxLength(100)
                                            ->columnSpan(1),
                                        TextInput::make('contact_person')
                                            ->label('Contact Person')
                                            ->maxLength(255)
                                            ->columnSpan(1),
                                        TextInput::make('phone')
                                            ->label('Phone')
                                            ->tel()
                                            ->maxLength(30)
                                            ->columnSpan(1),
                                        TextInput::make('email')
                                            ->label('Email')
                                            ->email()
                                            ->maxLength(255)
                                            ->columnSpan(1),
                                        TextInput::make('website')
                                            ->label('Website')
                                            ->url()
                                            ->maxLength(255)
                                            ->columnSpan(1),
                                        TextInput::make('tax_ntn_number')
                                            ->label('Tax / NTN Number')
                                            ->maxLength(100)
                                            ->columnSpan(1),
                                    ])
                                    ->columns(2),

                                Section::make('Address')
                                    ->description('Manufacturer location details')
                                    ->schema([
                                        Textarea::make('address')
                                            ->label('Address')
                                            ->rows(2)
                                            ->columnSpanFull(),
                                        TextInput::make('city')
                                            ->label('City')
                                            ->maxLength(100)
                                            ->columnSpan(1),
                                        TextInput::make('country')
                                            ->label('Country')
                                            ->default('Pakistan')
                                            ->maxLength(100)
                                            ->columnSpan(1),
                                    ])
                                    ->columns(2),
                            ]),

                        // ---------- 3. DOSAGE FORM (medicines only) ----------
                        Select::make('dosage_form_id')
                            ->label('Dosage Form')
                            ->relationship('dosageForm', 'name')
                            ->searchable()
                            ->preload()
                            ->required(fn (Get $get) => self::isMedicine($get))
                            ->visible(fn (Get $get) => self::isMedicine($get))
                            ->placeholder('Select dosage form')
                            ->createOptionModalHeading('Add Dosage Form')
                            ->createOptionForm([
                                Section::make('Dosage Form Details')
                                    ->description('e.g. Tablet, Syrup, Injection, Cream, Drops')
                                    ->icon(Heroicon::OutlinedBeaker)
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Name')
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpan(1),
                                        Toggle::make('status')
                                            ->label('Active')
                                            ->default(true)
                                            ->onColor('success')
                                            ->offColor('danger')
                                            ->columnSpan(1),
                                        Textarea::make('description')
                                            ->label('Description')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                            ]),

                        // ---------- 4. UNIT ----------
                        Select::make('unit_id')
                            ->label('Unit')
                            ->relationship('unit', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder('Select unit')
                            ->createOptionModalHeading('Add Unit')
                            ->createOptionForm([
                                Section::make('Unit Details')
                                    ->description('Define a measurement unit (e.g. Tablet, Bottle, Box, Piece)')
                                    ->icon(Heroicon::OutlinedScale)
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Name')
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpan(1),
                                        TextInput::make('short_code')
                                            ->label('Short Code')
                                            ->maxLength(20)
                                            ->columnSpan(1),
                                        Textarea::make('description')
                                            ->label('Description')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        Toggle::make('status')
                                            ->label('Active')
                                            ->default(true)
                                            ->onColor('success')
                                            ->offColor('danger')
                                            ->columnSpan(1),
                                    ])
                                    ->columns(2),
                            ]),

                    ])
                    ->columns(4),

                Section::make('Stock Overview')
                    ->description('Current stock is tracked via Batches & Expiry')
                    ->icon(Heroicon::OutlinedArchiveBox)
                    ->columnSpanFull()
                    ->visible(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\EditRecord)
                    ->schema([
                        Placeholder::make('current_stock_display')
                            ->label('Current Total Stock')
                            ->content(function ($record) {
                                if (! $record) {
                                    return '—';
                                }

                                $total = $record->batches()->sum('quantity');
                                $batchCount = $record->batches()->count();

                                return "{$total} units across {$batchCount} batch(es)";
                            }),

                        Placeholder::make('add_batch_link')
                            ->label('')
                            ->content(function ($record) {
                                if (! $record) {
                                    return '';
                                }

                                $url = MedicineBatchResource::getUrl('create', ['medicine_id' => $record->id]);

                                return new HtmlString(
                                    '<a href="' . $url . '" class="inline-flex items-center gap-1 px-3 py-2 rounded-lg bg-primary-600 text-white text-sm font-medium hover:bg-primary-500">+ Add New Batch</a>'
                                );
                            }),
                    ])
                    ->columns(2),

                Section::make('Reorder Thresholds')
                    ->description('Used for low-stock alerts across all batches of this product')
                    ->icon(Heroicon::OutlinedBellAlert)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('minimum_stock_level')
                            ->label('Minimum Stock Level')
                            ->helperText('Alert when stock falls below this')
                            ->numeric()
                            ->default(0),

                        TextInput::make('maximum_stock_level')
                            ->label('Maximum Stock Level')
                            ->helperText('Upper limit for stock holding')
                            ->numeric(),

                        TextInput::make('reorder_level')
                            ->label('Reorder Level')
                            ->helperText('Trigger point to reorder stock')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(3),

                Section::make('Other')
                    ->description('Status and additional notes')
                    ->icon(Heroicon::OutlinedDocumentText)
                    ->columnSpanFull()
                    ->schema([
                        Toggle::make('status')
                            ->label('Active')
                            ->default(true),
                        // Toggle::make('prescription_required')
                        //     ->label('Prescription Required')
                        //     ->visible(fn (Get $get) => self::isMedicine($get)),
                        // Textarea::make('description')
                        //     ->label('Description')
                        //     ->rows(3)
                        //     ->columnSpanFull(),
                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    // ==================================================================
    // INFOLIST (View page)
    // ==================================================================

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->icon(Heroicon::OutlinedIdentification)
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(4)->schema([
                            Grid::make(3)
                                ->schema([
                                    TextEntry::make('name')
                                        ->label('Product Name')
                                        ->weight('bold'),
                                    TextEntry::make('product_type')
                                        ->label('Product Type')
                                        ->badge()
                                        ->color('info')
                                        ->formatStateUsing(fn ($state) => self::productTypes()[$state] ?? $state),
                                    TextEntry::make('packaging_type')
                                        ->label('Packaging')
                                        ->badge()
                                        ->color('gray')
                                        ->formatStateUsing(fn ($state) => self::packagingTypes()[$state] ?? $state),
                                    TextEntry::make('generic_name')
                                        ->label('Generic Name')
                                        ->visible(fn ($record) => $record->product_type === 'medicine'),
                                    TextEntry::make('strength')
                                        ->label('Strength')
                                        ->visible(fn ($record) => $record->product_type === 'medicine'),
                                    TextEntry::make('sku')
                                        ->label('SKU')
                                        ->badge()
                                        ->color('gray'),
                                    TextEntry::make('barcode')
                                        ->label('Barcode')
                                        ->placeholder('—'),
                                    TextEntry::make('pack_size')
                                        ->label('Pack Size')
                                        ->placeholder('—'),
                                    IconEntry::make('has_expiry')
                                        ->label('Has Expiry')
                                        ->boolean(),
                                ])
                                ->columnSpan(3),

                            ImageEntry::make('image')
                                ->hiddenLabel()
                                ->circular()
                                ->imageSize(110)
                                ->defaultImageUrl(fn () => 'https://ui-avatars.com/api/?background=0D9488&color=fff&name=Rx')
                                ->columnSpan(1),
                        ]),
                    ]),

                Section::make('Classification')
                    ->icon(Heroicon::OutlinedTag)
                    ->columnSpanFull()
                    ->columns(4)
                    ->schema([
                        TextEntry::make('category.name')
                            ->label('Category')
                            ->badge()
                            ->color('info'),
                        TextEntry::make('manufacturer.name')
                            ->label('Manufacturer'),
                        TextEntry::make('dosageForm.name')
                            ->label('Dosage Form')
                            ->badge()
                            ->color('gray')
                            ->visible(fn ($record) => $record->product_type === 'medicine'),
                        TextEntry::make('unit.name')
                            ->label('Unit'),
                    ]),

                Section::make('Stock')
                    ->icon(Heroicon::OutlinedArchiveBox)
                    ->columnSpanFull()
                    ->columns(4)
                    ->schema([
                        TextEntry::make('current_stock')
                            ->label('Current Stock')
                            ->state(fn ($record) => (int) $record->batches()->sum('quantity'))
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
                        TextEntry::make('minimum_stock_level')
                            ->label('Minimum Level'),
                        TextEntry::make('maximum_stock_level')
                            ->label('Maximum Level')
                            ->placeholder('—'),
                        TextEntry::make('reorder_level')
                            ->label('Reorder Level'),

                        RepeatableEntry::make('batches')
                            ->label('Batches')
                            ->columnSpanFull()
                            ->columns(4)
                            ->schema([
                                TextEntry::make('batch_number')
                                    ->label('Batch #'),
                                TextEntry::make('expiry_date')
                                    ->label('Expiry')
                                    ->date()
                                    ->placeholder('No expiry'),
                                TextEntry::make('quantity')
                                    ->label('Quantity')
                                    ->suffix(' units'),
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
                            ]),
                    ]),

                Section::make('Other')
                    ->icon(Heroicon::OutlinedDocumentText)
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        IconEntry::make('status')
                            ->label('Active')
                            ->boolean(),
                        TextEntry::make('created_at')
                            ->label('Added')
                            ->dateTime('d M Y'),
                        TextEntry::make('description')
                            ->placeholder('—')
                            ->columnSpanFull(),
                        TextEntry::make('notes')
                            ->placeholder('—')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    // ==================================================================
    // TABLE / PAGES / SEARCH / WIDGETS
    // ==================================================================

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
            // 'view' => ViewMedicine::route('/{record}'),
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