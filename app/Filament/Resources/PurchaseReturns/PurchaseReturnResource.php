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
                            ->required(),
                        Select::make('medicine_id')
                            ->label('Medicine')
                            ->relationship('medicine', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('medicine_batch_id')
                            ->label('Batch')
                            ->relationship('batch', 'batch_number')
                            ->searchable()
                            ->preload(),
                        TextInput::make('batch_number')
                            ->label('Batch Number')
                            ->required(),
                    ])
                    ->columns(4),

                Section::make('Quantity & Refund')
                    ->description('How much is being returned and the refund amount')
                    ->icon(Heroicon::OutlinedCalculator)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('quantity')
                            ->label('Quantity')
                            ->numeric()
                            ->required(),
                        TextInput::make('purchase_price')
                            ->label('Purchase Price')
                            ->numeric()
                            ->prefix(fn () => currency_symbol())
                            ->required(),
                        TextInput::make('refund_amount')
                            ->label('Refund Amount')
                            ->numeric()
                            ->prefix(fn () => currency_symbol())
                            ->default(0),
                        DatePicker::make('return_date')
                            ->label('Return Date')
                            ->required()
                            ->default(now()),
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