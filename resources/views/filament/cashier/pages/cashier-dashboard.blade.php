<x-filament-panels::page>

@php
    $hour = now()->hour;
    $greeting = $hour < 12 ? 'Good Morning' : ($hour < 17 ? 'Good Afternoon' : 'Good Evening');
    $isDay = $hour >= 6 && $hour < 18;

    $daysInMonth = now()->daysInMonth;
    $dayOfMonth = now()->day;
    $monthProgress = round(($dayOfMonth / $daysInMonth) * 100);

    // Collection ratio for the ring in the overview card
    $totalSales = (float) $this->todayTotal;
    $paidSales = (float) $this->todayPaid;
    $paidPct = $totalSales > 0 ? min(100, (int) round(($paidSales / $totalSales) * 100)) : 0;

    // Payment method share (of money collected)
    $collected = max($paidSales, 0.0001);
    $pct = fn ($v) => min(100, (int) round(((float) $v / $collected) * 100));

    $methods = [
        ['label' => 'Cash',          'value' => $this->todayCash,  'tone' => 'cash'],
        ['label' => 'Card',          'value' => $this->todayCard,  'tone' => 'card'],
        ['label' => 'Bank Transfer', 'value' => $this->todayBank,  'tone' => 'bank'],
        ['label' => 'Other',         'value' => $this->todayOther, 'tone' => 'other'],
    ];
@endphp

{{-- Loading skeleton (shows briefly until content paints) --}}
<div wire:loading.delay.longer wire:target="" style="position: fixed; inset: 0; background: #eefafb; z-index: 40; padding: 24px; margin-left: var(--pms-sidebar-width, 280px);">
    <div style="height: 32px; width: 180px; background: #dbeeee; border-radius: 8px; margin-bottom: 16px; animation: csh-shimmer 1.4s infinite;"></div>
    <div style="height: 160px; border-radius: 16px; background: #dbeeee; margin-bottom: 16px; animation: csh-shimmer 1.4s infinite;"></div>
    <div style="display: flex; gap: 16px;">
        @for ($i = 0; $i < 5; $i++)
            <div style="flex: 1; height: 100px; border-radius: 12px; background: #dbeeee; animation: csh-shimmer 1.4s infinite;"></div>
        @endfor
    </div>
</div>

<div class="csh">

    {{-- =========================================================
        TOP STRIP: breadcrumb + live chips
    ========================================================== --}}

    <div class="csh-strip csh-anim" style="--d: 0ms">

        <div class="csh-crumbs">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955a1.126 1.126 0 0 1 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
            <span>Home</span>
            <svg class="csh-sep" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            <strong>Dashboard</strong>
        </div>

        <div class="csh-chips">

            {{-- Live clock --}}
            <div class="csh-chip"
                 x-data="{ time: '' }"
                 x-init="
                    const tick = () => {
                        time = new Intl.DateTimeFormat('en-GB', {
                            timeZone: 'Europe/London',
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit',
                            hour12: true,
                        }).format(new Date());
                    };
                    tick();
                    setInterval(tick, 1000);
                 ">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                <span x-text="time">{{ now()->format('h:i:s A') }}</span>
            </div>

            {{-- Date --}}
            <div class="csh-chip">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                {{ now()->format('D, d M Y') }}
            </div>

            {{-- Month progress --}}
            <div class="csh-chip" title="{{ $dayOfMonth }} of {{ $daysInMonth }} days into {{ now()->format('F') }}">
                <span class="csh-mini-bar"><i style="width: {{ $monthProgress }}%"></i></span>
                {{ $monthProgress }}% of {{ now()->format('M') }}
            </div>

            {{-- Status --}}
            <div class="csh-chip ok">
                <span class="csh-live"></span>
                All Systems Operational
            </div>

        </div>

    </div>


    {{-- =========================================================
        HERO + OVERVIEW
    ========================================================== --}}

    <div class="csh-top">

        {{-- HERO --}}
        <!-- <section class="csh-hero csh-anim" style="--d: 60ms">

            <span class="csh-blob b1"></span>
            <span class="csh-blob b2"></span>

            <div class="csh-hero-body">

                <div class="csh-online">
                    <span class="csh-live"></span>
                    Cashier Online
                </div>

                <h1>
                    {{ $greeting }},
                    <span>{{ auth()->user()->name ?? 'Cashier' }}</span>
                </h1>

                <p>Manage sales, payments and customer transactions quickly.</p>

                <div class="csh-hero-actions">

                    <a href="{{ url('/cashier/pos') }}" class="csh-btn primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        New Sale
                    </a>

                    <a href="{{ url('/cashier/sale-history') }}" class="csh-btn ghost">
                        Sales History
                    </a>

                </div>

            </div>

            {{-- Illustration --}}
            <div class="csh-hero-art">
                <img src="{{ asset('images/cashier-hero.png') }}" alt="Pharmacy Cashier">
            </div>

        </section> -->
        {{-- HERO --}}
<section class="csh-hero csh-anim" style="--d: 60ms">

    <span class="csh-blob b1"></span>
    <span class="csh-blob b2"></span>

    <div class="csh-hero-body">

        <div class="csh-online">
            <span class="csh-live"></span>
            Cashier Online
        </div>

        <h1>
            {{ $greeting }},
            <span>{{ auth()->user()->name ?? 'Cashier' }}</span>
        </h1>

        <p>
            Manage sales, payments and customer transactions quickly.
        </p>

        <div class="csh-hero-actions">

            <a href="{{ url('/cashier/pos') }}" class="csh-btn primary">
                <svg viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2.2">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                New Sale
            </a>

            <a href="{{ url('/cashier/sale-history') }}"
               class="csh-btn ghost">
                Sales History
            </a>

        </div>

    </div>

 <div class="csh-hero-art">
    <img
        src="{{ asset('images/cashier-hero.jpg') }}"
        alt="Pharmacy Cashier"
    >
</div>

    

</section>
       



        {{-- TODAY'S OVERVIEW --}}
        <aside class="csh-overview csh-anim" style="--d: 120ms">

            <div class="csh-ov-head">

                <div>
                    <span class="csh-ov-label">Today's Overview</span>
                    <strong class="csh-ov-date">{{ now()->format('M d, Y') }}</strong>
                </div>

                <div class="csh-ring" style="--p: {{ $paidPct }}" title="{{ $paidPct }}% of today's sales collected">
                    <span>{{ $paidPct }}<small>%</small></span>
                </div>

            </div>

            <div class="csh-ov-row">
                <span>Sales Today</span>
                <strong>{{ format_currency($this->todayTotal) }}</strong>
            </div>

            <div class="csh-ov-row">
                <span>Transactions</span>
                <strong>{{ $this->todayInvoices }}</strong>
            </div>

            <div class="csh-ov-row">
                <span>Outstanding Due</span>
                <strong class="due">{{ format_currency($this->todayDue) }}</strong>
            </div>

        </aside>

    </div>


    {{-- =========================================================
        STAT CARDS
    ========================================================== --}}

    <div class="csh-stats">

        {{-- Sales --}}
        <div class="csh-stat csh-anim" style="--d: 160ms">
            <div class="csh-stat-icon teal">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18"/><path stroke-linecap="round" stroke-linejoin="round" d="M7 16l4-5 3 3 5-7"/></svg>
            </div>
            <div class="csh-stat-text">
                <span>Sales Today</span>
                <strong>{{ format_currency($this->todayTotal) }}</strong>
                <small>Gross sales today</small>
            </div>
        </div>

        {{-- Transactions --}}
        <div class="csh-stat csh-anim" style="--d: 200ms">
            <div class="csh-stat-icon cyan">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h6.586a1 1 0 0 1 .707.293l3.414 3.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2Z"/></svg>
            </div>
            <div class="csh-stat-text">
                <span>Transactions</span>
                <strong>{{ $this->todayInvoices }}</strong>
                <small>Invoices processed</small>
            </div>
        </div>

        {{-- Paid --}}
        <div class="csh-stat csh-anim" style="--d: 240ms">
            <div class="csh-stat-icon green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z"/></svg>
            </div>
            <div class="csh-stat-text">
                <span>Total Paid</span>
                <strong>{{ format_currency($this->todayPaid) }}</strong>
                <small class="good">Collected today</small>
            </div>
        </div>

        {{-- Due --}}
        <div class="csh-stat csh-anim" style="--d: 280ms">
            <div class="csh-stat-icon amber">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/></svg>
            </div>
            <div class="csh-stat-text">
                <span>Total Due</span>
                <strong>{{ format_currency($this->todayDue) }}</strong>
                <small class="warn">Outstanding today</small>
            </div>
        </div>

        {{-- Average --}}
        <div class="csh-stat csh-anim" style="--d: 320ms">
            <div class="csh-stat-icon indigo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 19V9m6 10V5m6 14v-7m4 7V3"/></svg>
            </div>
            <div class="csh-stat-text">
                <span>Average Sale</span>
                <strong>
                    {{
                        $this->todayInvoices > 0
                            ? format_currency($this->todayTotal / $this->todayInvoices)
                            : format_currency(0)
                    }}
                </strong>
                <small>Per transaction</small>
            </div>
        </div>

    </div>


    {{-- =========================================================
        QUICK ACTIONS + TODAY'S SALES
    ========================================================== --}}

    <div class="csh-grid">

        {{-- QUICK ACTIONS --}}
        <section class="csh-panel csh-anim" style="--d: 360ms">

            <header class="csh-panel-head">
                <div>
                    <h2>Quick Actions</h2>
                    <p>Frequently used cashier operations.</p>
                </div>
            </header>

            <div class="csh-actions">
             

                <a href="{{ url('/cashier/pos') }}" class="csh-action primary">
                    <span class="csh-action-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    </span>
                    <span class="csh-action-text">
                        <strong>New Sale</strong>
                        <small>Open POS</small>
                    </span>
                    <svg class="csh-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </a>

                <a href="{{ url('/cashier/sale-history') }}" class="csh-action">
                    <span class="csh-action-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    </span>
                    <span class="csh-action-text">
                        <strong>Sales History</strong>
                        <small>View completed sales</small>
                    </span>
                    <svg class="csh-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </a>

                <a href="{{ url('/cashier/sale-returns') }}" class="csh-action">
                    <span class="csh-action-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3"/></svg>
                    </span>
                    <span class="csh-action-text">
                        <strong>Sale Returns</strong>
                        <small>Process returns</small>
                    </span>
                    <svg class="csh-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </a>

                <a href="{{ url('/cashier/daily-sales') }}" class="csh-action">
                    <span class="csh-action-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75c0 .621-.504 1.125-1.125 1.125h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/></svg>
                    </span>
                    <span class="csh-action-text">
                        <strong>Daily Sales</strong>
                        <small>View daily sales report</small>
                    </span>
                    <svg class="csh-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </a>

            </div>

        </section>


        {{-- TODAY'S SALES --}}
        <section class="csh-panel csh-anim" style="--d: 400ms">

            <header class="csh-panel-head">
                <div>
                    <h2>Today's Sales</h2>
                    <p>Latest transactions.</p>
                </div>

                <a href="{{ url('/cashier/sale-history') }}" class="csh-link">
                    View All
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                </a>
            </header>

            <div class="csh-table-head">
                <span>Invoice</span>
                <span>Customer</span>
                <span class="r">Amount</span>
                <span class="c">Status</span>
            </div>

            <div class="csh-sales">

                @forelse($this->recentSales as $sale)

                    <div class="csh-sale">

                        <div class="csh-sale-inv">
                            <span class="csh-avatar">#</span>
                            <div>
                                <strong>{{ $sale->invoice_number }}</strong>
                                <small>{{ $sale->created_at?->format('h:i A') }}</small>
                            </div>
                        </div>

                        <div class="csh-sale-cust">
                            {{ $sale->customer?->name ?? 'Walk-in Customer' }}
                        </div>

                        <div class="csh-sale-amt">
                            {{ format_currency($sale->grand_total) }}
                        </div>

                        <div class="c">
                            @if(($sale->due_amount ?? 0) > 0)
                                <span class="csh-pill due">Due</span>
                            @else
                                <span class="csh-pill paid">Paid</span>
                            @endif
                        </div>

                    </div>

                @empty

                    <div class="csh-empty">
                        <span class="csh-empty-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/></svg>
                        </span>
                        <strong>No sales today</strong>
                        <small>Today's transactions will appear here.</small>
                    </div>

                @endforelse

            </div>

        </section>

    </div>


    {{-- =========================================================
        PAYMENT SUMMARY
    ========================================================== --}}

    <section class="csh-panel csh-anim" style="--d: 440ms">

        <header class="csh-panel-head">

            <div>
                <h2>Payment Summary</h2>
                <p>Today's collected amount by payment method.</p>
            </div>

            <div class="csh-total">
                <span>Total Collected</span>
                <strong>{{ format_currency($this->todayPaid) }}</strong>
            </div>

        </header>

        <div class="csh-methods">

            @foreach ($methods as $m)

                <div class="csh-method {{ $m['tone'] }}">

                    <div class="csh-method-top">

                        <span class="csh-method-icon">
                            @switch($m['tone'])
                                @case('cash')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 0 0-2.25-2.25H15a3 3 0 1 1-6 0H5.25A2.25 2.25 0 0 0 3 12m18 0v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 9m18 0V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v3"/></svg>
                                    @break
                                @case('card')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z"/></svg>
                                    @break
                                @case('bank')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z"/></svg>
                                    @break
                                @default
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/></svg>
                            @endswitch
                        </span>

                        <div class="csh-method-text">
                            <span>{{ $m['label'] }}</span>
                            <strong>{{ format_currency($m['value']) }}</strong>
                        </div>

                        <em>{{ $pct($m['value']) }}%</em>

                    </div>

                    <div class="csh-bar"><i style="width: {{ $pct($m['value']) }}%"></i></div>

                </div>

            @endforeach

        </div>

    </section>

</div>


{{-- =============================================================
    CASHIER DASHBOARD CSS
============================================================= --}}

<style>

@keyframes csh-shimmer {
    0%, 100% { opacity: 0.6; }
    50% { opacity: 1; }
}

@keyframes csh-pulse {
    0%   { box-shadow: 0 0 0 0 rgba(18, 173, 120, 0.55); }
    70%  { box-shadow: 0 0 0 6px rgba(18, 173, 120, 0); }
    100% { box-shadow: 0 0 0 0 rgba(18, 173, 120, 0); }
}

@keyframes csh-rise {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ---------- Root + page spacing ---------- */

.csh {
    --c-primary: #00838f;
    --c-primary-dark: #00646d;
    --c-primary-soft: #e6f6f7;
    --c-border: #d9e9eb;
    --c-text: #0f3a40;
    --c-muted: #6b868b;
    --c-card: #ffffff;
    --c-green: #17a673;
    --c-amber: #d98a00;

    width: 100%;
    max-width: 100%;
    box-sizing: border-box;

    /* real breathing room from the sidebar */
    padding: 22px clamp(20px, 2.2vw, 36px) 36px !important;

    display: flex;
    flex-direction: column;
    gap: 20px;

    color: var(--c-text);
}

.csh *,
.csh *::before,
.csh *::after {
    box-sizing: border-box;
}

.csh svg {
    display: block;
    flex-shrink: 0;
}

.csh-anim {
    opacity: 0;
    animation: csh-rise 0.5s cubic-bezier(.2, .7, .2, 1) forwards;
    animation-delay: var(--d, 0ms);
}

/* remove extra Filament bottom space */
.fi-page:has(.csh) .fi-page-content,
.fi-page:has(.csh),
.fi-page:has(.csh) .fi-page-content-ctn,
.fi-main:has(.csh) {
    padding-bottom: 0 !important;
    margin-bottom: 0 !important;
}


/* ---------- Top strip ---------- */

.csh-strip {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}

.csh-crumbs {
    display: flex;
    align-items: center;
    gap: 8px;

    font-size: 13px;
    color: var(--c-primary);
}

.csh-crumbs svg {
    width: 16px;
    height: 16px;
}

.csh-crumbs .csh-sep {
    width: 11px;
    height: 11px;
    color: #9bb8bc;
}

.csh-crumbs span {
    font-weight: 600;
}

.csh-crumbs strong {
    color: var(--c-text);
    font-weight: 700;
}

.csh-chips {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
}

.csh-chip {
    display: inline-flex;
    align-items: center;
    gap: 7px;

    height: 32px;
    padding: 0 13px;

    border-radius: 999px;

    background: var(--c-card);
    border: 1px solid var(--c-border);

    color: #0b5f68;

    font-size: 12px;
    font-weight: 600;
    font-variant-numeric: tabular-nums;

    box-shadow: 0 1px 2px rgba(0, 70, 80, 0.04);
}

.csh-chip svg {
    width: 14px;
    height: 14px;
}

.csh-chip.ok {
    background: #ecfaf4;
    border-color: #bfe9d8;
    color: #0c7a54;
}

.csh-live {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #17a673;
    animation: csh-pulse 1.6s infinite;
}

.csh-mini-bar {
    width: 34px;
    height: 5px;
    border-radius: 999px;
    background: #d3ebed;
    overflow: hidden;
}

.csh-mini-bar i {
    display: block;
    height: 100%;
    border-radius: 999px;
    background: linear-gradient(90deg, #00a3b1, #00838f);
}


/* ---------- Hero + overview ---------- */

.csh-top {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 310px;
    gap: 18px;
}

.csh-hero {
    position: relative;

    min-height: 228px;

    border-radius: 20px;
    border: 1px solid #cfe6e8;

    background:
        radial-gradient(120% 140% at 0% 0%, #e2f6f7 0%, #f3fbfb 55%, #e9f7f8 100%);

    overflow: hidden;

    box-shadow: 0 6px 24px rgba(0, 90, 100, 0.07);
}

.csh-blob {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
}

.csh-blob.b1 {
    width: 260px;
    height: 260px;
    left: -80px;
    bottom: -120px;
    background: radial-gradient(circle, rgba(0, 163, 177, 0.16), transparent 70%);
}

.csh-blob.b2 {
    width: 200px;
    height: 200px;
    right: 28%;
    top: -90px;
    background: radial-gradient(circle, rgba(0, 131, 143, 0.10), transparent 70%);
}

.csh-hero-body {
    position: relative;
    z-index: 2;

    max-width: 58%;

    padding: 28px 0 28px 30px;
}

.csh-online {
    display: inline-flex;
    align-items: center;
    gap: 8px;

    height: 28px;
    padding: 0 12px;

    border-radius: 999px;

    background: rgba(255, 255, 255, 0.85);
    border: 1px solid #c6e7e9;

    color: var(--c-primary);

    font-size: 11.5px;
    font-weight: 700;
}

.csh-hero h1 {
    margin: 14px 0 6px;

    font-size: clamp(24px, 2.4vw, 32px);
    line-height: 1.15;
    font-weight: 800;
    letter-spacing: -0.02em;

    color: var(--c-text);
}

.csh-hero h1 span {
    background: linear-gradient(90deg, #00838f, #00b0bf);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}

.csh-hero p {
    margin: 0;

    color: #2b7f88;

    font-size: 14px;
    font-weight: 500;
}

.csh-hero-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;

    margin-top: 22px;
}

.csh-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;

    height: 40px;
    padding: 0 18px;

    border-radius: 11px;

    font-size: 13px;
    font-weight: 700;
    text-decoration: none !important;

    transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
}

.csh-btn svg {
    width: 16px;
    height: 16px;
}

.csh-btn.primary {
    background: linear-gradient(135deg, #00919d, #006d77);
    color: #fff;

    box-shadow: 0 6px 16px rgba(0, 131, 143, 0.32);
}

.csh-btn.primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 22px rgba(0, 131, 143, 0.38);
}

.csh-btn.ghost {
    background: #fff;
    color: var(--c-primary);
    border: 1px solid #a9d7da;
}

.csh-btn.ghost:hover {
    background: var(--c-primary-soft);
    transform: translateY(-2px);
}

.csh-hero-art {
    position: absolute;
    z-index: 1;

    top: 0;
    right: 0;
    bottom: 0;

    width: 44%;

    pointer-events: none;
}

.csh-hero-art img {
    display: block;

    width: 100%;
    height: 100%;

    object-fit: contain;
    object-position: right bottom;

    mix-blend-mode: multiply;

    -webkit-mask-image: linear-gradient(to right, transparent 0%, rgba(0, 0, 0, 0.55) 22%, #000 55%);
    mask-image: linear-gradient(to right, transparent 0%, rgba(0, 0, 0, 0.55) 22%, #000 55%);
}


/* ---------- Overview card ---------- */

.csh-overview {
    display: flex;
    flex-direction: column;

    padding: 20px 20px 10px;

    border-radius: 20px;
    border: 1px solid var(--c-border);

    background: var(--c-card);

    box-shadow: 0 6px 24px rgba(0, 90, 100, 0.06);
}

.csh-ov-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;

    padding-bottom: 14px;
}

.csh-ov-label {
    display: block;

    color: var(--c-primary);

    font-size: 12px;
    font-weight: 600;
}

.csh-ov-date {
    display: block;

    margin-top: 3px;

    font-size: 19px;
    font-weight: 800;
    letter-spacing: -0.01em;
}

.csh-ring {
    --p: 0;

    position: relative;

    width: 60px;
    height: 60px;
    min-width: 60px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 50%;

    background:
        conic-gradient(#17a673 calc(var(--p) * 1%), #e1eef0 0);
}

.csh-ring::before {
    content: '';

    position: absolute;
    inset: 6px;

    border-radius: 50%;
    background: #fff;
}

.csh-ring span {
    position: relative;

    font-size: 14px;
    font-weight: 800;
    color: #0c7a54;
}

.csh-ring small {
    font-size: 9px;
    font-weight: 700;
}

.csh-ov-row {
    display: flex;
    align-items: center;
    justify-content: space-between;

    padding: 13px 0;

    border-top: 1px solid #e4f0f1;
}

.csh-ov-row span {
    color: var(--c-muted);

    font-size: 12.5px;
    font-weight: 500;
}

.csh-ov-row strong {
    font-size: 14px;
    font-weight: 800;
}

.csh-ov-row strong.due {
    color: var(--c-amber);
}


/* ---------- Stat cards ---------- */

.csh-stats {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 14px;
}

.csh-stat {
    display: flex;
    align-items: center;
    gap: 14px;

    min-width: 0;

    padding: 18px;

    border-radius: 16px;
    border: 1px solid var(--c-border);

    background: var(--c-card);

    box-shadow: 0 2px 10px rgba(0, 70, 80, 0.04);

    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
}

.csh-stat:hover {
    transform: translateY(-3px);
    border-color: #b6dcdf;
    box-shadow: 0 12px 26px rgba(0, 90, 100, 0.10);
}

.csh-stat-icon {
    width: 46px;
    height: 46px;
    min-width: 46px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 13px;
}

.csh-stat-icon svg {
    width: 22px;
    height: 22px;
}

.csh-stat-icon.teal   { background: #e3f5f6; color: #00838f; }
.csh-stat-icon.cyan   { background: #e6f1fb; color: #1f78b4; }
.csh-stat-icon.green  { background: #e5f7ee; color: #17a673; }
.csh-stat-icon.amber  { background: #fff3da; color: #d98a00; }
.csh-stat-icon.indigo { background: #eceffd; color: #5b6bc0; }

.csh-stat-text {
    min-width: 0;
}

.csh-stat-text span {
    display: block;

    color: var(--c-muted);

    font-size: 12px;
    font-weight: 500;
}

.csh-stat-text strong {
    display: block;

    margin: 3px 0 2px;

    font-size: 20px;
    font-weight: 800;
    letter-spacing: -0.015em;
    line-height: 1.15;

    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.csh-stat-text small {
    display: block;

    color: var(--c-muted);

    font-size: 11px;
}

.csh-stat-text small.good { color: #17a673; font-weight: 600; }
.csh-stat-text small.warn { color: #d98a00; font-weight: 600; }


/* ---------- Panels ---------- */

.csh-grid {
    display: grid;
    grid-template-columns: minmax(0, 0.9fr) minmax(0, 1.1fr);
    gap: 18px;
}
 

.csh-panel {
    border-radius: 18px;
    border: 1px solid var(--c-border);

    background: var(--c-card);

    overflow: hidden;

    box-shadow: 0 2px 12px rgba(0, 70, 80, 0.04);
}

.csh-panel-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;

    padding: 18px 22px;

    border-bottom: 1px solid #e4f0f1;
}

.csh-panel-head h2 {
    margin: 0;

    font-size: 16px;
    font-weight: 800;
    letter-spacing: -0.01em;
}

.csh-panel-head p {
    margin: 3px 0 0;

    color: var(--c-muted);

    font-size: 12px;
}

.csh-link {
    display: inline-flex;
    align-items: center;
    gap: 4px;

    color: var(--c-primary);

    font-size: 12.5px;
    font-weight: 700;
    text-decoration: none !important;
}

.csh-link svg {
    width: 12px;
    height: 12px;

    transition: transform 0.15s ease;
}

.csh-link:hover svg {
    transform: translateX(3px);
}


/* ---------- Quick actions ---------- */

.csh-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;

    padding: 18px;
}

.csh-action {
    display: flex;
    align-items: center;
    gap: 12px;

    min-height: 78px;

    padding: 14px;

    border-radius: 14px;
    border: 1px solid var(--c-border);

    background: #fff;

    text-decoration: none !important;

    transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease, background 0.18s ease;
}

.csh-action:hover {
    transform: translateY(-3px);
    border-color: #8fcfd5;
    background: #f6fcfc;

    box-shadow: 0 10px 22px rgba(0, 90, 100, 0.10);
}

.csh-action-icon {
    width: 42px;
    height: 42px;
    min-width: 42px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 12px;

    background: var(--c-primary-soft);
    color: var(--c-primary);
}

.csh-action-icon svg {
    width: 20px;
    height: 20px;
}

.csh-action-text {
    min-width: 0;
}

.csh-action-text strong {
    display: block;

    color: var(--c-text);

    font-size: 13.5px;
    font-weight: 700;
}

.csh-action-text small {
    display: block;

    margin-top: 2px;

    color: var(--c-muted);

    font-size: 11px;
}

.csh-arrow {
    width: 16px;
    height: 16px;

    margin-left: auto;

    color: #9bb8bc;

    transition: transform 0.18s ease, color 0.18s ease;
}

.csh-action:hover .csh-arrow {
    transform: translateX(4px);
    color: var(--c-primary);
}

.csh-action.primary {
    border-color: transparent;

    background: linear-gradient(135deg, #00919d, #006a73);

    box-shadow: 0 8px 20px rgba(0, 131, 143, 0.28);
}

.csh-action.primary:hover {
    background: linear-gradient(135deg, #00a0ad, #00727c);
    border-color: transparent;
}

.csh-action.primary .csh-action-icon {
    background: rgba(255, 255, 255, 0.18);
    color: #fff;
}

.csh-action.primary .csh-action-text strong,
.csh-action.primary .csh-action-text small,
.csh-action.primary .csh-arrow {
    color: #fff;
}

.csh-action.primary .csh-action-text small {
    opacity: 0.85;
}


/* ---------- Sales table ---------- */

.csh-table-head,
.csh-sale {
    display: grid;
    grid-template-columns: minmax(0, 1.5fr) minmax(0, 1fr) minmax(0, 0.8fr) 70px;
    align-items: center;
    gap: 12px;
}

.csh-table-head {
    padding: 11px 22px;

    background: #f5fafa;
    border-bottom: 1px solid #e4f0f1;

    color: #6b868b;

    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: 0.07em;
    text-transform: uppercase;
}

.csh-table-head .r { text-align: right; }
.csh-table-head .c { text-align: center; }

.csh-sale {
    padding: 13px 22px;

    border-bottom: 1px solid #eef5f6;

    transition: background 0.15s ease;
}

.csh-sale:last-child {
    border-bottom: 0;
}

.csh-sale:hover {
    background: #f6fbfb;
}

.csh-sale .c {
    display: flex;
    justify-content: center;
}

.csh-sale-inv {
    display: flex;
    align-items: center;
    gap: 11px;

    min-width: 0;
}

.csh-avatar {
    width: 34px;
    height: 34px;
    min-width: 34px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 10px;

    background: var(--c-primary-soft);
    color: var(--c-primary);

    font-size: 13px;
    font-weight: 800;
}

.csh-sale-inv > div {
    min-width: 0;
}

.csh-sale-inv strong {
    display: block;

    font-size: 12.5px;
    font-weight: 700;

    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.csh-sale-inv small {
    display: block;

    margin-top: 2px;

    color: var(--c-muted);

    font-size: 11px;
}

.csh-sale-cust {
    color: #4f6e73;

    font-size: 12.5px;

    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.csh-sale-amt {
    text-align: right;

    font-size: 13px;
    font-weight: 800;

    white-space: nowrap;
}

.csh-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;

    height: 24px;
    padding: 0 10px;

    border-radius: 999px;

    font-size: 11px;
    font-weight: 700;
}

.csh-pill::before {
    content: '';

    width: 6px;
    height: 6px;

    border-radius: 50%;

    background: currentColor;
}

.csh-pill.paid { background: #e6f7ee; color: #17895f; }
.csh-pill.due  { background: #fff1d6; color: #b36d00; }


/* ---------- Empty ---------- */

.csh-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

    min-height: 220px;

    text-align: center;
}

.csh-empty-icon {
    width: 54px;
    height: 54px;

    display: flex;
    align-items: center;
    justify-content: center;

    margin-bottom: 10px;

    border-radius: 16px;

    background: var(--c-primary-soft);
    color: var(--c-primary);
}

.csh-empty-icon svg {
    width: 26px;
    height: 26px;
}

.csh-empty strong {
    font-size: 14px;
    font-weight: 700;
}

.csh-empty small {
    margin-top: 4px;

    color: var(--c-muted);

    font-size: 12px;
}


/* ---------- Payment summary ---------- */

.csh-total {
    text-align: right;
}

.csh-total span {
    display: block;

    color: var(--c-muted);

    font-size: 11.5px;
}

.csh-total strong {
    display: block;

    margin-top: 2px;

    font-size: 20px;
    font-weight: 800;
    letter-spacing: -0.015em;
}

.csh-methods {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
}

.csh-method {
    --tone: #00838f;
    --tone-soft: #e3f5f6;

    padding: 20px 22px;

    border-right: 1px solid #e9f2f3;
}

.csh-method:last-child {
    border-right: 0;
}

.csh-method.cash  { --tone: #17a673; --tone-soft: #e5f7ee; }
.csh-method.card  { --tone: #1f78b4; --tone-soft: #e6f1fb; }
.csh-method.bank  { --tone: #5b6bc0; --tone-soft: #eceffd; }
.csh-method.other { --tone: #8a97a0; --tone-soft: #eef1f3; }

.csh-method-top {
    display: flex;
    align-items: center;
    gap: 12px;
}

.csh-method-icon {
    width: 40px;
    height: 40px;
    min-width: 40px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 12px;

    background: var(--tone-soft);
    color: var(--tone);
}

.csh-method-icon svg {
    width: 20px;
    height: 20px;
}

.csh-method-text {
    min-width: 0;
    flex: 1;
}

.csh-method-text span {
    display: block;

    color: var(--c-muted);

    font-size: 12px;
}

.csh-method-text strong {
    display: block;

    margin-top: 2px;

    font-size: 16px;
    font-weight: 800;
    letter-spacing: -0.01em;

    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.csh-method em {
    font-style: normal;
    font-size: 12px;
    font-weight: 800;

    color: var(--tone);
}

.csh-bar {
    height: 6px;

    margin-top: 16px;

    border-radius: 999px;

    background: #e8f1f2;

    overflow: hidden;
}

.csh-bar i {
    display: block;

    height: 100%;

    border-radius: 999px;

    background: var(--tone);

    transition: width 0.8s cubic-bezier(.2, .7, .2, 1);
}


/* ---------- Dark mode ---------- */

.dark .csh {
    --c-card: #162a2e;
    --c-border: #264349;
    --c-text: #e6f2f3;
    --c-muted: #8fb0b5;
    --c-primary-soft: #1b3a40;
}

.dark .csh-hero {
    background: radial-gradient(120% 140% at 0% 0%, #17363b 0%, #11292d 60%, #14323a 100%);
    border-color: #264349;
}

.dark .csh-chip,
.dark .csh-online,
.dark .csh-btn.ghost,
.dark .csh-action,
.dark .csh-overview,
.dark .csh-ring::before {
    background: var(--c-card);
}

.dark .csh-table-head,
.dark .csh-sale:hover {
    background: #1a3338;
}

.dark .csh-hero-art img {
    mix-blend-mode: normal;
    opacity: 0.85;
}


/* ---------- Responsive ---------- */

@media (max-width: 1280px) {

    .csh-stats {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .csh-methods {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .csh-method:nth-child(2) {
        border-right: 0;
    }

    .csh-method:nth-child(-n+2) {
        border-bottom: 1px solid #e9f2f3;
    }
}

@media (max-width: 1100px) {

    .csh-top,
    .csh-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 760px) {

    .csh-hero-body {
        max-width: 100%;
        padding: 24px 22px;
    }

    .csh-hero-art {
        display: none;
    }

    .csh-stats {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .csh-actions {
        grid-template-columns: 1fr;
    }

    .csh-table-head,
    .csh-sale {
        grid-template-columns: minmax(0, 1fr) auto auto;
    }

    .csh-table-head span:nth-child(2),
    .csh-sale-cust {
        display: none;
    }

    .csh-methods {
        grid-template-columns: 1fr;
    }

    .csh-method {
        border-right: 0;
        border-bottom: 1px solid #e9f2f3;
    }

    .csh-method:last-child {
        border-bottom: 0;
    }
}

@media (max-width: 520px) {

    .csh-stats {
        grid-template-columns: 1fr;
    }
}

@media (prefers-reduced-motion: reduce) {

    .csh-anim {
        animation: none;
        opacity: 1;
    }

    .csh-live {
        animation: none;
    }
}




/* =========================================================
   CASHIER HERO - INTEGRATED IMAGE
   No separate image box
========================================================= */

.csh-hero {
    position: relative;
    overflow: hidden;
}


/* IMAGE AREA */

.csh-hero-art {
    position: absolute;
    top: 0;
    right: -15px;

    width: 48%;
    height: 100%;

    z-index: 2;

    overflow: hidden;

    background: transparent;

    pointer-events: none;
}


/* REAL PHOTO */

.csh-hero-art img {
    position: absolute;

    top: -3%;
    right: 0;

    width: 108%;
    height: 106%;

    object-fit: cover;
    object-position: center;

    display: block;

    border: 0;
    border-radius: 0;

    transform: scale(1.02);

    /*
       IMPORTANT:
       Left edge completely soft ho jayegi.
       Is se image alag box nahi lagegi.
    */
    -webkit-mask-image: linear-gradient(
        to right,
        transparent 0%,
        transparent 5%,
        rgba(0,0,0,.20) 15%,
        rgba(0,0,0,.55) 28%,
        rgba(0,0,0,.85) 42%,
        #000 58%
    );

    mask-image: linear-gradient(
        to right,
        transparent 0%,
        transparent 5%,
        rgba(0,0,0,.20) 15%,
        rgba(0,0,0,.55) 28%,
        rgba(0,0,0,.85) 42%,
        #000 58%
    );

    transition: transform .45s ease;
}


/* SOFT TEAL BLEND */

.csh-hero-art::before {
    content: "";

    position: absolute;

    left: 0;
    top: 0;
    bottom: 0;

    width: 50%;

    z-index: 5;

    pointer-events: none;

    background: linear-gradient(
        90deg,
        #edfafa 0%,
        rgba(237,250,250,.95) 15%,
        rgba(237,250,250,.70) 30%,
        rgba(237,250,250,.35) 48%,
        rgba(237,250,250,.08) 75%,
        transparent 100%
    );
}


/* VERY LIGHT TOP/BOTTOM BLEND */

.csh-hero-art::after {
    content: "";

    position: absolute;

    inset: 0;

    z-index: 4;

    pointer-events: none;

    background:
        linear-gradient(
            to bottom,
            rgba(237,250,250,.18),
            transparent 18%,
            transparent 82%,
            rgba(237,250,250,.12)
        );
}


/* HOVER */

.csh-hero:hover .csh-hero-art img {
    transform: scale(1.05);
}




</style>

</x-filament-panels::page>