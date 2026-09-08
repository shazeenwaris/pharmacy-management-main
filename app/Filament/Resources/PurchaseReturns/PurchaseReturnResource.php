<?php

namespace App\Filament\Resources\PurchaseReturns;

use App\Filament\Resources\PurchaseReturns\Pages\ListPurchaseReturns;
use App\Filament\Resources\PurchaseReturns\Pages\CreatePurchaseReturn;
use App\Filament\Resources\PurchaseReturns\Pages\EditPurchaseReturn;
use App\Models\PurchaseReturn;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\Hidden;

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

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Return Details')
                    ->description('Select the original purchase and medicine being returned')
                    ->icon(Heroicon::OutlinedDocumentText)
                    ->columnSpanFull()
                    ->schema([
                        Select::make('purchase_id')
                            ->label('Purchase Invoice')
                            ->relationship('purchase', 'invoice_number')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                // Reset dependent fields whenever the purchase changes
                                $set('medicine_id', null);
                                $set('medicine_batch_id', null);
                                $set('batch_number', null);
                                $set('purchase_price', null);
                                $set('quantity', null);
                                $set('refund_amount', 0);

                                if (blank($state)) {
                                    return;
                                }

                                $items = \App\Models\PurchaseItem::where('purchase_id', $state)->get();

                                // If this purchase only has one medicine, auto-select it —
                                // no need to make the user pick from a dropdown of one.
                                if ($items->count() === 1) {
                                    $purchaseItem = $items->first();

                                    $set('medicine_id', $purchaseItem->medicine_id);
                                    $set('batch_number', $purchaseItem->batch_number);
                                    $set('purchase_price', $purchaseItem->purchase_price);

                                    $batch = \App\Models\MedicineBatch::where('medicine_id', $purchaseItem->medicine_id)
                                        ->where('batch_number', $purchaseItem->batch_number)
                                        ->first();

                                    $set('medicine_batch_id', $batch?->id);
                                }
                            }),

                        Select::make('medicine_id')
                            ->label('Medicine')
                            ->options(function (callable $get) {
                                $purchaseId = $get('purchase_id');

                                if (blank($purchaseId)) {
                                    return [];
                                }

                                return \App\Models\PurchaseItem::where('purchase_id', $purchaseId)
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
                                $purchaseId = $get('purchase_id');

                                if (blank($state) || blank($purchaseId)) {
                                    return;
                                }

                                $purchaseItem = \App\Models\PurchaseItem::where('purchase_id', $purchaseId)
                                    ->where('medicine_id', $state)
                                    ->first();

                                if (! $purchaseItem) {
                                    return;
                                }

                                $set('batch_number', $purchaseItem->batch_number);
                                $set('purchase_price', $purchaseItem->purchase_price);

                                $batch = \App\Models\MedicineBatch::where('medicine_id', $state)
                                    ->where('batch_number', $purchaseItem->batch_number)
                                    ->first();

                                $set('medicine_batch_id', $batch?->id);
                                $set('quantity', null);
                                $set('refund_amount', 0);
                            }),

                       Hidden::make('medicine_batch_id')
    ->dehydrated(),

TextInput::make('batch_number')
    ->label('Batch Number')
    ->required()
    ->readOnly(),
                    ])
                    
->columns(3),

                Section::make('Quantity & Refund')
                    ->description('How much is being returned and the refund amount')
                    ->icon(Heroicon::OutlinedCalculator)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('quantity')
                            ->label('Quantity')
                            ->numeric()
                            ->required()
                            ->live(onBlur: true)
                            ->helperText(function (callable $get) {
                                $batchId = $get('medicine_batch_id');

                                if (blank($batchId)) {
                                    return null;
                                }

                                $batch = \App\Models\MedicineBatch::find($batchId);

                                return $batch ? "Available in stock: {$batch->quantity}" : null;
                            })
                            ->rules([
                                function (callable $get) {
                                    return function (string $attribute, $value, \Closure $fail) use ($get) {
                                        $batchId = $get('medicine_batch_id');

                                        if (blank($batchId)) {
                                            return;
                                        }

                                        $batch = \App\Models\MedicineBatch::find($batchId);

                                        if ($batch && $value > $batch->quantity) {
                                            $fail("Cannot return more than the available stock ({$batch->quantity}).");
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
                            ->label('Purchase Price')
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
                    ])
                    ->columns(4),

                Section::make('Reason')
                    ->description('Why this item is being returned')
                    ->icon(Heroicon::OutlinedExclamationTriangle)
                    ->columnSpanFull()
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
                    ])
                    ->columns(2),
            ]);
    }

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