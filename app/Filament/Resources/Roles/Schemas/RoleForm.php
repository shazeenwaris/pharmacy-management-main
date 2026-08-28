<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Spatie\Permission\Models\Permission;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Role Details')
                    ->description('Basic identity of this role')
                    ->icon(Heroicon::OutlinedShieldCheck)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')
                            ->label('Role Name')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->helperText('e.g. admin, pharmacist, cashier, inventory_manager'),
                    ]),

                Section::make('Permissions')
                    ->description('Select what this role is allowed to do across the system')
                    ->icon(Heroicon::OutlinedKey)
                    ->columnSpanFull()
                    ->schema([
                        CheckboxList::make('permissions')
                            ->label('')
                            ->relationship('permissions', 'name')
                            ->options(fn () => Permission::query()->pluck('name', 'id'))
                            ->searchable()
                            ->bulkToggleable()
                            ->columns(3)
                            ->gridDirection('row'),
                    ]),
            ]);
    }
}