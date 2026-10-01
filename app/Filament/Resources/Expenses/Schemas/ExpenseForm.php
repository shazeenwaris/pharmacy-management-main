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
              TextInput::make('account_name')
    ->label('Account')
    ->placeholder('Enter account name')
    ->required()
    ->maxLength(255),

                /*
                |--------------------------------------------------------------------------
                | Amount
                |--------------------------------------------------------------------------
                */
                        TextInput::make('amount')
    ->label('Amount')
    ->numeric()
    ->prefix(fn () => currency_symbol())
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
    ->label('Reference (Optional)')
    ->maxLength(255)
    ->placeholder('Invoice, receipt or transaction number'),

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