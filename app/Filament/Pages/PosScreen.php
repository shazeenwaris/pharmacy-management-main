<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class PosScreen extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    protected static ?string $navigationLabel = 'New Sale';

    protected static ?string $title = 'Sales / POS';

    protected static string|\UnitEnum|null $navigationGroup = 'Sales / POS';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'sales';   // <-- YE LINE ADD KARO

    protected string $view = 'filament.pages.pos-screen';

    public static function canAccess(): bool
    {
        return auth()->user()?->can('create sales') ?? false;
    }
}