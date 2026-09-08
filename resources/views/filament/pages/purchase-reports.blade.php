<x-filament-panels::page>

    <div class="pms-purchase-reports space-y-7">

        {{-- =========================================================
             PAGE HEADER
        ========================================================== --}}
        <div class="relative overflow-hidden rounded-3xl px-6 py-6 shadow-sm sm:px-7 sm:py-7" style="background: #00707A;">

            {{-- Decorative Background --}}
            <div class="pointer-events-none absolute -right-16 -top-20 h-52 w-52 rounded-full bg-white/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-20 left-1/3 h-44 w-44 rounded-full bg-white/10 blur-3xl"></div>

            <div class="relative flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

                {{-- Title --}}
                <div class="flex min-w-0 items-center gap-4">

                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl text-white shadow-lg" style="background: rgba(255,255,255,0.15);">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M3 7h18M5 7v10a2 2 0 002 2h10a2 2 0 002-2V7M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2M9 12h6M9 16h4"
                            />
                        </svg>
                    </div>

                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-3">
                            <h2 class="text-2xl font-bold tracking-tight text-white sm:text-3xl">
                                Purchase Reports
                            </h2>

                            <span class="rounded-full px-3 py-1 text-xs font-semibold text-white" style="background: rgba(255,255,255,0.18);">
                                Procurement Analytics
                            </span>
                        </div>

                        <p class="mt-1.5 text-sm leading-6 text-white/80">
                            Analyze purchases, suppliers, medicine costs and purchasing activity.
                        </p>
                    </div>

                </div>

                {{-- Live Status --}}
                <div class="flex shrink-0 items-center gap-2 self-start rounded-xl border border-white/20 px-4 py-2.5 shadow-sm backdrop-blur-sm lg:self-auto" style="background: rgba(255,255,255,0.10);">

                    <span class="relative flex h-2.5 w-2.5">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-300 opacity-60"></span>
                        <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-emerald-400"></span>
                    </span>

                    <span class="text-xs font-semibold text-white">
                        Live Database
                    </span>

                </div>

            </div>
        </div>


        {{-- =========================================================
             FILTERS
        ========================================================== --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">

            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <div class="flex items-center gap-2">

                        <div class="flex h-9 w-9 items-center justify-center rounded-xl" style="background: rgba(0,112,122,0.1); color: #00707A;">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M3 4h18M6 8h12M10 12h4M11 16h2M12 20v-4"
                                />
                            </svg>
                        </div>

                        <h3 class="text-base font-bold text-slate-900">
                            Report Filters
                        </h3>

                    </div>

                    <p class="mt-1 text-sm text-slate-500">
                        Filter purchase data by date and supplier.
                    </p>
                </div>

            </div>


            {{-- Filter Fields --}}
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">

                {{-- Date Range --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Date Range
                    </label>

                    <select
                        wire:model.live="dateRange"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-medium text-slate-700 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10"
                    >
                        <option value="all">All Time</option>
                        <option value="today">Today</option>
                        <option value="7_days">Last 7 Days</option>
                        <option value="this_month">This Month</option>
                        <option value="last_month">Last Month</option>
                        <option value="custom">Custom Range</option>
                    </select>
                </div>


                {{-- Supplier --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Supplier
                    </label>

                    <select
                        wire:model.live="supplierId"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-medium text-slate-700 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10"
                    >
                        <option value="">All Suppliers</option>

                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">
                                {{ $supplier->supplier_name }}
                            </option>
                        @endforeach
                    </select>
                </div>


                {{-- From Date --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        From Date
                    </label>

                    <input
                        type="date"
                        wire:model.live="fromDate"
                        @disabled($dateRange !== 'custom')
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-medium text-slate-700 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                </div>


                {{-- To Date --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        To Date
                    </label>

                    <input
                        type="date"
                        wire:model.live="toDate"
                        @disabled($dateRange !== 'custom')
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-medium text-slate-700 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                </div>

            </div>


            {{-- Filter Buttons --}}
            <div class="mt-5 flex flex-col gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">

                <button
                    type="button"
                    wire:click="resetFilters"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:border-teal-200 hover:bg-teal-50 hover:text-teal-700"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M4 4v5h5M20 20v-5h-5M5.5 9A7 7 0 0117 6.5L20 9M18.5 15A7 7 0 017 17.5L4 15"
                        />
                    </svg>

                    Reset
                </button>


                <button
                    type="button"
                    wire:click="applyFilters"
                    class="inline-flex items-center justify-center gap-2 rounded-xl px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:shadow-md"
                    style="background: #00707A;"
                    onmouseover="this.style.background='#005a63'"
                    onmouseout="this.style.background='#00707A'"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M3 4h18M6 8h12M10 12h4M11 16h2M12 20v-4"
                        />
                    </svg>

                    Apply Filters
                </button>

            </div>

        </div>


        {{-- =========================================================
             SUMMARY CARDS
        ========================================================== --}}
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

            {{-- Total Purchases --}}
            <div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg" style="--hover-border: #00707A;">

                <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full opacity-0 transition-opacity duration-300 group-hover:opacity-100" style="background: rgba(0,112,122,0.08);"></div>

                <div class="relative flex items-start justify-between gap-4">

                    <div class="min-w-0">
                        <p class="text-sm font-medium text-slate-500">
                            Total Purchases
                        </p>

                        <h3 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                            {{ number_format($totalPurchases) }}
                        </h3>

                        <div class="mt-3 flex items-center gap-1.5 text-xs font-medium" style="color: #00707A;">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7"/>
                            </svg>

                            Purchase invoices
                        </div>
                    </div>

                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl transition-all duration-300 group-hover:scale-110 group-hover:text-white" style="background: rgba(0,112,122,0.1); color: #00707A;" onmouseover="this.style.background='#00707A'" onmouseout="this.style.background='rgba(0,112,122,0.1)'">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M3 7h18M5 7v10a2 2 0 002 2h10a2 2 0 002-2V7M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2"
                            />
                        </svg>
                    </div>

                </div>
            </div>


            {{-- Purchase Amount --}}
            <div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-emerald-200 hover:shadow-lg hover:shadow-emerald-100/50">

                <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-emerald-50 opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>

                <div class="relative flex items-start justify-between gap-4">

                    <div class="min-w-0">
                        <p class="text-sm font-medium text-slate-500">
                            Purchase Amount
                        </p>

                        <h3 class="mt-2 truncate text-2xl font-bold tracking-tight text-slate-900">
                            {{ format_currency($totalPurchaseAmount) }}
                        </h3>

                        <div class="mt-3 flex items-center gap-1.5 text-xs font-medium text-emerald-600">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-10V4m0 16v-2"
                                />
                            </svg>

                            Total procurement cost
                        </div>
                    </div>

                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 transition-all duration-300 group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-10V4m0 16v-2"
                            />
                        </svg>
                    </div>

                </div>
            </div>


            {{-- Items Purchased --}}
            <div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">

                <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full opacity-0 transition-opacity duration-300 group-hover:opacity-100" style="background: rgba(0,112,122,0.08);"></div>

                <div class="relative flex items-start justify-between gap-4">

                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Items Purchased
                        </p>

                        <h3 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                            {{ number_format($totalItemsPurchased) }}
                        </h3>

                        <div class="mt-3 flex items-center gap-1.5 text-xs font-medium" style="color: #00707A;">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                                />
                            </svg>

                            Units received
                        </div>
                    </div>

                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl transition-all duration-300 group-hover:scale-110 group-hover:text-white" style="background: rgba(0,112,122,0.1); color: #00707A;" onmouseover="this.style.background='#00707A'" onmouseout="this.style.background='rgba(0,112,122,0.1)'">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                            />
                        </svg>
                    </div>

                </div>
            </div>


            {{-- Returns --}}
            <div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-rose-200 hover:shadow-lg hover:shadow-rose-100/50">

                <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-rose-50 opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>

                <div class="relative flex items-start justify-between gap-4">

                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Purchase Returns
                        </p>

                        <h3 class="mt-2 text-2xl font-bold tracking-tight text-slate-900">
                            {{ format_currency($totalReturns) }}
                        </h3>

                        <div class="mt-3 flex items-center gap-1.5 text-xs font-medium text-rose-600">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 14l-4-4m0 0l4-4m-4 4h11a4 4 0 014 4v1"
                                />
                            </svg>

                            Refunded purchase value
                        </div>
                    </div>

                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-rose-600 transition-all duration-300 group-hover:scale-110 group-hover:bg-rose-600 group-hover:text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M9 14l-4-4m0 0l4-4m-4 4h11a4 4 0 014 4v1"
                            />
                        </svg>
                    </div>

                </div>
            </div>

        </div>


        {{-- =========================================================
             REPORT TYPES
        ========================================================== --}}
        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

            {{-- Daily --}}
            <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">

                <div class="flex items-center gap-4">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl transition group-hover:text-white" style="background: rgba(0,112,122,0.1); color: #00707A;" onmouseover="this.style.background='#00707A'" onmouseout="this.style.background='rgba(0,112,122,0.1)'">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"
                            />
                        </svg>
                    </div>

                    <div class="min-w-0">
                        <h3 class="font-semibold text-slate-900">
                            Daily Purchases
                        </h3>

                        <p class="mt-0.5 text-sm text-slate-500">
                            Daily purchase summary
                        </p>
                    </div>

                </div>
            </div>


            {{-- Supplier --}}
            <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">

                <div class="flex items-center gap-4">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl transition group-hover:text-white" style="background: rgba(0,112,122,0.1); color: #00707A;" onmouseover="this.style.background='#00707A'" onmouseout="this.style.background='rgba(0,112,122,0.1)'">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6M9 9h.01M15 9h.01M9 12h.01M15 12h.01"
                            />
                        </svg>
                    </div>

                    <div class="min-w-0">
                        <h3 class="font-semibold text-slate-900">
                            Supplier-wise Purchases
                        </h3>

                        <p class="mt-0.5 text-sm text-slate-500">
                            Purchases by supplier
                        </p>
                    </div>

                </div>
            </div>


            {{-- Medicine --}}
            <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">

                <div class="flex items-center gap-4">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl transition group-hover:text-white" style="background: rgba(0,112,122,0.1); color: #00707A;" onmouseover="this.style.background='#00707A'" onmouseout="this.style.background='rgba(0,112,122,0.1)'">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M10.5 3.75a6.75 6.75 0 019.75 9.25l-5.25 5.25a6.75 6.75 0 01-9.25-9.75l5.25-5.25zM8.25 8.25l7.5 7.5"
                            />
                        </svg>
                    </div>

                    <div class="min-w-0">
                        <h3 class="font-semibold text-slate-900">
                            Medicine-wise Purchases
                        </h3>

                        <p class="mt-0.5 text-sm text-slate-500">
                            Purchase quantity and cost
                        </p>
                    </div>

                </div>
            </div>

        </div>


        {{-- =========================================================
             DAILY PURCHASES
        ========================================================== --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">

                <div>
                    <h3 class="text-lg font-bold text-slate-900">
                        Daily Purchases
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Purchase activity grouped by date
                    </p>
                </div>

                <div class="w-fit rounded-lg px-3 py-1.5 text-xs font-semibold" style="background: rgba(0,112,122,0.1); color: #00707A;">
                    Last 10 records
                </div>

            </div>


            <div class="overflow-x-auto">

                <table class="w-full min-w-[600px]">

                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/70">

                            <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                Date
                            </th>

                            <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                Purchase Count
                            </th>

                            <th class="px-6 py-3.5 text-right text-xs font-bold uppercase tracking-wider text-slate-500">
                                Total Amount
                            </th>

                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @forelse($dailyPurchases as $purchase)

                            <tr class="transition-colors hover:bg-teal-50/30">

                                <td class="px-6 py-4">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg" style="background: rgba(0,112,122,0.1); color: #00707A;">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="1.8"
                                                    d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"
                                                />
                                            </svg>
                                        </div>

                                        <span class="font-medium text-slate-700">
                                            {{ $purchase->purchase_date->format('d M Y') }}
                                        </span>

                                    </div>

                                </td>

                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded-lg px-2.5 py-1 text-xs font-semibold" style="background: rgba(0,112,122,0.1); color: #00707A;">
                                        {{ number_format($purchase->purchase_count) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-right font-bold text-slate-900">
                                    {{ format_currency($purchase->total_amount) }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center">

                                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.7"
                                                d="M20 13V7a2 2 0 00-1-1.73l-6-3.46a2 2 0 00-2 0L5 5.27A2 2 0 004 7v6a2 2 0 001 1.73l6 3.46a2 2 0 002 0l6-3.46A2 2 0 0020 13z"
                                            />
                                        </svg>
                                    </div>

                                    <p class="mt-3 text-sm font-medium text-slate-600">
                                        No purchase data available
                                    </p>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>

            </div>
        </div>


        {{-- =========================================================
             SUPPLIER + MEDICINE
        ========================================================== --}}
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">


            {{-- Supplier Purchases --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-100 px-5 py-5 sm:px-6">

                    <div class="flex items-center justify-between gap-3">

                        <div>
                            <h3 class="text-lg font-bold text-slate-900">
                                Supplier-wise Purchases
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                Top suppliers by purchase amount
                            </p>
                        </div>

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl" style="background: rgba(0,112,122,0.1); color: #00707A;">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6"
                                />
                            </svg>
                        </div>

                    </div>
                </div>


                <div class="overflow-x-auto">

                    <table class="w-full min-w-[500px]">

                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/70">

                                <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                    Supplier
                                </th>

                                <th class="px-6 py-3.5 text-center text-xs font-bold uppercase tracking-wider text-slate-500">
                                    Purchases
                                </th>

                                <th class="px-6 py-3.5 text-right text-xs font-bold uppercase tracking-wider text-slate-500">
                                    Amount
                                </th>

                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">

                            @forelse($supplierPurchases as $supplier)

                                <tr class="transition-colors hover:bg-teal-50/30">

                                    <td class="px-6 py-4">

                                        <div class="flex items-center gap-3">

                                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-xs font-bold" style="background: rgba(0,112,122,0.1); color: #00707A;">
                                                {{ strtoupper(substr($supplier->supplier_name, 0, 1)) }}
                                            </div>

                                            <span class="font-semibold text-slate-700">
                                                {{ $supplier->supplier_name }}
                                            </span>

                                        </div>

                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex rounded-lg px-2.5 py-1 text-xs font-semibold" style="background: rgba(0,112,122,0.1); color: #00707A;">
                                            {{ number_format($supplier->purchase_count) }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-right font-bold text-slate-900">
                                       {{ format_currency($supplier->total_amount) }}
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-sm text-slate-500">
                                        No supplier purchase data available.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>
            </div>


            {{-- Medicine Purchases --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-100 px-5 py-5 sm:px-6">

                    <div class="flex items-center justify-between gap-3">

                        <div>
                            <h3 class="text-lg font-bold text-slate-900">
                                Medicine-wise Purchases
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                Top medicines by purchase cost
                            </p>
                        </div>

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl" style="background: rgba(0,112,122,0.1); color: #00707A;">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M10.5 3.75a6.75 6.75 0 019.75 9.25l-5.25 5.25a6.75 6.75 0 01-9.25-9.75l5.25-5.25zM8.25 8.25l7.5 7.5"
                                />
                            </svg>
                        </div>

                    </div>
                </div>


                <div class="overflow-x-auto">

                    <table class="w-full min-w-[500px]">

                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/70">

                                <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                    Medicine
                                </th>

                                <th class="px-6 py-3.5 text-center text-xs font-bold uppercase tracking-wider text-slate-500">
                                    Quantity
                                </th>

                                <th class="px-6 py-3.5 text-right text-xs font-bold uppercase tracking-wider text-slate-500">
                                    Amount
                                </th>

                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">

                            @forelse($medicinePurchases as $medicine)

                                <tr class="transition-colors hover:bg-teal-50/30">

                                    <td class="px-6 py-4">

                                        <div class="flex items-center gap-3">

                                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg" style="background: rgba(0,112,122,0.1); color: #00707A;">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="1.8"
                                                        d="M10.5 3.75a6.75 6.75 0 019.75 9.25l-5.25 5.25a6.75 6.75 0 01-9.25-9.75l5.25-5.25z"
                                                    />
                                                </svg>
                                            </div>

                                            <span class="font-semibold text-slate-700">
                                                {{ $medicine->name }}
                                            </span>

                                        </div>

                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex rounded-lg px-2.5 py-1 text-xs font-semibold" style="background: rgba(0,112,122,0.1); color: #00707A;">
                                            {{ number_format($medicine->total_quantity) }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-right font-bold text-slate-900">
                                        {{ format_currency($medicine->total_amount) }}
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-sm text-slate-500">
                                        No medicine purchase data available.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>
            </div>

        </div>


        {{-- =========================================================
             RECENT PURCHASES
        ========================================================== --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">

                <div>
                    <h3 class="text-lg font-bold text-slate-900">
                        Recent Purchases
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Latest purchase invoices and supplier activity
                    </p>
                </div>

                <span class="w-fit rounded-lg px-3 py-1.5 text-xs font-semibold" style="background: rgba(0,112,122,0.1); color: #00707A;">
                    Latest 10
                </span>

            </div>


            <div class="overflow-x-auto">

                <table class="w-full min-w-[800px]">

                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/70">

                            <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                Invoice
                            </th>

                            <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                Supplier
                            </th>

                            <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                Date
                            </th>

                            <th class="px-6 py-3.5 text-right text-xs font-bold uppercase tracking-wider text-slate-500">
                                Amount
                            </th>

                            <th class="px-6 py-3.5 text-center text-xs font-bold uppercase tracking-wider text-slate-500">
                                Status
                            </th>

                        </tr>
                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        @forelse($recentPurchases as $purchase)

                            <tr class="transition-colors hover:bg-teal-50/30">

                                {{-- Invoice --}}
                                <td class="px-6 py-4">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg" style="background: rgba(0,112,122,0.1); color: #00707A;">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="1.8"
                                                    d="M7 3h10a2 2 0 012 2v14l-7-3-7 3V5a2 2 0 012-2z"
                                                />
                                            </svg>
                                        </div>

                                        <span class="font-semibold text-slate-800">
                                            {{ $purchase->invoice_number }}
                                        </span>

                                    </div>

                                </td>


                                {{-- Supplier --}}
                                <td class="px-6 py-4 text-sm font-medium text-slate-600">
                                    {{ $purchase->supplier?->supplier_name ?? 'N/A' }}
                                </td>


                                {{-- Date --}}
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $purchase->purchase_date?->format('d M Y') ?? 'N/A' }}
                                </td>


                                {{-- Amount --}}
                                <td class="px-6 py-4 text-right font-bold text-slate-900">
                                   {{ format_currency($purchase->total_amount) }}
                                </td>


                                {{-- Status --}}
                                <td class="px-6 py-4 text-center">

                                    @php
                                        $status = strtolower($purchase->status ?? 'unknown');

                                        $statusClasses = match ($status) {
                                            'completed',
                                            'paid',
                                            'approved' =>
                                                'bg-emerald-50 text-emerald-700 ring-emerald-600/10',

                                            'pending',
                                            'draft' =>
                                                'bg-amber-50 text-amber-700 ring-amber-600/10',

                                            'cancelled',
                                            'canceled',
                                            'rejected' =>
                                                'bg-rose-50 text-rose-700 ring-rose-600/10',

                                            default =>
                                                'bg-slate-100 text-slate-600 ring-slate-500/10',
                                        };
                                    @endphp

                                    <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-semibold capitalize ring-1 ring-inset {{ $statusClasses }}">

                                        <span class="h-1.5 w-1.5 rounded-full bg-current"></span>

                                        {{ $purchase->status ?? 'Unknown' }}

                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5" class="px-6 py-14 text-center">

                                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.7"
                                                d="M20 13V7a2 2 0 00-1-1.73l-6-3.46a2 2 0 00-2 0L5 5.27A2 2 0 004 7v6a2 2 0 001 1.73l6 3.46a2 2 0 002 0l6-3.46A2 2 0 0020 13z"
                                            />
                                        </svg>
                                    </div>

                                    <p class="mt-3 font-medium text-slate-600">
                                        No purchases found
                                    </p>

                                    <p class="mt-1 text-sm text-slate-400">
                                        Purchase records will appear here once available.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>
        </div>

    </div>


    {{-- =============================================================
         CUSTOM REPORT PAGE POLISH
    ============================================================= --}}
    <style>
        .pms-purchase-reports {
            animation: pmsPurchaseFadeIn 0.45s ease-out;
        }

        .pms-purchase-reports table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .pms-purchase-reports th,
        .pms-purchase-reports td {
            vertical-align: middle;
        }

        @keyframes pmsPurchaseFadeIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .pms-purchase-reports {
                animation: none;
            }

            .pms-purchase-reports * {
                transition: none !important;
            }
        }
    </style>

</x-filament-panels::page>