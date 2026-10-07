<?php

namespace App\Filament\Resources\Medicines;

use App\Filament\Resources\MedicineBatches\MedicineBatchResource;
use App\Filament\Resources\Medicines\Pages\CreateMedicine;
use App\Filament\Resources\Medicines\Pages\EditMedicine;
use App\Filament\Resources\Medicines\Pages\ListMedicines;
use App\Models\Medicine;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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

    /**
     * Labels used in the Packaging & Pricing fields, per packaging type.
     * [strips label, tablets label, price label (per ...)]
     */
    protected static function packagingLabels(?string $type): array
    {
        return match ($type) {
            'pack' => ['Packs per Box', 'Pieces per Pack', 'Pack'],
            'single' => ['Units per Box', 'Units per Pack', 'Unit'],
            default => ['Strips per Box', 'Tablets per Box', 'Box'],
        };
    }

    /** Strips per box: 1 to 100 */
    protected static function countOptions(): array
    {
        return array_combine(range(1, 100), range(1, 100));
    }

    /** Tablets (or pieces) per box: 1 to 500 */
    protected static function quantityOptions(): array
    {
        return array_combine(range(1, 500), range(1, 500));
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
                \Filament\Schemas\Components\View::make('medicine-photo-style')
                    ->view('filament.medicine-photo-style'),

                // ---------------------------------------------------------
                // 1. Product Type & Packaging (type + packaging + pricing)
                // ---------------------------------------------------------
                Section::make('Product Type & Packaging')
                    ->description('What kind of product is this, and how is it packed?')
                    ->icon(Heroicon::OutlinedSquares2x2)
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Select::make('product_type')
                                    ->label('Product Type')
                                    ->options(fn () => self::productTypes())
                                    ->default('medicine')
                                    ->required()
                                    ->native(false)
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set) {
                                        [$packaging, $expiry] = match ($state) {
                                            'medicine' => ['strip', true],
                                            'personal_care', 'baby_care' => ['pack', true],
                                            default => ['single', false],
                                        };

                                        $set('packaging_type', $packaging);
                                        $set('has_expiry', $expiry);

                                        if ($packaging !== 'strip') {
                                            $set('strips_per_box', 1);
                                        }

                                        if ($packaging === 'single') {
                                            $set('units_per_strip', 1);
                                        }
                                    }),

                                Select::make('packaging_type')
                                    ->label('Packaging Unit')
                                    ->options(fn () => self::packagingTypes())
                                    ->default('strip')
                                    ->required()
                                    ->native(false)
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set) {
                                        if ($state !== 'strip') {
                                            $set('strips_per_box', 1);
                                        }

                                        if ($state === 'single') {
                                            $set('units_per_strip', 1);
                                        }
                                    }),

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

                        Grid::make(3)
                            ->schema([
                                // Strips per Box (only for Strip packaging).
                                // Batches use this to show the per-strip price automatically.
                                Select::make('strips_per_box')
                                    ->label('Strips per Box')
                                    ->options(fn () => self::countOptions())
                                    ->default(1)
                                    ->required(fn (Get $get) => ($get('packaging_type') ?? 'strip') === 'strip')
                                    ->searchable()
                                    ->native(false)
                                    ->live()
                                    ->visible(fn (Get $get) => ($get('packaging_type') ?? 'strip') === 'strip')
                                    ->dehydratedWhenHidden()
                                    ->dehydrateStateUsing(fn ($state) => max(1, (int) ($state ?: 1))),

                                // Tablets per Box (total tablets in one box).
                                Select::make('units_per_strip')
                                    ->label(fn (Get $get) => self::packagingLabels($get('packaging_type'))[1])
                                    ->options(fn () => self::quantityOptions())
                                    ->default(1)
                                    ->required()
                                    ->searchable()
                                    ->native(false)
                                    ->live()
                                    ->disabled(fn (Get $get) => $get('packaging_type') === 'single')
                                    ->dehydrated()
                                    ->rule(function (Get $get) {
                                        return function (string $attribute, $value, \Closure $fail) use ($get) {
                                            if (($get('packaging_type') ?? 'strip') !== 'strip') {
                                                return;
                                            }

                                            $strips = max(1, (int) ($get('strips_per_box') ?? 1));
                                            $units = (int) $value;

                                            if ($strips > 1 && ($units < $strips || $units % $strips !== 0)) {
                                                $fail("Tablets per Box must divide evenly by Strips per Box (e.g. {$strips} strips x 10 = " . ($strips * 10) . ' tablets).');
                                            }
                                        };
                                    })
                                    ->hint(function (Get $get) {
                                        $type = $get('packaging_type') ?? 'strip';
                                        $units = max(1, (int) ($get('units_per_strip') ?? 1));
                                        $strips = max(1, (int) ($get('strips_per_box') ?? 1));

                                        if ($type === 'single') {
                                            return 'Sold individually';
                                        }

                                        if ($type === 'pack') {
                                            return "1 Pack = {$units} Piece" . ($units === 1 ? '' : 's');
                                        }

                                        $text = "1 Box = {$units} Tablet" . ($units === 1 ? '' : 's');

                                        if ($strips > 1) {
                                            $perStrip = round($units / $strips, 2);
                                            $text = "1 Box = {$strips} Strips = {$units} Tablets ({$perStrip} per strip)";
                                        }

                                        return $text;
                                    }),

                                TextInput::make('sale_price')
                                    ->label(fn (Get $get) => 'Sale Price (per ' . self::packagingLabels($get('packaging_type'))[2] . ')')
                                    ->numeric()
                                    ->minValue(0)
                                    ->prefix(fn () => currency_symbol())
                                    ->required(),
                            ]),
                    ]),

                // ---------------------------------------------------------
                // 2. Basic Information
                // ---------------------------------------------------------
                Section::make('Basic Information')
                    ->description('Core identification details for this product')
                    ->icon(Heroicon::OutlinedIdentification)
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(3)
                            ->schema([
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

                                FileUpload::make('image')
                                    ->label('Product Photo')
                                    ->image()
                                    ->directory('medicines')
                                    ->imageEditor()
                                    ->extraAttributes(['id' => 'compact-photo-upload']),
                            ]),
                    ]),

                // ---------------------------------------------------------
                // 3. Classification
                // ---------------------------------------------------------
                Section::make('Classification')
                    ->description('Category, manufacturer, dosage form and unit')
                    ->icon(Heroicon::OutlinedTag)
                    ->columnSpanFull()
                    ->schema([
                        Grid::make()
                            ->columns(fn (Get $get) => self::isMedicine($get) ? 4 : 3)
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
                                    ->dehydrated(fn (Get $get) => self::isMedicine($get))
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
                            ]),
                    ]),

                // ---------------------------------------------------------
                // 4. Stock Overview (Edit page only)
                // ---------------------------------------------------------
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

                // ---------------------------------------------------------
                // 5. Reorder Thresholds
                // ---------------------------------------------------------
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

                // ---------------------------------------------------------
                // 6. Other
                // ---------------------------------------------------------
                Section::make('Other')
                    ->description('Status and additional notes')
                    ->icon(Heroicon::OutlinedDocumentText)
                    ->columnSpanFull()
                    ->schema([
                        Toggle::make('status')
                            ->label('Active')
                            ->default(true),
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

                Section::make('Packaging & Pricing')
                    ->icon(Heroicon::OutlinedCube)
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        TextEntry::make('strips_per_box')
                            ->label('Strips per Box')
                            ->visible(fn ($record) => $record->packaging_type === 'strip'),
                        TextEntry::make('units_per_strip')
                            ->label(fn ($record) => self::packagingLabels($record->packaging_type)[1]),
                        TextEntry::make('sale_price')
                            ->label('Sale Price')
                            ->formatStateUsing(fn ($state) => format_currency($state)),
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
            'edit' => EditMedicine::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'sku', 'generic_name', 'barcode'];
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