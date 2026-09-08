<?php

namespace App\Filament\Resources\AppNotifications\Pages;

use App\Filament\Resources\AppNotifications\AppNotificationResource;
use App\Filament\Resources\AppNotifications\Widgets\AppNotificationBanner;
use App\Filament\Resources\AppNotifications\Widgets\AppNotificationStats;
use Filament\Resources\Pages\ListRecords;

class ListAppNotifications extends ListRecords
{
    protected static string $resource = AppNotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            AppNotificationBanner::class,
            AppNotificationStats::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return 1;
    }
}