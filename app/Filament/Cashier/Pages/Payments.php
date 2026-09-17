<?php

namespace App\Filament\Cashier\Pages;

use App\Models\Sale;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Collection;

class Payments extends Page
{
    protected string $view = 'filament.cashier.pages.payments';

    public string $search = '';

    /**
     * Get all sales that still have an outstanding balance.
     */
    public function getDueSalesProperty(): Collection
    {
        return Sale::query()
            ->with('customer')
            ->whereColumn('paid_amount', '<', 'grand_total')
            ->when(
                filled($this->search),
                function ($query) {
                    $search = trim($this->search);

                    $query->where(function ($q) use ($search) {
                        $q->where('invoice_number', 'like', "%{$search}%")
                            ->orWhereHas('customer', function ($customerQuery) use ($search) {
                                $customerQuery
                                    ->where('name', 'like', "%{$search}%")
                                    ->orWhere('phone', 'like', "%{$search}%");
                            });
                    });
                }
            )
            ->latest('id')
            ->get();
    }

    /**
     * Total outstanding amount across all due sales.
     */
    public function getTotalOutstandingProperty(): float
    {
        return (float) Sale::query()
            ->whereColumn('paid_amount', '<', 'grand_total')
            ->selectRaw('COALESCE(SUM(grand_total - paid_amount), 0) as total')
            ->value('total');
    }

    /**
     * Number of sales with an outstanding balance.
     */
    public function getOutstandingSalesCountProperty(): int
    {
        return Sale::query()
            ->whereColumn('paid_amount', '<', 'grand_total')
            ->count();
    }

    /**
     * Calculate outstanding amount for a sale.
     */
    public function getDueAmount(Sale $sale): float
    {
        return max(
            0,
            (float) $sale->grand_total - (float) $sale->paid_amount
        );
    }
}