<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),

                TextColumn::make('phone')
                    ->searchable(),

                TextColumn::make('roles.name')
                    ->label('Role')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        return $record->roles->pluck('name')->implode(', ');
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin' => 'Admin',
                        'pharmacist' => 'Pharmacist',
                        'cashier' => 'Cashier',
                        'inventory_manager' => 'Inventory Manager',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'pharmacist' => 'success',
                        'cashier' => 'warning',
                        'inventory_manager' => 'info',
                        default => 'gray',
                    }),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label('Role')
                    ->options([
                        'admin' => 'Admin',
                        'pharmacist' => 'Pharmacist',
                        'cashier' => 'Cashier',
                        'inventory_manager' => 'Inventory Manager',
                    ])
                    ->query(function ($query, array $data) {
                        if (filled($data['value'])) {
                            $query->whereHas('roles', function ($roleQuery) use ($data) {
                                $roleQuery->where('name', $data['value']);
                            });
                        }
                    }),

                TernaryFilter::make('is_active')
                    ->label('Active status'),
            ])
            ->recordActions([
                EditAction::make(),

                // NEW: Delete — hidden for your own account and for admin users
                DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Delete this user?')
                    ->modalDescription('This user will be removed permanently. To only stop them from logging in, turn off "Active" instead.')
                    ->hidden(fn ($record) => $record->id === auth()->id() || $record->hasRole('admin')),
            ])
             ->recordActionsColumnLabel('Actions')
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
                
            ]);
    }
}