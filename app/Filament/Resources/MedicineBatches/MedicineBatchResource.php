<?php

namespace App\Filament\Resources\MedicineBatches;

use App\Filament\Resources\MedicineBatches\Pages\CreateMedicineBatch;
use App\Filament\Resources\MedicineBatches\Pages\EditMedicineBatch;
use App\Filament\Resources\MedicineBatches\Pages\ListMedicineBatches;
use App\Filament\Resources\Medicines\MedicineResource;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\PurchaseItem;
use BackedEnum;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\Rules\Unique;
use UnitEnum;

class MedicineBatchResource extends Resource
{
    protected static ?string $model = MedicineBatch::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string|UnitEnum|null $navigationGroup = 'Medicines';

    protected static ?int $navigationSort = 6;

    protected static ?string $navigationLabel = 'Batches & Expiry';

    // ==================================================================
    // Permissions
    // ==================================================================

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasAnyPermission(['track batches', 'track expiry']) ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('track batches') ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->can('track batches') ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->can('track batches') ?? false;
    }

    // ==================================================================
    // Product helpers
    //
    // A batch never stores its own packaging structure or price — these
    // always come from the Product.
    //
    // NOTE: `loose_strips_received` column is reused for both types:
    //   - strip type => loose STRIPS
    //   - pack  type => loose PIECES
    // ==================================================================

    protected static function product(Get $get): ?Medicine
    {
        $id = $get('medicine_id');

        return blank($id) ? null : Medicine::find($id);
    }

    /** strip | pack | single */
    protected static function packagingType(Get $get): string
    {
        return self::product($get)?->packaging_type ?? 'strip';
    }

    protected static function hasExpiry(Get $get): bool
    {
        return (bool) (self::product($get)?->has_expiry ?? true);
    }

    protected static function priceUnitLabel(Get $get): string
    {
        return match (self::packagingType($get)) {
            'pack' => 'Pack',
            'single' => 'Unit',
            default => 'Box',
        };
    }

    protected static function fillFromProduct(Medicine $product, Get $get, Set $set): void
    {
        $type = $product->packaging_type ?? 'strip';

        $set('strips_per_box', max(1, (int) ($product->strips_per_box ?: 1)));
        $set('units_per_strip', max(1, (int) ($product->units_per_strip ?: 1)));
        $set('sale_price', $product->sale_price ?: null);

        if ($type === 'single') {
            $set('boxes_received', (int) ($get('quantity') ?? 0));
            $set('loose_strips_received', 0);
        } else {
            self::calculateQuantity($get, $set, $type);
        }
    }

    /**
     * The batch number the admin must enter for the NEXT batch of this product:
     * number of existing batches + 1 (skips a number that is already taken,
     * e.g. after a batch was deleted).
     */
    protected static function nextBatchNumber(int $medicineId): int
    {
        $number = MedicineBatch::where('medicine_id', $medicineId)->count() + 1;

        while (MedicineBatch::where('medicine_id', $medicineId)
            ->where('batch_number', (string) $number)
            ->exists()) {
            $number++;
        }

        return $number;
    }

    /**
     * Popup shown when a Product is selected.
     *
     * It only INFORMS the admin about the batches this product already has
     * (how many, batch numbers, quantity, expiry). Nothing is added or changed
     * automatically — the admin adds the new batch manually.
     */
    protected static function notifyCurrentStock(Medicine $product): void
    {
        $batches = MedicineBatch::where('medicine_id', $product->id)
            ->orderBy('expiry_date')
            ->get();

        $count = $batches->count();

        // First batch for this product
        if ($count === 0) {
            Notification::make()
                ->info()
                ->title("{$product->name} — no existing batches")
                ->body('This product has no batch yet. The batch you add now will be its first batch — enter 1 as the batch number.')
                ->duration(8000)
                ->send();

            return;
        }

        $totalStock = (int) $batches->sum('quantity');
        $nextNumber = self::nextBatchNumber($product->id);

        // Show up to 5 batches in the popup, the rest as "+ N more"
        $lines = $batches->take(5)->map(function ($batch) {
            $expiry = $batch->expiry_date
                ? Carbon::parse($batch->expiry_date)->format('d M Y')
                : 'No expiry';

            return 'Batch <strong>' . e($batch->batch_number) . '</strong> — '
                . number_format((int) $batch->quantity) . ' units (Exp: ' . e($expiry) . ')';
        })->implode('<br>');

        if ($count > 5) {
            $lines .= '<br>+ ' . ($count - 5) . ' more batch(es)';
        }

        $body = "This product already has <strong>{$count}</strong> batch" . ($count === 1 ? '' : 'es')
            . " ({$totalStock} units in total).<br><br>"
            . $lines
            . "<br><br>The batch you add now will be batch <strong>#{$nextNumber}</strong>. Enter <strong>{$nextNumber}</strong> as the batch number and fill in the other details manually.";

        Notification::make()
            ->warning()
            ->title("{$product->name} — existing batches")
            ->body(new HtmlString($body))
            ->duration(12000)
            ->send();
    }

    /**
     * strip: boxes × units_per_strip + loose strips × (units_per_strip / strips_per_box)
     * pack : packs × units_per_strip + loose pieces (1 piece = 1 unit)
     * single: quantity is entered directly — nothing to calculate.
     */
    protected static function calculateQuantity(Get $get, Set $set, ?string $type = null): void
    {
        $type ??= self::packagingType($get);

        if ($type === 'single') {
            return;
        }

        $boxes = max((int) ($get('boxes_received') ?? 0), 0);
        $units = max((int) ($get('units_per_strip') ?? 1), 1);
        $loose = max((int) ($get('loose_strips_received') ?? 0), 0);

        $fromBoxes = $boxes * $units;
        $fromLoose = 0;

        if ($type === 'strip') {
            $strips = max((int) ($get('strips_per_box') ?? 1), 1);
            $fromLoose = (int) round($loose * ($units / $strips));
        } elseif ($type === 'pack') {
            $fromLoose = $loose; // loose pieces = units directly
        }

        $set('quantity', $fromBoxes + $fromLoose);
    }

    protected static function warn(string $title, string $body): void
    {
        Notification::make()
            ->danger()
            ->title($title)
            ->body($body)
            ->send();
    }

    // ==================================================================
    // FORM
    // ==================================================================

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Batch Details')
                    ->description('Which product this batch belongs to, and its identity')
                    ->icon(Heroicon::OutlinedCalendarDays)
                    ->columnSpanFull()
                    ->columns(4)
                    ->schema([
                        self::purchaseItemSelect(),
                        self::productSelect(),
                        self::batchNumberInput(),
                        self::manufacturingDatePicker(),
                        self::expiryDatePicker(),
                    ]),

                Section::make('Packaging & Quantity')
                    ->description('Packaging structure comes from the Product — enter full Boxes/Packs and/or loose Strips/Pieces')
                    ->icon(Heroicon::OutlinedArchiveBox)
                    ->columnSpanFull()
                    ->columns(5)
                    ->schema([
                        self::boxesReceivedInput(),
                        self::stripsPerBoxInput(),
                        self::looseStripsReceivedInput(),
                        self::unitsPerStripInput(),
                        self::quantityInput(),

                        Placeholder::make('calculation_preview')
                            ->hiddenLabel()
                            ->content(fn (Get $get) => self::calculationCard($get))
                            ->columnSpanFull(),
                    ]),

                Section::make('Pricing')
                    ->description('Sale price is copied from the Product — per-strip / per-piece prices are calculated automatically')
                    ->icon(Heroicon::OutlinedBanknotes)
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        self::salePriceInput(),

                        Placeholder::make('price_breakdown')
                            ->hiddenLabel()
                            ->content(fn (Get $get) => self::priceBreakdownCard($get))
                            ->columnSpanFull(),
                    ]),

                Section::make('Status & Notes')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->columnSpanFull()
                    ->schema([
                        Toggle::make('status')
                            ->label('Active')
                            ->default(true)
                            ->onColor('success')
                            ->offColor('danger'),

                        Textarea::make('notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    // ------------------------------------------------------------------
    // Form fields (Batch Details)
    // ------------------------------------------------------------------

    protected static function purchaseItemSelect(): Select
    {
        return Select::make('purchase_item_id')
            ->label('Load from Purchase (optional)')
            ->options(function () {
                return PurchaseItem::with(['medicine', 'purchase'])
                    ->whereDoesntHave('medicineBatch')
                    ->get()
                    ->mapWithKeys(fn ($item) => [
                        $item->id => ($item->medicine?->name ?? 'Unknown')
                            . ' — Invoice #' . ($item->purchase?->invoice_number ?? '-')
                            . ' (' . $item->quantity . ' units)',
                    ]);
            })
            ->searchable()
            ->live()
            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                if (blank($state)) {
                    return;
                }

                $item = PurchaseItem::find($state);
                $product = $item ? Medicine::find($item->medicine_id) : null;

                if (! $item || ! $product) {
                    return;
                }

                $type = $product->packaging_type ?? 'strip';

                $set('medicine_id', $product->id);
                $set('strips_per_box', max(1, (int) ($product->strips_per_box ?: 1)));
                $set('units_per_strip', max(1, (int) ($product->units_per_strip ?: 1)));
                $set('sale_price', $product->sale_price ?: null);

                if ($type === 'single') {
                    $set('boxes_received', (int) $item->quantity);
                    $set('quantity', (int) $item->quantity);
                } else {
                    $set('boxes_received', (int) $item->boxes_received);
                    self::calculateQuantity($get, $set, $type);
                }

                if ($item->expiry_date) {
                    $set('expiry_date', Carbon::parse($item->expiry_date)->format('Y-m-d'));
                }

                Notification::make()
                    ->success()
                    ->title('Details loaded from Purchase')
                    ->body('Just enter the Batch Number, then save.')
                    ->send();

                // Let the admin see the batches this product already has
                self::notifyCurrentStock($product);
            })
            ->columnSpan(4);
    }

    protected static function productSelect(): Select
    {
        return Select::make('medicine_id')
            ->label('Product')
            ->relationship('medicine', 'name')
            ->searchable()
            ->preload()
            ->required()
            ->default(fn () => request()->query('medicine_id'))
            ->live()
            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                if (blank($state)) {
                    return;
                }

                $product = Medicine::find($state);

                if (! $product) {
                    return;
                }

                self::fillFromProduct($product, $get, $set);
                self::notifyCurrentStock($product);
            })
            ->suffixAction(
                Action::make('syncFromProduct')
                    ->icon('heroicon-m-arrow-path')
                    ->label('Sync from Product')
                    ->tooltip('Re-pull packaging and Sale Price from this Product, and recalculate quantity')
                    ->visible(fn ($livewire, Get $get) => $livewire instanceof EditRecord && filled($get('medicine_id')))
                    ->requiresConfirmation()
                    ->modalHeading('Sync this batch with its Product?')
                    ->modalDescription('This overwrites packaging values and Sale Price with the Product\'s current values, and recalculates Total Quantity.')
                    ->action(function (Get $get, Set $set) {
                        $product = Medicine::find($get('medicine_id'));

                        if (! $product) {
                            return;
                        }

                        self::fillFromProduct($product, $get, $set);

                        Notification::make()
                            ->success()
                            ->title('Synced with Product')
                            ->body("Packaging and price now match \"{$product->name}\".")
                            ->send();
                    })
            )
            ->columnSpan(2);
    }

    protected static function batchNumberInput(): TextInput
    {
        return TextInput::make('batch_number')
            ->required()
            ->maxLength(255)
            ->trim()
            // The same product cannot have two batches with the same batch number
            // (a different product may still use the same number).
            ->unique(
                ignoreRecord: true,
                modifyRuleUsing: fn (Unique $rule, Get $get) => $rule->where('medicine_id', $get('medicine_id')),
            )
            ->validationMessages([
                'unique' => 'This batch number already exists for this product. Please enter a new batch number.',
            ])
            // On the Create page the batch number must be the next number in sequence
            // (1st batch = 1, 2nd batch = 2, ...). Editing an old batch is not restricted.
            ->rule(function (Get $get, $livewire) {
                return function (string $attribute, $value, \Closure $fail) use ($get, $livewire) {
                    if ($livewire instanceof EditRecord || blank($get('medicine_id'))) {
                        return;
                    }

                    $next = self::nextBatchNumber((int) $get('medicine_id'));

                    if (trim((string) $value) !== (string) $next) {
                        $fail("The batch number for this product must be {$next}.");
                    }
                };
            })
            ->helperText(function (Get $get, $livewire) {
                if ($livewire instanceof EditRecord || blank($get('medicine_id'))) {
                    return null;
                }

                return 'Next batch number for this product: ' . self::nextBatchNumber((int) $get('medicine_id'));
            })
            ->live(onBlur: true)
            ->afterStateUpdated(function ($state, Get $get, Set $set, ?Model $record, $livewire) {
                if (blank($state) || blank($get('medicine_id'))) {
                    return;
                }

                $exists = MedicineBatch::where('medicine_id', $get('medicine_id'))
                    ->where('batch_number', trim((string) $state))
                    ->when($record, fn ($query) => $query->where('id', '!=', $record->id))
                    ->exists();

                if ($exists) {
                    self::warn(
                        'Batch number already exists',
                        "Batch \"{$state}\" is already added for this product. Please enter the next batch number."
                    );
                    $set('batch_number', null);

                    return;
                }

                if (! ($livewire instanceof EditRecord)) {
                    $next = self::nextBatchNumber((int) $get('medicine_id'));

                    if (trim((string) $state) !== (string) $next) {
                        self::warn(
                            'Wrong batch number',
                            "The next batch number for this product must be {$next}."
                        );
                        $set('batch_number', null);
                    }
                }
            })
            ->columnSpan(2);
    }

    protected static function manufacturingDatePicker(): DatePicker
    {
        return DatePicker::make('manufacturing_date')
            ->columnSpan(2)
            ->visible(fn (Get $get) => self::hasExpiry($get))
            ->live()
            ->afterStateUpdated(function ($state, Get $get, Set $set) {
                if (blank($state)) {
                    return;
                }

                if (Carbon::parse($state)->gt(today())) {
                    self::warn('Invalid manufacturing date', 'Manufacturing date cannot be in the future.');
                    $set('manufacturing_date', null);

                    return;
                }

                $expiry = $get('expiry_date');

                if (filled($expiry) && Carbon::parse($state)->gte(Carbon::parse($expiry))) {
                    self::warn('Invalid manufacturing date', 'Manufacturing date must be earlier than the expiry date.');
                    $set('manufacturing_date', null);
                }
            });
    }

    protected static function expiryDatePicker(): DatePicker
    {
        return DatePicker::make('expiry_date')
            ->columnSpan(2)
            ->required(fn (Get $get) => self::hasExpiry($get))
            ->visible(fn (Get $get) => self::hasExpiry($get))
            ->minDate(now()->addDay())
            ->live()
            ->afterStateUpdated(function ($state, Get $get, Set $set) {
                if (blank($state)) {
                    return;
                }

                if (Carbon::parse($state)->lte(today())) {
                    self::warn('Invalid expiry date', 'Expiry date must be a future date. Today or past dates are not allowed.');
                    $set('expiry_date', null);

                    return;
                }

                $manufacturing = $get('manufacturing_date');

                if (filled($manufacturing) && Carbon::parse($state)->lte(Carbon::parse($manufacturing))) {
                    self::warn('Invalid expiry date', 'Expiry date must be later than the manufacturing date.');
                    $set('expiry_date', null);
                }
            });
    }

    // ------------------------------------------------------------------
    // Form fields (Packaging & Quantity)
    // ------------------------------------------------------------------

    protected static function boxesReceivedInput(): TextInput
    {
        return TextInput::make('boxes_received')
            ->label(fn (Get $get) => self::packagingType($get) === 'pack' ? 'Packs Received' : 'Boxes Received')
            ->helperText(fn (Get $get) => match (self::packagingType($get)) {
                'strip' => 'Optional — you can also buy only loose strips',
                'pack' => 'Optional — you can also buy only loose pieces',
                default => null,
            })
            ->numeric()
            ->minValue(0)
            ->default(0)
            ->dehydrateStateUsing(fn ($state) => (int) ($state ?? 0))
            ->suffix(fn (Get $get) => self::packagingType($get) === 'pack' ? 'Pack(s)' : 'Box(es)')
            ->visible(fn (Get $get) => self::packagingType($get) !== 'single')
            ->dehydratedWhenHidden()
            ->live(onBlur: true)
            ->afterStateUpdated(function ($state, Get $get, Set $set) {
                if (blank($state) || (int) $state < 0) {
                    $set('boxes_received', 0);
                }

                self::calculateQuantity($get, $set);
            });
    }

    protected static function stripsPerBoxInput(): TextInput
    {
        return TextInput::make('strips_per_box')
            ->label('Strips per Box')
            ->numeric()
            ->minValue(1)
            ->default(1)
            ->suffix('Strip(s)')
            ->visible(fn (Get $get) => self::packagingType($get) === 'strip')
            ->readOnly()
            ->dehydratedWhenHidden();
    }

    /**
     * Loose items bought outside a full Box/Pack.
     *  - strip type => loose strips
     *  - pack type  => loose pieces
     * Stored in the same `loose_strips_received` column (no migration needed).
     */
    protected static function looseStripsReceivedInput(): TextInput
    {
        return TextInput::make('loose_strips_received')
            ->label(fn (Get $get) => self::packagingType($get) === 'pack'
                ? 'Loose Pieces Received'
                : 'Loose Strips Received')
            ->helperText(fn (Get $get) => self::packagingType($get) === 'pack'
                ? 'Pieces bought outside a full pack, if any (even 1 piece)'
                : 'Strips bought outside a full box, if any')
            ->numeric()
            ->minValue(0)
            ->default(0)
            ->dehydrateStateUsing(fn ($state) => (int) ($state ?? 0))
            ->suffix(fn (Get $get) => self::packagingType($get) === 'pack' ? 'Piece(s)' : 'Strip(s)')
            ->visible(fn (Get $get) => in_array(self::packagingType($get), ['strip', 'pack'], true))
            ->dehydratedWhenHidden()
            ->live(onBlur: true)
            ->afterStateUpdated(function ($state, Get $get, Set $set) {
                if (blank($state) || (int) $state < 0) {
                    $set('loose_strips_received', 0);
                }

                self::calculateQuantity($get, $set);
            });
    }

    protected static function unitsPerStripInput(): TextInput
    {
        return TextInput::make('units_per_strip')
            ->label(fn (Get $get) => self::packagingType($get) === 'pack' ? 'Pieces per Pack' : 'Tablets per Box')
            ->numeric()
            ->minValue(1)
            ->default(1)
            ->suffix(fn (Get $get) => self::packagingType($get) === 'pack' ? 'Piece(s)' : 'Tablet(s)')
            ->visible(fn (Get $get) => self::packagingType($get) !== 'single')
            ->readOnly()
            ->dehydratedWhenHidden();
    }

    protected static function quantityInput(): TextInput
    {
        return TextInput::make('quantity')
            ->label('Total Quantity')
            ->numeric()
            ->default(0)
            ->required()
            ->minValue(1)
            ->validationMessages([
                'min' => 'Total quantity must be at least 1.',
            ])
            ->readOnly(fn (Get $get) => self::packagingType($get) !== 'single')
            ->suffix('Units')
            ->extraInputAttributes(['style' => 'font-weight:700;'])
            ->live(onBlur: true)
            ->afterStateUpdated(function ($state, Get $get, Set $set) {
                if (self::packagingType($get) !== 'single') {
                    return;
                }

                if (blank($state) || (int) $state < 1) {
                    self::warn('Invalid quantity', 'Quantity must be at least 1.');
                    $set('quantity', 0);
                    $set('boxes_received', 0);

                    return;
                }

                $set('boxes_received', (int) $state);
                $set('strips_per_box', 1);
                $set('units_per_strip', 1);
                $set('loose_strips_received', 0);
            })
            ->suffixAction(
                Action::make('recalculate')
                    ->icon('heroicon-m-arrow-path')
                    ->label('Recalculate')
                    ->visible(fn ($livewire, Get $get) => $livewire instanceof EditRecord
                        && self::packagingType($get) !== 'single')
                    ->requiresConfirmation()
                    ->modalHeading('Recalculate Quantity?')
                    ->modalDescription('This will recalculate the quantity from Boxes/Packs Received (+ loose strips/pieces) × the Product\'s packaging, and overwrite this batch\'s quantity.')
                    ->action(fn (Get $get, Set $set) => self::calculateQuantity($get, $set))
            );
    }

    // ------------------------------------------------------------------
    // Form fields (Pricing)
    // ------------------------------------------------------------------

    protected static function salePriceInput(): TextInput
    {
        return TextInput::make('sale_price')
            ->label(fn (Get $get) => 'Sale Price (per ' . self::priceUnitLabel($get) . ')')
            ->numeric()
            ->minValue(0)
            ->required()
            ->live(onBlur: true)
            ->prefix(fn () => currency_symbol())
            ->afterStateUpdated(function ($state, Set $set) {
                if (filled($state) && (float) $state < 0) {
                    self::warn('Invalid sale price', 'Sale price cannot be negative.');
                    $set('sale_price', null);
                }
            });
    }

    // ==================================================================
    // UI helper cards (Placeholder content)
    // ==================================================================

    protected static function calculationCard(Get $get): HtmlString
    {
        $type = self::packagingType($get);
        $boxes = max((int) ($get('boxes_received') ?? 0), 0);
        $units = max((int) ($get('units_per_strip') ?? 1), 1);
        $strips = max((int) ($get('strips_per_box') ?? 1), 1);
        $loose = max((int) ($get('loose_strips_received') ?? 0), 0);
        $qty = max((int) ($get('quantity') ?? 0), 0);

        $part = fn (string $n, string $label) =>
            "<span style='font-weight:600;'>{$n}</span> <span style='opacity:.6;font-size:13px;'>{$label}</span>";
        $op = "<span style='opacity:.4;margin:0 10px;font-weight:600;'>×</span>";
        $plus = "<span style='opacity:.4;margin:0 10px;font-weight:600;'>+</span>";

        $left = match ($type) {
            'strip' => $part((string) $boxes, $boxes === 1 ? 'Box' : 'Boxes')
                . $op . $part((string) $units, $units === 1 ? 'Tablet' : 'Tablets')
                . ($strips > 1
                    ? "<span style='opacity:.5;font-size:12px;margin-left:12px;'>(1 Box = {$strips} Strips)</span>"
                    : '')
                . ($loose > 0
                    ? $plus . $part((string) $loose, $loose === 1 ? 'Loose Strip' : 'Loose Strips')
                    : ''),
            'pack' => $part((string) $boxes, $boxes === 1 ? 'Pack' : 'Packs')
                . $op . $part((string) $units, $units === 1 ? 'Piece' : 'Pieces')
                . ($loose > 0
                    ? $plus . $part((string) $loose, $loose === 1 ? 'Loose Piece' : 'Loose Pieces')
                    : ''),
            default => "<span style='opacity:.6;font-size:13px;'>Single units — enter the quantity directly</span>",
        };

        $qtyFromBoxes = $boxes * $units;
        $qtyFromLoose = match ($type) {
            'strip' => (int) round($loose * ($units / $strips)),
            'pack' => $loose,
            default => 0,
        };
        $total = number_format($type === 'single' ? $qty : $qtyFromBoxes + $qtyFromLoose);

        return new HtmlString(
            "<div style='display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;padding:12px 16px;border-radius:10px;background:rgba(13,148,136,0.07);border:1px solid rgba(13,148,136,0.22);border-left:4px solid #0d9488;'>"
            . "<div style='font-size:14px;'>{$left}</div>"
            . "<div style='font-size:14px;'><span style='opacity:.6;'>Total</span> "
            . "<span style='display:inline-block;margin-left:6px;padding:4px 14px;border-radius:999px;background:#0d9488;color:#fff;font-weight:700;font-size:15px;'>{$total} Units</span></div>"
            . "</div>"
        );
    }

    protected static function priceBreakdownCard(Get $get): HtmlString
    {
        $type = self::packagingType($get);
        $boxes = max((int) ($get('boxes_received') ?? 0), 0);
        $units = $type === 'single' ? 1 : max((int) ($get('units_per_strip') ?? 1), 1);
        $strips = max((int) ($get('strips_per_box') ?? 1), 1);
        $loose = max((int) ($get('loose_strips_received') ?? 0), 0);
        $sale = (float) ($get('sale_price') ?? 0);
        $symbol = currency_symbol();

        $unitSale = $units > 0 ? $sale / $units : $sale;
        $perStrip = $sale / $strips;

        // Revenue = full boxes/packs + loose strips/pieces at their own unit price
        $totalRevenue = $sale * $boxes;

        if ($type === 'strip') {
            $totalRevenue += $loose * $perStrip;
        } elseif ($type === 'pack') {
            $totalRevenue += $loose * $unitSale;
        }

        $money = fn (float $v) => ($v < 0 ? '-' : '') . $symbol . number_format(abs($v), 2);

        $stat = fn (string $label, string $value, bool $highlight = false) =>
            "<div style='flex:1;min-width:120px;padding:10px 14px;border-radius:10px;"
            . ($highlight ? 'background:#0d9488;color:#fff;' : 'background:rgba(255,255,255,0.6);')
            . "'>"
            . "<div style='font-size:10px;letter-spacing:.06em;text-transform:uppercase;opacity:" . ($highlight ? '.85' : '.55') . ";'>{$label}</div>"
            . "<div style='font-size:16px;font-weight:700;margin-top:2px;'>{$value}</div>"
            . "</div>";

        $tiles = $stat('Sale (' . self::priceUnitLabel($get) . ')', $money($sale));

        if ($type === 'strip') {
            $tiles .= $stat('Per Strip', $money($perStrip));
            $tiles .= $stat('Per Tablet', $money($unitSale));
        } elseif ($type === 'pack') {
            $tiles .= $stat('Per Piece', $money($unitSale));
        }

        $tiles .= $stat('Total Revenue', $money($totalRevenue), highlight: true);

        return new HtmlString(
            "<div style='display:flex;gap:10px;flex-wrap:wrap;padding:12px;border-radius:14px;border:1px solid rgba(13,148,136,0.22);background:rgba(13,148,136,0.08);'>"
                . $tiles
            . "</div>"
        );
    }

    // ==================================================================
    // TABLE
    // ==================================================================

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('medicine.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('medicine.product_type')
                    ->label('Type')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn ($state) => MedicineResource::productTypes()[$state] ?? $state)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('medicine.packaging_type')
                    ->label('Packaging')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'pack' => 'warning',
                        'single' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(function ($state, $record) {
                        $qty = (int) ($record->quantity ?? 0);

                        if ($state === 'single') {
                            return 'Single';
                        }

                        if ($state === 'pack') {
                            // Total pieces in this batch
                            return $qty . ' ' . ($qty === 1 ? 'Piece' : 'Pieces');
                        }

                        $stripsPerBox = max((int) ($record->medicine?->strips_per_box ?? 1), 1);
                        $unitsPerBox = max((int) ($record->medicine?->units_per_strip ?? 1), 1);
                        $tabletsPerStrip = max($unitsPerBox / $stripsPerBox, 1);

                        // Total strips in this batch, derived from quantity
                        $totalStrips = (int) round($qty / $tabletsPerStrip);

                        return $totalStrips . ' ' . ($totalStrips === 1 ? 'Strip' : 'Strips');
                    }),

                TextColumn::make('batch_number')
                    ->label('Batch #')
                    ->searchable(),

                TextColumn::make('boxes_received')
                    ->label('Received')
                    ->formatStateUsing(function ($state, $record) {
                        $type = $record->medicine?->packaging_type;

                        if ($type === 'single') {
                            return '—';
                        }

                        // Received = only full Packs / Boxes. Loose pieces/strips
                        // are shown in the Packaging column instead.
                        $count = (int) $state;

                        $label = $type === 'pack'
                            ? ($count === 1 ? 'Pack' : 'Packs')
                            : ($count === 1 ? 'Box' : 'Boxes');

                        return number_format($count) . ' ' . $label;
                    })
                    ->sortable(),

                TextColumn::make('expiry_date')
                    ->date()
                    ->placeholder('No expiry')
                    ->sortable()
                    ->color(fn ($record) => match ($record->expiry_status) {
                        'expired' => 'danger',
                        'expiring_soon' => 'warning',
                        'no_expiry' => 'gray',
                        default => 'success',
                    }),

                TextColumn::make('quantity')
                    ->label('Total Qty')
                    ->weight('semibold')
                    ->sortable(),

                TextColumn::make('sale_price')
                    ->label('Sale Price')
                    ->formatStateUsing(fn ($state) => format_currency($state))
                    ->sortable(),

                TextColumn::make('supplier.supplier_name')
                    ->label('Supplier')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('expiry_status')
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
            ->filters([
                SelectFilter::make('medicine_id')
                    ->label('Product')
                    ->relationship('medicine', 'name')
                    ->searchable()
                    ->preload(),

                Filter::make('expiring_soon')
                    ->label('Expiring Soon (30 days)')
                    ->query(fn (Builder $query): Builder => $query
                        ->whereNotNull('expiry_date')
                        ->whereBetween('expiry_date', [now(), now()->addDays(30)])),

                Filter::make('expired')
                    ->label('Expired')
                    ->query(fn (Builder $query): Builder => $query
                        ->whereNotNull('expiry_date')
                        ->where('expiry_date', '<', now())),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->recordActionsColumnLabel('Actions')
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('expiry_date', 'asc');
    }

    // ==================================================================
    // PAGES
    // ==================================================================

    public static function getPages(): array
    {
        return [
            'index' => ListMedicineBatches::route('/'),
            'create' => CreateMedicineBatch::route('/create'),
            'edit' => EditMedicineBatch::route('/{record}/edit'),
        ];
    }
}