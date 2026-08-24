<x-filament-panels::page>

    @php
        $stats = $this->getReportStats();
        $grossProfit = $this->getGrossProfit();
        $netProfit = $this->getNetProfit();
    @endphp

    <div class="space-y-6">

        {{-- =====================================================
             HEADER / FILTER
        ====================================================== --}}

        <div class="pms-card">

            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">

                <div>
                    <h2 class="text-xl font-bold text-pms-text-primary">
                        Business Reports
                    </h2>

                    <p class="mt-1 text-sm text-pms-text-muted">
                        Analyze your pharmacy sales, purchases, inventory and finances.
                    </p>
                </div>

                <div class="flex flex-wrap items-end gap-3">

                    {{-- Period --}}
                    <div>
                        <label class="mb-1 block text-xs font-medium text-pms-text-muted">
                            Period
                        </label>

                        <select
                            wire:model.live="period"
                            wire:change="setPeriod($event.target.value)"
                            class="rounded-lg border border-pms-border bg-white px-3 py-2 text-sm text-pms-text-primary focus:border-pms-primary focus:ring-pms-primary"
                        >
                            <option value="today">Today</option>
                            <option value="yesterday">Yesterday</option>
                            <option value="this_week">This Week</option>
                            <option value="this_month">This Month</option>
                            <option value="this_year">This Year</option>
                            <option value="custom">Custom</option>
                        </select>
                    </div>

                    {{-- From --}}
                    <div>
                        <label class="mb-1 block text-xs font-medium text-pms-text-muted">
                            From
                        </label>

                        <input
                            type="date"
                            wire:model.live="fromDate"
                            class="rounded-lg border border-pms-border bg-white px-3 py-2 text-sm text-pms-text-primary"
                        >
                    </div>

                    {{-- To --}}
                    <div>
                        <label class="mb-1 block text-xs font-medium text-pms-text-muted">
                            To
                        </label>

                        <input
                            type="date"
                            wire:model.live="toDate"
                            class="rounded-lg border border-pms-border bg-white px-3 py-2 text-sm text-pms-text-primary"
                        >
                    </div>

                    <button
                        type="button"
                        wire:click="applyDateRange"
                        class="pms-btn-primary"
                    >
                        Apply
                    </button>

                </div>

            </div>

        </div>


        {{-- =====================================================
             KPI CARDS
        ====================================================== --}}

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            {{-- Revenue --}}
            <div class="pms-stat-card">

                <div class="flex items-start justify-between">

                    <div>
                        <p class="pms-stat-label">
                            Total Revenue
                        </p>

                        <h3 class="pms-stat-value mt-1">
                            Rs. {{ number_format($stats['revenue'], 2) }}
                        </h3>
                    </div>

                    <div class="pms-stat-card-icon">
                        <x-heroicon-o-banknotes class="h-5 w-5"/>
                    </div>

                </div>

                <p class="text-xs text-pms-text-muted">
                    Sales generated during selected period
                </p>

            </div>


            {{-- Purchases --}}
            <div class="pms-stat-card">

                <div class="flex items-start justify-between">

                    <div>
                        <p class="pms-stat-label">
                            Total Purchases
                        </p>

                        <h3 class="pms-stat-value mt-1">
                            Rs. {{ number_format($stats['purchases'], 2) }}
                        </h3>
                    </div>

                    <div class="pms-stat-card-icon">
                        <x-heroicon-o-shopping-cart class="h-5 w-5"/>
                    </div>

                </div>

                <p class="text-xs text-pms-text-muted">
                    Purchases during selected period
                </p>

            </div>


            {{-- Expenses --}}
            <div class="pms-stat-card">

                <div class="flex items-start justify-between">

                    <div>
                        <p class="pms-stat-label">
                            Total Expenses
                        </p>

                        <h3 class="pms-stat-value mt-1">
                            Rs. {{ number_format($stats['expenses'], 2) }}
                        </h3>
                    </div>

                    <div class="pms-stat-card-icon">
                        <x-heroicon-o-receipt-percent class="h-5 w-5"/>
                    </div>

                </div>

                <p class="text-xs text-pms-text-muted">
                    Operating expenses
                </p>

            </div>


            {{-- Stock Value --}}
            <div class="pms-stat-card">

                <div class="flex items-start justify-between">

                    <div>
                        <p class="pms-stat-label">
                            Stock Valuation
                        </p>

                        <h3 class="pms-stat-value mt-1">
                            Rs. {{ number_format($stats['stock_value'], 2) }}
                        </h3>
                    </div>

                    <div class="pms-stat-card-icon">
                        <x-heroicon-o-cube class="h-5 w-5"/>
                    </div>

                </div>

                <p class="text-xs text-pms-text-muted">
                    Current inventory cost value
                </p>

            </div>

        </div>


        {{-- =====================================================
             REPORT CATEGORIES
        ====================================================== --}}

        <div>

            <div class="mb-4">
                <h2 class="text-lg font-bold text-pms-text-primary">
                    Reports
                </h2>

                <p class="text-sm text-pms-text-muted">
                    Select a report category to explore detailed business data.
                </p>
            </div>


            {{-- SALES --}}
            <div class="mb-6">

                <h3 class="mb-3 flex items-center gap-2 text-base font-semibold text-pms-text-primary">

                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-pms-success-light text-pms-primary">
                        <x-heroicon-o-chart-bar class="h-4 w-4"/>
                    </span>

                    Sales Reports

                </h3>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">

                    @foreach ([
                        ['title' => 'Daily Sales', 'description' => 'View sales by day.', 'icon' => 'calendar-days'],
                        ['title' => 'Weekly Sales', 'description' => 'Analyze weekly performance.', 'icon' => 'chart-bar'],
                        ['title' => 'Monthly Sales', 'description' => 'Track monthly revenue.', 'icon' => 'presentation-chart-line'],
                        ['title' => 'Yearly Sales', 'description' => 'Review yearly performance.', 'icon' => 'calendar'],
                        ['title' => 'Sales by Medicine', 'description' => 'Find your best-selling medicines.', 'icon' => 'beaker'],
                        ['title' => 'Sales by Category', 'description' => 'Compare category performance.', 'icon' => 'squares-2x2'],
                        ['title' => 'Sales by Cashier', 'description' => 'Analyze cashier performance.', 'icon' => 'user-group'],
                    ] as $report)

                        <div class="pms-card group transition hover:-translate-y-0.5 hover:shadow-md">

                            <div class="flex items-start gap-3">

                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-pms-success-light text-pms-primary">
                                    <x-heroicon-o-document-chart-bar class="h-5 w-5"/>
                                </div>

                                <div class="min-w-0 flex-1">

                                    <h4 class="font-semibold text-pms-text-primary">
                                        {{ $report['title'] }}
                                    </h4>

                                    <p class="mt-1 text-xs leading-5 text-pms-text-muted">
                                        {{ $report['description'] }}
                                    </p>

                                    <button
                                        type="button"
                                        class="mt-3 text-xs font-semibold text-pms-primary hover:underline"
                                    >
                                        View Report →
                                    </button>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>


            {{-- PURCHASE --}}
            <div class="mb-6">

                <h3 class="mb-3 flex items-center gap-2 text-base font-semibold text-pms-text-primary">

                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-pms-success-light text-pms-primary">
                        <x-heroicon-o-shopping-cart class="h-4 w-4"/>
                    </span>

                    Purchase Reports

                </h3>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">

                    @foreach ([
                        ['title' => 'Daily Purchases', 'description' => 'Review purchases by date.'],
                        ['title' => 'Supplier-wise Purchases', 'description' => 'Compare supplier spending.'],
                        ['title' => 'Medicine-wise Purchases', 'description' => 'Analyze purchased medicines.'],
                    ] as $report)

                        <div class="pms-card group transition hover:-translate-y-0.5 hover:shadow-md">

                            <div class="flex items-start gap-3">

                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-pms-success-light text-pms-primary">
                                    <x-heroicon-o-document-chart-bar class="h-5 w-5"/>
                                </div>

                                <div>

                                    <h4 class="font-semibold text-pms-text-primary">
                                        {{ $report['title'] }}
                                    </h4>

                                    <p class="mt-1 text-xs text-pms-text-muted">
                                        {{ $report['description'] }}
                                    </p>

                                    <button
                                        type="button"
                                        class="mt-3 text-xs font-semibold text-pms-primary hover:underline"
                                    >
                                        View Report →
                                    </button>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>


            {{-- INVENTORY --}}
            <div class="mb-6">

                <h3 class="mb-3 flex items-center gap-2 text-base font-semibold text-pms-text-primary">

                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-pms-success-light text-pms-primary">
                        <x-heroicon-o-cube class="h-4 w-4"/>
                    </span>

                    Inventory Reports

                </h3>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">

                    @foreach ([
                        ['title' => 'Current Stock', 'description' => 'View current inventory levels.'],
                        ['title' => 'Low Stock', 'description' => 'Medicines requiring reorder.'],
                        ['title' => 'Out of Stock', 'description' => 'Medicines currently unavailable.'],
                        ['title' => 'Expired Medicines', 'description' => 'View expired inventory.'],
                        ['title' => 'Near-expiry Medicines', 'description' => 'Track upcoming expiries.'],
                        ['title' => 'Stock Valuation', 'description' => 'Calculate current stock value.'],
                    ] as $report)

                        <div class="pms-card transition hover:-translate-y-0.5 hover:shadow-md">

                            <div class="flex items-start gap-3">

                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-pms-success-light text-pms-primary">
                                    <x-heroicon-o-document-chart-bar class="h-5 w-5"/>
                                </div>

                                <div>

                                    <h4 class="font-semibold text-pms-text-primary">
                                        {{ $report['title'] }}
                                    </h4>

                                    <p class="mt-1 text-xs text-pms-text-muted">
                                        {{ $report['description'] }}
                                    </p>

                                    <button
                                        type="button"
                                        class="mt-3 text-xs font-semibold text-pms-primary hover:underline"
                                    >
                                        View Report →
                                    </button>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>


            {{-- FINANCIAL --}}
            <div>

                <h3 class="mb-3 flex items-center gap-2 text-base font-semibold text-pms-text-primary">

                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-pms-success-light text-pms-primary">
                        <x-heroicon-o-banknotes class="h-4 w-4"/>
                    </span>

                    Financial Reports

                </h3>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">

                    @foreach ([
                        ['title' => 'Revenue', 'description' => 'Track total sales revenue.'],
                        ['title' => 'Cost of Goods', 'description' => 'Analyze medicine cost.'],
                        ['title' => 'Gross Profit', 'description' => 'Revenue minus cost of goods.'],
                        ['title' => 'Expenses', 'description' => 'Review business expenses.'],
                        ['title' => 'Net Profit', 'description' => 'Track final business profit.'],
                        ['title' => 'Supplier Dues', 'description' => 'View outstanding supplier balances.'],
                        ['title' => 'Customer Dues', 'description' => 'View outstanding customer balances.'],
                    ] as $report)

                        <div class="pms-card transition hover:-translate-y-0.5 hover:shadow-md">

                            <div class="flex items-start gap-3">

                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-pms-success-light text-pms-primary">
                                    <x-heroicon-o-document-chart-bar class="h-5 w-5"/>
                                </div>

                                <div>

                                    <h4 class="font-semibold text-pms-text-primary">
                                        {{ $report['title'] }}
                                    </h4>

                                    <p class="mt-1 text-xs text-pms-text-muted">
                                        {{ $report['description'] }}
                                    </p>

                                    <button
                                        type="button"
                                        class="mt-3 text-xs font-semibold text-pms-primary hover:underline"
                                    >
                                        View Report →
                                    </button>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>

    </div>

</x-filament-panels::page>