<?php

namespace App\Filament\Resources\MedicineBatches;

use App\Filament\Resources\MedicineBatches\Pages\ListMedicineBatches;
use App\Filament\Resources\MedicineBatches\Pages\CreateMedicineBatch;
use App\Filament\Resources\MedicineBatches\Pages\EditMedicineBatch;
use App\Models\Medicine;
use App\Models\MedicineBatch;
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
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
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
use UnitEnum;

class MedicineBatchResource extends Resource
{
    protected static ?string $model = MedicineBatch::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string|UnitEnum|null $navigationGroup = 'Medicines';

    protected static ?int $navigationSort = 6;

    protected static ?string $navigationLabel = 'Batches & Expiry';

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

    // ------------------------------------------------------------------
    // Product helpers (packaging type / expiry come from the product)
    // ------------------------------------------------------------------

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

    // ------------------------------------------------------------------
    // FORM
    // ------------------------------------------------------------------

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
                        Select::make('medicine_id')
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
                                $type = $product?->packaging_type ?? 'strip';

                                // Reuse packaging numbers from the last batch of this product
                                $lastBatch = MedicineBatch::where('medicine_id', $state)
                                    ->where('strips_per_box', '>', 0)
                                    ->where('units_per_strip', '>', 0)
                                    ->latest('id')
                                    ->first();

                                if ($type === 'strip' && $lastBatch) {
                                    $set('strips_per_box', $lastBatch->strips_per_box);
                                    $set('units_per_strip', $lastBatch->units_per_strip);
                                }

                                if ($type === 'pack') {
                                    $set('strips_per_box', 1);

                                    if ($lastBatch) {
                                        $set('units_per_strip', $lastBatch->units_per_strip);
                                    }
                                }

                                if ($type === 'single') {
                                    $set('strips_per_box', 1);
                                    $set('units_per_strip', 1);
                                    $set('boxes_received', 0);
                                    $set('quantity', 0);
                                } else {
                                    self::calculateQuantity($get, $set, $type);
                                }

                                $currentStock = MedicineBatch::where('medicine_id', $state)->sum('quantity');

                                Notification::make()
                                    ->info()
                                    ->title("{$product?->name} — current stock")
                                    ->body("Current total stock: {$currentStock} units (all batches combined). This new batch will be added to it.")
                                    ->send();
                            })
                            ->columnSpan(2),

                        TextInput::make('batch_number')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),

                        DatePicker::make('manufacturing_date')
                            ->columnSpan(2)
                            ->visible(fn (Get $get) => self::hasExpiry($get))
                            ->live()
                            ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                if (blank($state)) {
                                    return;
                                }

                                if (Carbon::parse($state)->gt(today())) {
                                    self::warn(
                                        'Invalid manufacturing date',
                                        'Manufacturing date cannot be in the future.'
                                    );
                                    $set('manufacturing_date', null);

                                    return;
                                }

                                $expiry = $get('expiry_date');

                                if (filled($expiry) && Carbon::parse($state)->gte(Carbon::parse($expiry))) {
                                    self::warn(
                                        'Invalid manufacturing date',
                                        'Manufacturing date must be earlier than the expiry date.'
                                    );
                                    $set('manufacturing_date', null);
                                }
                            }),

                        DatePicker::make('expiry_date')
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
                                    self::warn(
                                        'Invalid expiry date',
                                        'Expiry date must be a future date. Today or past dates are not allowed.'
                                    );
                                    $set('expiry_date', null);

                                    return;
                                }

                                $manufacturing = $get('manufacturing_date');

                                if (filled($manufacturing) && Carbon::parse($state)->lte(Carbon::parse($manufacturing))) {
                                    self::warn(
                                        'Invalid expiry date',
                                        'Expiry date must be later than the manufacturing date.'
                                    );
                                    $set('expiry_date', null);
                                }
                            }),
                    ]),

                Section::make('Packaging & Quantity')
                    ->description('Enter the packaging details — the total quantity is calculated automatically')
                    ->icon(Heroicon::OutlinedArchiveBox)
                    ->columnSpanFull()
                    ->schema([

                        // Boxes (strip) or Packs (pack). Hidden for single units.
                        TextInput::make('boxes_received')
                            ->label(fn (Get $get) => self::packagingType($get) === 'pack' ? 'Packs Received' : 'Boxes Received')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->suffix(fn (Get $get) => self::packagingType($get) === 'pack' ? 'Pack(s)' : 'Box(es)')
                            ->visible(fn (Get $get) => self::packagingType($get) !== 'single')
                            ->dehydratedWhenHidden()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Get $get, Set $set, $livewire) {
                                if (blank($state) || (int) $state < 0) {
                                    self::warn(
                                        'Invalid quantity',
                                        'Received count cannot be empty or negative. It has been reset to 0.'
                                    );
                                    $set('boxes_received', 0);
                                }

                                if ($livewire instanceof \Filament\Resources\Pages\CreateRecord) {
                                    self::calculateQuantity($get, $set);
                                }
                            }),

                        // Strips per box: only for strip-type products.
                        TextInput::make('strips_per_box')
                            ->label('Strips per Box')
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->suffix('Strip(s)')
                            ->visible(fn (Get $get) => self::packagingType($get) === 'strip')
                            ->dehydratedWhenHidden()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Get $get, Set $set, $livewire) {
                                if (blank($state) || (int) $state < 1) {
                                    self::warn(
                                        'Invalid strips per box',
                                        'Strips per box must be at least 1. It has been reset to 1.'
                                    );
                                    $set('strips_per_box', 1);
                                }

                                if ($livewire instanceof \Filament\Resources\Pages\CreateRecord) {
                                    self::calculateQuantity($get, $set);
                                }
                            }),

                        // Tablets per strip (strip) or Pieces per pack (pack).
                        TextInput::make('units_per_strip')
                            ->label(fn (Get $get) => self::packagingType($get) === 'pack' ? 'Pieces per Pack' : 'Tablets per Strip')
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->suffix(fn (Get $get) => self::packagingType($get) === 'pack' ? 'Piece(s)' : 'Tablet(s)')
                            ->visible(fn (Get $get) => self::packagingType($get) !== 'single')
                            ->dehydratedWhenHidden()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Get $get, Set $set, $livewire) {
                                if (blank($state) || (int) $state < 1) {
                                    self::warn(
                                        'Invalid packaging value',
                                        'This value must be at least 1. It has been reset to 1.'
                                    );
                                    $set('units_per_strip', 1);
                                }

                                if ($livewire instanceof \Filament\Resources\Pages\CreateRecord) {
                                    self::calculateQuantity($get, $set);
                                }
                            }),

                        // Read-only (calculated) for strip/pack, editable for single units.
                        TextInput::make('quantity')
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
                                    self::warn(
                                        'Invalid quantity',
                                        'Quantity must be at least 1.'
                                    );
                                    $set('quantity', 0);
                                    $set('boxes_received', 0);

                                    return;
                                }

                                // For single units: boxes = quantity so pricing math stays consistent
                                $set('boxes_received', (int) $state);
                                $set('strips_per_box', 1);
                                $set('units_per_strip', 1);
                            })
                            ->suffixAction(
                                Action::make('recalculate')
                                    ->icon('heroicon-m-arrow-path')
                                    ->label('Recalculate')
                                    ->visible(fn ($livewire, Get $get) => $livewire instanceof \Filament\Resources\Pages\EditRecord
                                        && self::packagingType($get) !== 'single')
                                    ->requiresConfirmation()
                                    ->modalHeading('Recalculate Quantity?')
                                    ->modalDescription('This will recalculate the quantity from the packaging fields and overwrite this batch\'s quantity.')
                                    ->action(function (Get $get, Set $set) {
                                        self::calculateQuantity($get, $set);
                                    })
                            )
                            ->columnSpan(fn (Get $get) => match (self::packagingType($get)) {
                                'strip' => 1,
                                default => 2,
                            }),

                        Placeholder::make('calculation_preview')
                            ->hiddenLabel()
                            ->content(fn (Get $get) => self::calculationCard($get))
                            ->columnSpanFull(),

                    ])
                    ->columns(4),

                Section::make('Pricing')
                    ->description('Enter the price for one full package (box, pack or single unit) — smaller unit prices are calculated automatically')
                    ->icon(Heroicon::OutlinedBanknotes)
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('purchase_price')
                            ->label(fn (Get $get) => 'Purchase Price (per ' . self::priceUnitLabel($get) . ')')
                            ->numeric()
                            ->minValue(0)
                            ->live(onBlur: true)
                            ->prefix(fn () => currency_symbol())
                            ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                if (filled($state) && (float) $state < 0) {
                                    self::warn('Invalid purchase price', 'Purchase price cannot be negative.');
                                    $set('purchase_price', null);

                                    return;
                                }

                                self::warnIfSaleBelowPurchase($get);
                            }),

                        TextInput::make('sale_price')
                            ->label(fn (Get $get) => 'Sale Price (per ' . self::priceUnitLabel($get) . ')')
                            ->numeric()
                            ->minValue(0)
                            ->live(onBlur: true)
                            ->prefix(fn () => currency_symbol())
                            ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                if (filled($state) && (float) $state < 0) {
                                    self::warn('Invalid sale price', 'Sale price cannot be negative.');
                                    $set('sale_price', null);

                                    return;
                                }

                                self::warnIfSaleBelowPurchase($get);
                            }),

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
    // UI helpers
    // ------------------------------------------------------------------

    protected static function calculationCard(Get $get): HtmlString
    {
        $type = self::packagingType($get);
        $boxes = max((int) ($get('boxes_received') ?? 0), 0);
        $strips = max((int) ($get('strips_per_box') ?? 1), 1);
        $units = max((int) ($get('units_per_strip') ?? 1), 1);
        $qty = max((int) ($get('quantity') ?? 0), 0);

        $part = fn (string $n, string $label) =>
            "<span style='font-weight:600;'>{$n}</span> <span style='opacity:.6;font-size:13px;'>{$label}</span>";
        $op = "<span style='opacity:.4;margin:0 10px;font-weight:600;'>×</span>";

        $left = match ($type) {
            'strip' => $part((string) $boxes, $boxes === 1 ? 'Box' : 'Boxes')
                . $op . $part((string) $strips, $strips === 1 ? 'Strip' : 'Strips')
                . $op . $part((string) $units, $units === 1 ? 'Tablet' : 'Tablets'),
            'pack' => $part((string) $boxes, $boxes === 1 ? 'Pack' : 'Packs')
                . $op . $part((string) $units, $units === 1 ? 'Piece' : 'Pieces'),
            default => "<span style='opacity:.6;font-size:13px;'>Single units — enter the quantity directly</span>",
        };

        $total = number_format($type === 'single' ? $qty : ($type === 'pack' ? $boxes * $units : $boxes * $strips * $units));

        return new HtmlString(
            "<div style='display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;padding:12px 16px;border-radius:10px;background:rgba(13,148,136,0.07);border:1px solid rgba(13,148,136,0.22);border-left:4px solid #0d9488;'>"
            . "<div style='font-size:14px;'>{$left}</div>"
            . "<div style='font-size:14px;'><span style='opacity:.6;'>Total</span> "
            . "<span style='display:inline-block;margin-left:6px;padding:4px 14px;border-radius:999px;background:#0d9488;color:#fff;font-weight:700;font-size:15px;'>{$total} Units</span></div>"
            . "</div>"
        );
    }

    // protected static function priceCard(string $title, float $purchase, float $sale, string $symbol): string
    // {
    //     $profit = $sale - $purchase;
    //     $profitColor = $profit >= 0 ? '#16a34a' : '#dc2626';
    //     $sign = $profit >= 0 ? '+' : '-';
    //     $fmt = fn (float $v) => $symbol . number_format(abs($v), 2);
    //     $row = fn (string $label, string $value, string $extra = '') =>
    //         "<div style='display:flex;justify-content:space-between;font-size:13px;padding:3px 0;{$extra}'><span style='opacity:.65;'>{$label}</span><span style='font-weight:600;'>{$value}</span></div>";

    //     return "<div style='padding:14px;border-radius:12px;border:1px solid rgba(128,128,128,0.25);'>"
    //         . "<div style='font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#0d9488;margin-bottom:8px;'>{$title}</div>"
    //         . $row('Purchase', $fmt($purchase))
    //         . $row('Sale', $fmt($sale))
    //         . "<div style='border-top:1px dashed rgba(128,128,128,0.35);margin:8px 0 4px;'></div>"
    //         . $row('Profit', $sign . $fmt($profit), "color:{$profitColor};")
    //         . "</div>";
    // }

    // protected static function priceBreakdownCard(Get $get): HtmlString
    // {
    //     $type = self::packagingType($get);
    //     $boxes = max((int) ($get('boxes_received') ?? 0), 0);
    //     $strips = $type === 'strip' ? max((int) ($get('strips_per_box') ?? 1), 1) : 1;
    //     $units = $type === 'single' ? 1 : max((int) ($get('units_per_strip') ?? 1), 1);
    //     $purchase = (float) ($get('purchase_price') ?? 0);
    //     $sale = (float) ($get('sale_price') ?? 0);
    //     $symbol = currency_symbol();

    //     $totalCost = $purchase * $boxes;
    //     $totalRevenue = $sale * $boxes;
    //     $totalProfit = $totalRevenue - $totalCost;
    //     $margin = $purchase > 0 ? (($sale - $purchase) / $purchase) * 100 : 0;
    //     $profitColor = $totalProfit >= 0 ? '#16a34a' : '#dc2626';
    //     $money = fn (float $v) => ($v < 0 ? '-' : '') . $symbol . number_format(abs($v), 2);

    //     $stat = fn (string $label, string $value, string $color = 'inherit') =>
    //         "<div style='flex:1;min-width:130px;'><div style='font-size:11px;letter-spacing:.06em;text-transform:uppercase;opacity:.6;'>{$label}</div>"
    //         . "<div style='font-size:17px;font-weight:700;margin-top:2px;color:{$color};'>{$value}</div></div>";

    //     $cards = match ($type) {
    //         'strip' => self::priceCard('Per Box', $purchase, $sale, $symbol)
    //             . self::priceCard('Per Strip', $purchase / $strips, $sale / $strips, $symbol)
    //             . self::priceCard('Per Tablet', $purchase / ($strips * $units), $sale / ($strips * $units), $symbol),
    //         'pack' => self::priceCard('Per Pack', $purchase, $sale, $symbol)
    //             . self::priceCard('Per Piece', $purchase / $units, $sale / $units, $symbol),
    //         default => self::priceCard('Per Unit', $purchase, $sale, $symbol),
    //     };

    //     return new HtmlString(
    //         "<div style='display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px;'>"
    //         . $cards
    //         . "</div>"
    //         . "<div style='display:flex;gap:16px;flex-wrap:wrap;margin-top:12px;padding:14px 16px;border-radius:12px;background:rgba(13,148,136,0.08);border:1px solid rgba(13,148,136,0.25);'>"
    //         . $stat('Batch Cost', $money($totalCost))
    //         . $stat('Expected Revenue', $money($totalRevenue))
    //         . $stat('Expected Profit', $money($totalProfit), $profitColor)
    //         . $stat('Margin', number_format($margin, 1) . '%', $profitColor)
    //         . "</div>"
    //     );
    // }
    protected static function priceBreakdownCard(Get $get): HtmlString
    {
        $type = self::packagingType($get);
        $boxes = max((int) ($get('boxes_received') ?? 0), 0);
        $strips = $type === 'strip' ? max((int) ($get('strips_per_box') ?? 1), 1) : 1;
        $units = $type === 'single' ? 1 : max((int) ($get('units_per_strip') ?? 1), 1);
        $purchase = (float) ($get('purchase_price') ?? 0);
        $sale = (float) ($get('sale_price') ?? 0);
        $symbol = currency_symbol();

        $unitPurchase = $purchase / ($strips * $units);
        $unitSale = $sale / ($strips * $units);

        $totalCost = $purchase * $boxes;
        $totalRevenue = $sale * $boxes;
        $totalProfit = $totalRevenue - $totalCost;
        $margin = $purchase > 0 ? (($sale - $purchase) / $purchase) * 100 : 0;
        $isProfit = $totalProfit >= 0;
        $accent = $isProfit ? '#0d9488' : '#dc2626';
        $accentSoft = $isProfit ? 'rgba(13,148,136,0.08)' : 'rgba(220,38,38,0.08)';
        $accentBorder = $isProfit ? 'rgba(13,148,136,0.22)' : 'rgba(220,38,38,0.25)';

        $money = fn (float $v) => ($v < 0 ? '-' : '') . $symbol . number_format(abs($v), 2);

        // Small unit-price rows (Per Strip / Per Tablet or Per Piece) — only when they add information
        $rows = '';
        if ($type === 'strip') {
            $rows .= self::unitRow('Per Strip', $purchase / $strips, $sale / $strips, $symbol);
            $rows .= self::unitRow('Per Tablet', $unitPurchase, $unitSale, $symbol);
        } elseif ($type === 'pack') {
            $rows .= self::unitRow('Per Piece', $unitPurchase, $unitSale, $symbol);
        }

        $unitBlock = $rows === '' ? '' : (
            "<div style='margin-top:14px;border-top:1px solid rgba(128,128,128,0.15);padding-top:10px;'>{$rows}</div>"
        );

        $stat = fn (string $label, string $value, ?string $color = null) =>
            "<div style='flex:1;min-width:110px;'>"
            . "<div style='font-size:10.5px;letter-spacing:.06em;text-transform:uppercase;opacity:.55;'>{$label}</div>"
            . "<div style='font-size:16px;font-weight:700;margin-top:2px;" . ($color ? "color:{$color};" : '') . "'>{$value}</div>"
            . "</div>";

        return new HtmlString(
            "<div style='border-radius:14px;border:1px solid {$accentBorder};background:{$accentSoft};overflow:hidden;'>"

                // Header: headline profit/loss number for this batch
                . "<div style='display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;padding:16px 18px;border-bottom:1px solid {$accentBorder};'>"
                    . "<div>"
                        . "<div style='font-size:11px;font-weight:600;letter-spacing:.06em;text-transform:uppercase;opacity:.6;'>" . ($isProfit ? 'Expected Profit' : 'Expected Loss') . " on this batch</div>"
                        . "<div style='font-size:26px;font-weight:800;color:{$accent};line-height:1.15;margin-top:2px;'>" . ($isProfit ? '+' : '-') . $symbol . number_format(abs($totalProfit), 2) . "</div>"
                    . "</div>"
                    . "<div style='padding:6px 14px;border-radius:999px;background:{$accent};color:#fff;font-weight:700;font-size:13px;white-space:nowrap;'>"
                        . number_format($margin, 1) . "% margin"
                    . "</div>"
                . "</div>"

                // Body: cost vs revenue + per-unit breakdown
                . "<div style='padding:14px 18px 16px;'>"
                    . "<div style='display:flex;gap:20px;flex-wrap:wrap;'>"
                        . $stat('Cost (' . self::priceUnitLabel($get) . ')', $money($purchase))
                        . $stat('Sale (' . self::priceUnitLabel($get) . ')', $money($sale))
                        . $stat('Total Cost', $money($totalCost))
                        . $stat('Total Revenue', $money($totalRevenue))
                    . "</div>"
                    . $unitBlock
                . "</div>"
            . "</div>"
        );
    }

    protected static function unitRow(string $label, float $purchase, float $sale, string $symbol): string
    {
        $profit = $sale - $purchase;
        $color = $profit >= 0 ? '#16a34a' : '#dc2626';
        $sign = $profit >= 0 ? '+' : '-';

        return "<div style='display:flex;align-items:center;justify-content:space-between;font-size:13px;padding:4px 0;'>"
            . "<span style='opacity:.65;'>{$label}</span>"
            . "<span>"
                . "<span style='opacity:.6;'>{$symbol}" . number_format($purchase, 2) . " → </span>"
                . "<span style='font-weight:600;'>{$symbol}" . number_format($sale, 2) . "</span>"
                . "<span style='color:{$color};font-weight:600;margin-left:8px;'>({$sign}{$symbol}" . number_format(abs($profit), 2) . ")</span>"
            . "</span>"
            . "</div>";
    }

    /**
     * Show a red popup notification.
     */
    protected static function warn(string $title, string $body): void
    {
        Notification::make()
            ->danger()
            ->title($title)
            ->body($body)
            ->send();
    }

    /**
     * Popup warning (not blocking) when sale price is lower than purchase price.
     */
    protected static function warnIfSaleBelowPurchase(Get $get): void
    {
        $purchase = $get('purchase_price');
        $sale = $get('sale_price');

        if (blank($purchase) || blank($sale)) {
            return;
        }

        if ((float) $sale < (float) $purchase) {
            Notification::make()
                ->warning()
                ->title('Sale price is lower than purchase price')
                ->body('You will sell this batch at a loss. Please double-check both prices.')
                ->send();
        }
    }

    /**
     * strip  : boxes × strips × tablets
     * pack   : packs × pieces
     * single : quantity is entered directly (nothing to calculate)
     */
    protected static function calculateQuantity(Get $get, Set $set, ?string $type = null): void
    {
        $type ??= self::packagingType($get);

        $boxes = max((int) ($get('boxes_received') ?? 0), 0);
        $strips = max((int) ($get('strips_per_box') ?? 1), 1);
        $units = max((int) ($get('units_per_strip') ?? 1), 1);

        if ($type === 'strip') {
            $set('quantity', $boxes * $strips * $units);
        } elseif ($type === 'pack') {
            $set('strips_per_box', 1);
            $set('quantity', $boxes * $units);
        }
    }

    // ------------------------------------------------------------------
    // TABLE
    // ------------------------------------------------------------------

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
                    ->color('gray')
                    ->formatStateUsing(fn ($state) => \App\Filament\Resources\Medicines\MedicineResource::productTypes()[$state] ?? $state)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('batch_number')
                    ->label('Batch #')
                    ->searchable(),

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
                    ->sortable(),

                TextColumn::make('sale_price')
                    ->label('Sale Price')
                    ->formatStateUsing(fn ($state) => format_currency($state))
                    ->sortable(),

                TextColumn::make('supplier.supplier_name')
                    ->label('Supplier')
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

    public static function getPages(): array
    {
        return [
            'index' => ListMedicineBatches::route('/'),
            'create' => CreateMedicineBatch::route('/create'),
            'edit' => EditMedicineBatch::route('/{record}/edit'),
        ];
    }
}