<?php

namespace App\Filament\Pages;

use App\Models\Expense;
use App\Models\MedicineBatch;
use App\Models\Purchase;
use App\Models\Sale;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;

class Reports extends Page
{
    protected string $view = 'filament.pages.reports';

    protected static ?string $title = 'Reports';

    protected static ?string $navigationLabel = 'Reports';

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return 'heroicon-o-chart-bar';
    }

    protected static ?int $navigationSort = 90;

    // baqi code same...
    public string $period = 'today';

    public ?string $fromDate = null;

    public ?string $toDate = null;

    public function mount(): void
    {
        $this->setPeriod('today');
    }

    public function setPeriod(string $period): void
    {
        $this->period = $period;

        $today = Carbon::today();

        match ($period) {
            'today' => [
                $this->fromDate = $today->toDateString(),
                $this->toDate = $today->toDateString(),
            ],

            'yesterday' => [
                $this->fromDate = $today->copy()->subDay()->toDateString(),
                $this->toDate = $today->copy()->subDay()->toDateString(),
            ],

            'this_week' => [
                $this->fromDate = $today->copy()->startOfWeek()->toDateString(),
                $this->toDate = $today->copy()->endOfWeek()->toDateString(),
            ],

            'this_month' => [
                $this->fromDate = $today->copy()->startOfMonth()->toDateString(),
                $this->toDate = $today->copy()->endOfMonth()->toDateString(),
            ],

            'this_year' => [
                $this->fromDate = $today->copy()->startOfYear()->toDateString(),
                $this->toDate = $today->copy()->endOfYear()->toDateString(),
            ],

            default => [
                $this->fromDate = $today->toDateString(),
                $this->toDate = $today->toDateString(),
            ],
        };
    }

    public function applyDateRange(): void
    {
        $this->period = 'custom';
    }

    public function getReportStats(): array
    {
        $from = Carbon::parse($this->fromDate)->startOfDay();
        $to = Carbon::parse($this->toDate)->endOfDay();

        $revenue = Sale::query()
            ->whereBetween('created_at', [$from, $to])
            ->sum('grand_total');

        $purchases = Purchase::query()
            ->whereBetween('purchase_date', [
                $from->toDateString(),
                $to->toDateString(),
            ])
            ->sum('total_amount');

        $expenses = Expense::query()
            ->whereBetween('expense_date', [
                $from->toDateString(),
                $to->toDateString(),
            ])
            ->sum('amount');

        $stockValue = MedicineBatch::query()
            ->where('status', true)
            ->selectRaw('COALESCE(SUM(quantity * COALESCE(purchase_price, 0)), 0) as value')
            ->value('value');

        return [
            'revenue' => (float) $revenue,
            'purchases' => (float) $purchases,
            'expenses' => (float) $expenses,
            'stock_value' => (float) $stockValue,
        ];
    }

    public function getGrossProfit(): float
    {
        /*
         * Initial dashboard calculation.
         *
         * Detailed COGS will be implemented in the
         * dedicated financial report after we connect
         * SaleItem -> MedicineBatch.
         */
        return 0;
    }

    public function getNetProfit(): float
    {
        return $this->getGrossProfit()
            - $this->getReportStats()['expenses'];
    }
}