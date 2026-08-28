<?php

namespace App\Filament\Resources\ExpenseCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ExpenseCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('name')
                    ->label('Category Name')
                    ->placeholder('e.g. Electricity')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                Textarea::make('description')
                    ->label('Description')
                    ->placeholder('Optional description')
                    ->rows(3)
                    ->columnSpanFull(),

                Toggle::make('status')
                    ->label('Active')
                    ->default(true)
                    ->inline(false),

            ]);
    }
}