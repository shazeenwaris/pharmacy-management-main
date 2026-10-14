<?php

namespace App\Filament\Resources\PurchaseReturns;

use App\Filament\Resources\PurchaseReturns\Pages\CreatePurchaseReturn;
use App\Filament\Resources\PurchaseReturns\Pages\ListPurchaseReturns;
use App\Models\MedicineBatch;
use App\Models\PurchaseItem;
use App\Models\PurchaseReturn;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturnResource extends Resource
{
    protected static ?string $model = PurchaseReturn::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowUturnLeft;

    protected static ?string $navigationLabel = 'Purchase Returns';

    protected static string|\UnitEnum|null $navigationGroup = 'Purchases';

    protected static ?int $navigationSort = 3;

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('manage purchases') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('manage purchases') ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->can('manage settings') ?? false;
    }

    // ------------------------------------------------------------------
    // Helpers: everything auto-fills from the original purchase
    // ------------------------------------------------------------------

    /**
     * Find the stock batch that came from this purchase item.
     *
     * 1) Linked directly (batch created with "Load from Purchase" -> purchase_item_id)
     * 2) Same medicine + same batch number typed on the purchase item
     * 3) Same medicine + same expiry date + same boxes received (older batches that
     *    were created by hand, so they have no direct link)
     */
    protected static function findBatch(PurchaseItem $item): ?MedicineBatch
    {
        $batch = $item->medicineBatch;

        if ($batch) {
            return $batch;
        }

        if (filled($item->batch_number)) {
            $batch = MedicineBatch::where('medicine_id', $item->medicine_id)
                ->where('batch_number', $item->batch_number)
                ->first();

            if ($batch) {
                return $batch;
            }
        }

        if ($item->expiry_date && filled($item->boxes_received)) {
            return MedicineBatch::where('medicine_id', $item->medicine_id)
                ->whereDate('expiry_date', $item->expiry_date)
                ->where('boxes_received', $item->boxes_received)
                ->orderBy('id')
                ->first();
        }

        return null;
    }

    /**
     * Cost of ONE stock unit (tablet / piece).
     * purchase_price on the purchase item is per Box/Pack, but stock quantity is in units.
     */
    protected static function unitCost(PurchaseItem $item): float
    {
        $price = (float) $item->purchase_price;
        $boxes = (float) ($item->boxes_received ?? 0);
        $qty = (float) ($item->quantity ?? 0);

        if ($boxes > 0 && $qty > 0) {
            return round($price * $boxes / $qty, 4);
        }

        return round($price, 4);
    }

    /**
     * Fill medicine, batch and price from a purchase + medicine.
     */
    protected static function fillFromPurchase(?int $purchaseId, ?int $medicineId, callable $set): void
    {
        $set('medicine_batch_id', null);
        $set('batch_number', null);
        $set('purchase_price', null);
        $set('quantity', null);
        $set('refund_amount', 0);

        if (blank($purchaseId) || blank($medicineId)) {
            return;
        }

        $item = PurchaseItem::where('purchase_id', $purchaseId)
            ->where('medicine_id', $medicineId)
            ->first();

        if (! $item) {
            return;
        }

        $set('purchase_price', self::unitCost($item));

        $batch = self::findBatch($item);

        if ($batch) {
            $set('medicine_batch_id', $batch->id);
            $set('batch_number', $batch->batch_number);
        }
    }

    protected static function batchStock(callable $get): ?float
    {
        $batchId = $get('medicine_batch_id');

        if (blank($batchId)) {
            return null;
        }

        $batch = MedicineBatch::find($batchId);

        return $batch ? (float) $batch->quantity : null;
    }

    protected static function formatQty(float $qty): string
    {
        return rtrim(rtrim(number_format($qty, 3, '.', ''), '0'), '.');
    }

    // ------------------------------------------------------------------
    // Form
    // ------------------------------------------------------------------

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Return Details')
                    ->description('Select the original purchase — medicine, batch and price fill in automatically')
                    ->icon(Heroicon::OutlinedDocumentText)
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        Select::make('purchase_id')
                            ->label('Purchase Invoice')
                            ->relationship('purchase', 'invoice_number')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('medicine_id', null);
                                self::fillFromPurchase(null, null, $set);

                                if (blank($state)) {
                                    return;
                                }

                                $items = PurchaseItem::where('purchase_id', $state)->get();

                                // Only one medicine on this invoice: select it automatically
                                if ($items->pluck('medicine_id')->unique()->count() === 1) {
                                    $medicineId = (int) $items->first()->medicine_id;

                                    $set('medicine_id', $medicineId);
                                    self::fillFromPurchase((int) $state, $medicineId, $set);
                                }
                            }),

                        Select::make('medicine_id')
                            ->label('Medicine')
                            ->options(function (callable $get) {
                                $purchaseId = $get('purchase_id');

                                if (blank($purchaseId)) {
                                    return [];
                                }

                                return PurchaseItem::where('purchase_id', $purchaseId)
                                    ->with('medicine')
                                    ->get()
                                    ->filter(fn ($item) => $item->medicine)
                                    ->pluck('medicine.name', 'medicine_id')
                                    ->unique();
                            })
                            ->searchable()
                            ->required()
                            ->live()
                            ->disabled(fn (callable $get) => blank($get('purchase_id')))
                            ->helperText(fn (callable $get) => blank($get('purchase_id'))
                                ? 'Select a purchase invoice first.'
                                : null)
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                self::fillFromPurchase(
                                    filled($get('purchase_id')) ? (int) $get('purchase_id') : null,
                                    filled($state) ? (int) $state : null,
                                    $set
                                );
                            }),

                        // Batch is picked automatically. If the system cannot match it,
                        // the user can pick it from this list manually.
                        Select::make('medicine_batch_id')
                            ->label('Batch Number')
                            ->options(function (callable $get) {
                                $medicineId = $get('medicine_id');

                                if (blank($medicineId)) {
                                    return [];
                                }

                                // Only the batch number is shown (no stock info)
                                return MedicineBatch::where('medicine_id', $medicineId)
                                    ->orderBy('expiry_date')
                                    ->pluck('batch_number', 'id')
                                    ->all();
                            })
                            ->searchable()
                            ->required()
                            ->live()
                            ->disabled(fn (callable $get) => blank($get('medicine_id')))
                            ->helperText(fn (callable $get) => blank($get('medicine_id'))
                                ? null
                                : (blank($get('medicine_batch_id'))
                                    ? 'Batch could not be matched automatically — please choose it.'
                                    : null))
                            ->afterStateUpdated(function ($state, callable $set) {
                                $batch = filled($state) ? MedicineBatch::find($state) : null;

                                $set('batch_number', $batch?->batch_number);
                                $set('quantity', null);
                                $set('refund_amount', 0);
                            }),

                        // Saved with the return (kept for the existing batch_number column)
                        Hidden::make('batch_number')
                            ->dehydrated(),
                    ]),

                Section::make('Quantity & Refund')
                    ->description('Quantity is in stock units. Refund is calculated automatically')
                    ->icon(Heroicon::OutlinedCalculator)
                    ->columnSpanFull()
                    ->columns(4)
                    ->schema([
                        TextInput::make('quantity')
                            ->label('Quantity')
                            ->numeric()
                            ->minValue(1)
                            ->required()
                            ->live(onBlur: true)
                            ->helperText(function (callable $get) {
                                $stock = self::batchStock($get);

                                return $stock === null
                                    ? null
                                    : 'Available in stock: ' . self::formatQty($stock) . ' units';
                            })
                            ->rules([
                                function (callable $get) {
                                    return function (string $attribute, $value, \Closure $fail) use ($get) {
                                        $stock = self::batchStock($get);

                                        if ($stock !== null && (float) $value > $stock) {
                                            $fail('Cannot return more than the available stock (' . self::formatQty($stock) . ').');
                                        }
                                    };
                                },
                            ])
                            ->afterStateUpdated(function (callable $set, callable $get) {
                                $qty = (float) ($get('quantity') ?? 0);
                                $price = (float) ($get('purchase_price') ?? 0);

                                $set('refund_amount', round($qty * $price, 2));
                            }),

                        TextInput::make('purchase_price')
                            ->label('Purchase Price (per unit)')
                            ->numeric()
                            ->prefix(fn () => currency_symbol())
                            ->required()
                            ->readOnly(),

                        TextInput::make('refund_amount')
                            ->label('Refund Amount')
                            ->numeric()
                            ->prefix(fn () => currency_symbol())
                            ->default(0)
                            ->readOnly(),

                        DatePicker::make('return_date')
                            ->label('Return Date')
                            ->required()
                            ->default(now())
                            ->maxDate(now()),
                    ]),

                Section::make('Reason')
                    ->description('Why this item is being returned')
                    ->icon(Heroicon::OutlinedExclamationTriangle)
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Select::make('reason')
                            ->label('Reason')
                            ->options([
                                'damaged' => 'Damaged',
                                'expired' => 'Expired',
                                'wrong_item' => 'Wrong Item Delivered',
                                'excess_stock' => 'Excess Stock',
                                'other' => 'Other',
                            ])
                            ->required()
                            ->native(false),

                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    // ------------------------------------------------------------------
    // Table
    // ------------------------------------------------------------------

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('purchase.invoice_number')
                    ->label('Purchase Invoice')
                    ->searchable(),
                TextColumn::make('medicine.name')
                    ->searchable(),
                TextColumn::make('batch_number'),
                TextColumn::make('quantity'),
                TextColumn::make('refund_amount')
                    ->formatStateUsing(fn ($state) => format_currency($state)),
                TextColumn::make('reason')
                    ->badge(),
                TextColumn::make('return_date')
                    ->date()
                    ->sortable(),
            ])
            ->recordActions([
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('return_date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPurchaseReturns::route('/'),
            'create' => CreatePurchaseReturn::route('/create'),
        ];
    }
}