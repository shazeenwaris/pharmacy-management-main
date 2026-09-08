<?php

namespace App\Filament\Resources\AppNotifications\Widgets;

use Filament\Widgets\Widget;

class AppNotificationBanner extends Widget
{
    protected string $view = 'filament.resources.app-notifications.widgets.app-notification-banner';
    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';
}