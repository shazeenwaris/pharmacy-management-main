<?php

namespace App\Filament\Pages;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\PurchaseReturn;
use App\Models\Supplier;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PurchaseReports extends Page
{
    protected static ?string $title = 'Purchase Reports';

    protected static ?string $navigationLabel = 'Purchase Reports';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $slug = 'reports/purchases';

    protected string $view = 'filament.pages.purchase-reports';


    /*
    |--------------------------------------------------------------------------
    | Filters
    |--------------------------------------------------------------------------
    */

   public string $dateRange = 'all';

public ?string $fromDate = null;
public ?string $toDate = null;

public ?int $supplierId = null;

// Actual applied filters
public string $appliedDateRange = 'all';
public ?string $appliedFromDate = null;
public ?string $appliedToDate = null;
public ?int $appliedSupplierId = null;

    /*
    |--------------------------------------------------------------------------
    | Filter Actions
    |--------------------------------------------------------------------------
    */

 public function applyFilters(): void
{
    if ($this->dateRange === 'custom') {
        $this->validate([
            'fromDate' => ['required', 'date'],
            'toDate' => ['required', 'date', 'after_or_equal:fromDate'],
        ]);
    }

    $this->appliedDateRange = $this->dateRange;
    $this->appliedFromDate = $this->fromDate;
    $this->appliedToDate = $this->toDate;
    $this->appliedSupplierId = $this->supplierId;
}

    public function resetFilters(): void
    {
        $this->dateRange = 'all';

        $this->fromDate = null;

        $this->toDate = null;

        $this->supplierId = null;
    }


    /*
    |--------------------------------------------------------------------------
    | Date Filter
    |--------------------------------------------------------------------------
    */
protected function getDateRange(): array
{
    $from = null;
    $to = null;

    switch ($this->appliedDateRange) {

        case 'today':

            $from = Carbon::today()->startOfDay();
            $to = Carbon::today()->endOfDay();

            break;


        case '7_days':

            $from = Carbon::today()
                ->subDays(6)
                ->startOfDay();

            $to = Carbon::today()
                ->endOfDay();

            break;


        case 'this_month':

            $from = Carbon::now()
                ->startOfMonth()
                ->startOfDay();

            $to = Carbon::now()
                ->endOfMonth()
                ->endOfDay();

            break;


        case 'last_month':

            $lastMonth = Carbon::now()->subMonth();

            $from = $lastMonth
                ->copy()
                ->startOfMonth()
                ->startOfDay();

            $to = $lastMonth
                ->copy()
                ->endOfMonth()
                ->endOfDay();

            break;


        case 'this_year':

            $from = Carbon::now()
                ->startOfYear()
                ->startOfDay();

            $to = Carbon::now()
                ->endOfYear()
                ->endOfDay();

            break;


        case 'last_year':

            $lastYear = Carbon::now()->subYear();

            $from = $lastYear
                ->copy()
                ->startOfYear()
                ->startOfDay();

            $to = $lastYear
                ->copy()
                ->endOfYear()
                ->endOfDay();

            break;


        case 'custom':

            if ($this->appliedFromDate) {

                $from = Carbon::parse($this->appliedFromDate)
                    ->startOfDay();
            }

            if ($this->appliedToDate) {

                $to = Carbon::parse($this->appliedToDate)
                    ->endOfDay();
            }

            break;
    }

    return [
        'from' => $from,
        'to' => $to,
    ];
}

    /*
    |--------------------------------------------------------------------------
    | Base Purchase Query
    |--------------------------------------------------------------------------
    */

    protected function purchaseQuery()
{
    $dates = $this->getDateRange();

    $query = Purchase::query();

    /*
    |--------------------------------------------------------------------------
    | DATE FILTER
    |--------------------------------------------------------------------------
    */

    if ($dates['from']) {
        $query->whereDate(
            'purchase_date',
            '>=',
            $dates['from']->format('Y-m-d')
        );
    }

    if ($dates['to']) {
        $query->whereDate(
            'purchase_date',
            '<=',
            $dates['to']->format('Y-m-d')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SUPPLIER FILTER
    |--------------------------------------------------------------------------
    */

    if ($this->appliedSupplierId) {

        $query->where(
            'supplier_id',
            $this->appliedSupplierId
        );
    }

    return $query;
}


    /*
    |--------------------------------------------------------------------------
    | View Data
    |--------------------------------------------------------------------------
    */

    public function getViewData(): array
    {
        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $totalPurchases = $this->purchaseQuery()
            ->count();


        $totalPurchaseAmount = $this->purchaseQuery()
            ->sum('total_amount');


        /*
        |--------------------------------------------------------------------------
        | Purchased Items
        |--------------------------------------------------------------------------
        */

        $purchaseIds = $this->purchaseQuery()
            ->pluck('id');


        $totalItemsPurchased = PurchaseItem::query()
            ->whereIn('purchase_id', $purchaseIds)
            ->sum('quantity');


        /*
        |--------------------------------------------------------------------------
        | Purchase Returns
        |--------------------------------------------------------------------------
        */

        $returnsQuery = PurchaseReturn::query();

        $dates = $this->getDateRange();

        if ($dates['from']) {
            $returnsQuery->whereDate(
                'return_date',
                '>=',
                $dates['from']
            );
        }

        if ($dates['to']) {
            $returnsQuery->whereDate(
                'return_date',
                '<=',
                $dates['to']
            );
        }

        if ($this->supplierId) {
            $returnsQuery->whereHas(
                'purchase',
                function ($query) {
                    $query->where(
                        'supplier_id',
                        $this->supplierId
                    );
                }
            );
        }

        $totalReturns = $returnsQuery->sum(
            'refund_amount'
        );


        /*
        |--------------------------------------------------------------------------
        | Daily Purchases
        |--------------------------------------------------------------------------
        */

        $dailyPurchases = $this->purchaseQuery()
            ->select(
                'purchase_date',
                DB::raw('COUNT(*) as purchase_count'),
                DB::raw('SUM(total_amount) as total_amount')
            )
            ->groupBy('purchase_date')
            ->orderByDesc('purchase_date')
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Supplier Purchases
        |--------------------------------------------------------------------------
        */

        $supplierPurchases = Purchase::query()
            ->join(
                'suppliers',
                'purchases.supplier_id',
                '=',
                'suppliers.id'
            )
            ->select(
                'suppliers.id',
                'suppliers.supplier_name',
                DB::raw('COUNT(purchases.id) as purchase_count'),
                DB::raw('SUM(purchases.total_amount) as total_amount')
            )
            ->when(
                $dates['from'],
                fn ($query) =>
                    $query->whereDate(
                        'purchases.purchase_date',
                        '>=',
                        $dates['from']
                    )
            )
            ->when(
                $dates['to'],
                fn ($query) =>
                    $query->whereDate(
                        'purchases.purchase_date',
                        '<=',
                        $dates['to']
                    )
            )
            ->when(
                $this->supplierId,
                fn ($query) =>
                    $query->where(
                        'purchases.supplier_id',
                        $this->supplierId
                    )
            )
            ->groupBy(
                'suppliers.id',
                'suppliers.supplier_name'
            )
            ->orderByDesc('total_amount')
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Medicine Purchases
        |--------------------------------------------------------------------------
        */

        $medicinePurchases = PurchaseItem::query()
            ->join(
                'medicines',
                'purchase_items.medicine_id',
                '=',
                'medicines.id'
            )
            ->whereIn(
                'purchase_items.purchase_id',
                $purchaseIds
            )
            ->select(
                'medicines.id',
                'medicines.name',
                DB::raw(
                    'SUM(purchase_items.quantity) as total_quantity'
                ),
                DB::raw(
                    'SUM(purchase_items.total_amount) as total_amount'
                )
            )
            ->groupBy(
                'medicines.id',
                'medicines.name'
            )
            ->orderByDesc('total_amount')
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Recent Purchases
        |--------------------------------------------------------------------------
        */

        $recentPurchases = $this->purchaseQuery()
            ->with([
                'supplier',
                'creator',
            ])
            ->latest('purchase_date')
            ->latest('id')
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Suppliers
        |--------------------------------------------------------------------------
        */

        $suppliers = Supplier::query()
            ->orderBy('supplier_name')
            ->get([
                'id',
                'supplier_name',
            ]);


        return [

            'totalPurchases' => $totalPurchases,

            'totalPurchaseAmount' => $totalPurchaseAmount,

            'totalItemsPurchased' => $totalItemsPurchased,

            'totalReturns' => $totalReturns,

            'dailyPurchases' => $dailyPurchases,

            'supplierPurchases' => $supplierPurchases,

            'medicinePurchases' => $medicinePurchases,

            'recentPurchases' => $recentPurchases,

            'suppliers' => $suppliers,

        ];
    }
}