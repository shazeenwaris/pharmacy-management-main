<?php

namespace App\Filament\Pharmacist\Pages;

use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Prescription;
use App\Models\Customer;
use Carbon\Carbon;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';

    protected string $view = 'filament.pharmacist.pages.dashboard';

    public string $range = '7';

    public function updatedRange(): void
    {
        $this->dispatch('salesTrendUpdated', trend: $this->getSalesTrend((int) $this->range));
    }

    public function getViewData(): array
    {
        return [
            'stats'              => $this->getStats(),
            'salesTrend'         => $this->getSalesTrend((int) $this->range),
            'recentSales'        => $this->getRecentSales(),
            'lowStockItems'      => $this->getLowStockItems(),
            'expiringSoon'       => $this->getExpiringSoon(),
            'pendingPrescriptions' => $this->getPendingPrescriptions(),
        ];
    }

    protected function getStats(): array
    {
        $todaySales = Sale::whereDate('created_at', Carbon::today())->sum('grand_total');
        $todayCount = Sale::whereDate('created_at', Carbon::today())->count();

        $yesterdaySales = Sale::whereDate('created_at', Carbon::yesterday())->sum('grand_total');
        $salesChange = $yesterdaySales > 0
            ? round((($todaySales - $yesterdaySales) / $yesterdaySales) * 100, 1)
            : 0;

        $lowStockCount = Medicine::withSum('batches as stock_qty', 'quantity')
            ->get()
            ->filter(fn ($m) => ($m->stock_qty ?? 0) > 0 && $m->stock_qty <= $m->minimum_stock_level)
            ->count();

        $expiringSoonCount = MedicineBatch::where('quantity', '>', 0)
            ->whereBetween('expiry_date', [now(), now()->addDays(30)])
            ->count();

        $pendingPrescriptions = Prescription::where('status', 'active')->count();

        return [
            'today_sales'          => $todaySales,
            'today_count'          => $todayCount,
            'sales_change'         => $salesChange,
            'low_stock_count'      => $lowStockCount,
            'expiring_soon_count'  => $expiringSoonCount,
            'pending_prescriptions' => $pendingPrescriptions,
        ];
    }

    protected function getSalesTrend(int $days): array
    {
        $start = Carbon::now()->subDays($days - 1)->startOfDay();

        $sales = Sale::where('created_at', '>=', $start)
            ->selectRaw('DATE(created_at) as day, SUM(grand_total) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        $labels = [];
        $data = [];

        for ($i = 0; $i < $days; $i++) {
            $date = $start->copy()->addDays($i)->format('Y-m-d');
            $labels[] = Carbon::parse($date)->format('M d');
            $data[] = (float) ($sales[$date] ?? 0);
        }

        return ['labels' => $labels, 'data' => $data];
    }

    protected function getRecentSales()
    {
        return Sale::with('customer')
            ->withCount('items')
            ->latest('created_at')
            ->limit(5)
            ->get();
    }

    protected function getLowStockItems()
    {
        return Medicine::withSum('batches as stock_qty', 'quantity')
            ->get()
            ->filter(fn ($m) => ($m->stock_qty ?? 0) <= $m->minimum_stock_level)
            ->sortBy('stock_qty')
            ->take(5);
    }

    protected function getExpiringSoon()
    {
        return MedicineBatch::with('medicine')
            ->where('quantity', '>', 0)
            ->whereDate('expiry_date', '<=', Carbon::now()->addDays(90))
            ->orderBy('expiry_date')
            ->limit(5)
            ->get();
    }

    protected function getPendingPrescriptions()
    {
        return Prescription::where('status', 'active')
            ->latest('prescription_date')
            ->limit(5)
            ->get();
    }
}