<?php

namespace App\Filament\Resources\Expenses\Schemas;

use App\Models\Account;
use App\Models\ExpenseCategory;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ExpenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                /*
                |--------------------------------------------------------------------------
                | Expense Date
                |--------------------------------------------------------------------------
                */
                DatePicker::make('expense_date')
                    ->label('Expense Date')
                    ->default(now())
                    ->required(),

                /*
                |--------------------------------------------------------------------------
                | Expense Category
                |--------------------------------------------------------------------------
                */
                Select::make('expense_category_id')
                    ->label('Expense Category')
                    ->options(
                        ExpenseCategory::query()
                            ->orderBy('name')
                            ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->preload()
                    ->required()
                    ->placeholder('Select expense category'),

                /*
                |--------------------------------------------------------------------------
                | Account
                |--------------------------------------------------------------------------
                */
                Select::make('account_id')
                    ->label('Account')
                    ->options(
                        Account::query()
                            ->where('status', 'active')
                            ->orderBy('account_name')
                            ->pluck('account_name', 'id')
                    )
                    ->searchable()
                    ->preload()
                    ->required()
                    ->placeholder('Select account'),

                /*
                |--------------------------------------------------------------------------
                | Amount
                |--------------------------------------------------------------------------
                */
                TextInput::make('amount')
                    ->label('Amount')
                    ->numeric()
                    ->prefix('Rs.')
                    ->required()
                    ->minValue(0.01),

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
                        'other' => 'Other',
                    ])
                    ->required(),

                /*
                |--------------------------------------------------------------------------
                | Reference
                |--------------------------------------------------------------------------
                */
                TextInput::make('reference')
                    ->label('Reference')
                    ->maxLength(255)
                    ->placeholder('Invoice / Receipt / Reference'),

                /*
                |--------------------------------------------------------------------------
                | Description
                |--------------------------------------------------------------------------
                */
                Textarea::make('description')
                    ->label('Description')
                    ->rows(3)
                    ->columnSpanFull(),

            ]);
    }
}