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
    <div class="cashier-dashboard">

        {{-- =========================================================
            DASHBOARD HERO
        ========================================================== --}}

      <div class="cashier-hero">

    <div class="cashier-hero-content">

        <div class="cashier-status">
            <span class="status-dot"></span>
            Cashier Online
        </div>

        <h1>
            Good {{ now()->format('A') === 'AM' ? 'Morning' : 'Evening' }},
            {{ auth()->user()->name ?? 'Cashier' }} 👋
        </h1>

        <p>
            Manage sales, payments and customer transactions quickly.
        </p>

        <div class="cashier-hero-actions">

            <a href="{{ url('/cashier/sales/create') }}"
               class="cashier-primary-btn">
                <span class="btn-icon">+</span>
                New Sale
            </a>

            <a href="{{ url('/cashier/sale-history') }}"
               class="cashier-secondary-btn">
                Sales History
            </a>

        </div>

        {{-- CASHIER IMAGE --}}
        <div class="cashier-hero-image">
            <img
                src="{{ asset('images/cashier-hero.png') }}"
                alt="Pharmacy Cashier"
            >
        </div>

    </div>

   

<!-- </div> -->


            {{-- TODAY OVERVIEW --}}

            <div class="cashier-overview">

                <div class="overview-label">
                    Today's Overview
                </div>

                <strong class="overview-date">
                    {{ now()->format('M d, Y') }}
                </strong>

                <div class="overview-item">
                    <span>Sales Today</span>

                    <strong>
                        {{ format_currency($this->todayTotal) }}
                    </strong>
                </div>

                <div class="overview-item">
                    <span>Transactions</span>

                    <strong>
                        {{ $this->todayInvoices }}
                    </strong>
                </div>

                <div class="overview-item">
                    <span>Outstanding Due</span>

                    <strong class="due-value">
                        {{ format_currency($this->todayDue) }}
                    </strong>
                </div>

            </div>

        </div>


        {{-- =========================================================
            STAT CARDS
        ========================================================== --}}

        <div class="cashier-stats">

            {{-- SALES --}}

            <div class="cashier-stat-card">

                <div class="cashier-stat-icon sales-icon">
                    <svg viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M3 3v18h18"/>
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M7 16l4-5 3 3 5-7"/>
                    </svg>
                </div>

                <div>
                    <span>Sales Today</span>

                    <strong>
                        {{ format_currency($this->todayTotal) }}
                    </strong>

                    <small>
                        Gross sales today
                    </small>
                </div>

            </div>


            {{-- TRANSACTIONS --}}

            <div class="cashier-stat-card">

                <div class="cashier-stat-icon transaction-icon">
                    <svg viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>

                <div>
                    <span>Transactions</span>

                    <strong>
                        {{ $this->todayInvoices }}
                    </strong>

                    <small>
                        Invoices processed
                    </small>
                </div>

            </div>


            {{-- PAID --}}

            <div class="cashier-stat-card">

                <div class="cashier-stat-icon paid-icon">
                    <svg viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M2.25 8.25h19.5M2.25 9.75h19.5m-16.5 3.75h3m-3 3h5.25"/>
                        <rect x="3"
                              y="5"
                              width="18"
                              height="14"
                              rx="2"/>
                    </svg>
                </div>

                <div>
                    <span>Total Paid</span>

                    <strong>
                        {{ format_currency($this->todayPaid) }}
                    </strong>

                    <small class="success-text">
                        Collected today
                    </small>
                </div>

            </div>


            {{-- DUE --}}

            <div class="cashier-stat-card">

                <div class="cashier-stat-icon due-icon">
                    <svg viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <circle cx="12"
                                cy="12"
                                r="9"/>
                        <path stroke-linecap="round"
                              d="M12 7v5l3 2"/>
                    </svg>
                </div>

                <div>
                    <span>Total Due</span>

                    <strong>
                        {{ format_currency($this->todayDue) }}
                    </strong>

                    <small class="warning-text">
                        Outstanding today
                    </small>
                </div>

            </div>


            {{-- AVERAGE --}}

            <div class="cashier-stat-card">

                <div class="cashier-stat-icon average-icon">
                    <svg viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M4 19V9m6 10V5m6 14v-7m4 7V3"/>
                    </svg>
                </div>

                <div>
                    <span>Average Sale</span>

                    <strong>
                        {{
                            $this->todayInvoices > 0
                                ? format_currency(
                                    $this->todayTotal / $this->todayInvoices
                                )
                                : format_currency(0)
                        }}
                    </strong>

                    <small>
                        Per transaction
                    </small>
                </div>

            </div>

        </div>


        {{-- =========================================================
            MAIN GRID
        ========================================================== --}}

        <div class="cashier-main-grid">


            {{-- =====================================================
                QUICK ACTIONS
            ====================================================== --}}

            <div class="cashier-panel">

                <div class="cashier-panel-header">

                    <div>
                        <h2>Quick Actions</h2>

                        <p>
                            Frequently used cashier operations.
                        </p>
                    </div>

                </div>


                <div class="cashier-actions-grid">


                    <a href="{{ url('/cashier/sales/create') }}"
                       class="cashier-action primary">

                        <div class="action-icon">
                            +
                        </div>

                        <div class="action-content">
                            <strong>New Sale</strong>
                            <span>Open POS</span>
                        </div>

                        <span class="action-arrow">→</span>

                    </a>


                    <a href="{{ url('/cashier/sale-history') }}"
                       class="cashier-action">

                        <div class="action-icon">
                            ↗
                        </div>

                        <div class="action-content">
                            <strong>Sales History</strong>
                            <span>View completed sales</span>
                        </div>

                        <span class="action-arrow">→</span>

                    </a>


                    <a href="{{ url('/cashier/sale-returns') }}"
                       class="cashier-action">

                        <div class="action-icon">
                            ↩
                        </div>

                        <div class="action-content">
                            <strong>Sale Returns</strong>
                            <span>Process returns</span>
                        </div>

                        <span class="action-arrow">→</span>

                    </a>


                    <!-- <a href="{{ url('/cashier/payments') }}"
                       class="cashier-action">

                        <div class="action-icon">
                            £
                        </div>

                        <div class="action-content">
                            <strong>Payments</strong>
                            <span>Receive customer payments</span>
                        </div>

                        <span class="action-arrow">→</span>

                    </a> -->

                </div>

            </div>


            {{-- =====================================================
                TODAY'S SALES
            ====================================================== --}}

            <div class="cashier-panel">

                <div class="cashier-panel-header">

                    <div>
                        <h2>Today's Sales</h2>

                        <p>
                            Latest transactions.
                        </p>
                    </div>

                    <a href="{{ url('/cashier/sale-history') }}">
                        View All
                    </a>

                </div>


                <div class="cashier-sales-list">

                    @forelse($this->recentSales as $sale)

                        <div class="cashier-sale-row">

                            <div class="sale-invoice">

                                <div class="sale-avatar">
                                    #
                                </div>

                                <div>
                                    <strong>
                                        {{ $sale->invoice_number }}
                                    </strong>

                                    <span>
                                        {{ $sale->created_at?->format('h:i A') }}
                                    </span>
                                </div>

                            </div>


                            <div class="sale-customer">

                                {{ $sale->customer?->name ?? 'Walk-in Customer' }}

                            </div>


                            <div class="sale-amount">

                                {{ format_currency($sale->grand_total) }}

                            </div>


                            @if(($sale->due_amount ?? 0) > 0)

                                <span class="sale-status due">
                                    Due
                                </span>

                            @else

                                <span class="sale-status paid">
                                    Paid
                                </span>

                            @endif

                        </div>

                    @empty

                        <div class="cashier-empty">

                            <div class="empty-icon">
                                —
                            </div>

                            <strong>
                                No sales today
                            </strong>

                            <span>
                                Today's transactions will appear here.
                            </span>

                        </div>

                    @endforelse

                </div>

            </div>

        </div>


        {{-- =========================================================
            PAYMENT SUMMARY
        ========================================================== --}}

        <div class="cashier-payment-panel">

            <div class="cashier-panel-header">

                <div>
                    <h2>Payment Summary</h2>

                    <p>
                        Today's collected amount by payment method.
                    </p>
                </div>

                <div class="payment-total">

                    <span>Total Collected</span>

                    <strong>
                        {{ format_currency($this->todayPaid) }}
                    </strong>

                </div>

            </div>


            <div class="payment-grid">


                {{-- CASH --}}

                <div class="payment-item">

                    <div class="payment-icon">
                        £
                    </div>

                    <div>
                        <span>Cash</span>

                        <strong>
                            {{ format_currency($this->todayCash) }}
                        </strong>
                    </div>

                </div>


                {{-- CARD --}}

                <div class="payment-item">

                    <div class="payment-icon">
                        💳
                    </div>

                    <div>
                        <span>Card</span>

                        <strong>
                            {{ format_currency($this->todayCard) }}
                        </strong>
                    </div>

                </div>


                {{-- BANK --}}

                <div class="payment-item">

                    <div class="payment-icon">
                        🏦
                    </div>

                    <div>
                        <span>Bank Transfer</span>

                        <strong>
                            {{ format_currency($this->todayBank) }}
                        </strong>
                    </div>

                </div>


                {{-- OTHER --}}

                <div class="payment-item">

                    <div class="payment-icon">
                        •••
                    </div>

                    <div>
                        <span>Other</span>

                        <strong>
                            {{ format_currency($this->todayOther) }}
                        </strong>
                    </div>

                </div>

            </div>

        </div>

    </div>


    

    {{-- =============================================================
        CASHIER DASHBOARD CSS
    ============================================================= --}}

    <style>

        .cashier-dashboard {

            --cashier-primary: #008b96;
            --cashier-primary-dark: #00737c;
            --cashier-soft: #eaf8f9;
            --cashier-border: #d7e8ea;
            --cashier-text: #123d42;
            --cashier-muted: #70898d;
            --cashier-white: #ffffff;

            /* padding-bottom: 30px; */
            padding-bottom: 0;
    margin-bottom: 0;

        }

/* =========================================================
   REMOVE EXTRA FILAMENT BOTTOM SPACE
========================================================= */

.fi-page:has(.cashier-dashboard) .fi-page-content {
    padding-bottom: 0 !important;
    margin-bottom: 0 !important;
}

.fi-page:has(.cashier-dashboard) {
    padding-bottom: 0 !important;
    margin-bottom: 0 !important;
}

.fi-page:has(.cashier-dashboard) .fi-page-content-ctn {
    padding-bottom: 0 !important;
    margin-bottom: 0 !important;
}

.fi-main:has(.cashier-dashboard) {
    padding-bottom: 0 !important;
    margin-bottom: 0 !important;
}
        /* =========================================================
           HERO
        ========================================================== */

.cashier-hero {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 260px;
    gap: 16px;

    min-height: 175px;
    margin-bottom: 20px;

    border: none;
    border-radius: 0;
    overflow: visible;
    background: transparent;
}


     .cashier-hero-content {
    position: relative;
    padding: 24px 270px 22px 24px;
    min-height: 175px;

    background: linear-gradient(
        110deg,
        #eefafa 0%,
        #f7fcfc 65%,
        #e7f6f7 100%
    );

    border: 1px solid var(--cashier-border);
    border-radius: 16px;
    overflow: hidden;
}
.cashier-hero-image {
    position: absolute;

    right: 0;
    top: 0;
    bottom: 0;

    width: 350px;
    height: 199px;

    display: flex;
    align-items: flex-end;
    justify-content: flex-end;

    overflow: hidden;

    pointer-events: none;
}

.cashier-hero-image img {
    width: 100%;
    height: 100%;

    object-fit: contain;
    object-position: right bottom;

    display: block;

    /* Transparent / blended look */
    mix-blend-mode: multiply;
    opacity: 0.75;

    /* Right side par thora fade effect */
    -webkit-mask-image: linear-gradient(
        to right,
        transparent 0%,
        rgba(0, 0, 0, 0.4) 25%,
        #000 55%
    );

    mask-image: linear-gradient(
        to right,
        transparent 0%,
        rgba(0, 0, 0, 0.4) 25%,
        #555252 55%
    );
}

        .cashier-status {

            display: inline-flex;

            align-items: center;

            gap: 7px;

            padding: 5px 10px;

            border-radius: 20px;

            background: #d9f4f5;

            color: var(--cashier-primary);

            font-size: 10px;

            font-weight: 700;

        }


        .status-dot {

            width: 7px;

            height: 7px;

            border-radius: 50%;

            background: #12ad78;

        }


        .cashier-hero h1 {

            margin: 10px 0 3px;

            color: var(--cashier-text);

            font-size: 24px;

            line-height: 1.2;

            font-weight: 750;

        }


        .cashier-hero p {

            margin: 0;

            color: var(--cashier-primary);

            font-size: 12px;

        }


        .cashier-hero-actions {

            display: flex;

            gap: 7px;

            margin-top: 16px;

        }


        .cashier-primary-btn,
        .cashier-secondary-btn {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 6px;

            min-height: 32px;

            padding: 0 13px;

            border-radius: 8px;

            font-size: 11px;

            font-weight: 700;

            text-decoration: none;

            transition: .18s ease;

        }


        .cashier-primary-btn {

            background: var(--cashier-primary);

            color: white;

        }


        .cashier-primary-btn:hover {

            background: var(--cashier-primary-dark);

            color: white;

            transform: translateY(-1px);

        }


        .cashier-secondary-btn {

            background: white;

            border: 1px solid #a9d7da;

            color: var(--cashier-primary);

        }


        .cashier-secondary-btn:hover {

            background: var(--cashier-soft);

            color: var(--cashier-primary);

        }


        .btn-icon {

            font-size: 16px;

            line-height: 1;

        }


        /* =========================================================
           OVERVIEW
        ========================================================== */

        .cashier-overview {
    padding: 17px 16px;

    background: white;
    border: 1px solid var(--cashier-border);
    border-radius: 16px;

    box-shadow: 0 2px 8px rgba(0, 70, 80, .035);
}

        .overview-label {

            color: var(--cashier-primary);

            font-size: 10px;

        }


        .overview-date {

            display: block;

            margin-top: 2px;

            color: var(--cashier-text);

            font-size: 14px;

        }


        .overview-item {

            display: flex;

            align-items: center;

            justify-content: space-between;

            padding: 9px 0;

            margin-top: 5px;

            border-top: 1px solid #dcebed;

        }


        .overview-item span {

            color: var(--cashier-muted);

            font-size: 10px;

        }


        .overview-item strong {

            color: var(--cashier-text);

            font-size: 11px;

        }


        .overview-item .due-value {

            color: #b77900;

        }


        /* =========================================================
           STATS
        ========================================================== */

        .cashier-stats {

            display: grid;

            grid-template-columns:
                repeat(5, minmax(0, 1fr));

            gap: 12px;

            margin-bottom: 18px;

        }


        .cashier-stat-card {

            display: flex;

            align-items: center;

            gap: 11px;

            min-width: 0;

            padding: 14px;

            background: white;

            border: 1px solid var(--cashier-border);

            border-radius: 12px;

            box-shadow:
                0 2px 8px rgba(0, 70, 80, .035);

        }


        .cashier-stat-icon {

            width: 40px;

            height: 40px;

            min-width: 40px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 9px;

            background: var(--cashier-soft);

            color: var(--cashier-primary);

        }


        .cashier-stat-icon svg {

            width: 19px;

            height: 19px;

        }


        .due-icon {

            background: #fff5df;

            color: #b77900;

        }


        .paid-icon {

            background: #eaf8ef;

            color: #218653;

        }


        .average-icon {

            background: #eef1fb;

            color: #5969a6;

        }


        .cashier-stat-card > div:last-child {

            min-width: 0;

        }


        .cashier-stat-card span {

            display: block;

            color: var(--cashier-muted);

            font-size: 10px;

            margin-bottom: 3px;

        }


        .cashier-stat-card strong {

            display: block;

            color: var(--cashier-text);

            font-size: 17px;

            line-height: 1.15;

            white-space: nowrap;

            overflow: hidden;

            text-overflow: ellipsis;

        }


        .cashier-stat-card small {

            display: block;

            margin-top: 3px;

            color: var(--cashier-muted);

            font-size: 9px;

        }


        .success-text {

            color: #218653 !important;

        }


        .warning-text {

            color: #b77900 !important;

        }


        /* =========================================================
           MAIN GRID
        ========================================================== */

        .cashier-main-grid {

            display: grid;

            grid-template-columns: .85fr 1.15fr;

            gap: 16px;

            margin-bottom: 16px;

        }


        .cashier-panel,
        .cashier-payment-panel {

            background: white;

            border: 1px solid var(--cashier-border);

            border-radius: 13px;

            overflow: hidden;

            box-shadow:
                0 2px 8px rgba(0, 70, 80, .025);

        }


        /* =========================================================
           PANEL HEADER
        ========================================================== */

        .cashier-panel-header {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 12px;

            padding: 14px 17px;

            border-bottom: 1px solid var(--cashier-border);

        }


        .cashier-panel-header h2 {

            margin: 0;

            color: var(--cashier-text);

            font-size: 14px;

            font-weight: 700;

        }


        .cashier-panel-header p {

            margin: 3px 0 0;

            color: var(--cashier-muted);

            font-size: 10px;

        }


        .cashier-panel-header > a {

            color: var(--cashier-primary);

            font-size: 10px;

            font-weight: 700;

            text-decoration: none;

        }


        /* =========================================================
           ACTIONS
        ========================================================== */

        .cashier-actions-grid {

            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 9px;

            padding: 13px;

        }


        .cashier-action {

            display: flex;

            align-items: center;

            gap: 9px;

            min-height: 67px;

            padding: 10px;

            border: 1px solid var(--cashier-border);

            border-radius: 9px;

            text-decoration: none;

            transition: .18s ease;

        }


        .cashier-action:hover {

            border-color: var(--cashier-primary);

            background: #f8fdfd;

            transform: translateY(-1px);

        }


        .cashier-action.primary {

            background: var(--cashier-primary);

            border-color: var(--cashier-primary);

        }


        .action-icon {

            width: 34px;

            height: 34px;

            min-width: 34px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 8px;

            background: var(--cashier-soft);

            color: var(--cashier-primary);

            font-size: 18px;

            font-weight: 600;

        }


        .cashier-action.primary .action-icon {

            background: rgba(255,255,255,.16);

            color: white;

        }


        .action-content {

            min-width: 0;

        }


        .action-content strong {

            display: block;

            color: var(--cashier-text);

            font-size: 11px;

            font-weight: 700;

        }


        .action-content span {

            display: block;

            margin-top: 2px;

            color: var(--cashier-muted);

            font-size: 9px;

        }


        .cashier-action.primary .action-content strong,
        .cashier-action.primary .action-content span {

            color: white;

        }


        .action-arrow {

            margin-left: auto;

            color: var(--cashier-muted);

            font-size: 14px;

        }


        .cashier-action.primary .action-arrow {

            color: white;

        }


        /* =========================================================
           SALES
        ========================================================== */

        .cashier-sale-row {

            display: grid;

            grid-template-columns: 1.4fr 1fr .8fr auto;

            align-items: center;

            gap: 10px;

            padding: 11px 17px;

            border-bottom: 1px solid #edf4f5;

        }


        .cashier-sale-row:last-child {

            border-bottom: 0;

        }


        .sale-invoice {

            display: flex;

            align-items: center;

            gap: 8px;

            min-width: 0;

        }


        .sale-avatar {

            width: 29px;

            height: 29px;

            min-width: 29px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 7px;

            background: var(--cashier-soft);

            color: var(--cashier-primary);

            font-size: 11px;

            font-weight: 700;

        }


        .sale-invoice strong {

            display: block;

            color: var(--cashier-text);

            font-size: 10px;

        }


        .sale-invoice span {

            display: block;

            margin-top: 2px;

            color: var(--cashier-muted);

            font-size: 8px;

        }


        .sale-customer {

            color: var(--cashier-muted);

            font-size: 9px;

            white-space: nowrap;

            overflow: hidden;

            text-overflow: ellipsis;

        }


        .sale-amount {

            color: var(--cashier-text);

            font-size: 10px;

            font-weight: 700;

            white-space: nowrap;

        }


        .sale-status {

            display: inline-flex;

            justify-content: center;

            padding: 4px 7px;

            border-radius: 5px;

            font-size: 8px;

            font-weight: 700;

        }


        .sale-status.paid {

            background: #e8f8ee;

            color: #218653;

        }


        .sale-status.due {

            background: #fff3df;

            color: #b76a00;

        }


        /* =========================================================
           EMPTY
        ========================================================== */

        .cashier-empty {

            display: flex;

            flex-direction: column;

            align-items: center;

            justify-content: center;

            min-height: 190px;

            text-align: center;

        }


        .empty-icon {

            margin-bottom: 6px;

            color: var(--cashier-muted);

            font-size: 24px;

        }


        .cashier-empty strong {

            color: var(--cashier-text);

            font-size: 11px;

        }


        .cashier-empty span {

            margin-top: 3px;

            color: var(--cashier-muted);

            font-size: 9px;

        }


        /* =========================================================
           PAYMENT SUMMARY
        ========================================================== */

        .cashier-payment-panel {

            margin-bottom: 10px;

        }


        .payment-total {

            text-align: right;

        }


        .payment-total span {

            display: block;

            color: var(--cashier-muted);

            font-size: 9px;

        }


        .payment-total strong {

            display: block;

            margin-top: 2px;

            color: var(--cashier-text);

            font-size: 15px;

        }


        .payment-grid {

            display: grid;

            grid-template-columns:
                repeat(4, 1fr);

        }


        .payment-item {

            display: flex;

            align-items: center;

            gap: 9px;

            padding: 14px 17px;

            border-right: 1px solid #edf4f5;

        }


        .payment-item:last-child {

            border-right: 0;

        }


        .payment-icon {

            width: 31px;

            height: 31px;

            min-width: 31px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 7px;

            background: var(--cashier-soft);

            color: var(--cashier-primary);

            font-size: 10px;

            font-weight: 700;

        }


        .payment-item span {

            display: block;

            color: var(--cashier-muted);

            font-size: 9px;

        }


        .payment-item strong {

            display: block;

            margin-top: 2px;

            color: var(--cashier-text);

            font-size: 11px;

        }


        /* =========================================================
           RESPONSIVE
        ========================================================== */

        @media (max-width: 1150px) {

            .cashier-stats {

                grid-template-columns:
                    repeat(3, 1fr);

            }

        }


        @media (max-width: 950px) {

            .cashier-hero {

                grid-template-columns: 1fr;

            }

            .cashier-overview {

                border-left: 0;

                border-top: 1px solid var(--cashier-border);

            }

            .cashier-main-grid {

                grid-template-columns: 1fr;

            }

            .payment-grid {

                grid-template-columns: repeat(2, 1fr);

            }

            .payment-item:nth-child(2) {

                border-right: 0;

            }

            .payment-item:nth-child(-n+2) {

                border-bottom: 1px solid #edf4f5;

            }

        }


        @media (max-width: 650px) {

            .cashier-stats {

                grid-template-columns: 1fr;

            }

            .cashier-actions-grid {

                grid-template-columns: 1fr;

            }

            .cashier-sale-row {

                grid-template-columns: 1fr auto;

            }

            .sale-customer {

                display: none;

            }

            .payment-grid {

                grid-template-columns: 1fr;

            }

            .payment-item {

                border-right: 0;

                border-bottom: 1px solid #edf4f5;

            }

            .payment-item:last-child {

                border-bottom: 0;

            }

        }
        

    </style>

</x-filament-panels::page>