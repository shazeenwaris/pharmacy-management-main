<?php

namespace App\Filament\Pages;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SalesReports extends Page
{
    protected static ?string $title = 'Sales Reports';

    protected static ?string $navigationLabel = 'Sales Reports';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $slug = 'reports/sales';

    protected string $view = 'filament.pages.sales-reports';

    public ?string $fromDate = null;

    public ?string $toDate = null;

    public function mount(): void
    {
        $this->fromDate = now()->startOfMonth()->format('Y-m-d');
        $this->toDate = now()->format('Y-m-d');
    }

   protected function getDateRangeQuery($query)
{
    return $query
        ->whereDate('sales.created_at', '>=', $this->fromDate)
        ->whereDate('sales.created_at', '<=', $this->toDate);
}
    public function getTotalRevenueProperty(): float
    {
        return (float) $this->getDateRangeQuery(
            Sale::query()
        )->sum('grand_total');
    }

    public function getTotalSalesProperty(): int
    {
        return $this->getDateRangeQuery(
            Sale::query()
        )->count();
    }

    public function getSoldQuantityProperty(): float
{
    return (float) SaleItem::query()
        ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
        ->whereDate('sales.created_at', '>=', $this->fromDate)
        ->whereDate('sales.created_at', '<=', $this->toDate)
        ->sum('sale_items.quantity');
}

    public function getAverageSaleProperty(): float
    {
        $totalSales = $this->totalSales;

        if ($totalSales <= 0) {
            return 0;
        }

        return $this->totalRevenue / $totalSales;
    }

    public function getDailySalesProperty()
    {
        return $this->getDateRangeQuery(
            Sale::query()
        )
            ->select(
                DB::raw('DATE(created_at) as sale_date'),
                DB::raw('COUNT(*) as total_bills'),
                DB::raw('SUM(grand_total) as total_revenue')
            )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderByDesc('sale_date')
            ->get();
    }

    public function getSalesByMedicineProperty()
    {
        return SaleItem::query()
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->join('medicines', 'medicines.id', '=', 'sale_items.medicine_id')
            ->whereDate('sales.created_at', '>=', $this->fromDate)
            ->whereDate('sales.created_at', '<=', $this->toDate)
            ->select(
                'medicines.id',
                'medicines.name',
                DB::raw('SUM(sale_items.quantity) as sold_quantity'),
                DB::raw('SUM(sale_items.total) as revenue')
            )
            ->groupBy('medicines.id', 'medicines.name')
            ->orderByDesc('sold_quantity')
            ->get();
    }

    public function getSalesByCategoryProperty()
    {
        return SaleItem::query()
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->join('medicines', 'medicines.id', '=', 'sale_items.medicine_id')
            ->leftJoin(
                'categories',
                'categories.id',
                '=',
                'medicines.category_id'
            )
            ->whereDate('sales.created_at', '>=', $this->fromDate)
            ->whereDate('sales.created_at', '<=', $this->toDate)
            ->select(
                'categories.id',
                'categories.name',
                DB::raw('SUM(sale_items.quantity) as sold_quantity'),
                DB::raw('SUM(sale_items.total) as revenue')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('revenue')
            ->get();
    }

   public function getSalesByCashierProperty()
{
    // Get IDs of users who actually have the cashier role
    $cashierIds = User::role('cashier')->pluck('id');

    return Sale::query()
        ->leftJoin(
            'users',
            'users.id',
            '=',
            'sales.created_by'
        )
        ->whereIn('sales.created_by', $cashierIds)
        ->whereDate(
            'sales.created_at',
            '>=',
            $this->fromDate
        )
        ->whereDate(
            'sales.created_at',
            '<=',
            $this->toDate
        )
        ->select(
            'users.id',
            'users.name',
            DB::raw('COUNT(sales.id) as total_bills'),
            DB::raw('SUM(sales.grand_total) as revenue')
        )
        ->groupBy(
            'users.id',
            'users.name'
        )
        ->orderByDesc('revenue')
        ->get();
}
    public function applyFilters(): void
    {
        $this->validate([
            'fromDate' => ['required', 'date'],
            'toDate' => ['required', 'date', 'after_or_equal:fromDate'],
        ]);
    }

    public function resetFilters(): void
    {
        $this->fromDate = now()->startOfMonth()->format('Y-m-d');
        $this->toDate = now()->format('Y-m-d');
    }
}