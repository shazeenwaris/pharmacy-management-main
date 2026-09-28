<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ExpiryTrackerOverview;
use App\Models\MedicineBatch;
use App\Notifications\MedicineExpiryNotification;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class ExpiryTracker extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static string|UnitEnum|null $navigationGroup = null;

    protected static ?string $navigationLabel = 'Expiry Tracker';

    protected static ?int $navigationSort = 90;

    protected static ?string $slug = 'expiry-tracker';

    protected string $view = 'filament.pages.expiry-tracker';

    protected function getHeaderWidgets(): array
    {
        return [
            ExpiryTrackerOverview::class,
        ];
    }

    /**
     * Create expiry notifications when the page is opened.
     */
    public function mount(): void
    {
        $user = auth()->user();

        if (! $user) {
            return;
        }

        $batches = MedicineBatch::query()
            ->with('medicine')
            ->where('quantity', '>', 0)
            ->whereDate('expiry_date', '>=', now()->startOfDay())
            ->whereDate('expiry_date', '<=', now()->addDays(30)->endOfDay())
            ->get();

        foreach ($batches as $batch) {

            $exists = $user->notifications()
                ->where('type', MedicineExpiryNotification::class)
                ->whereJsonContains('data->batch_id', $batch->id)
                ->whereJsonContains('data->alert', 'expiry_30')
                ->exists();

            if (! $exists) {
                $user->notify(
                    new MedicineExpiryNotification($batch)
                );
            }
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                MedicineBatch::query()
                    ->with('medicine')
                    ->orderBy('expiry_date', 'asc')
            )
            ->columns([
                TextColumn::make('medicine.name')
                    ->label('Medicine')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('batch_number')
                    ->label('Batch')
                    ->searchable(),

                TextColumn::make('quantity')
                    ->label('Quantity')
                    ->sortable(),

                TextColumn::make('expiry_date')
                    ->label('Expiry Date')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('days_left')
                    ->label('Days Left')
                    ->state(
                        fn (MedicineBatch $record) =>
                            now()->startOfDay()->diffInDays(
                                $record->expiry_date,
                                false
                            )
                    )
                    ->formatStateUsing(function ($state) {
                        $days = (int) $state;

                        if ($days < 0) {
                            return 'Expired ' . abs($days) . ' days ago';
                        }

                        return $days . ' days left';
                    })
                    ->badge()
                    ->color(function (MedicineBatch $record) {
                        $days = now()->startOfDay()->diffInDays(
                            $record->expiry_date,
                            false
                        );

                        return match (true) {
                            $days < 0 => 'danger',
                            $days <= 30 => 'warning',
                            default => 'success',
                        };
                    }),
            ])
            ->filters([
                Filter::make('expired')
                    ->label('Expired')
                    ->query(
                        fn (Builder $query): Builder =>
                            $query->where(
                                'expiry_date',
                                '<',
                                now()
                            )
                    ),

                Filter::make('within_7')
                    ->label('Expiring within 7 days')
                    ->query(
                        fn (Builder $query): Builder =>
                            $query->whereBetween('expiry_date', [
                                now(),
                                now()->addDays(7),
                            ])
                    ),

                Filter::make('within_30')
                    ->label('Expiring within 30 days')
                    ->query(
                        fn (Builder $query): Builder =>
                            $query->whereBetween('expiry_date', [
                                now(),
                                now()->addDays(30),
                            ])
                    ),

                Filter::make('within_60')
                    ->label('Expiring within 60 days')
                    ->query(
                        fn (Builder $query): Builder =>
                            $query->whereBetween('expiry_date', [
                                now(),
                                now()->addDays(60),
                            ])
                    ),

                Filter::make('within_90')
                    ->label('Expiring within 90 days')
                    ->query(
                        fn (Builder $query): Builder =>
                            $query->whereBetween('expiry_date', [
                                now(),
                                now()->addDays(90),
                            ])
                    ),
            ])
            ->defaultSort('expiry_date', 'asc');
    }
}