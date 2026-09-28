<?php

namespace App\Filament\Pharmacist\Pages;

use App\Models\AppNotification;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class ExpiryAlerts extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedClock;

    protected static ?string $navigationLabel = 'Expiry Alerts';

    protected static ?string $title = 'Expiry Alerts';

    protected static ?string $slug = 'alerts/expiry';

    protected string $view =
        'filament.pharmacist.pages.expiry-alerts';


    /*
    |--------------------------------------------------------------------------
    | Overview Stats
    |--------------------------------------------------------------------------
    */

    public int $expiringSoon = 0;

    public int $criticalExpiry = 0;

    public int $warningExpiry = 0;


    /*
    |--------------------------------------------------------------------------
    | Mount
    |--------------------------------------------------------------------------
    */

    public function mount(): void
    {
        $this->expiringSoon = AppNotification::query()
            ->where('type', 'expiry')
            ->count();

        $this->criticalExpiry = AppNotification::query()
            ->where('type', 'expiry')
            ->where('level', 'danger')
            ->count();

        $this->warningExpiry = AppNotification::query()
            ->where('type', 'expiry')
            ->where('level', 'warning')
            ->count();
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
                    ->where('type', 'expiry')
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

            ->emptyStateHeading('No expiry alerts')

            ->emptyStateDescription(
                'No medicines are expiring soon.'
            )

            ->emptyStateIcon(
                Heroicon::OutlinedCheckCircle
            );
    }
}