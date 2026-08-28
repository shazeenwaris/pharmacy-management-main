<?php

namespace App\Filament\Pages;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\SupplierPayment;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class FinancialReports extends Page
{
    protected static ?string $title = 'Financial Reports';

    protected static ?string $navigationLabel = 'Financial Reports';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $slug = 'reports/financial';

    protected string $view = 'filament.pages.financial-reports';

    public string $dateRange = 'all';

    public ?string $fromDate = null;

    public ?string $toDate = null;

    public function mount(): void
    {
        $this->fromDate = null;
        $this->toDate = null;
    }

    public function applyFilters(): void
    {
        $this->dispatch('$refresh');
    }

    public function resetFilters(): void
    {
        $this->dateRange = 'all';
        $this->fromDate = null;
        $this->toDate = null;

        $this->dispatch('$refresh');
    }

    protected function getDateRange(): array
    {
        $from = null;
        $to = null;

        switch ($this->dateRange) {
            case 'today':
                $from = Carbon::today()->startOfDay();
                $to = Carbon::today()->endOfDay();
                break;

            case '7_days':
                $from = Carbon::today()->subDays(6)->startOfDay();
                $to = Carbon::today()->endOfDay();
                break;

            case 'this_month':
                $from = Carbon::now()->startOfMonth();
                $to = Carbon::now()->endOfMonth();
                break;

            case 'last_month':
                $from = Carbon::now()->subMonth()->startOfMonth();
                $to = Carbon::now()->subMonth()->endOfMonth();
                break;

            case 'custom':
                if ($this->fromDate) {
                    $from = Carbon::parse($this->fromDate)->startOfDay();
                }

                if ($this->toDate) {
                    $to = Carbon::parse($this->toDate)->endOfDay();
                }
                break;
        }

        return [$from, $to];
    }

    protected function applyDateFilter($query, string $column, $from, $to)
    {
        if ($from) {
            $query->where($column, '>=', $from);
        }

        if ($to) {
            $query->where($column, '<=', $to);
        }

        return $query;
    }

    protected function getFinancialData(): array
    {
        [$from, $to] = $this->getDateRange();

        /*
        |--------------------------------------------------------------------------
        | Revenue
        |--------------------------------------------------------------------------
        */

        $salesQuery = Sale::query();

        $this->applyDateFilter(
            $salesQuery,
            'created_at',
            $from,
            $to
        );

        $totalRevenue = (float) $salesQuery->sum('grand_total');

        $totalSales = (int) $salesQuery->count();

        /*
        |--------------------------------------------------------------------------
        | Purchase Cost / COGS
        |--------------------------------------------------------------------------
        */

        $purchaseQuery = Purchase::query();

        $this->applyDateFilter(
            $purchaseQuery,
            'purchase_date',
            $from?->toDateString(),
            $to?->toDateString()
        );

        $purchaseIds = $purchaseQuery->pluck('id');

        $totalPurchaseCost = 0;

        if ($purchaseIds->isNotEmpty()) {
            $totalPurchaseCost = (float) DB::table('purchase_items')
                ->whereIn('purchase_id', $purchaseIds)
                ->sum('total_amount');
        }

        $totalPurchases = $purchaseIds->count();

        /*
        |--------------------------------------------------------------------------
        | Expenses
        |--------------------------------------------------------------------------
        */

        $expenseQuery = Expense::query();

        $this->applyDateFilter(
            $expenseQuery,
            'expense_date',
            $from?->toDateString(),
            $to?->toDateString()
        );

        $totalExpenses = (float) $expenseQuery->sum('amount');

        $totalExpenseCount = (int) $expenseQuery->count();

        /*
        |--------------------------------------------------------------------------
        | Profit
        |--------------------------------------------------------------------------
        */

        $grossProfit = $totalRevenue - $totalPurchaseCost;

        $netProfit = $grossProfit - $totalExpenses;

        /*
        |--------------------------------------------------------------------------
        | Supplier Dues
        |--------------------------------------------------------------------------
        |
        | Opening Balance + Purchases - Payments
        |
        */

        $supplierOpeningBalance = (float) Supplier::sum('opening_balance');

        $supplierPurchasesQuery = Purchase::query();

        $this->applyDateFilter(
            $supplierPurchasesQuery,
            'purchase_date',
            $from?->toDateString(),
            $to?->toDateString()
        );

        $supplierPurchases = (float) $supplierPurchasesQuery->sum('total_amount');

        $supplierPaymentsQuery = SupplierPayment::query();

        $this->applyDateFilter(
            $supplierPaymentsQuery,
            'payment_date',
            $from?->toDateString(),
            $to?->toDateString()
        );

        $supplierPayments = (float) $supplierPaymentsQuery->sum('amount');

        $supplierDues =
            $supplierOpeningBalance
            + $supplierPurchases
            - $supplierPayments;

        /*
        |--------------------------------------------------------------------------
        | Customer Dues
        |--------------------------------------------------------------------------
        |
        | Opening Balance + Sales - Paid Amount
        |
        */

        $customerOpeningBalance = (float) Customer::sum('opening_balance');

        $customerSalesQuery = Sale::query();

        $this->applyDateFilter(
            $customerSalesQuery,
            'created_at',
            $from,
            $to
        );

        $customerSales = (float) $customerSalesQuery->sum('grand_total');

        $customerPaid = (float) $customerSalesQuery->sum('paid_amount');

        $customerDues =
            $customerOpeningBalance
            + $customerSales
            - $customerPaid;

        /*
        |--------------------------------------------------------------------------
        | Recent Sales
        |--------------------------------------------------------------------------
        */

        $recentSalesQuery = Sale::with('customer')
            ->latest('created_at')
            ->limit(10);

        $this->applyDateFilter(
            $recentSalesQuery,
            'created_at',
            $from,
            $to
        );

        $recentSales = $recentSalesQuery->get();

        /*
        |--------------------------------------------------------------------------
        | Recent Expenses
        |--------------------------------------------------------------------------
        */

        $recentExpensesQuery = Expense::with('category')
            ->latest('expense_date')
            ->limit(10);

        $this->applyDateFilter(
            $recentExpensesQuery,
            'expense_date',
            $from?->toDateString(),
            $to?->toDateString()
        );

        $recentExpenses = $recentExpensesQuery->get();

        /*
        |--------------------------------------------------------------------------
        | Monthly Revenue
        |--------------------------------------------------------------------------
        */

        $monthlyRevenueQuery = Sale::query()
            ->selectRaw('DATE(created_at) as report_date')
            ->selectRaw('COUNT(*) as sale_count')
            ->selectRaw('SUM(grand_total) as total_amount')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderByDesc('report_date')
            ->limit(10);

        $this->applyDateFilter(
            $monthlyRevenueQuery,
            'created_at',
            $from,
            $to
        );

        $dailyRevenue = $monthlyRevenueQuery->get();

        /*
        |--------------------------------------------------------------------------
        | Expense Categories
        |--------------------------------------------------------------------------
        */

        $expenseCategoryQuery = Expense::query()
            ->leftJoin(
                'expense_categories',
                'expenses.expense_category_id',
                '=',
                'expense_categories.id'
            )
            ->select(
                'expense_categories.name as category_name'
            )
            ->selectRaw('SUM(expenses.amount) as total_amount')
            ->selectRaw('COUNT(expenses.id) as expense_count')
            ->groupBy('expense_categories.id', 'expense_categories.name')
            ->orderByDesc('total_amount')
            ->limit(10);

        $this->applyDateFilter(
            $expenseCategoryQuery,
            'expenses.expense_date',
            $from?->toDateString(),
            $to?->toDateString()
        );

        $expenseCategories = $expenseCategoryQuery->get();

        return compact(
            'totalRevenue',
            'totalSales',
            'totalPurchaseCost',
            'totalPurchases',
            'totalExpenses',
            'totalExpenseCount',
            'grossProfit',
            'netProfit',
            'supplierDues',
            'supplierPurchases',
            'supplierPayments',
            'customerDues',
            'customerSales',
            'customerPaid',
            'recentSales',
            'recentExpenses',
            'dailyRevenue',
            'expenseCategories',
            'from',
            'to'
        );
    }

    protected function getViewData(): array
    {
        return $this->getFinancialData();
    }
}