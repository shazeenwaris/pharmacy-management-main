<?php

namespace App\Filament\Resources\Accounts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AccountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            TextInput::make('account_name')
                ->label('Account Name')
                ->required()
                ->maxLength(255),

            Select::make('account_type')
                ->label('Account Type')
                ->options([
                    'cash' => 'Cash',
                    'bank' => 'Bank',
                    'mobile_wallet' => 'Mobile Wallet',
                    'other' => 'Other',
                ])
                ->required()
                ->default('cash'),

            TextInput::make('account_number')
                ->label('Account Number')
                ->maxLength(255),

            TextInput::make('account_title')
                ->label('Account Title')
                ->maxLength(255),

            TextInput::make('bank_name')
                ->label('Bank Name')
                ->maxLength(255),

            TextInput::make('iban')
                ->label('IBAN')
                ->maxLength(255),

            TextInput::make('branch')
                ->label('Branch')
                ->maxLength(255),

            TextInput::make('opening_balance')
                ->label('Opening Balance')
                ->numeric()
                ->minValue(0)
                ->default(0)
                ->required(),

            TextInput::make('current_balance')
                ->label('Current Balance')
                ->numeric()
                ->default(0)
                ->required(),

            Select::make('status')
                ->label('Status')
                ->options([
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                ])
                ->required()
                ->default('active'),

            Textarea::make('notes')
                ->label('Notes')
                ->rows(3)
                ->columnSpanFull(),

        ]);
    }
}