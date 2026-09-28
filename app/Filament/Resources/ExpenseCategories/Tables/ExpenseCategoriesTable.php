<?php

namespace App\Filament\Resources\ExpenseCategories\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ExpenseCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('name')
                    ->label('Category')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->toggleable(),

                IconColumn::make('status')
                    ->label('Status')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('expenses_count')
                    ->label('Expenses')
                    ->counts('expenses')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(
                        isToggledHiddenByDefault: true
                    ),

            ])

            /*
            |--------------------------------------------------------------------------
            | Record Actions
            |--------------------------------------------------------------------------
            */
            // ->recordActions([
            //     ViewAction::make(),
            //     EditAction::make(),
            //     DeleteAction::make(),
            // ])
            ->recordActions([
    EditAction::make(),

    DeleteAction::make()
        ->before(function ($record, $action) {
            if ($record->expenses()->exists()) {
                \Filament\Notifications\Notification::make()
                    ->title('Category cannot be deleted')
                    ->body('This category is already used by expenses. Please move or delete those expenses first.')
                    ->danger()
                    ->send();

                $action->halt();
            }
        }),
])
->recordActionsColumnLabel('Actions')
            ->defaultSort('name');
    }
}