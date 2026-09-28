<?php

namespace App\Filament\Pharmacist\Resources\Customers;

use App\Filament\Pharmacist\Resources\Customers\Pages\ListCustomers;
use App\Filament\Pharmacist\Resources\Customers\Pages\CreateCustomer;
use App\Filament\Pharmacist\Resources\Customers\Pages\EditCustomer;
use App\Filament\Pharmacist\Resources\Customers\Pages\ViewCustomer;
use App\Models\Customer;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
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

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Customer Profile')
                    ->description('Basic contact information for this customer')
                    ->icon(Heroicon::OutlinedUser)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->label('Phone')
                            ->required()
                            ->tel(),
                        TextInput::make('email')
                            ->label('Email')
                            ->email(),
                        DatePicker::make('date_of_birth')
                            ->label('Date of Birth'),
                        TextInput::make('address')
                            ->label('Address')
                            ->columnSpanFull(),
                    ])
                    ->columns(4),

                Section::make('Credit Settings')
                    ->description('Opening balance and credit limit for this customer')
                    ->icon(Heroicon::OutlinedCalculator)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('opening_balance')
                            ->label('Opening Balance')
                            ->numeric()
                            ->prefix(fn () => currency_symbol())
                            ->default(0),
                        TextInput::make('credit_limit')
                            ->label('Credit Limit')
                            ->numeric()
                            ->prefix(fn () => currency_symbol())
                            ->default(0),
                        Toggle::make('status')
                            ->label('Active')
                            ->default(true),
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
    ViewAction::make()
        ->label('View')
        ->color('gray'),
    EditAction::make()
        ->label('Edit')
        ->color('primary'),
    DeleteAction::make()
        ->label('Delete')
        ->color('danger'),
])
  ->recordActionsColumnLabel('Actions')
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
            'view' => ViewCustomer::route('/{record}'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'phone', 'email'];
    }

    public static function getGlobalSearchResultTitle(\Illuminate\Database\Eloquent\Model $record): string
    {
        return $record->name;
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            'Phone' => $record->phone,
        ];
    }
}