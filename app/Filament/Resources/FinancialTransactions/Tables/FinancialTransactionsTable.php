<?php

namespace App\Filament\Resources\FinancialTransactions\Tables;

use App\Models\Account;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use App\Models\Expense;
use Illuminate\Database\Eloquent\Builder;

class FinancialTransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                /*
                |--------------------------------------------------------------------------
                | Transaction Date
                |--------------------------------------------------------------------------
                */
                TextColumn::make('transaction_date')
                    ->label('Date')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->searchable(),

                /*
                |--------------------------------------------------------------------------
                | Transaction Type
                |--------------------------------------------------------------------------
                */
                TextColumn::make('transaction_type')
                    ->label('Type')
                    ->badge()
                    ->colors([
                        'success' => 'income',
                        'danger' => 'expense',
                    ])
                    ->formatStateUsing(
                        fn (?string $state): string =>
                            ucfirst($state ?? '-')
                    )
                    ->sortable(),

                /*
                |--------------------------------------------------------------------------
                | Account
                |--------------------------------------------------------------------------
                */
                TextColumn::make('account.account_name')
                    ->label('Account')
                    ->searchable()
                    ->sortable(),

                /*
                |--------------------------------------------------------------------------
                | Amount
                |--------------------------------------------------------------------------
                */
                TextColumn::make('amount')
                    ->label('Amount')
                    ->money('PKR')
                    ->sortable(),

                /*
                |--------------------------------------------------------------------------
                | Payment Method
                |--------------------------------------------------------------------------
                */
                TextColumn::make('payment_method')
                    ->label('Payment Method')
                    ->badge()
                    ->formatStateUsing(
                        fn (?string $state): string =>
                            $state
                                ? ucfirst(str_replace('_', ' ', $state))
                                : '-'
                    )
                    ->sortable(),

                /*
                |--------------------------------------------------------------------------
                | Reference
                |--------------------------------------------------------------------------
                */
   TextColumn::make('reference')
    ->label('Reference')
    ->getStateUsing(function ($record): string {
        return $record->referenceable?->reference ?? '-';
    })
    ->placeholder('-'),
                /*
                |--------------------------------------------------------------------------
                | Description
                |--------------------------------------------------------------------------
                */
                TextColumn::make('description')
                    ->label('Description')
                    ->limit(45)
                    ->tooltip(
                        fn (?string $state): ?string => $state
                    )
                    ->searchable(),

                /*
                |--------------------------------------------------------------------------
                | Created By
                |--------------------------------------------------------------------------
                */
                // TextColumn::make('created_by')
                //     ->label('Created By')
                //     ->default('-')
                //     ->sortable(),

                /*
                |--------------------------------------------------------------------------
                | Created At
                |--------------------------------------------------------------------------
                */
            //     TextColumn::make('created_at')
            //         ->label('Created')
            //         ->dateTime('d M Y, h:i A')
            //         ->sortable()
            //         ->toggleable(isToggledHiddenByDefault: true),
            ])

            /*
            |--------------------------------------------------------------------------
            | Filters
            |--------------------------------------------------------------------------
            */
            ->filters([

                SelectFilter::make('transaction_type')
                    ->label('Transaction Type')
                    ->options([
                        'income' => 'Income',
                        'expense' => 'Expense',
                    ]),

                SelectFilter::make('account_id')
                    ->label('Account')
                    ->options(
                        fn (): array => Account::query()
                            ->orderBy('account_name')
                            ->pluck('account_name', 'id')
                            ->toArray()
                    ),

                SelectFilter::make('payment_method')
                    ->label('Payment Method')
                    ->options([
                        'cash' => 'Cash',
                        'bank' => 'Bank',
                        'card' => 'Card',
                        'online' => 'Online',
                        'credit' => 'Credit',
                        'other' => 'Other',
                    ]),

                Filter::make('today')
                    ->label('Today')
                    ->query(
                        fn (Builder $query): Builder =>
                            $query->whereDate(
                                'transaction_date',
                                today()
                            )
                    ),
            ])

            /*
            |--------------------------------------------------------------------------
            | Record Actions
            |--------------------------------------------------------------------------
            */
           ->recordActions([
    ViewAction::make(),
    EditAction::make(),
    DeleteAction::make(),
])
->recordActionsColumnLabel('Actions')
            /*
            |--------------------------------------------------------------------------
            | Bulk Actions
            |--------------------------------------------------------------------------
            */
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])

            /*
            |--------------------------------------------------------------------------
            | Default Sorting
            |--------------------------------------------------------------------------
            */
            ->defaultSort(
                'transaction_date',
                'desc'
            );
    }
}