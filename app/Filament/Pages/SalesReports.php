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

    /*
    |--------------------------------------------------------------------------
    | Filters
    |--------------------------------------------------------------------------
    */

    public string $reportPeriod = 'month';

    public ?string $fromDate = null;

    public ?string $toDate = null;

    /*
    |--------------------------------------------------------------------------
    | Mount
    |--------------------------------------------------------------------------
    */

    public function mount(): void
    {
        $this->setReportPeriod('month');
    }

    /*
    |--------------------------------------------------------------------------
    | Report Period
    |--------------------------------------------------------------------------
    */

    public function setReportPeriod(string $period): void
    {
        $allowedPeriods = [
            'week',
            'month',
            'year',
            'custom',
        ];

        if (! in_array($period, $allowedPeriods, true)) {
            return;
        }

        $this->reportPeriod = $period;

        /*
        |--------------------------------------------------------------------------
        | Custom Range
        |--------------------------------------------------------------------------
        */

        if ($period === 'custom') {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | This Week
        |--------------------------------------------------------------------------
        */

        if ($period === 'week') {
            $this->fromDate = now()
                ->startOfWeek()
                ->format('Y-m-d');

            $this->toDate = now()
                ->endOfWeek()
                ->format('Y-m-d');

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | This Month
        |--------------------------------------------------------------------------
        */

        if ($period === 'month') {
            $this->fromDate = now()
                ->startOfMonth()
                ->format('Y-m-d');

            $this->toDate = now()
                ->format('Y-m-d');

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | This Year
        |--------------------------------------------------------------------------
        */

        if ($period === 'year') {
            $this->fromDate = now()
                ->startOfYear()
                ->format('Y-m-d');

            $this->toDate = now()
                ->format('Y-m-d');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Date Range Query
    |--------------------------------------------------------------------------
    */

    protected function getDateRangeQuery($query)
    {
        return $query
            ->whereDate(
                'sales.created_at',
                '>=',
                $this->fromDate
            )
            ->whereDate(
                'sales.created_at',
                '<=',
                $this->toDate
            );
    }

    /*
    |--------------------------------------------------------------------------
    | KPI - Total Revenue
    |--------------------------------------------------------------------------
    */

    public function getTotalRevenueProperty(): float
    {
        return (float) $this->getDateRangeQuery(
            Sale::query()
        )->sum('grand_total');
    }

    /*
    |--------------------------------------------------------------------------
    | KPI - Total Sales
    |--------------------------------------------------------------------------
    */

    public function getTotalSalesProperty(): int
    {
        return $this->getDateRangeQuery(
            Sale::query()
        )->count();
    }

    /*
    |--------------------------------------------------------------------------
    | KPI - Sold Quantity
    |--------------------------------------------------------------------------
    */

    public function getSoldQuantityProperty(): float
    {
        return (float) SaleItem::query()
            ->join(
                'sales',
                'sales.id',
                '=',
                'sale_items.sale_id'
            )
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
            ->sum('sale_items.quantity');
    }

    /*
    |--------------------------------------------------------------------------
    | KPI - Average Sale
    |--------------------------------------------------------------------------
    */

    public function getAverageSaleProperty(): float
    {
        $totalSales = $this->totalSales;

        if ($totalSales <= 0) {
            return 0;
        }

        return $this->totalRevenue / $totalSales;
    }

    /*
    |--------------------------------------------------------------------------
    | Period Breakdown
    |--------------------------------------------------------------------------
    |
    | Week  -> Daily
    | Month -> Daily
    | Year  -> Monthly
    | Custom -> Daily
    |
    */
public function getSalesBreakdownProperty()
{
    /*
    |--------------------------------------------------------------------------
    | CUSTOM DATE
    |--------------------------------------------------------------------------
    | Show ONLY the selected date.
    |--------------------------------------------------------------------------
    */

    if ($this->reportPeriod === 'custom') {

        $date = Carbon::parse($this->fromDate);

        $sale = Sale::query()
            ->whereDate('created_at', $date->format('Y-m-d'))
            ->select(
                DB::raw('COUNT(*) as total_bills'),
                DB::raw('SUM(grand_total) as total_revenue')
            )
            ->first();

        return collect([
            (object) [
                'period_key' => $date->format('Y-m-d'),
                'period_label' => $date->format('d M Y'),
                'total_bills' => (int) ($sale->total_bills ?? 0),
                'total_revenue' => (float) ($sale->total_revenue ?? 0),
            ],
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | YEAR
    |--------------------------------------------------------------------------
    */

    if ($this->reportPeriod === 'year') {

        $from = Carbon::parse($this->fromDate)->startOfYear();
        $to = Carbon::parse($this->fromDate)->endOfYear();

        $sales = Sale::query()
            ->whereBetween('created_at', [$from, $to])
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as period_key"),
                DB::raw('COUNT(*) as total_bills'),
                DB::raw('SUM(grand_total) as total_revenue')
            )
            ->groupBy(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m')")
            )
            ->get()
            ->keyBy('period_key');

        $rows = collect();

        $current = $from->copy()->startOfMonth();

        while ($current <= $to) {

            $key = $current->format('Y-m');
            $sale = $sales->get($key);

            $rows->push((object) [
                'period_key' => $key,
                'period_label' => $current->format('F Y'),
                'total_bills' => (int) ($sale->total_bills ?? 0),
                'total_revenue' => (float) ($sale->total_revenue ?? 0),
            ]);

            $current->addMonth();
        }

        return $rows->sortByDesc('period_key')->values();
    }


    /*
    |--------------------------------------------------------------------------
    | WEEK / MONTH
    |--------------------------------------------------------------------------
    */

    $from = Carbon::parse($this->fromDate)->startOfDay();
    $to = Carbon::parse($this->toDate)->endOfDay();

    $sales = Sale::query()
        ->whereBetween('created_at', [$from, $to])
        ->select(
            DB::raw('DATE(created_at) as period_key'),
            DB::raw('COUNT(*) as total_bills'),
            DB::raw('SUM(grand_total) as total_revenue')
        )
        ->groupBy(
            DB::raw('DATE(created_at)')
        )
        ->get()
        ->keyBy('period_key');

    $rows = collect();

    $current = $from->copy()->startOfDay();
    $end = $to->copy()->startOfDay();

    while ($current <= $end) {

        $key = $current->format('Y-m-d');

        $sale = $sales->get($key);

        $rows->push((object) [
            'period_key' => $key,
            'period_label' => $current->format('d M Y'),
            'total_bills' => (int) ($sale->total_bills ?? 0),
            'total_revenue' => (float) ($sale->total_revenue ?? 0),
        ]);

        $current->addDay();
    }

    return $rows->sortByDesc('period_key')->values();
}
    /*
    |--------------------------------------------------------------------------
    | Sales By Medicine
    |--------------------------------------------------------------------------
    */

    public function getSalesByMedicineProperty()
    {
        return SaleItem::query()
            ->join(
                'sales',
                'sales.id',
                '=',
                'sale_items.sale_id'
            )
            ->join(
                'medicines',
                'medicines.id',
                '=',
                'sale_items.medicine_id'
            )
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
                'medicines.id',
                'medicines.name',
                DB::raw(
                    'SUM(sale_items.quantity) as sold_quantity'
                ),
                DB::raw(
                    'SUM(sale_items.total) as revenue'
                )
            )
            ->groupBy(
                'medicines.id',
                'medicines.name'
            )
            ->orderByDesc('sold_quantity')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Sales By Category
    |--------------------------------------------------------------------------
    */

    public function getSalesByCategoryProperty()
    {
        return SaleItem::query()
            ->join(
                'sales',
                'sales.id',
                '=',
                'sale_items.sale_id'
            )
            ->join(
                'medicines',
                'medicines.id',
                '=',
                'sale_items.medicine_id'
            )
            ->leftJoin(
                'categories',
                'categories.id',
                '=',
                'medicines.category_id'
            )
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
                'categories.id',
                'categories.name',
                DB::raw(
                    'SUM(sale_items.quantity) as sold_quantity'
                ),
                DB::raw(
                    'SUM(sale_items.total) as revenue'
                )
            )
            ->groupBy(
                'categories.id',
                'categories.name'
            )
            ->orderByDesc('revenue')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Sales By Cashier
    |--------------------------------------------------------------------------
    */

    public function getSalesByCashierProperty()
    {
        $cashierIds = User::role('cashier')->pluck('id');

        return Sale::query()
            ->leftJoin(
                'users',
                'users.id',
                '=',
                'sales.created_by'
            )
            ->whereIn(
                'sales.created_by',
                $cashierIds
            )
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
                DB::raw(
                    'COUNT(sales.id) as total_bills'
                ),
                DB::raw(
                    'SUM(sales.grand_total) as revenue'
                )
            )
            ->groupBy(
                'users.id',
                'users.name'
            )
            ->orderByDesc('revenue')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Apply Custom Filter
    |--------------------------------------------------------------------------
    */

    public function applyFilters(): void
{
    $this->validate([
        'fromDate' => [
            'required',
            'date',
        ],
    ]);

    // Custom Range = EXACT selected date only
    $selectedDate = Carbon::parse($this->fromDate);

    $this->fromDate = $selectedDate
        ->copy()
        ->startOfDay()
        ->format('Y-m-d');

    $this->toDate = $selectedDate
        ->copy()
        ->endOfDay()
        ->format('Y-m-d');

    $this->reportPeriod = 'custom';
}
    /*
    |--------------------------------------------------------------------------
    | Reset
    |--------------------------------------------------------------------------
    */

    public function resetFilters(): void
    {
        $this->setReportPeriod('month');
    }

    /*
    |--------------------------------------------------------------------------
    | Report Title
    |--------------------------------------------------------------------------
    */

    public function getBreakdownTitleProperty(): string
    {
        return match ($this->reportPeriod) {
            'week' => 'WEEKLY SALES',
            'month' => 'MONTHLY SALES',
            'year' => 'YEARLY SALES',
            'custom' => 'SALES BREAKDOWN',
            default => 'SALES BREAKDOWN',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Report Description
    |--------------------------------------------------------------------------
    */

    public function getBreakdownDescriptionProperty(): string
    {
        return match ($this->reportPeriod) {
            'week' => 'Daily sales performance for this week.',
            'month' => 'Daily sales performance for this month.',
            'year' => 'Monthly sales performance for this year.',
            'custom' => 'Sales performance for the selected date range.',
            default => 'Sales performance breakdown.',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Table Period Label
    |--------------------------------------------------------------------------
    */

    public function getBreakdownPeriodLabelProperty(): string
    {
        return $this->reportPeriod === 'year'
            ? 'Month'
            : 'Date';
    }
}