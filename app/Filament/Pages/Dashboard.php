<?php

namespace App\Filament\Pages;

use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Customer;
use Carbon\Carbon;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
   protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-home';

    protected string $view = 'filament.pages.dashboard';

    public string $range = '7';

    public function updatedRange(): void
    {
        $this->dispatch('salesTrendUpdated', trend: $this->getSalesTrend((int) $this->range));
    }

    public function getViewData(): array
    {
        return [
            'stats'         => $this->getStats(),
            'salesTrend'    => $this->getSalesTrend((int) $this->range),
            'stockStatus'   => $this->getStockStatus(),
            'recentSales'   => $this->getRecentSales(),
            'lowStockItems' => $this->getLowStockItems(),
            'topSelling'    => $this->getTopSelling(),
            'expiringSoon'  => $this->getExpiringSoon(),
        ];
    }

    protected function getStats(): array
    {
        $totalMedicines = Medicine::count();

        $stockValue = MedicineBatch::where('quantity', '>', 0)
            ->selectRaw('COALESCE(SUM(quantity * purchase_price), 0) as total')
            ->value('total');

        $todaySales = Sale::whereDate('created_at', Carbon::today())->sum('grand_total');

        $totalCustomers = Customer::count();

        $lowStockCount = Medicine::withSum('batches as stock_qty', 'quantity')
            ->get()
            ->filter(fn ($m) => ($m->stock_qty ?? 0) > 0 && $m->stock_qty <= $m->reorder_level)
            ->count();

        // % change vs yesterday for today's sales
        $yesterdaySales = Sale::whereDate('created_at', Carbon::yesterday())->sum('grand_total');
        $salesChange = $yesterdaySales > 0
            ? round((($todaySales - $yesterdaySales) / $yesterdaySales) * 100, 1)
            : 0;

        return [
            'total_medicines' => $totalMedicines,
            'stock_value'     => $stockValue,
            'today_sales'     => $todaySales,
            'sales_change'    => $salesChange,
            'total_customers' => $totalCustomers,
            'low_stock_count' => $lowStockCount,
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

    protected function getStockStatus(): array
    {
        $medicines = Medicine::withSum('batches as stock_qty', 'quantity')->get();

        $inStock    = $medicines->filter(fn ($m) => ($m->stock_qty ?? 0) > $m->reorder_level)->count();
        $lowStock   = $medicines->filter(fn ($m) => ($m->stock_qty ?? 0) > 0 && $m->stock_qty <= $m->reorder_level)->count();
        $outOfStock = $medicines->filter(fn ($m) => ($m->stock_qty ?? 0) <= 0)->count();

        return [
            'in_stock'     => $inStock,
            'low_stock'    => $lowStock,
            'out_of_stock' => $outOfStock,
            'total'        => $medicines->count(),
        ];
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
            ->filter(fn ($m) => ($m->stock_qty ?? 0) <= $m->reorder_level)
            ->sortBy('stock_qty')
            ->take(5);
    }

    protected function getTopSelling()
    {
        return SaleItem::query()
            ->join('medicines', 'medicines.id', '=', 'sale_items.medicine_id')
            ->selectRaw('medicines.id, medicines.name, SUM(sale_items.quantity) as total_sold')
            ->where('sale_items.created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('medicines.id', 'medicines.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();
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
}