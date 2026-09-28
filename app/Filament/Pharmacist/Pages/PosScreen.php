<?php

namespace App\Filament\Pharmacist\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class PosScreen extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    protected static ?string $navigationLabel = 'New Sale';

    protected static ?string $title = 'Sales / POS';

    protected static ?string $slug = 'sales';

    protected string $view = 'filament.pages.pos-screen';
}