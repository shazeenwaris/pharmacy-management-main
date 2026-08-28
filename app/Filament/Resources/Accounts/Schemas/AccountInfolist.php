<?php

namespace App\Filament\Resources\Accounts\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AccountInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('account_name'),
                TextEntry::make('account_type')
                    ->badge(),
                TextEntry::make('account_number')
                    ->placeholder('-'),
                TextEntry::make('account_title')
                    ->placeholder('-'),
                TextEntry::make('bank_name')
                    ->placeholder('-'),
                TextEntry::make('iban')
                    ->placeholder('-'),
                TextEntry::make('branch')
                    ->placeholder('-'),
                TextEntry::make('opening_balance')
                    ->numeric(),
                TextEntry::make('current_balance')
                    ->numeric(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
