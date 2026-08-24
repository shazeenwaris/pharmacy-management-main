<?php

namespace App\Filament\Resources\FinancialTransactions\Schemas;

use App\Models\Account;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;

use Filament\Schemas\Schema;

class FinancialTransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                /*
                |--------------------------------------------------------------------------
                | Account
                |--------------------------------------------------------------------------
                */

                Select::make('account_id')
                    ->label('Account')
                    ->relationship(
                        name: 'account',
                        titleAttribute: 'account_name',
                        modifyQueryUsing: fn ($query) =>
                            $query
                                ->where('status', 'active')
                                ->orderBy('account_name')
                    )
                    ->searchable()
                    ->preload()
                    ->required()
                    ->placeholder('Select account'),

                /*
                |--------------------------------------------------------------------------
                | Transaction Date
                |--------------------------------------------------------------------------
                */

                DatePicker::make('transaction_date')
                    ->label('Transaction Date')
                    ->default(now())
                    ->required(),

                /*
                |--------------------------------------------------------------------------
                | Transaction Type
                |--------------------------------------------------------------------------
                */

                Select::make('transaction_type')
                    ->label('Transaction Type')
                    ->options([
                        'income' => 'Income',
                        'expense' => 'Expense',
                        'transfer' => 'Transfer',
                        'adjustment' => 'Adjustment',
                    ])
                    ->native(false)
                    ->required(),

                /*
                |--------------------------------------------------------------------------
                | Amount
                |--------------------------------------------------------------------------
                */

                TextInput::make('amount')
                    ->label('Amount')
                    ->numeric()
                    ->prefix('PKR')
                    ->minValue(0.01)
                    ->required(),

                /*
                |--------------------------------------------------------------------------
                | Payment Method
                |--------------------------------------------------------------------------
                */

                Select::make('payment_method')
                    ->label('Payment Method')
                    ->options([
                        'cash' => 'Cash',
                        'bank' => 'Bank',
                        'card' => 'Card',
                        'online' => 'Online',
                        'credit' => 'Credit',
                        'other' => 'Other',
                    ])
                    ->native(false)
                    ->searchable()
                    ->placeholder('Select payment method'),

                /*
                |--------------------------------------------------------------------------
                | Reference Type
                |--------------------------------------------------------------------------
                */

                Select::make('reference_type')
                    ->label('Reference Type')
                    ->options([
                        'sale' => 'Sale',
                        'purchase' => 'Purchase',
                        'supplier_payment' => 'Supplier Payment',
                        'customer_payment' => 'Customer Payment',
                        'expense' => 'Expense',
                        'manual' => 'Manual',
                    ])
                    ->native(false)
                    ->searchable()
                    ->live()
                    ->placeholder('Select reference type')

                    ->afterStateUpdated(function (?string $state, callable $set): void {

    if (in_array($state, ['expense', 'manual'], true)) {
        return;
    }

    if (! $state) {
        return;
    }

    $label = match ($state) {
        'sale' => 'Sale',
        'purchase' => 'Purchase',
        'supplier_payment' => 'Supplier Payment',
        'customer_payment' => 'Customer Payment',
        default => ucfirst($state),
    };

    $set('reference_type', null);
    $set('reference_id', null);

    Notification::make()
    ->warning()
    ->title('Reference Not Available')
    ->body("{$label} reference is not available yet.")
    ->duration(5000)
    ->send();
}),
                /*
                |--------------------------------------------------------------------------
                | Reference ID
                |--------------------------------------------------------------------------
                */

                TextInput::make('reference_id')
                    ->label('Reference ID')
                    ->numeric()
                    ->nullable()
                    ->helperText(
                        'Enter the ID of the related record.'
                    )
                    ->visible(
                        fn ($get): bool =>
                            in_array(
                                $get('reference_type'),
                                ['expense', 'manual'],
                                true
                            )
                    ),

                /*
                |--------------------------------------------------------------------------
                | Description
                |--------------------------------------------------------------------------
                */

                Textarea::make('description')
                    ->label('Description')
                    ->rows(3)
                    ->nullable()
                    ->columnSpanFull(),

            ]);
    }
}