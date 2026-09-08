<x-filament-panels::page>

    <div class="w-full space-y-6">

        {{-- ========================================================= --}}
        {{-- PAGE HEADER --}}
        {{-- ========================================================= --}}

        <div class="relative overflow-hidden rounded-2xl bg-[#00707A] px-6 py-7 shadow-lg">

            {{-- Decorative circles --}}
            <div class="pointer-events-none absolute -right-16 -top-20 h-56 w-56 rounded-full bg-white/10 blur-2xl"></div>
            <div class="pointer-events-none absolute -bottom-24 right-32 h-48 w-48 rounded-full bg-white/10 blur-3xl"></div>

            <div class="relative flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

                <div class="min-w-0">

                    <div class="mb-2 flex items-center gap-2 text-sm font-medium text-white/80">

                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/15">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                            </svg>
                        </span>

                        Inventory Management

                    </div>

                    <h1 class="text-2xl font-bold tracking-tight text-white sm:text-3xl">
                        Inventory Reports
                    </h1>

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-white/80 sm:text-base">
                        Monitor stock levels, inventory valuation, low-stock medicines
                        and expiry status from one place.
                    </p>

                </div>

                <div class="shrink-0">

                    <div class="rounded-xl border border-white/20 bg-white/10 px-5 py-3 backdrop-blur-sm">

                        <p class="text-xs font-medium text-white/70">
                            Report Date
                        </p>

                        <p class="mt-1 text-sm font-semibold text-white">
                            {{ now()->format('d M Y') }}
                        </p>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- FILTERS --}}
        {{-- ========================================================= --}}

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

            <div class="flex flex-col gap-4 border-b border-gray-100 px-5 py-5 sm:px-6 lg:flex-row lg:items-center lg:justify-between">

                <div class="flex items-center gap-3">

                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#00707A]/10 text-[#00707A]">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                        </svg>
                    </div>

                    <div>

                        <h2 class="font-semibold text-gray-900">
                            Report Filters
                        </h2>

                        <p class="mt-0.5 text-xs text-gray-500">
                            Refine the inventory data below.
                        </p>

                    </div>

                </div>

                <button
                    type="button"
                    wire:click="resetFilters"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:border-[#00707A] hover:bg-[#00707A]/5 hover:text-[#00707A]"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    Reset Filters
                </button>

            </div>


            <div class="p-5 sm:p-6">

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-5">

                    {{-- Search --}}
                    <div>

                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Search
                        </label>

                        <div class="relative">

                            <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                                </svg>
                            </span>

                            <input
                                type="text"
                                wire:model.live.debounce.400ms="search"
                                placeholder="Medicine, SKU, barcode..."
                                class="w-full rounded-xl border-gray-200 bg-gray-50 py-2.5 pl-10 pr-3 text-sm text-gray-900 placeholder:text-gray-400 focus:border-[#00707A] focus:bg-white focus:ring-[#00707A]"
                            >

                        </div>

                    </div>


                    {{-- Category --}}
                    <div>

                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Category
                        </label>

                        <select
                            wire:model.live="category"
                            class="w-full rounded-xl border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 focus:border-[#00707A] focus:bg-white focus:ring-[#00707A]"
                        >

                            <option value="">
                                All Categories
                            </option>

                            @foreach($categories as $item)

                                <option value="{{ $item->id }}">
                                    {{ $item->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Manufacturer --}}
                    <div>

                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Manufacturer
                        </label>

                        <select
                            wire:model.live="manufacturer"
                            class="w-full rounded-xl border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 focus:border-[#00707A] focus:bg-white focus:ring-[#00707A]"
                        >

                            <option value="">
                                All Manufacturers
                            </option>

                            @foreach($manufacturers as $item)

                                <option value="{{ $item->id }}">
                                    {{ $item->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Stock Status --}}
                    <div>

                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Stock Status
                        </label>

                        <select
                            wire:model.live="stockStatus"
                            class="w-full rounded-xl border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 focus:border-[#00707A] focus:bg-white focus:ring-[#00707A]"
                        >

                            <option value="">
                                All Stock
                            </option>

                            <option value="in_stock">
                                In Stock
                            </option>

                            <option value="low_stock">
                                Low Stock
                            </option>

                            <option value="out_of_stock">
                                Out of Stock
                            </option>

                        </select>

                    </div>


                    {{-- Expiry Status --}}
                    <div>

                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Expiry Status
                        </label>

                        <select
                            wire:model.live="expiryStatus"
                            class="w-full rounded-xl border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 focus:border-[#00707A] focus:bg-white focus:ring-[#00707A]"
                        >

                            <option value="">
                                All Expiry
                            </option>

                            <option value="active">
                                Active
                            </option>

                            <option value="near_expiry">
                                Near Expiry
                            </option>

                            <option value="expired">
                                Expired
                            </option>

                        </select>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- KPI CARDS --}}
        {{-- ========================================================= --}}

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

            {{-- Total Medicines --}}
            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-md">

                <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-[#00707A]/10 blur-2xl"></div>

                <div class="relative flex items-start justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Total Medicines
                        </p>

                        <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900">
                            {{ number_format($totalMedicines) }}
                        </p>

                        <p class="mt-2 text-xs text-gray-400">
                            Active medicines
                        </p>

                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#00707A]/10 text-[#00707A]">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3v4.5a3.375 3.375 0 01-.673 2.014L5.25 15.75A2.25 2.25 0 007 19.5h10a2.25 2.25 0 001.75-3.736l-3.827-6.236A3.375 3.375 0 0114.25 7.5V3M8.25 3h7.5" />
                        </svg>
                    </div>

                </div>

            </div>


            {{-- Active Batches --}}
            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-md">

                <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-blue-50 blur-2xl"></div>

                <div class="relative flex items-start justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Active Batches
                        </p>

                        <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900">
                            {{ number_format($totalBatches) }}
                        </p>

                        <p class="mt-2 text-xs text-gray-400">
                            Available batches
                        </p>

                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-8.25 4.5-8.25-4.5M20.25 7.5v9l-8.25 4.5m8.25-13.5l-8.25-4.5-8.25 4.5m16.5 0l-8.25 4.5m0 0l-8.25-4.5m8.25 4.5v9m-8.25-13.5v9l8.25 4.5" />
                        </svg>
                    </div>

                </div>

            </div>


            {{-- Current Stock --}}
            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-md">

                <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-indigo-50 blur-2xl"></div>

                <div class="relative flex items-start justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Current Stock
                        </p>

                        <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900">
                            {{ number_format($currentStock) }}
                        </p>

                        <p class="mt-2 text-xs text-gray-400">
                            Available units
                        </p>

                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-500">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                        </svg>
                    </div>

                </div>

            </div>


            {{-- Valuation --}}
            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-md">

                <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-amber-50 blur-2xl"></div>

                <div class="relative flex items-start justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Stock Valuation
                        </p>

                        <p class="mt-2 text-2xl font-bold tracking-tight text-gray-900">
                            {{ format_currency($stockValuation) }}
                        </p>

                        <p class="mt-2 text-xs text-gray-400">
                            Purchase value
                        </p>

                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-500">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- ALERT CARDS --}}
        {{-- ========================================================= --}}

        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

            {{-- Low Stock --}}
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">

                <div class="flex items-center gap-4">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>

                    <div>

                        <p class="text-sm font-semibold text-amber-800">
                            Low Stock
                        </p>

                        <p class="mt-1 text-2xl font-bold text-amber-950">
                            {{ number_format($lowStockCount) }}
                        </p>

                        <p class="text-xs text-amber-700">
                            Medicines need attention
                        </p>

                    </div>

                </div>

            </div>


            {{-- Near Expiry --}}
            <div class="rounded-2xl border border-orange-200 bg-orange-50 p-5">

                <div class="flex items-center gap-4">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-orange-100 text-orange-600">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>

                    <div>

                        <p class="text-sm font-semibold text-orange-800">
                            Near Expiry
                        </p>

                        <p class="mt-1 text-2xl font-bold text-orange-950">
                            {{ number_format($nearExpiryCount) }}
                        </p>

                        <p class="text-xs text-orange-700">
                            Batches within 30 days
                        </p>

                    </div>

                </div>

            </div>


            {{-- Expired --}}
            <div class="rounded-2xl border border-red-200 bg-red-50 p-5">

                <div class="flex items-center gap-4">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                    </div>

                    <div>

                        <p class="text-sm font-semibold text-red-800">
                            Expired
                        </p>

                        <p class="mt-1 text-2xl font-bold text-red-950">
                            {{ number_format($expiredCount) }}
                        </p>

                        <p class="text-xs text-red-700">
                            Batches with remaining stock
                        </p>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- LOW STOCK TABLE --}}
        {{-- ========================================================= --}}

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

            <div class="flex flex-col gap-3 border-b border-gray-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>

                    <div>

                        <h2 class="font-semibold text-gray-900">
                            Low Stock Medicines
                        </h2>

                        <p class="mt-0.5 text-xs text-gray-500">
                            Medicines at or below their reorder level.
                        </p>

                    </div>

                </div>

                <span class="inline-flex w-fit items-center rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                    {{ number_format($lowStockCount) }} Items
                </span>

            </div>


            <div class="overflow-x-auto">

                <table class="w-full min-w-[700px]">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Medicine
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                SKU
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Current Stock
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Reorder Level
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Status
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse($lowStockMedicines as $medicine)

                            <tr class="transition hover:bg-[#00707A]/5">

                                <td class="px-6 py-4">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#00707A]/10 text-[#00707A]">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3v4.5a3.375 3.375 0 01-.673 2.014L5.25 15.75A2.25 2.25 0 007 19.5h10a2.25 2.25 0 001.75-3.736l-3.827-6.236A3.375 3.375 0 0114.25 7.5V3M8.25 3h7.5" />
                                            </svg>
                                        </div>

                                        <div>

                                            <p class="font-semibold text-gray-900">
                                                {{ $medicine->name }}
                                            </p>

                                            @if($medicine->generic_name)

                                                <p class="mt-0.5 text-xs text-gray-400">
                                                    {{ $medicine->generic_name }}
                                                </p>

                                            @endif

                                        </div>

                                    </div>

                                </td>

                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $medicine->sku ?? '—' }}
                                </td>

                                <td class="px-6 py-4">

                                    <span class="font-bold text-amber-600">
                                        {{ number_format($medicine->current_stock ?? 0) }}
                                    </span>

                                </td>

                                <td class="px-6 py-4 text-sm font-medium text-gray-600">
                                    {{ number_format($medicine->reorder_level) }}
                                </td>

                                <td class="px-6 py-4">

                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">

                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>

                                        Low Stock

                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5" class="px-6 py-12 text-center">

                                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#00707A]/10 text-[#00707A]">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-6 w-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                    </div>

                                    <p class="mt-3 font-semibold text-gray-900">
                                        Stock levels look good
                                    </p>

                                    <p class="mt-1 text-sm text-gray-500">
                                        No medicines are currently below reorder level.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- OUT OF STOCK + NEAR EXPIRY --}}
        {{-- ========================================================= --}}

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">


            {{-- OUT OF STOCK --}}
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

                <div class="flex items-center justify-between border-b border-gray-100 px-5 py-5">

                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                        </div>

                        <div>

                            <h2 class="font-semibold text-gray-900">
                                Out of Stock
                            </h2>

                            <p class="mt-0.5 text-xs text-gray-500">
                                Medicines with no available stock.
                            </p>

                        </div>

                    </div>

                    <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">
                        {{ number_format($outOfStockCount) }}
                    </span>

                </div>


                <div class="max-h-[420px] overflow-y-auto">

                    @forelse($outOfStockMedicines as $medicine)

                        <div class="flex items-center justify-between gap-4 border-b border-gray-100 px-5 py-4 last:border-0 hover:bg-gray-50">

                            <div class="flex min-w-0 items-center gap-3">

                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3v4.5a3.375 3.375 0 01-.673 2.014L5.25 15.75A2.25 2.25 0 007 19.5h10a2.25 2.25 0 001.75-3.736l-3.827-6.236A3.375 3.375 0 0114.25 7.5V3M8.25 3h7.5" />
                                    </svg>
                                </div>

                                <div class="min-w-0">

                                    <p class="truncate font-semibold text-gray-900">
                                        {{ $medicine->name }}
                                    </p>

                                    <p class="mt-0.5 truncate text-xs text-gray-400">
                                        SKU: {{ $medicine->sku ?? '—' }}
                                    </p>

                                </div>

                            </div>

                            <span class="shrink-0 rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                Out
                            </span>

                        </div>

                    @empty

                        <div class="px-5 py-12 text-center">

                            <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-[#00707A]/10 text-[#00707A]">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </div>

                            <p class="mt-2 text-sm font-semibold text-gray-900">
                                No out-of-stock medicines
                            </p>

                        </div>

                    @endforelse

                </div>

            </div>


            {{-- NEAR EXPIRY --}}
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

                <div class="flex items-center justify-between border-b border-gray-100 px-5 py-5">

                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-50 text-orange-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>

                        <div>

                            <h2 class="font-semibold text-gray-900">
                                Near Expiry
                            </h2>

                            <p class="mt-0.5 text-xs text-gray-500">
                                Batches expiring within 30 days.
                            </p>

                        </div>

                    </div>

                    <span class="rounded-full bg-orange-50 px-3 py-1 text-xs font-semibold text-orange-700">
                        {{ number_format($nearExpiryCount) }}
                    </span>

                </div>


                <div class="max-h-[420px] overflow-y-auto">

                    @forelse($nearExpiryBatches as $batch)

                        @php
                            $daysLeft = now()->startOfDay()->diffInDays(
                                $batch->expiry_date,
                                false
                            );
                        @endphp

                        <div class="flex items-center justify-between gap-4 border-b border-gray-100 px-5 py-4 last:border-0 hover:bg-gray-50">

                            <div class="flex min-w-0 items-center gap-3">

                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-orange-50 text-orange-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-8.25 4.5-8.25-4.5M20.25 7.5v9l-8.25 4.5m8.25-13.5l-8.25-4.5-8.25 4.5m16.5 0l-8.25 4.5m0 0l-8.25-4.5m8.25 4.5v9m-8.25-13.5v9l8.25 4.5" />
                                    </svg>
                                </div>

                                <div class="min-w-0">

                                    <p class="truncate font-semibold text-gray-900">
                                        {{ $batch->medicine?->name ?? 'Unknown Medicine' }}
                                    </p>

                                    <p class="mt-0.5 truncate text-xs text-gray-400">
                                        Batch {{ $batch->batch_number }}
                                    </p>

                                </div>

                            </div>

                            <div class="shrink-0 text-right">

                                <p class="text-xs font-semibold text-orange-600">
                                    {{ $daysLeft }} days
                                </p>

                                <p class="mt-0.5 text-[11px] text-gray-400">
                                    {{ $batch->expiry_date->format('d M Y') }}
                                </p>

                            </div>

                        </div>

                    @empty

                        <div class="px-5 py-12 text-center">

                            <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-[#00707A]/10 text-[#00707A]">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </div>

                            <p class="mt-2 text-sm font-semibold text-gray-900">
                                No near-expiry batches
                            </p>

                        </div>

                    @endforelse

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- EXPIRED BATCHES --}}
        {{-- ========================================================= --}}

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

            <div class="flex flex-col gap-3 border-b border-gray-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                    </div>

                    <div>

                        <h2 class="font-semibold text-gray-900">
                            Expired Medicine Batches
                        </h2>

                        <p class="mt-0.5 text-xs text-gray-500">
                            Expired batches that still have remaining quantity.
                        </p>

                    </div>

                </div>

                <span class="inline-flex w-fit rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">
                    {{ number_format($expiredCount) }} Expired
                </span>

            </div>


            <div class="overflow-x-auto">

                <table class="w-full min-w-[750px]">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Medicine
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Batch
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Quantity
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Expiry Date
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100">

                        @forelse($expiredBatches as $batch)

                            <tr class="transition hover:bg-red-50/30">

                                <td class="px-6 py-4">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3v4.5a3.375 3.375 0 01-.673 2.014L5.25 15.75A2.25 2.25 0 007 19.5h10a2.25 2.25 0 001.75-3.736l-3.827-6.236A3.375 3.375 0 0114.25 7.5V3M8.25 3h7.5" />
                                            </svg>
                                        </div>

                                        <div>

                                            <p class="font-semibold text-gray-900">
                                                {{ $batch->medicine?->name ?? 'Unknown Medicine' }}
                                            </p>

                                            @if($batch->medicine?->generic_name)

                                                <p class="mt-0.5 text-xs text-gray-400">
                                                    {{ $batch->medicine->generic_name }}
                                                </p>

                                            @endif

                                        </div>

                                    </div>

                                </td>

                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $batch->batch_number }}
                                </td>

                                <td class="px-6 py-4">

                                    <span class="font-bold text-red-600">
                                        {{ number_format($batch->quantity) }}
                                    </span>

                                </td>

                                <td class="px-6 py-4">

                                    <span class="font-medium text-red-600">
                                        {{ $batch->expiry_date->format('d M Y') }}
                                    </span>

                                </td>

                                <td class="px-6 py-4">

                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">

                                        <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>

                                        Expired

                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5" class="px-6 py-12 text-center">

                                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#00707A]/10 text-[#00707A]">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-6 w-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                    </div>

                                    <p class="mt-3 font-semibold text-gray-900">
                                        No expired batches
                                    </p>

                                    <p class="mt-1 text-sm text-gray-500">
                                        There are no expired batches with remaining quantity.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- FOOTER --}}
        {{-- ========================================================= --}}

        <div class="flex flex-col gap-2 rounded-2xl border border-gray-200 bg-gray-50 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">

            <p class="text-xs text-gray-500">
                Inventory data is calculated from active medicine batches.
            </p>

            <p class="text-xs font-medium text-gray-600">
                Last updated: {{ now()->format('d M Y, h:i A') }}
            </p>

        </div>

    </div>

</x-filament-panels::page>