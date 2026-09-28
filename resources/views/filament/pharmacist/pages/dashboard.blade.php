
<x-filament-panels::page>

    @php
        $hour = now()->hour;

        $greeting = $hour < 12
            ? 'Good Morning'
            : ($hour < 17 ? 'Good Afternoon' : 'Good Evening');

        $greetingIcon = $hour < 12
            ? '☀️'
            : ($hour < 17 ? '🌤️' : '🌙');
    @endphp

    {{-- =========================
        MAIN DASHBOARD ROOT
    ========================== --}}
    <div class="space-y-5">

        {{-- =========================
            TOP GREETING BAR
        ========================== --}}
        <div
            class="flex items-center justify-between flex-wrap gap-3"
            style="margin-top:-10px; margin-bottom:4px;"
        >

            <div class="flex items-center gap-2">

                <div
                    class="flex items-center gap-1 text-xs font-semibold"
                    style="color:#00636C;"
                >
                    <span>{{ $greetingIcon }}</span>

                    <span>
                        {{ $greeting }},
                        {{ auth()->user()->name ?? 'Pharmacist' }}
                    </span>
                </div>

            </div>


            <div class="flex items-center gap-2">

                {{-- Date --}}
                <div
                    class="flex items-center gap-1.5 rounded-full px-3 py-1.5 text-[11px] font-semibold"
                    style="
                        background:#F1FBFC;
                        border:1px solid rgba(0,131,143,0.12);
                        color:#00636C;
                    "
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        style="width:12px;height:12px;"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"
                        />
                    </svg>

                    {{ now()->format('D, d M Y') }}

                </div>


                {{-- Duty Status --}}
                <div
                    class="flex items-center gap-1.5 rounded-full px-3 py-1.5 text-[11px] font-semibold"
                    style="
                        background:#ECFDF5;
                        border:1px solid rgba(16,185,129,0.15);
                        color:#00838F;
                    "
                >

                    <span
                        style="
                            width:6px;
                            height:6px;
                            border-radius:999px;
                            background:#00838F;
                        "
                    ></span>

                    On Duty

                </div>

            </div>

        </div>


        {{-- =========================
            WELCOME SECTION
        ========================== --}}
        <div class="flex flex-col gap-4 lg:flex-row">

            {{-- Welcome Banner --}}
            <div
                class="relative flex-1 overflow-hidden rounded-2xl p-6 shadow-sm"
                style="
                    background:linear-gradient(
                        135deg,
                        #E0F7FA 0%,
                        #F1FBFC 55%,
                        #FFFFFF 100%
                    );
                    border:1px solid rgba(0,131,143,0.10);
                "
            >

                {{-- Medicine Image --}}
                <div class="pointer-events-none absolute -right-2 -top-2 -bottom-2 hidden w-80 md:block">
                    <img
                        src="https://images.unsplash.com/photo-1761361414308-b1b683d1f33a?fm=jpg&q=80&w=800&auto=format&fit=crop"
                        alt=""
                        class="h-full w-full object-cover"
                        style="
                            filter:saturate(0.55) hue-rotate(140deg) brightness(1.1) contrast(1.02);

                            mask-image:
                                radial-gradient(
                                    circle at 75% 35%,
                                    black 0%,
                                    black 32%,
                                    transparent 55%
                                ),
                                radial-gradient(
                                    circle at 55% 60%,
                                    black 0%,
                                    black 28%,
                                    transparent 52%
                                ),
                                radial-gradient(
                                    circle at 88% 65%,
                                    black 0%,
                                    black 26%,
                                    transparent 50%
                                ),
                                radial-gradient(
                                    circle at 68% 85%,
                                    black 0%,
                                    black 22%,
                                    transparent 48%
                                ),
                                radial-gradient(
                                    circle at 40% 30%,
                                    black 0%,
                                    black 18%,
                                    transparent 45%
                                ),
                                radial-gradient(
                                    circle at 90% 20%,
                                    black 0%,
                                    black 20%,
                                    transparent 45%
                                );

                            mask-composite:add;

                            -webkit-mask-image:
                                radial-gradient(
                                    circle at 75% 35%,
                                    black 0%,
                                    black 32%,
                                    transparent 55%
                                ),
                                radial-gradient(
                                    circle at 55% 60%,
                                    black 0%,
                                    black 28%,
                                    transparent 52%
                                ),
                                radial-gradient(
                                    circle at 88% 65%,
                                    black 0%,
                                    black 26%,
                                    transparent 50%
                                ),
                                radial-gradient(
                                    circle at 68% 85%,
                                    black 0%,
                                    black 22%,
                                    transparent 48%
                                ),
                                radial-gradient(
                                    circle at 40% 30%,
                                    black 0%,
                                    black 18%,
                                    transparent 45%
                                ),
                                radial-gradient(
                                    circle at 90% 20%,
                                    black 0%,
                                    black 20%,
                                    transparent 45%
                                );

                            -webkit-mask-composite:source-over;
                        "
                        loading="lazy"
                    >
                </div>


                {{-- Existing Welcome Content --}}
                <div class="relative z-10">

                    <div
                        class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-medium"
                        style="
                            background:rgba(0,131,143,0.10);
                            color:#00838F;
                        "
                    >

                        <span
                            class="h-1.5 w-1.5 rounded-full bg-emerald-500"
                        ></span>

                        Pharmacist Panel

                    </div>


                    <h2
                        class="mt-3 text-2xl font-bold"
                        style="color:#00636C;"
                    >
                        Ready for today's shift
                    </h2>


                    <p
                        class="mt-1 text-sm"
                        style="color:rgba(0,131,143,0.80);"
                    >
                        Serve customers, dispense prescriptions, keep stock healthy
                    </p>


                    {{-- Action Buttons --}}
                    <div class="mt-5 flex flex-wrap gap-2">

                        <a
                            href="{{ route('filament.pharmacist.pages.sales') }}"
                            wire:navigate
                            class="rounded-lg px-4 py-2 text-xs font-semibold text-white shadow-sm transition"
                            style="background:#00838F;"
                        >
                            + New Sale
                        </a>


                        <a
                            href="{{ \App\Filament\Pharmacist\Resources\Prescriptions\PrescriptionResource::getUrl('create') }}"
                            wire:navigate
                            class="rounded-lg bg-white px-4 py-2 text-xs font-semibold transition"
                            style="
                                color:#00838F;
                                border:1px solid rgba(0,131,143,0.25);
                            "
                        >
                            New Prescription
                        </a>


                        <a
                            href="{{ route('filament.pharmacist.pages.sales') }}"
                            wire:navigate
                            class="rounded-lg bg-white px-4 py-2 text-xs font-semibold transition"
                            style="
                                color:#00838F;
                                border:1px solid rgba(0,131,143,0.25);
                            "
                        >
                            📷 Scan Barcode
                        </a>

                    </div>

                </div>

            </div>


            {{-- Today's Overview --}}
            <div
                class="w-full shrink-0 rounded-2xl p-4 shadow-sm lg:w-60"
                style="
                    background:#F1FBFC;
                    border:1px solid rgba(0,131,143,0.10);
                "
            >

                <p
                    class="text-xs font-medium"
                    style="color:rgba(0,131,143,0.70);"
                >
                    Today's Overview
                </p>


                <p
                    class="text-sm font-semibold"
                    style="color:#00636C;"
                >
                    {{ now()->format('M d, Y') }}
                </p>


                <div class="mt-3 space-y-3 text-xs">

                    <div
                        class="flex items-center justify-between pb-2"
                        style="border-bottom:1px solid rgba(0,131,143,0.10);"
                    >

                        <span style="color:rgba(0,131,143,0.70);">
                            Sales Today
                        </span>

                        <div class="text-right">

                            <span
                                class="block font-semibold"
                                style="color:#00636C;"
                            >
                                {{ format_currency($stats['today_sales']) }}
                            </span>

                            <span
                                class="text-[10px] {{ $stats['sales_change'] >= 0 ? 'text-emerald-600' : 'text-red-500' }}"
                            >
                                {{ $stats['sales_change'] >= 0 ? '↑' : '↓' }}
                                {{ abs($stats['sales_change']) }}%
                            </span>

                        </div>

                    </div>


                    <div
                        class="flex items-center justify-between pb-2"
                        style="border-bottom:1px solid rgba(0,131,143,0.10);"
                    >

                        <span style="color:rgba(0,131,143,0.70);">
                            Pending Prescriptions
                        </span>

                        <span class="font-semibold text-amber-600">
                            {{ $stats['pending_prescriptions'] }}
                        </span>

                    </div>


                    <div class="flex items-center justify-between">

                        <span style="color:rgba(0,131,143,0.70);">
                            Low Stock Items
                        </span>

                        <span class="font-semibold text-amber-600">
                            {{ $stats['low_stock_count'] }}
                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================
            STATIC STAT CARDS
            4 CARDS + PROPER GAP
        ========================== --}}

        @php
            $statCards = [
                [
                    'label' => "Today's Sales",
                    'value' => format_currency($stats['today_sales']),
                    'sub' => $stats['today_count'] . ' transactions',
                    'icon' => 'heroicon-o-shopping-cart',
                    'color' => '#00838F',
                ],
                [
                    'label' => 'Pending Prescriptions',
                    'value' => $stats['pending_prescriptions'],
                    'sub' => 'Awaiting dispensing',
                    'icon' => 'heroicon-o-document-text',
                    'color' => '#0097A7',
                ],
                [
                    'label' => 'Low Stock Items',
                    'value' => $stats['low_stock_count'],
                    'sub' => 'Below minimum level',
                    'icon' => 'heroicon-o-exclamation-triangle',
                    'color' => '#F59E0B',
                ],
                [
                    'label' => 'Expiring Soon (30d)',
                    'value' => $stats['expiring_soon_count'],
                    'sub' => 'Batches to watch',
                    'icon' => 'heroicon-o-clock',
                    'color' => '#DC4C4C',
                ],
            ];
        @endphp


        {{-- 4 cards in one row --}}
        <div
            class="grid w-full gap-4"
            style="
                grid-template-columns:repeat(4,minmax(0,1fr));
            "
        >

            @foreach ($statCards as $card)

                <div
                    class="min-w-0 rounded-xl bg-white p-4 shadow-sm"
                    style="border:1px solid #F1F5F9;"
                >

                    <div
                        class="inline-flex rounded-lg p-2"
                        style="background-color:{{ $card['color'] }}1A;"
                    >

                        <x-dynamic-component
                            :component="$card['icon']"
                            class="h-5 w-5"
                            style="color:{{ $card['color'] }}"
                        />

                    </div>


                    <p class="mt-3 text-xs font-medium text-gray-500">
                        {{ $card['label'] }}
                    </p>


                    <p class="text-lg font-bold text-gray-800">
                        {{ $card['value'] }}
                    </p>


                    <p class="text-[11px] text-gray-400">
                        {{ $card['sub'] }}
                    </p>

                </div>

            @endforeach

        </div>


        {{-- =========================
            PRESCRIPTION QUEUE
        ========================== --}}
        <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">

            {{-- Header --}}
            <div class="mb-4 flex items-center justify-between">

                <div>

                    <div class="flex items-center gap-2">

                        <h3 class="text-sm font-semibold text-gray-800">
                            Prescription Queue
                        </h3>

                        <span
                            class="rounded-full bg-[#E0F7FA] px-2 py-0.5 text-[10px] font-semibold text-[#00838F]"
                        >
                            {{ $stats['pending_prescriptions'] }} Pending
                        </span>

                    </div>

                    <p class="mt-1 text-xs text-gray-400">
                        Prescriptions waiting for dispensing
                    </p>

                </div>


                <a
                    href="{{ \App\Filament\Pharmacist\Resources\Prescriptions\PrescriptionResource::getUrl('index') }}"
                    wire:navigate
                    class="text-xs font-semibold text-[#00838F] hover:underline"
                >
                    View all →
                </a>

            </div>


            {{-- Prescription List --}}
            <div class="space-y-3">

                @forelse ($pendingPrescriptions as $rx)

                    <div
                        class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-3 transition hover:border-[#B8E3E6] hover:bg-[#F8FCFC]"
                    >

                        {{-- Left Side --}}
                        <div class="flex min-w-0 items-center gap-3">

                            {{-- Icon --}}
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg"
                                style="
                                    background:#FFF7D6;
                                    color:#F59E0B;
                                "
                            >

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    class="h-5 w-5"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                    />
                                </svg>

                            </div>


                            {{-- Patient Information --}}
                            <div class="min-w-0">

                                <div class="flex items-center gap-2">

                                    <p class="text-sm font-semibold text-gray-800">
                                        {{ $rx->patient_name }}
                                    </p>

                                    <span
                                        class="rounded-md bg-orange-50 px-2 py-0.5 text-[10px] font-semibold uppercase text-orange-500"
                                    >
                                        Pending
                                    </span>

                                </div>


                                <div class="mt-1 flex items-center gap-2 text-xs text-gray-400">

                                    <span>
                                        Rx #{{ $rx->prescription_number }}
                                    </span>

                                    <span class="text-gray-300">•</span>

                                    <span>
                                        Dr. {{ $rx->doctor_name ?? '—' }}
                                    </span>

                                </div>

                            </div>

                        </div>


                        {{-- Review --}}
                        <a
                            href="{{ \App\Filament\Pharmacist\Resources\Prescriptions\PrescriptionResource::getUrl('edit', ['record' => $rx]) }}"
                            wire:navigate
                            class="ml-4 shrink-0 rounded-md bg-white px-3 py-1.5 text-xs font-semibold text-[#00838F] ring-1 ring-[#B8E3E6] transition hover:bg-[#E0F7FA]"
                        >
                            Review
                        </a>

                    </div>

                @empty

                    <div
                        class="rounded-lg border border-dashed border-gray-200 py-8 text-center"
                    >

                        <div
                            class="mx-auto mb-2 flex h-10 w-10 items-center justify-center rounded-full bg-[#E0F7FA] text-[#00838F]"
                        >

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                class="h-5 w-5"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                />
                            </svg>

                        </div>

                        <p class="text-sm font-medium text-gray-700">
                            No pending prescriptions
                        </p>

                        <p class="mt-1 text-xs text-gray-400">
                            All prescriptions have been handled.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>


        {{-- =========================
            RECENT SALES + LOW STOCK
        ========================== --}}
        <div
            class="grid gap-5"
            style="grid-template-columns:repeat(2,minmax(0,1fr));"
        >

            {{-- Recent Sales --}}
            <div
                class="min-w-0 rounded-xl bg-white p-5 shadow-sm"
                style="border:1px solid #F1F5F9;"
            >

                <h3 class="mb-3 text-sm font-semibold text-gray-700">
                    Recent Sales
                </h3>


                <div class="space-y-3">

                    @forelse ($recentSales as $sale)

                        <div
                            class="flex items-center justify-between border-b border-gray-50 pb-2 last:border-0"
                        >

                            <div>

                                <p class="text-sm font-medium text-gray-700">
                                    {{ $sale->customer->name ?? 'Walk-in Customer' }}
                                </p>

                                <p class="text-xs text-gray-400">
                                    {{ $sale->items_count }} items
                                    · {{ $sale->created_at->diffForHumans() }}
                                </p>

                            </div>


                            <span
                                class="text-sm font-semibold"
                                style="color:#00838F;"
                            >
                                {{ format_currency($sale->grand_total) }}
                            </span>

                        </div>

                    @empty

                        <p class="text-sm text-gray-400">
                            No sales yet.
                        </p>

                    @endforelse

                </div>

            </div>


            {{-- Low Stock --}}
            <div
                class="min-w-0 rounded-xl bg-white p-5 shadow-sm"
                style="border:1px solid #F1F5F9;"
            >

                <h3 class="mb-3 text-sm font-semibold text-gray-700">
                    Low Stock Alerts
                </h3>


                <div class="space-y-3">

                    @forelse ($lowStockItems as $item)

                        <div
                            class="flex items-center justify-between border-b border-gray-50 pb-2 last:border-0"
                        >

                            <p class="text-sm font-medium text-gray-700">
                                {{ $item->name }}
                            </p>


                            <span
                                class="rounded-full bg-amber-50 px-2 py-0.5 text-xs font-semibold text-amber-600"
                            >
                                {{ $item->stock_qty ?? 0 }} left
                            </span>

                        </div>

                    @empty

                        <p class="text-sm text-gray-400">
                            All stock levels healthy.
                        </p>

                    @endforelse

                </div>

            </div>

        </div>


        {{-- =========================
            EXPIRY TRACKER
        ========================== --}}
        <div
            class="rounded-xl bg-white p-5 shadow-sm"
            style="border:1px solid #F1F5F9;"
        >

            <h3 class="mb-3 text-sm font-semibold text-gray-700">
                Expiry Tracker
            </h3>


            <div class="overflow-x-auto">

                <table class="w-full text-left text-sm">

                    <thead>

                        <tr class="text-xs uppercase text-gray-400">

                            <th class="pb-2">
                                Medicine
                            </th>

                            <th class="pb-2">
                                Batch
                            </th>

                            <th class="pb-2">
                                Qty
                            </th>

                            <th class="pb-2">
                                Expiry Date
                            </th>

                            <th class="pb-2">
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($expiringSoon as $batch)

                            @php
                                $daysLeft = (int) ceil(
                                    (
                                        strtotime($batch->expiry_date)
                                        - strtotime(now()->startOfDay())
                                    ) / 86400
                                );

                                $badgeColor = $daysLeft <= 30
                                    ? 'bg-red-50 text-red-600'
                                    : 'bg-amber-50 text-amber-600';
                            @endphp


                            <tr class="border-t border-gray-50">

                                <td class="py-2 text-gray-700">
                                    {{ $batch->medicine->name ?? '-' }}
                                </td>

                                <td class="py-2 text-gray-500">
                                    {{ $batch->batch_number }}
                                </td>

                                <td class="py-2 text-gray-500">
                                    {{ $batch->quantity }}
                                </td>

                                <td class="py-2 text-gray-500">
                                    {{ \Carbon\Carbon::parse($batch->expiry_date)->format('M d, Y') }}
                                </td>

                                <td class="py-2">

                                    <span
                                        class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $badgeColor }}"
                                    >
                                        {{ $daysLeft >= 0
                                            ? "Expires in {$daysLeft} days"
                                            : 'Expired'
                                        }}
                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="py-3 text-gray-400"
                                >
                                    No batches expiring soon.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</x-filament-panels::page>

