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

class AppNotificationResource extends Resource
{
    protected static ?string $model = AppNotification::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBell;

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

    public static function getNavigationBadge(): ?string
    {
        $count = AppNotification::unread()->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->icon(fn (string $state): string => match ($state) {
                        'low_stock' => 'heroicon-o-arrow-trending-down',
                        'out_of_stock' => 'heroicon-o-x-circle',
                        'expiry' => 'heroicon-o-calendar-days',
                        'payment_due' => 'heroicon-o-banknotes',
                        default => 'heroicon-o-bell',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'low_stock' => 'warning',
                        'out_of_stock' => 'danger',
                        'expiry' => 'warning',
                        'payment_due' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'low_stock' => 'Low Stock',
                        'out_of_stock' => 'Out of Stock',
                        'expiry' => 'Expiry',
                        'payment_due' => 'Payment Due',
                        default => ucfirst(str_replace('_', ' ', $state)),
                    }),

                TextColumn::make('title')
                    ->searchable()
                    ->weight('semibold')
                    ->color('gray'),

                TextColumn::make('message')
                    ->limit(60)
                    ->searchable()
                    ->tooltip(fn ($record) => $record->message)
                    ->color('gray'),

                IconColumn::make('read_at')
                    ->label('Read')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->read_at !== null)
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-envelope')
                    ->trueColor('success')
                    ->falseColor('info'),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d M Y, h:i A')
                    ->since()
                    ->sortable()
                    ->color('gray'),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Type')
                    ->options([
                        'low_stock' => 'Low Stock',
                        'out_of_stock' => 'Out of Stock',
                        'expiry' => 'Expiry',
                        'payment_due' => 'Payment Due',
                    ]),

                TernaryFilter::make('read_at')
                    ->label('Read Status')
                    ->nullable()
                    ->trueLabel('Read')
                    ->falseLabel('Unread')
                    ->queries(
                        true: fn ($query) => $query->whereNotNull('read_at'),
                        false: fn ($query) => $query->whereNull('read_at'),
                    ),
            ])
            ->recordActions([
                Action::make('markAsRead')
                    ->label('Mark as Read')
                    ->icon(Heroicon::OutlinedCheck)
                    ->color('success')
                    ->visible(fn ($record) => $record->read_at === null)
                    ->action(fn ($record) => $record->markAsRead()),

                Action::make('open')
                    ->label('View')
                    ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                    ->url(fn ($record) => $record->link)
                    ->visible(fn ($record) => $record->link !== null),

                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->striped()
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAppNotifications::route('/'),
        ];
    }
}