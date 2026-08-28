<?php

namespace App\Filament\Resources\Purchases;

use App\Filament\Resources\Purchases\Pages\ListPurchases;
use App\Filament\Resources\Purchases\Pages\CreatePurchase;
use App\Filament\Resources\Purchases\Pages\EditPurchase;
use App\Models\Purchase;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
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
        Select::make('status')
            ->options([
                'completed' => 'Completed',
                'pending' => 'Pending',
            ])
            ->default('completed')
            ->required()
            ->native(false),
    ])
    ->columns(4),

Section::make('Items')
    ->description('Medicines included in this purchase')
    ->icon(Heroicon::OutlinedBeaker)
    ->columnSpanFull()
    ->schema([
        Repeater::make('items')
            ->relationship('items')
            ->label('')
            ->addActionLabel('Add Item')
            ->itemLabel(fn (array $state): ?string => $state['batch_number'] ?? null)
            ->collapsible()
            ->schema([
                Grid::make(4)
                    ->schema([
                        Select::make('medicine_id')
                            ->label('Medicine')
                            ->relationship('medicine', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(2),
                        TextInput::make('batch_number')
                            ->label('Batch Number')
                            ->required(),
                        TextInput::make('quantity')
                            ->label('Quantity')
                            ->numeric()
                            ->required(),
                    ]),
                Grid::make(4)
                    ->schema([
                        DatePicker::make('manufacturing_date')
                            ->label('Manufacturing Date'),
                        DatePicker::make('expiry_date')
                            ->label('Expiry Date')
                            ->required(),
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
                    ]),
                Grid::make(3)
                    ->schema([
                        TextInput::make('discount_percent')
                            ->label('Discount Percent')
                            ->numeric()
                            ->suffix('%')
                            ->default(0),
                        TextInput::make('tax_percent')
                            ->label('Tax Percent')
                            ->numeric()
                            ->suffix('%')
                            ->default(0),
                        TextInput::make('total_amount')
                            ->label('Total Amount')
                            ->numeric()
                            ->prefix(fn () => currency_symbol())
                            ->required(),
                    ]),
            ])
            ->columnSpanFull(),
    ]),

                Section::make('Totals')
                    ->description('Overall amounts for this purchase invoice')
                    ->icon(Heroicon::OutlinedCalculator)
                    ->schema([
                        TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->numeric()
                            ->prefix(fn () => currency_symbol())
                            ->default(0),
                        TextInput::make('discount_amount')
                            ->label('Discount Amount')
                            ->numeric()
                            ->prefix(fn () => currency_symbol())
                            ->default(0),
                        TextInput::make('tax_amount')
                            ->label('Tax Amount')
                            ->numeric()
                            ->prefix(fn () => currency_symbol())
                            ->default(0),
                        TextInput::make('total_amount')
                            ->label('Total Amount')
                            ->numeric()
                            ->prefix(fn () => currency_symbol())
                            ->default(0)
                            ->required(),
                    ])
                    ->columns(4),

                Section::make('Notes')
                    ->schema([
                        Textarea::make('notes')
                            ->label('')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')->searchable()->sortable(),
                TextColumn::make('supplier.supplier_name')->label('Supplier')->searchable(),
                TextColumn::make('purchase_date')->date()->sortable(),
                TextColumn::make('total_amount')->formatStateUsing(fn ($state) => format_currency($state))->sortable(),
                TextColumn::make('status')->badge(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
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