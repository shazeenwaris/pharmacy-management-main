<?php

namespace App\Filament\Pharmacist\Pages;

use App\Models\AppNotification;
use App\Models\Medicine;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class LowStockAlerts extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedExclamationTriangle;

    protected static ?string $navigationLabel = 'Low Stock Alerts';

    protected static ?string $title = 'Low Stock Alerts';

    protected static ?string $slug = 'alerts/low-stock';

    protected string $view =
        'filament.pharmacist.pages.low-stock-alerts';

    /*
    |--------------------------------------------------------------------------
    | Overview Stats
    |--------------------------------------------------------------------------
    */

    public int $lowStockItems = 0;

    public int $criticalItems = 0;

    public int $totalMedicines = 0;


    /*
    |--------------------------------------------------------------------------
    | Mount
    |--------------------------------------------------------------------------
    */

    public function mount(): void
    {
        $this->lowStockItems = AppNotification::query()
            ->where('type', 'low_stock')
            ->count();

        $this->criticalItems = AppNotification::query()
            ->where('type', 'low_stock')
            ->where('level', 'danger')
            ->count();

        $this->totalMedicines = Medicine::query()->count();
    }


    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    public function table(Table $table): Table
    {
        return $table
            ->query(
                AppNotification::query()
                    ->where('type', 'low_stock')
            )

            ->columns([

                TextColumn::make('title')
                    ->label('Title')
                    ->weight('semibold'),

                TextColumn::make('message')
                    ->label('Message')
                    ->wrap(),

                TextColumn::make('level')
                    ->label('Level')
                    ->badge()
                    ->color(
                        fn (string $state): string => match ($state) {
                            'danger' => 'danger',
                            'warning' => 'warning',
                            default => 'gray',
                        }
                    ),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),

            ])

            ->defaultSort('created_at', 'desc')

            ->emptyStateHeading('No low stock alerts')

            ->emptyStateDescription(
                'All medicines currently have healthy stock levels.'
            )

            ->emptyStateIcon(
                Heroicon::OutlinedCheckCircle
            );
    }
}