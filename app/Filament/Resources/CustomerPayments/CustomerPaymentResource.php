<?php

namespace App\Filament\Resources\CustomerPayments;

use App\Filament\Resources\CustomerPayments\Pages\ListCustomerPayments;
use App\Filament\Resources\CustomerPayments\Pages\CreateCustomerPayment;
use App\Filament\Resources\CustomerPayments\Pages\EditCustomerPayment;
use App\Models\CustomerPayment;
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

class CustomerPaymentResource extends Resource
{
    protected static ?string $model = CustomerPayment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?string $navigationLabel = 'Customer Payments';

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('manage customers') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('manage customers') ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->can('manage customers') ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->can('manage customers') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Payment Details')
                    ->schema([
                        Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        DatePicker::make('payment_date')
                            ->required()
                            ->default(now()),
                        TextInput::make('amount')
                            ->numeric()
                            ->prefix(fn () => currency_symbol())
                            ->required(),
                        Select::make('method')
                            ->options([
                                'cash' => 'Cash',
                                'card' => 'Card',
                                'bank' => 'Bank Transfer',
                                'other' => 'Other',
                            ])
                            ->default('cash')
                            ->required(),
                        TextInput::make('reference'),
                    ])
                    ->columns(2),

                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('payment_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('amount')
                    ->formatStateUsing(fn ($state) => format_currency($state))
                    ->sortable(),
                TextColumn::make('method')
                    ->badge(),
                TextColumn::make('reference'),
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
            ->defaultSort('payment_date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomerPayments::route('/'),
            'create' => CreateCustomerPayment::route('/create'),
            'edit' => EditCustomerPayment::route('/{record}/edit'),
        ];
    }
}