<?php

namespace App\Filament\Pharmacist\Widgets;

use App\Models\HeldSale;
use Filament\Widgets\Widget;

class HeldSalesStats extends Widget
{
    protected string $view = 'filament.pharmacist.widgets.held-sales-stats';

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    // protected function getViewData(): array
    // {
    //     $held = HeldSale::count();

    //     $heldToday = HeldSale::whereDate('created_at', today())->count();

    //     $oldestHeldAt = HeldSale::oldest('created_at')->value('created_at');

    //     $oldestMinutes = $oldestHeldAt
    //         ? now()->diffInMinutes($oldestHeldAt)
    //         : 0;

    //     return [
    //         'held' => $held,
    //         'heldToday' => $heldToday,
    //         'oldestMinutes' => $oldestMinutes,
    //     ];
    // }
    protected function getViewData(): array
{
    $held = HeldSale::count();

    $heldToday = HeldSale::whereDate('created_at', today())->count();

    $oldestRecord = HeldSale::oldest('created_at')->first();

    $oldestMinutes = 0;

    if ($oldestRecord && $oldestRecord->created_at) {
        $oldestMinutes = (int) abs(now()->diffInMinutes($oldestRecord->created_at));
    }

    return [
        'held' => $held,
        'heldToday' => $heldToday,
        'oldestMinutes' => $oldestMinutes,
    ];
}
}