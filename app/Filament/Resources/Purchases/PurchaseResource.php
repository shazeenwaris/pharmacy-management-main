<?php

namespace App\Filament\Resources\Purchases;

use App\Filament\Resources\Purchases\Pages\CreatePurchase;
use App\Filament\Resources\Purchases\Pages\EditPurchase;
use App\Filament\Resources\Purchases\Pages\ListPurchases;
use App\Models\Purchase;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PurchaseResource extends Resource
{
    protected static ?string $model = Purchase::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingCart;

    protected static ?string $navigationLabel = 'Purchases';

    protected static string|\UnitEnum|null $navigationGroup = 'Purchases';

    protected static ?int $navigationSort = 2;

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
        return auth()->user()?->can('manage purchases') ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->can('manage purchases') ?? false;
    }

    /*
    |--------------------------------------------------------------------------
    | Calculations
    |--------------------------------------------------------------------------
    */

    protected static function calculateQuantity(callable $get): int
    {
        // Quantity = boxes received (strips/tablets are no longer used)
        return (int) ($get('boxes_received') ?? 0);
    }

    protected static function calculateLineTotal(callable $get): float
    {
        $boxes = (float) ($get('boxes_received') ?? 0);
        $price = (float) ($get('purchase_price') ?? 0);
        $discount = (float) ($get('discount_percent') ?? 0);
        $tax = (float) ($get('tax_percent') ?? 0);

        $lineSubtotal = $boxes * $price;
        $lineDiscount = $lineSubtotal * ($discount / 100);
        $lineTax = ($lineSubtotal - $lineDiscount) * ($tax / 100);

        return round($lineSubtotal - $lineDiscount + $lineTax, 2);
    }

    /*
    |--------------------------------------------------------------------------
    | Form
    |--------------------------------------------------------------------------
    */

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Purchase Details')
                    ->description('Basic information about this purchase invoice')
                    ->icon(Heroicon::OutlinedDocumentText)
                    ->columnSpanFull()
                    ->schema([
                        Select::make('supplier_id')
                            ->label('Supplier')
                            ->relationship('supplier', 'supplier_name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('invoice_number')
                            ->label('Invoice Number')
                            ->required()
                            ->unique(ignoreRecord: true),
                        DatePicker::make('purchase_date')
                            ->label('Purchase Date')
                            ->required()
                            ->default(now()),
                    ])
                    ->columns(3),

                Section::make('Items')
                    ->description('Medicines included in this purchase')
                    ->icon(Heroicon::OutlinedBeaker)
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('items')
                            ->relationship('items')
                            ->label('')
                            ->addActionLabel('Add Item')
                            ->itemLabel(function (array $state) {
                                if (blank($state['medicine_id'] ?? null)) {
                                    return null;
                                }

                                return \App\Models\Medicine::find($state['medicine_id'])?->name;
                            })
                            ->collapsible()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $subtotal = 0;
                                $grandTotal = 0;

                                foreach ((array) $state as $item) {
                                    $boxes = (float) ($item['boxes_received'] ?? 0);
                                    $price = (float) ($item['purchase_price'] ?? 0);
                                    $discount = (float) ($item['discount_percent'] ?? 0);
                                    $tax = (float) ($item['tax_percent'] ?? 0);

                                    $lineSubtotal = $boxes * $price;
                                    $lineDiscount = $lineSubtotal * ($discount / 100);
                                    $lineTax = ($lineSubtotal - $lineDiscount) * ($tax / 100);

                                    $subtotal += ($lineSubtotal - $lineDiscount);
                                    $grandTotal += ($lineSubtotal - $lineDiscount + $lineTax);
                                }

                                $set('subtotal', round($subtotal, 2));
                                $set('total_amount', round($grandTotal, 2));
                            })
                            ->schema([
                                Grid::make(4)
                                    ->schema([
                                        // Row 1
                                        Select::make('medicine_id')
                                            ->label('Medicine')
                                            ->relationship('medicine', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->live()
                                            ->columnSpan(2)
                                            ->afterStateUpdated(function ($state, callable $set) {
                                                if (blank($state)) {
                                                    return;
                                                }

                                                $medicine = \App\Models\Medicine::find($state);

                                                if (! $medicine) {
                                                    return;
                                                }

                                                $set('purchase_price', $medicine->purchase_price ?: 0);
                                                $set('sale_price', $medicine->sale_price ?: 0);
                                                $set('boxes_received', 0);
                                                $set('quantity', 0);
                                                $set('total_amount', 0);
                                            }),

                                        TextInput::make('boxes_received')
                                            ->label('Boxes')
                                            ->numeric()
                                            ->minValue(0)
                                            ->default(0)
                                            ->suffix('Box')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function ($set, callable $get) {
                                                $set('quantity', self::calculateQuantity($get));
                                                $set('total_amount', self::calculateLineTotal($get));
                                            }),

                                        TextInput::make('quantity')
                                            ->label('Total Qty')
                                            ->numeric()
                                            ->required()
                                            ->default(0)
                                            ->readOnly(),

                                        // Row 2
                                        TextInput::make('purchase_price')
                                            ->label('Purchase Price')
                                            ->numeric()
                                            ->prefix(fn () => currency_symbol())
                                            ->required()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function ($set, callable $get) {
                                                $set('total_amount', self::calculateLineTotal($get));
                                            }),

                                        TextInput::make('sale_price')
                                            ->label('Sale Price')
                                            ->numeric()
                                            ->prefix(fn () => currency_symbol()),

                                        TextInput::make('discount_percent')
                                            ->label('Discount')
                                            ->numeric()
                                            ->suffix('%')
                                            ->default(0)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function ($set, callable $get) {
                                                $set('total_amount', self::calculateLineTotal($get));
                                            }),

                                        TextInput::make('tax_percent')
                                            ->label('Tax')
                                            ->numeric()
                                            ->suffix('%')
                                            ->default(0)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function ($set, callable $get) {
                                                $set('total_amount', self::calculateLineTotal($get));
                                            }),

                                        // Row 3
                                        DatePicker::make('expiry_date')
                                            ->label('Expiry Date')
                                            ->minDate(now()->addDay())
                                            ->columnSpan(2),

                                        TextInput::make('total_amount')
                                            ->label('Line Total')
                                            ->numeric()
                                            ->prefix(fn () => currency_symbol())
                                            ->required()
                                            ->readOnly()
                                            ->columnSpan(2),

                                        // Kept so database columns always get a value
                                        Hidden::make('strips_per_box')->default(1),
                                        Hidden::make('units_per_strip')->default(1),
                                    ]),
                            ])
                            ->columnSpanFull(),
                    ]),

                Hidden::make('subtotal')->default(0),
                Hidden::make('discount_amount')->default(0),
                Hidden::make('tax_amount')->default(0),
                Hidden::make('total_amount')->default(0)->live(),
                Hidden::make('status')->default('completed'),

                Section::make('Payment')
                    ->description('Payment details for this purchase')
                    ->icon(Heroicon::OutlinedBanknotes)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('paid_amount')
                            ->label('Paid Amount')
                            ->numeric()
                            ->prefix(fn () => currency_symbol())
                            ->required()
                            ->live(onBlur: true)
                            ->rule(function (callable $get) {
                                return function (string $attribute, $value, \Closure $fail) use ($get) {
                                    $total = (float) ($get('total_amount') ?? 0);

                                    if ((float) $value > $total) {
                                        $fail("Paid Amount cannot be greater than Total Amount ({$total}).");
                                    }
                                };
                            }),
                        Select::make('payment_method')
                            ->label('Payment Method')
                            ->options([
                                'cash' => 'Cash',
                                'bank' => 'Bank',
                                'card' => 'Card',
                                'online' => 'Online',
                                'other' => 'Other',
                            ])
                            ->default('cash')
                            ->required()
                            ->native(false),
                        Placeholder::make('total_summary')
                            ->label('')
                            ->columnSpan(1)
                            ->content(fn (callable $get) => new \Illuminate\Support\HtmlString(
                                '<div style="font-size:13px; line-height:1.9;">'
                                . '<div style="display:flex; justify-content:space-between;"><span>Subtotal</span><strong>' . format_currency($get('subtotal') ?? 0) . '</strong></div>'
                                . '<div style="display:flex; justify-content:space-between;"><span>Total</span><strong>' . format_currency($get('total_amount') ?? 0) . '</strong></div>'
                                . '</div>'
                            )),
                    ])
                    ->columns(3),

                Section::make('Notes')
                    ->columnSpanFull()
                    ->schema([
                        Textarea::make('notes')
                            ->label('')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')
                    ->label('Invoice')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('supplier.supplier_name')
                    ->label('Supplier')
                    ->searchable(),

                TextColumn::make('items')
                    ->label('Medicine')
                    ->getStateUsing(function ($record) {
                        if ($record->items->isEmpty()) {
                            return '—';
                        }

                        return $record->items->map(function ($item) {
                            $name = e($item->medicine->name ?? 'N/A');
                            $qty = e($item->quantity ?? 0);

                            return "<div style='margin-bottom:4px;'><span style='font-weight:600;'>{$name}</span><br><span style='font-size:11px;color:#718792;'>Qty: {$qty}</span></div>";
                        })->implode('');
                    })
                    ->html()
                    ->wrap(),

                TextColumn::make('purchase_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),

                TextColumn::make('total_amount')
                    ->label('Total')
                    ->formatStateUsing(fn ($state) => format_currency($state))
                    ->sortable(),

                TextColumn::make('paid_amount')
                    ->label('Paid')
                    ->formatStateUsing(fn ($state) => format_currency($state))
                    ->sortable(),

                TextColumn::make('payment_method')
                    ->label('Payment')
                    ->badge(),

                TextColumn::make('payment_status')
                    ->label('Payment Status')
                    ->getStateUsing(function ($record) {
                        $paid = (float) $record->paid_amount;
                        $total = (float) $record->total_amount;

                        if ($paid <= 0) {
                            return 'Pending';
                        }

                        if ($paid < $total) {
                            return 'Partial';
                        }

                        return 'Paid';
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pending' => 'danger',
                        'Partial' => 'warning',
                        'Paid' => 'success',
                        default => 'gray',
                    }),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('addPayment')
                        ->label('Add Payment')
                        ->icon('heroicon-o-banknotes')
                        ->color('success')
                        ->modalHeading('Add Payment')
                        ->modalSubmitActionLabel('Save Payment')
                        ->form(function ($record) {
                            $remaining = round((float) $record->total_amount - (float) $record->paid_amount, 2);

                            return [
                                DatePicker::make('payment_date')
                                    ->label('Payment Date')
                                    ->required()
                                    ->default(now()),

                                Placeholder::make('remaining')
                                    ->label('Remaining')
                                    ->content(format_currency($remaining)),

                                TextInput::make('amount')
                                    ->label('Payment Amount')
                                    ->numeric()
                                    ->prefix(fn () => currency_symbol())
                                    ->required()
                                    ->rule(function () use ($remaining) {
                                        return function (string $attribute, $value, \Closure $fail) use ($remaining) {
                                            if ((float) $value > $remaining) {
                                                $fail("Payment Amount cannot be greater than Remaining ({$remaining}).");
                                            }
                                            if ((float) $value <= 0) {
                                                $fail('Payment Amount must be greater than 0.');
                                            }
                                        };
                                    }),

                                Select::make('payment_method')
                                    ->label('Payment Method')
                                    ->options([
                                        'cash' => 'Cash',
                                        'bank' => 'Bank',
                                        'card' => 'Card',
                                        'online' => 'Online',
                                        'other' => 'Other',
                                    ])
                                    ->default('cash')
                                    ->required()
                                    ->native(false),

                                Select::make('account_id')
                                    ->label('Paid From')
                                    ->options(fn () => \App\Models\Account::pluck('account_name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                TextInput::make('reference')
                                    ->label('Reference'),

                                Textarea::make('notes')
                                    ->label('Notes')
                                    ->rows(2),
                            ];
                        })
                        ->action(function (array $data, $record) {
                            $record->payments()->create($data);

                            $record->update([
                                'paid_amount' => $record->payments()->sum('amount'),
                            ]);

                            \App\Models\FinancialTransaction::create([
                                'account_id'        => $data['account_id'],
                                'transaction_type'  => 'expense',
                                'amount'            => $data['amount'],
                                'transaction_date'  => $data['payment_date'],
                                'payment_method'    => $data['payment_method'],
                                'description'       => 'Payment for Purchase #' . $record->invoice_number,
                                'reference_type'    => 'purchase',
                                'reference_id'      => $record->id,
                            ]);
                        })
                        ->visible(fn ($record) => (float) $record->paid_amount < (float) $record->total_amount),

                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->color('gray')
                    ->size('sm'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->recordActionsColumnLabel('Actions')
            ->recordActionsAlignment('center')
            ->defaultSort('purchase_date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPurchases::route('/'),
            'create' => CreatePurchase::route('/create'),
            'edit' => EditPurchase::route('/{record}/edit'),
        ];
    }
}