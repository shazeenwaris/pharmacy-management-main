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


    /*
    |--------------------------------------------------------------------------
    | Filter Actions
    |--------------------------------------------------------------------------
    */

    public function applyFilters(): void
    {
        // The queries are generated fresh from the current filter values.
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

        switch ($this->dateRange) {

            case 'today':

                $from = Carbon::today();
                $to = Carbon::today();

                break;


            case '7_days':

                $from = Carbon::today()->subDays(6);
                $to = Carbon::today();

                break;


            case 'this_month':

                $from = Carbon::now()->startOfMonth();
                $to = Carbon::now()->endOfMonth();

                break;


            case 'last_month':

                $lastMonth = Carbon::now()->subMonth();

                $from = $lastMonth->copy()->startOfMonth();
                $to = $lastMonth->copy()->endOfMonth();

                break;


            case 'custom':

                if ($this->fromDate) {
                    $from = Carbon::parse($this->fromDate);
                }

                if ($this->toDate) {
                    $to = Carbon::parse($this->toDate);
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
        $query = Purchase::query();

        $dates = $this->getDateRange();

        if ($dates['from']) {
            $query->whereDate(
                'purchase_date',
                '>=',
                $dates['from']
            );
        }

        if ($dates['to']) {
            $query->whereDate(
                'purchase_date',
                '<=',
                $dates['to']
            );
        }

        if ($this->supplierId) {
            $query->where(
                'supplier_id',
                $this->supplierId
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