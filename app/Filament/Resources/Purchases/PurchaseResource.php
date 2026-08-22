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
                    ->schema([
                        Select::make('supplier_id')
                            ->relationship('supplier', 'supplier_name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('invoice_number')
                            ->required()
                            ->unique(ignoreRecord: true),
                        DatePicker::make('purchase_date')
                            ->required()
                            ->default(now()),
                        Select::make('status')
                            ->options([
                                'completed' => 'Completed',
                                'pending' => 'Pending',
                            ])
                            ->default('completed')
                            ->required(),
                    ])
                    ->columns(4),

                Section::make('Items')
                    ->schema([
                        Repeater::make('items')
                            ->relationship('items')
                            ->schema([
                                Select::make('medicine_id')
                                    ->label('Medicine')
                                    ->relationship('medicine', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                TextInput::make('batch_number')
                                    ->required(),
                                DatePicker::make('manufacturing_date'),
                                DatePicker::make('expiry_date')
                                    ->required(),
                                TextInput::make('quantity')
                                    ->numeric()
                                    ->required(),
                                TextInput::make('purchase_price')
                                    ->numeric()
                                    ->prefix(fn () => currency_symbol())
                                    ->required(),
                                TextInput::make('sale_price')
                                    ->numeric()
                                    ->prefix(fn () => currency_symbol())
                                    ->required(),
                                TextInput::make('discount_percent')
                                    ->numeric()
                                    ->suffix('%')
                                    ->default(0),
                                TextInput::make('tax_percent')
                                    ->numeric()
                                    ->suffix('%')
                                    ->default(0),
                                TextInput::make('total_amount')
                                    ->numeric()
                                    ->prefix(fn () => currency_symbol())
                                    ->required(),
                            ])
                            ->columns(4)
                            ->columnSpanFull(),
                    ]),

                Section::make('Totals')
                    ->schema([
                        TextInput::make('subtotal')->numeric()->prefix(fn () => currency_symbol())->default(0),
                        TextInput::make('discount_amount')->numeric()->prefix(fn () => currency_symbol())->default(0),
                        TextInput::make('tax_amount')->numeric()->prefix(fn () => currency_symbol())->default(0),
                        TextInput::make('total_amount')->numeric()->prefix(fn () => currency_symbol())->default(0)->required(),
                    ])
                    ->columns(4),

                Textarea::make('notes')->columnSpanFull(),
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