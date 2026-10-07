<?php

namespace App\Filament\Pages;

use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use Carbon\Carbon;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Builder;

class InventoryReports extends Page
{
    protected static ?string $title = 'Inventory Reports';

    protected static ?string $navigationLabel = 'Inventory Reports';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cube';

    protected static ?string $slug = 'reports/inventory';

    protected string $view = 'filament.pages.inventory-reports';

    public ?string $search = null;

    public ?string $category = null;

    public ?string $manufacturer = null;

    public ?string $stockStatus = null;

    public ?string $expiryStatus = null;

    public function resetFilters(): void
    {
        $this->search = null;
        $this->category = null;
        $this->manufacturer = null;
        $this->stockStatus = null;
        $this->expiryStatus = null;
    }

    public function getViewData(): array
    {
        $today = Carbon::today();
        $nearExpiryDate = Carbon::today()->addDays(30);

        /*
        |--------------------------------------------------------------------------
        | Base Medicine Query
        |--------------------------------------------------------------------------
        */

        $medicineQuery = Medicine::query()
            ->withSum([
                'batches as current_stock' => function ($query) use ($today) {
                    $query
                        ->where('status', true)
                        ->whereDate('expiry_date', '>=', $today);
                }
            ], 'quantity')
            ->where('status', true);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($this->search) {
            $search = trim($this->search);

            $medicineQuery->where(function (Builder $query) use ($search) {
                $query
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('generic_name', 'like', "%{$search}%")
                    ->orWhere('brand_name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Category Filter
        |--------------------------------------------------------------------------
        */

        if ($this->category) {
            $medicineQuery->where('category_id', $this->category);
        }

        /*
        |--------------------------------------------------------------------------
        | Manufacturer Filter
        |--------------------------------------------------------------------------
        */

        if ($this->manufacturer) {
            $medicineQuery->where('manufacturer_id', $this->manufacturer);
        }

        $medicines = $medicineQuery->get();

        /*
        |--------------------------------------------------------------------------
        | Stock Status Filter
        |--------------------------------------------------------------------------
        */

        $medicines = $medicines->filter(function ($medicine) {

            $stock = (int) ($medicine->current_stock ?? 0);

            return match ($this->stockStatus) {

                'in_stock' =>
                    $stock > (int) $medicine->reorder_level,

                'low_stock' =>
                    $stock > 0 &&
                    $stock <= (int) $medicine->reorder_level,

                'out_of_stock' =>
                    $stock <= 0,

                default => true,
            };
        })->values();

        /*
        |--------------------------------------------------------------------------
        | Low Stock
        |--------------------------------------------------------------------------
        */

        $lowStockMedicines = $medicines
            ->filter(function ($medicine) {

                $stock = (int) ($medicine->current_stock ?? 0);

                return $stock > 0 &&
                    $stock <= (int) $medicine->reorder_level;
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Out Of Stock
        |--------------------------------------------------------------------------
        */

        $outOfStockMedicines = $medicines
            ->filter(function ($medicine) {
                return (int) ($medicine->current_stock ?? 0) <= 0;
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Current Stock
        |--------------------------------------------------------------------------
        */

        $currentStock = $medicines->sum(function ($medicine) {
            return (int) ($medicine->current_stock ?? 0);
        });

        /*
        |--------------------------------------------------------------------------
        | Batches Query
        |--------------------------------------------------------------------------
        */

        $batchQuery = MedicineBatch::query()
            ->with('medicine')
            ->where('status', true)
            ->where('quantity', '>', 0)
            ->whereHas('medicine', function (Builder $query) {

                $query->where('status', true);

                if ($this->search) {

                    $search = trim($this->search);

                    $query->where(function (Builder $query) use ($search) {
                        $query
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('generic_name', 'like', "%{$search}%")
                            ->orWhere('brand_name', 'like', "%{$search}%")
                            ->orWhere('sku', 'like', "%{$search}%")
                            ->orWhere('barcode', 'like', "%{$search}%");
                    });
                }

                if ($this->category) {
                    $query->where('category_id', $this->category);
                }

                if ($this->manufacturer) {
                    $query->where('manufacturer_id', $this->manufacturer);
                }
            });

        /*
        |--------------------------------------------------------------------------
        | Expiry Status Filter
        |--------------------------------------------------------------------------
        */

        if ($this->expiryStatus === 'expired') {

            $batchQuery
                ->whereDate('expiry_date', '<', $today);

        } elseif ($this->expiryStatus === 'near_expiry') {

            $batchQuery
                ->whereDate('expiry_date', '>=', $today)
                ->whereDate('expiry_date', '<=', $nearExpiryDate);

        } elseif ($this->expiryStatus === 'active') {

            $batchQuery
                ->whereDate('expiry_date', '>', $nearExpiryDate);

        }

        $filteredBatches = $batchQuery
            ->orderBy('expiry_date')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Expired
        |--------------------------------------------------------------------------
        */

        // $expiredBatches = $filteredBatches
        //     ->filter(fn ($batch) => $batch->expiry_date->lt($today))
        //     ->values();
        $expiredBatches = $filteredBatches
    ->filter(fn ($batch) => $batch->expiry_date && $batch->expiry_date->lt($today))
    ->values();

        /*
        |--------------------------------------------------------------------------
        | Near Expiry
        |--------------------------------------------------------------------------
        */

        $nearExpiryBatches = $filteredBatches
            // ->filter(fn ($batch) =>
            //     $batch->expiry_date->gte($today) &&
            //     $batch->expiry_date->lte($nearExpiryDate)
            // )
            ->filter(fn ($batch) => $batch->expiry_date?->lt($today))
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Stock Valuation
        |--------------------------------------------------------------------------
        */

        $stockValuation = $medicines->sum(function ($medicine) {

            $stock = (int) ($medicine->current_stock ?? 0);

            return $stock * (float) $medicine->purchase_price;
        });

        /*
        |--------------------------------------------------------------------------
        | Total Medicines
        |--------------------------------------------------------------------------
        */

        $totalMedicines = $medicines->count();

        /*
        |--------------------------------------------------------------------------
        | Total Batches
        |--------------------------------------------------------------------------
        */

        $totalBatches = $filteredBatches->count();

        return [

            'categories' => Category::orderBy('name')->get(),

            'manufacturers' => Manufacturer::orderBy('name')->get(),

            'totalMedicines' => $totalMedicines,

            'totalBatches' => $totalBatches,

            'currentStock' => $currentStock,

            'lowStockCount' => $lowStockMedicines->count(),

            'outOfStockCount' => $outOfStockMedicines->count(),

            'expiredCount' => $expiredBatches->count(),

            'nearExpiryCount' => $nearExpiryBatches->count(),

            'stockValuation' => $stockValuation,

            'lowStockMedicines' => $lowStockMedicines,

            'outOfStockMedicines' => $outOfStockMedicines,

            'expiredBatches' => $expiredBatches,

            'nearExpiryBatches' => $nearExpiryBatches,
        ];
    }
}