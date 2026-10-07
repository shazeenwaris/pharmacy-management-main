<?php

namespace App\Filament\Resources\AppNotifications;

use App\Filament\Resources\AppNotifications\Pages\ListAppNotifications;
use App\Models\AppNotification;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AppNotificationResource extends Resource
{
    protected static ?string $model = AppNotification::class;

    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedBell;

    protected static ?string $navigationLabel = 'Alerts';

    public static function canViewAny(): bool
    {
        return true;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | Current User Notifications Only
    |--------------------------------------------------------------------------
    */

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('notifiable_type', \App\Models\User::class)
            ->where('notifiable_id', auth()->id());
    }

    /*
    |--------------------------------------------------------------------------
    | Sidebar Badge
    |--------------------------------------------------------------------------
    */

    public static function getNavigationBadge(): ?string
    {
        if (! auth()->check()) {
            return null;
        }

        $count = static::getEloquentQuery()
            ->whereNull('read_at')
            ->count();

        return $count > 0
            ? (string) $count
            : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('notification_type')
                    ->label('Type')
                    ->badge()
                    ->icon(function ($state): string {
                        return match ($state) {
                            'low_stock' => 'heroicon-o-arrow-trending-down',
                            'out_of_stock' => 'heroicon-o-x-circle',
                            'expiry' => 'heroicon-o-calendar-days',
                            'payment_due' => 'heroicon-o-banknotes',
                            'expiry_30' => 'heroicon-o-calendar-days',
                            default => 'heroicon-o-bell',
                        };
                    })
                    ->color(function ($state): string {
                        return match ($state) {
                            'low_stock' => 'warning',
                            'out_of_stock' => 'danger',
                            'expiry',
                            'expiry_30' => 'warning',
                            'payment_due' => 'info',
                            default => 'gray',
                        };
                    })
                    ->formatStateUsing(function ($state): string {
                        return match ($state) {
                            'low_stock' => 'Low Stock',
                            'out_of_stock' => 'Out of Stock',
                            'expiry',
                            'expiry_30' => 'Expiry',
                            'payment_due' => 'Payment Due',
                            default => ucfirst(
                                str_replace('_', ' ', $state)
                            ),
                        };
                    }),

                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->weight('semibold'),

                TextColumn::make('message')
                    ->label('Message')
                    ->limit(70)
                    ->searchable()
                    ->tooltip(
                        fn ($record) => $record->message
                    ),

                IconColumn::make('read_at')
                    ->label('Read')
                    ->boolean()
                    ->getStateUsing(
                        fn ($record) => $record->read_at !== null
                    )
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-envelope')
                    ->trueColor('success')
                    ->falseColor('info'),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->since(),
            ])

            ->filters([

                SelectFilter::make('notification_type')
                    ->label('Type')
                    ->options([
                        'low_stock' => 'Low Stock',
                        'out_of_stock' => 'Out of Stock',
                        'expiry' => 'Expiry',
                        'payment_due' => 'Payment Due',
                    ])
                    ->query(function (
                        Builder $query,
                        array $data
                    ): Builder {

                        if (blank($data['value'] ?? null)) {
                            return $query;
                        }

                        return $query->where(
                            'data->type',
                            $data['value']
                        );
                    }),

                TernaryFilter::make('read_at')
                    ->label('Read Status')
                    ->nullable()
                    ->trueLabel('Read')
                    ->falseLabel('Unread')
                    ->queries(
                        true: fn (Builder $query) =>
                            $query->whereNotNull('read_at'),

                        false: fn (Builder $query) =>
                            $query->whereNull('read_at'),
                    ),
            ])

            ->recordActions([

                Action::make('markAsRead')
                    ->label('Mark as Read')
                    ->icon(Heroicon::OutlinedCheck)
                    ->color('success')
                    ->visible(
                        fn ($record) =>
                            $record->read_at === null
                    )
                    ->action(
                        fn ($record) =>
                            $record->markAsRead()
                    ),

                Action::make('open')
                    ->label('View')
                    ->icon(
                        Heroicon::OutlinedArrowTopRightOnSquare
                    )
                    ->url(
                        fn ($record) =>
                            $record->link
                    )
                    ->visible(
                        fn ($record) =>
                            $record->link !== null
                    ),

                DeleteAction::make(),
            ])
                ->actionsColumnLabel('Actions')
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
                

            ->striped()

            ->defaultSort(
                'created_at',
                'desc'
            );
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAppNotifications::route('/'),
        ];
    }
}