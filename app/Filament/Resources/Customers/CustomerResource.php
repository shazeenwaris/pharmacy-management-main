<?php

namespace App\Filament\Resources\Customers;

use App\Filament\Resources\Customers\Pages\ListCustomers;
use App\Filament\Resources\Customers\Pages\CreateCustomer;
use App\Filament\Resources\Customers\Pages\EditCustomer;
use App\Models\Customer;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $navigationLabel = 'Customers';

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
                Section::make('Customer Profile')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->required()
                            ->tel(),
                        TextInput::make('email')
                            ->email(),
                        DatePicker::make('date_of_birth'),
                        TextInput::make('address')
                            ->columnSpanFull(),
                    ])
                    ->columns(3),

                Section::make('Credit Settings')
                    ->schema([
                        TextInput::make('opening_balance')
                            ->numeric()
                            ->prefix(fn () => currency_symbol())
                            ->default(0),
                        TextInput::make('credit_limit')
                            ->numeric()
                            ->prefix(fn () => currency_symbol())
                            ->default(0),
                        Toggle::make('status')
                            ->default(true),
                    ])
                    ->columns(3),

                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('sales_count')
                    ->counts('sales')
                    ->label('Total Purchases'),
                TextColumn::make('due_balance')
                    ->label('Due Balance')
                    ->formatStateUsing(fn ($state) => format_currency($state))
                    ->color(fn ($state) => $state > 0 ? 'danger' : 'success'),
                IconColumn::make('status')
                    ->boolean(),
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
            ->defaultSort('name', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomers::route('/'),
            'create' => CreateCustomer::route('/create'),
            'edit' => EditCustomer::route('/{record}/edit'),
        ];
    }
}