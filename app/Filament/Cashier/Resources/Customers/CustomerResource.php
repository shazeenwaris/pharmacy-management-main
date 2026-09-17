<?php

namespace App\Filament\Cashier\Resources\Customers;

use App\Filament\Cashier\Resources\Customers\Pages\ListCustomers;
use App\Filament\Cashier\Resources\Customers\Pages\ViewCustomer;
use App\Filament\Cashier\Resources\Customers\Tables\CustomersTable;
use App\Models\Customer;
use BackedEnum;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedUsers;

    protected static ?string $navigationLabel = 'Customers';

    protected static ?string $modelLabel = 'Customer';

    protected static ?string $pluralModelLabel = 'Customers';

    protected static ?string $recordTitleAttribute = 'name';

    /*
    |--------------------------------------------------------------------------
    | FORM
    |--------------------------------------------------------------------------
    */

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    public static function table(Table $table): Table
    {
        return CustomersTable::configure($table);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public static function getRelations(): array
    {
        return [];
    }

    /*
    |--------------------------------------------------------------------------
    | PAGES
    |--------------------------------------------------------------------------
    */

    public static function getPages(): array
    {
        return [
            'index' => ListCustomers::route('/'),
            'view' => ViewCustomer::route('/{record}'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | CASHIER = READ ONLY
    |--------------------------------------------------------------------------
    */

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | CUSTOMER DETAILS
    |--------------------------------------------------------------------------
    */

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([

                /*
                |--------------------------------------------------------------------------
                | CUSTOMER PROFILE
                |--------------------------------------------------------------------------
                */

                Section::make('Customer Profile')
                    ->description(
                        'Basic customer contact and personal information'
                    )
                    ->icon(Heroicon::OutlinedUserCircle)
                    ->schema([

                        TextEntry::make('name')
                            ->label('Customer Name')
                            ->icon(Heroicon::OutlinedUser)
                            ->weight('bold')
                            ->color('primary'),

                        TextEntry::make('phone')
                            ->label('Phone Number')
                            ->icon(Heroicon::OutlinedPhone)
                            ->placeholder('Not provided'),

                        TextEntry::make('email')
                            ->label('Email Address')
                            ->icon(Heroicon::OutlinedEnvelope)
                            ->placeholder('Not provided'),

                        TextEntry::make('date_of_birth')
                            ->label('Date of Birth')
                            ->icon(Heroicon::OutlinedCalendarDays)
                            ->date('d M Y')
                            ->placeholder('Not provided'),

                        TextEntry::make('address')
                            ->label('Address')
                            ->icon(Heroicon::OutlinedMapPin)
                            ->placeholder('Not provided')
                            ->columnSpanFull(),

                    ])
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                    ])
                    ->columnSpanFull()
                    ->extraAttributes([
                        'class' => 'pms-customer-section',
                    ]),

                /*
                |--------------------------------------------------------------------------
                | ACCOUNT SUMMARY
                |--------------------------------------------------------------------------
                */

                Section::make('Account Summary')
                    ->description(
                        'Customer credit, opening balance and current outstanding amount'
                    )
                    ->icon(Heroicon::OutlinedWallet)
                    ->schema([

                        TextEntry::make('opening_balance')
                            ->label('Opening Balance')
                            ->icon(Heroicon::OutlinedBanknotes)
                            ->formatStateUsing(
                                fn ($state) => format_currency($state)
                            )
                            ->weight('bold'),

                        TextEntry::make('credit_limit')
                            ->label('Credit Limit')
                            ->icon(Heroicon::OutlinedCreditCard)
                            ->formatStateUsing(
                                fn ($state) => format_currency($state)
                            )
                            ->weight('bold'),

                        TextEntry::make('due_balance')
                            ->label('Due Balance')
                            ->icon(Heroicon::OutlinedExclamationTriangle)
                            ->formatStateUsing(
                                fn ($state) => format_currency($state)
                            )
                            ->weight('bold')
                            ->color(
                                fn ($state) =>
                                    $state > 0
                                        ? 'danger'
                                        : 'success'
                            ),

                        TextEntry::make('status')
                            ->label('Account Status')
                            ->icon(Heroicon::OutlinedCheckCircle)
                            ->badge()
                            ->formatStateUsing(
                                fn ($state) =>
                                    $state
                                        ? 'Active'
                                        : 'Inactive'
                            )
                            ->color(
                                fn ($state) =>
                                    $state
                                        ? 'success'
                                        : 'danger'
                            ),

                    ])
                    ->columns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 4,
                    ])
                    ->columnSpanFull()
                    ->extraAttributes([
                        'class' => 'pms-customer-section',
                    ]),

                /*
                |--------------------------------------------------------------------------
                | SALES SUMMARY
                |--------------------------------------------------------------------------
                */

                Section::make('Sales Summary')
                    ->description(
                        'Customer purchase and payment history'
                    )
                    ->icon(Heroicon::OutlinedShoppingCart)
                    ->schema([

                        /*
                        |--------------------------------------------------------------------------
                        | TOTAL PURCHASES
                        |--------------------------------------------------------------------------
                        */

                        TextEntry::make('total_purchases')
                            ->label('Total Purchases')
                            ->icon(Heroicon::OutlinedShoppingBag)
                            ->state(function ($record) {
                                return $record
                                    ->sales()
                                    ->count();
                            })
                            ->badge()
                            ->color('primary'),

                        /*
                        |--------------------------------------------------------------------------
                        | TOTAL SALES
                        |--------------------------------------------------------------------------
                        */

                        TextEntry::make('total_sales_amount')
                            ->label('Total Sales')
                            ->icon(Heroicon::OutlinedCurrencyDollar)
                            ->state(function ($record) {
                                return $record
                                    ->sales()
                                    ->sum('grand_total');
                            })
                            ->formatStateUsing(
                                fn ($state) =>
                                    format_currency($state)
                            )
                            ->weight('bold'),

                        /*
                        |--------------------------------------------------------------------------
                        | TOTAL PAID
                        |--------------------------------------------------------------------------
                        */

                        TextEntry::make('total_paid')
                            ->label('Total Paid')
                            ->icon(Heroicon::OutlinedCheckBadge)
                            ->state(function ($record) {
                                return $record
                                    ->sales()
                                    ->sum('paid_amount');
                            })
                            ->formatStateUsing(
                                fn ($state) =>
                                    format_currency($state)
                            )
                            ->color('success')
                            ->weight('bold'),

                        /*
                        |--------------------------------------------------------------------------
                        | TOTAL DUE
                        |--------------------------------------------------------------------------
                        */

                        TextEntry::make('total_due')
                            ->label('Total Due')
                            ->icon(Heroicon::OutlinedExclamationCircle)
                            ->state(function ($record) {
                                return $record->due_balance;
                            })
                            ->formatStateUsing(
                                fn ($state) =>
                                    format_currency($state)
                            )
                            ->color(
                                fn ($state) =>
                                    $state > 0
                                        ? 'danger'
                                        : 'success'
                            )
                            ->weight('bold'),

                        /*
                        |--------------------------------------------------------------------------
                        | LAST PURCHASE
                        |--------------------------------------------------------------------------
                        */

                        TextEntry::make('last_purchase')
                            ->label('Last Purchase')
                            ->icon(Heroicon::OutlinedClock)
                            ->state(function ($record) {
                                return $record
                                    ->sales()
                                    ->latest('created_at')
                                    ->value('created_at');
                            })
                            ->dateTime('d M Y, h:i A')
                            ->placeholder('No purchases yet'),

                    ])
                    ->columns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 3,
                    ])
                    ->columnSpanFull()
                    ->extraAttributes([
                        'class' => 'pms-customer-section',
                    ]),
            ]);
    }
}