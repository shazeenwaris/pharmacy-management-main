<x-filament-panels::page>

    <div class="w-full space-y-6">

        {{-- ========================================================= --}}
        {{-- PAGE HEADER --}}
        {{-- ========================================================= --}}

        <div class="relative overflow-hidden rounded-2xl bg-[#008894] px-6 py-7 shadow-lg">

            {{-- Decorative circles --}}
            <div class="pointer-events-none absolute -right-16 -top-20 h-56 w-56 rounded-full bg-white/10 blur-2xl"></div>
            <div class="pointer-events-none absolute -bottom-24 right-32 h-48 w-48 rounded-full bg-white/10 blur-3xl"></div>

            <div class="relative flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

                <div class="min-w-0">

                    <div class="mb-2 flex items-center gap-2 text-sm font-medium text-white/80">

                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/15">
                            📊
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
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#008894]/10 text-[#008894]">
                        🔎
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
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:border-[#008894] hover:bg-[#008894]/5 hover:text-[#008894]"
                >
                    <span>↻</span>
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
                                🔍
                            </span>

                            <input
                                type="text"
                                wire:model.live.debounce.400ms="search"
                                placeholder="Medicine, SKU, barcode..."
                                class="w-full rounded-xl border-gray-200 bg-gray-50 py-2.5 pl-10 pr-3 text-sm text-gray-900 placeholder:text-gray-400 focus:border-[#008894] focus:bg-white focus:ring-[#008894]"
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
                            class="w-full rounded-xl border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 focus:border-[#008894] focus:bg-white focus:ring-[#008894]"
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
                            class="w-full rounded-xl border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 focus:border-[#008894] focus:bg-white focus:ring-[#008894]"
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
                            class="w-full rounded-xl border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 focus:border-[#008894] focus:bg-white focus:ring-[#008894]"
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
                            class="w-full rounded-xl border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700 focus:border-[#008894] focus:bg-white focus:ring-[#008894]"
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

                <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-[#008894]/10 blur-2xl"></div>

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

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#008894]/10 text-xl">
                        💊
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

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-xl">
                        📦
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

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-xl">
                        📊
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
                            Rs. {{ number_format($stockValuation, 2) }}
                        </p>

                        <p class="mt-2 text-xs text-gray-400">
                            Purchase value
                        </p>

                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-xl">
                        💰
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

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-lg">
                        ⚠️
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

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-orange-100 text-lg">
                        ⏳
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

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-red-100 text-lg">
                        ⛔
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

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-50">
                        ⚠️
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

                            <tr class="transition hover:bg-[#008894]/5">

                                <td class="px-6 py-4">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#008894]/10">
                                            💊
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

                                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#008894]/10 text-xl">
                                        ✓
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

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-50">
                            🚫
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

                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-50">
                                    💊
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

                            <div class="text-2xl">
                                ✓
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

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-50">
                            ⏳
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

                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-orange-50">
                                    📦
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

                            <div class="text-2xl">
                                ✓
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

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-50">
                        ⛔
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

                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-50">
                                            💊
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

                                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#008894]/10 text-xl text-[#008894]">
                                        ✓
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