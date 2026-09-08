<x-filament-panels::page>
   {{-- Top Info Strip --}}
@php
    $hour = now()->hour;
    $greeting = $hour < 12 ? 'Good Morning' : ($hour < 17 ? 'Good Afternoon' : 'Good Evening');
    $greetingIcon = $hour < 12 ? '☀️' : ($hour < 17 ? '🌤️' : '🌙');
    $daysInMonth = now()->daysInMonth;
    $dayOfMonth = now()->day;
    $monthProgress = round(($dayOfMonth / $daysInMonth) * 100);
@endphp

<div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; margin-top: -10px; margin-bottom: 4px;">

    <div style="display: flex; align-items: center; gap: 10px;">
        <div style="display: flex; align-items: center; gap: 6px; font-size: 12px; color: #00838F;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span style="color: #00838F; font-weight: 600;">Home</span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 10px; height: 10px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
            <span style="color: #00838F; font-weight: 600;">Dashboard</span>
        </div>

        <div style="width: 1px; height: 16px; background: #e0eaea;"></div>

        <div style="display: flex; align-items: center; gap: 5px; font-size: 12px; color: #00636C; font-weight: 600;">
            <span>{{ $greetingIcon }}</span>
            <span>{{ $greeting }}, {{ auth()->user()->name ?? 'Admin' }}</span>
        </div>
    </div>

    <div style="display: flex; align-items: center; gap: 8px;">

        {{-- Live Clock --}}
       <div x-data="{ time: '' }" x-init="
    setInterval(() => {
        time = new Intl.DateTimeFormat('en-GB', {
            timeZone: 'Europe/London',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true,
        }).format(new Date());
    }, 1000)
" style="display: flex; align-items: center; gap: 5px; padding: 5px 12px; border-radius: 999px; background: #F1FBFC; border: 1px solid rgba(0,131,143,0.12); font-size: 11px; color: #00636C; font-weight: 600; font-variant-numeric: tabular-nums;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 12px; height: 12px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span x-text="time">{{ now()->format('h:i:s A') }}</span>
        </div>

        {{-- Date Badge --}}
        <div style="display: flex; align-items: center; gap: 5px; padding: 5px 12px; border-radius: 999px; background: #F1FBFC; border: 1px solid rgba(0,131,143,0.12); font-size: 11px; color: #00636C; font-weight: 600;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 12px; height: 12px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
            </svg>
            {{ now()->format('D, d M Y') }}
        </div>

        <!-- {{-- Month Progress --}}
        <div title="{{ $dayOfMonth }} of {{ $daysInMonth }} days into {{ now()->format('F') }}" style="display: flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 999px; background: #FFF7ED; border: 1px solid rgba(234,145,55,0.15); font-size: 11px; color: #C2610C; font-weight: 600;">
            <div style="width: 32px; height: 5px; border-radius: 999px; background: rgba(234,145,55,0.15); overflow: hidden;">
                <div style="height: 100%; width: {{ $monthProgress }}%; background: #EA9137; border-radius: 999px;"></div>
            </div>
            {{ $monthProgress }}% of {{ now()->format('M') }}
        </div> -->
        {{-- Month Progress --}}
<div title="{{ $dayOfMonth }} of {{ $daysInMonth }} days into {{ now()->format('F') }}" style="display: flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 999px; background: #F1FBFC; border: 1px solid rgba(0,131,143,0.12); font-size: 11px; color: #00636C; font-weight: 600;">
    <div style="width: 32px; height: 5px; border-radius: 999px; background: rgba(0,131,143,0.15); overflow: hidden;">
        <div style="height: 100%; width: {{ $monthProgress }}%; background: #00838F; border-radius: 999px;"></div>
    </div>
    {{ $monthProgress }}% of {{ now()->format('M') }}
</div>

        {{-- Status Badge --}}
        <div style="display: flex; align-items: center; gap: 5px; padding: 5px 12px; border-radius: 999px; background: #ECFDF5; border: 1px solid rgba(16,185,129,0.15); font-size: 11px; color: #00838F; font-weight: 600;">
            <span style="width: 6px; height: 6px; border-radius: 999px; background: #00838F; animation: pms-pulse 1.5s infinite;"></span>
            All Systems Operational
        </div>
    </div>
</div>

<style>
    @keyframes pms-pulse {
        0% { box-shadow: 0 0 0 0 rgba(16,185,129,0.5); }
        70% { box-shadow: 0 0 0 5px rgba(16,185,129,0); }
        100% { box-shadow: 0 0 0 0 rgba(16,185,129,0); }
    }
</style>
    <div class="space-y-6"> 
        {{-- Loading Skeleton (shows briefly until content paints) --}}
<div wire:loading.delay.longer wire:target="" style="position: fixed; inset: 0; background: #eefafb; z-index: 40; padding: 24px; margin-left: var(--pms-sidebar-width, 280px);">
    <div style="height: 32px; width: 180px; background: #dbeeee; border-radius: 8px; margin-bottom: 16px; animation: pms-shimmer 1.4s infinite;"></div>
    <div style="height: 160px; border-radius: 16px; background: #dbeeee; margin-bottom: 16px; animation: pms-shimmer 1.4s infinite;"></div>
    <div style="display: flex; gap: 16px;">
        @for ($i = 0; $i < 5; $i++)
            <div style="flex: 1; height: 100px; border-radius: 12px; background: #dbeeee; animation: pms-shimmer 1.4s infinite;"></div>
        @endfor
    </div>
</div>

<style>
    @keyframes pms-shimmer {
        0%, 100% { opacity: 0.6; }
        50% { opacity: 1; }
    }
</style>

        {{-- Welcome Banner --}}
        <div class="flex flex-col gap-4 lg:flex-row">

            <div class="relative flex-1 overflow-hidden rounded-2xl bg-gradient-to-br from-[#E0F7FA] via-[#F1FBFC] to-white p-6 shadow-sm ring-1 ring-[#00838F]/10">

                <div class="pointer-events-none absolute -right-2 -top-2 -bottom-2 hidden w-80 md:block">
                    <img
                        src="https://images.unsplash.com/photo-1761361414308-b1b683d1f33a?fm=jpg&q=80&w=800&auto=format&fit=crop"
                        alt=""
                        class="h-full w-full object-cover"
                        style="
                            filter: saturate(0.55) hue-rotate(140deg) brightness(1.1) contrast(1.02);
                            mask-image:
                                radial-gradient(circle at 75% 35%, black 0%, black 32%, transparent 55%),
                                radial-gradient(circle at 55% 60%, black 0%, black 28%, transparent 52%),
                                radial-gradient(circle at 88% 65%, black 0%, black 26%, transparent 50%),
                                radial-gradient(circle at 68% 85%, black 0%, black 22%, transparent 48%),
                                radial-gradient(circle at 40% 30%, black 0%, black 18%, transparent 45%),
                                radial-gradient(circle at 90% 20%, black 0%, black 20%, transparent 45%);
                            mask-composite: add;
                            -webkit-mask-image:
                                radial-gradient(circle at 75% 35%, black 0%, black 32%, transparent 55%),
                                radial-gradient(circle at 55% 60%, black 0%, black 28%, transparent 52%),
                                radial-gradient(circle at 88% 65%, black 0%, black 26%, transparent 50%),
                                radial-gradient(circle at 68% 85%, black 0%, black 22%, transparent 48%),
                                radial-gradient(circle at 40% 30%, black 0%, black 18%, transparent 45%),
                                radial-gradient(circle at 90% 20%, black 0%, black 20%, transparent 45%);
                            -webkit-mask-composite: source-over;
                        "
                        loading="lazy"
                    >
                </div>

                <div class="relative z-10">
                    <div class="inline-flex items-center gap-2 rounded-full bg-[#00838F]/10 px-3 py-1 text-xs font-medium text-[#00838F]">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        System Online
                    </div>

                    <h2 class="mt-3 text-2xl font-bold text-[#00636C]">Smart Pharmacy Management</h2>
                    <p class="mt-1 text-sm text-[#00838F]/80">Right Medicine, Right Time, Better Care</p>

                    <div class="mt-5 flex gap-2">
    <a href="{{ route('filament.admin.pages.sales') }}"
       wire:navigate
       class="rounded-lg bg-[#00838F] px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-[#00707A]">
        + New Sale
    </a>
    <a href="{{ route('filament.admin.resources.medicines.create') }}"
       wire:navigate
       class="rounded-lg bg-white px-4 py-2 text-xs font-semibold text-[#00838F] ring-1 ring-[#00838F]/25 transition hover:bg-[#E0F7FA]">
        Add Medicine
    </a>
</div>
      
                </div>
            </div>

            {{-- Today's Overview --}}
            <div class="w-full shrink-0 rounded-2xl border border-[#00838F]/10 bg-[#F1FBFC] p-4 shadow-sm lg:w-60">
                <p class="text-xs font-medium text-[#00838F]/70">Today's Overview</p>
                <p class="text-sm font-semibold text-[#00636C]">{{ now()->format('M d, Y') }}</p>
                <div class="mt-3 space-y-3 text-xs">
                    <div class="flex items-center justify-between border-b border-[#00838F]/10 pb-2">
                        <span class="text-[#00838F]/70">Sales Today</span>
                        <div class="text-right">
                            <span class="block font-semibold text-[#00636C]">
                                {{ function_exists('format_currency') ? format_currency($stats['today_sales']) : number_format($stats['today_sales'], 2) }}
                            </span>
                            <span class="text-[10px] {{ $stats['sales_change'] >= 0 ? 'text-emerald-600' : 'text-red-500' }}">
                                {{ $stats['sales_change'] >= 0 ? '↑' : '↓' }} {{ abs($stats['sales_change']) }}%
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between border-b border-[#00838F]/10 pb-2">
                        <span class="text-[#00838F]/70">Customers</span>
                        <span class="font-semibold text-[#00636C]">{{ $stats['total_customers'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-[#00838F]/70">Low Stock Items</span>
                        <span class="font-semibold text-amber-600">{{ $stats['low_stock_count'] }}</span>
                    </div>
                </div>
            </div>
            
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
            @php
                $statCards = [
                    ['label' => 'Total Medicines', 'value' => number_format($stats['total_medicines']), 'icon' => 'heroicon-o-cube', 'color' => '#00838F'],
                    ['label' => 'Stock Value', 'value' => function_exists('format_currency') ? format_currency($stats['stock_value']) : number_format($stats['stock_value']), 'icon' => 'heroicon-o-banknotes', 'color' => '#26A69A'],
                    ['label' => "Today's Sales", 'value' => function_exists('format_currency') ? format_currency($stats['today_sales']) : number_format($stats['today_sales']), 'icon' => 'heroicon-o-shopping-cart', 'color' => '#00ACC1'],
                    ['label' => 'Customers', 'value' => number_format($stats['total_customers']), 'icon' => 'heroicon-o-users', 'color' => '#0097A7'],
                    ['label' => 'Low Stock Items', 'value' => number_format($stats['low_stock_count']), 'icon' => 'heroicon-o-exclamation-triangle', 'color' => '#F59E0B'],
                ];
            @endphp

            @foreach ($statCards as $card)
                <div class="rounded-xl border border-gray-100 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-center justify-between">
                        <div class="rounded-lg p-2" style="background-color: {{ $card['color'] }}1A;">
                            <x-dynamic-component :component="$card['icon']" class="h-5 w-5" style="color: {{ $card['color'] }}" />
                        </div>
                    </div>
                    <p class="mt-3 text-xs font-medium text-gray-500 dark:text-gray-400">{{ $card['label'] }}</p>
                    <p class="text-lg font-bold text-gray-800 dark:text-gray-100">{{ $card['value'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- Sales Trend Chart --}}
            <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800 lg:col-span-2">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200">Sales Trend</h3>
                    <div class="flex gap-1">
                        @foreach (['7' => '7 Days', '30' => '30 Days', '90' => '90 Days'] as $val => $label)
                            <button
                                wire:click="$set('range', '{{ $val }}')"
                                type="button"
                                class="rounded-md px-2 py-1 text-xs font-medium transition
                                    {{ $range === $val ? 'bg-[#00838F] text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300' }}"
                            >
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </div>
                <div wire:key="sales-trend-{{ $range }}" wire:ignore x-data="{
                    init() {
                        new Chart(this.$refs.salesCanvas, {
                            type: 'line',
                            data: {
                                labels: @js($salesTrend['labels']),
                                datasets: [{
                                    label: 'Sales',
                                    data: @js($salesTrend['data']),
                                    borderColor: '#00838F',
                                    backgroundColor: 'rgba(0,131,143,0.1)',
                                    fill: true,
                                    tension: 0.35,
                                    pointRadius: 3,
                                    pointBackgroundColor: '#00838F',
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: { legend: { display: false } },
                                scales: { y: { beginAtZero: true } }
                            }
                        });
                    }
                }">
                    <canvas x-ref="salesCanvas" height="90"></canvas>
                </div>
            </div>

            {{-- Stock Status Donut --}}
            <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h3 class="mb-4 text-sm font-semibold text-gray-700 dark:text-gray-200">Stock Status</h3>
                <div wire:ignore x-data="{
                    init() {
                        new Chart(this.$refs.stockCanvas, {
                            type: 'doughnut',
                            data: {
                                labels: ['In Stock', 'Low Stock', 'Out of Stock'],
                                datasets: [{
                                    data: [{{ $stockStatus['in_stock'] }}, {{ $stockStatus['low_stock'] }}, {{ $stockStatus['out_of_stock'] }}],
                                    backgroundColor: ['#00707A', '#00707A', '#00707A'],
                                    borderWidth: 0,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '70%',
                                plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 11 } } } }
                            }
                        });
                    }
                }" class="flex items-center justify-center" style="height: 220px;">
                    <canvas x-ref="stockCanvas"></canvas>
                </div>
                <p class="mt-3 text-center text-xs text-gray-400">Total: {{ $stockStatus['total'] }} medicines</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- Recent Sales --}}
            <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h3 class="mb-3 text-sm font-semibold text-gray-700 dark:text-gray-200">Recent Sales</h3>
                <div class="space-y-3">
                    @forelse ($recentSales as $sale)
                        <div class="flex items-center justify-between border-b border-gray-50 pb-2 last:border-0 dark:border-gray-700">
                            <div>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ $sale->customer->name ?? 'Walk-in Customer' }}</p>
                                <p class="text-xs text-gray-400">{{ $sale->items_count }} items · {{ $sale->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="text-sm font-semibold text-[#00838F]">
                                {{ function_exists('format_currency') ? format_currency($sale->grand_total) : number_format($sale->grand_total, 2) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400">No sales yet.</p>
                    @endforelse
                </div>
            </div>

            {{-- Low Stock Alerts --}}
            <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h3 class="mb-3 text-sm font-semibold text-gray-700 dark:text-gray-200">Low Stock Alerts</h3>
                <div class="space-y-3">
                    @forelse ($lowStockItems as $item)
                        <div class="flex items-center justify-between border-b border-gray-50 pb-2 last:border-0 dark:border-gray-700">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ $item->name }}</p>
                            <span class="rounded-full bg-amber-50 px-2 py-0.5 text-xs font-semibold text-amber-600 dark:bg-amber-900/30">
                                {{ $item->stock_qty ?? 0 }} left
                            </span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400">All stock levels healthy.</p>
                    @endforelse
                </div>
            </div>

            {{-- Top Selling Medicines --}}
            <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h3 class="mb-3 text-sm font-semibold text-gray-700 dark:text-gray-200">Top Selling Medicines</h3>
                <div class="space-y-3">
                    @forelse ($topSelling as $item)
                        <div class="flex items-center justify-between border-b border-gray-50 pb-2 last:border-0 dark:border-gray-700">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ $item->name }}</p>
                            <span class="text-xs font-semibold text-gray-500">{{ $item->total_sold }} sold</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400">No sales data in last 30 days.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Expiry Tracker --}}
        <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <h3 class="mb-3 text-sm font-semibold text-gray-700 dark:text-gray-200">Expiry Tracker</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-xs uppercase text-gray-400">
                            <th class="pb-2">Medicine</th>
                            <th class="pb-2">Batch</th>
                            <th class="pb-2">Qty</th>
                            <th class="pb-2">Expiry Date</th>
                            <th class="pb-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($expiringSoon as $batch)
                            @php
                                $daysLeft = (int) ceil((strtotime($batch->expiry_date) - strtotime(now()->startOfDay())) / 86400);
                                $badgeColor = $daysLeft <= 30 ? 'bg-red-50 text-red-600' : 'bg-amber-50 text-amber-600';
                            @endphp
                            <tr class="border-t border-gray-50 dark:border-gray-700">
                                <td class="py-2 text-gray-700 dark:text-gray-200">{{ $batch->medicine->name ?? '-' }}</td>
                                <td class="py-2 text-gray-500">{{ $batch->batch_number }}</td>
                                <td class="py-2 text-gray-500">{{ $batch->quantity }}</td>
                                <td class="py-2 text-gray-500">{{ \Carbon\Carbon::parse($batch->expiry_date)->format('M d, Y') }}</td>
                                <td class="py-2">
                                    <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $badgeColor }}">
                                        {{ $daysLeft >= 0 ? "Expires in {$daysLeft} days" : 'Expired' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-3 text-gray-400">No batches expiring soon.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    @once
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
    @endonce
</x-filament-panels::page>