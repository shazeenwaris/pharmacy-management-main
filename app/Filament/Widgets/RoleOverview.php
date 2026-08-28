<?php

namespace App\Filament\Widgets;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Filament\Widgets\Widget;

class RoleOverview extends Widget
{
    protected string $view = 'filament.widgets.role-overview';

     protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $totalRoles = Role::count();

        $totalPermissions = Permission::count();

        $assignedUsers = Role::withCount('users')
            ->get()
            ->sum('users_count');

        return [
            'totalRoles' => $totalRoles,
            'totalPermissions' => $totalPermissions,
            'assignedUsers' => $assignedUsers,
        ];
    }
}