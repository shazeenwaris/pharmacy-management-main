<?php

namespace App\Filament\Resources\AppNotifications\Widgets;

use App\Models\AppNotification;
use Filament\Widgets\Widget;

class AppNotificationStats extends Widget
{
    protected string $view = 'filament.resources.app-notifications.widgets.app-notification-stats';
    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    public function getStatsData(): array
    {
        return [
            'total'  => AppNotification::count(),
            'unread' => AppNotification::whereNull('read_at')->count(),
            'read'   => AppNotification::whereNotNull('read_at')->count(),
        ];
    }
}