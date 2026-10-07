<?php

namespace App\Filament\Pharmacist\Pages;

use App\Models\AppNotification;
use App\Models\User;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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
    | Current User's Expiry Notifications Only
    |--------------------------------------------------------------------------
    */

    protected function baseQuery(): Builder
    {
        return AppNotification::query()
            ->where('notifiable_type', User::class)
            ->where('notifiable_id', auth()->id())
            ->where('data->type', 'expiry');
    }

    /*
    |--------------------------------------------------------------------------
    | Mount
    |--------------------------------------------------------------------------
    */

    public function mount(): void
    {
        // All expiry alerts (expired + expiring soon)
        $this->expiringSoon = $this->baseQuery()->count();

        // Critical = already expired
        $this->criticalExpiry = $this->baseQuery()
            ->where('data->alert_key', 'like', 'expired:%')
            ->count();

        // Warning = expiring soon
        $this->warningExpiry = $this->baseQuery()
            ->where('data->alert_key', 'like', 'expiring:%')
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
            ->query($this->baseQuery())

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
                    ->getStateUsing(
                        fn ($record): string => str_starts_with($record->data['alert_key'] ?? '', 'expired:')
                            ? 'danger'
                            : 'warning'
                    )
                    ->formatStateUsing(
                        fn (string $state): string => $state === 'danger'
                            ? 'Expired'
                            : 'Expiring Soon'
                    )
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