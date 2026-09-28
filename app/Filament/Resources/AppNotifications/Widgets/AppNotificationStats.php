<?php

namespace App\Filament\Resources\AppNotifications\Widgets;

use App\Models\AppNotification;
use Filament\Widgets\Widget;

class AppNotificationStats extends Widget
{
    protected string $view =
        'filament.resources.app-notifications.widgets.app-notification-stats';

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    public function getStatsData(): array
    {
        $query = AppNotification::query()
            ->where(
                'notifiable_type',
                \App\Models\User::class
            )
            ->where(
                'notifiable_id',
                auth()->id()
            );

        return [
            'total' => (clone $query)->count(),

            'unread' => (clone $query)
                ->whereNull('read_at')
                ->count(),

            'read' => (clone $query)
                ->whereNotNull('read_at')
                ->count(),
        ];
    }
}