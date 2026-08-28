<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Filament\Widgets\Widget;

class UserOverview extends Widget
{
    protected string $view = 'filament.widgets.user-overview';

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $total = User::count();

        $active = null;
        $inactive = null;

        // Use active column only if it actually exists.
        if (Schema::hasColumn('users', 'active')) {
            $active = User::where('active', true)->count();
            $inactive = User::where('active', false)->count();
        }

        // Fallback for projects using status instead of active.
        elseif (Schema::hasColumn('users', 'status')) {
            $active = User::where('status', true)->count();
            $inactive = User::where('status', false)->count();
        }

        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
        ];
    }
}