<?php

namespace App\Filament\Pharmacist\Resources\SaleReturns;

use App\Filament\Pharmacist\Resources\SaleReturns\Pages\CreateSaleReturn;
use App\Filament\Pharmacist\Resources\SaleReturns\Pages\ListSaleReturns;
use App\Filament\Resources\SaleReturns\Schemas\SaleReturnForm;
use App\Filament\Resources\SaleReturns\Tables\SaleReturnsTable;
use App\Models\SaleReturn;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SaleReturnResource extends Resource
{
    protected static ?string $model = SaleReturn::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'reason';

    protected static string|\UnitEnum|null $navigationGroup = 'Sales / POS';

    protected static ?string $navigationLabel = 'Sales Returns';

    protected static ?int $navigationSort = 3;

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('handle returns') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('handle returns') ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return SaleReturnForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SaleReturnsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSaleReturns::route('/'),
            'create' => CreateSaleReturn::route('/create'),
        ];
    }
}