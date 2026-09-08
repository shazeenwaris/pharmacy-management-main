<x-filament-panels::page>

    @php
        $money = fn ($value) => format_currency((float) $value);
    @endphp

    <div class="pms-financial-page">

        {{-- =========================================================
             MODERN PAGE HEADER
        ========================================================== --}}
        <div class="pms-report-header">

            <div class="pms-header-glow pms-glow-one"></div>
            <div class="pms-header-glow pms-glow-two"></div>

            <div class="pms-header-content">

                <div class="pms-header-left">

                    <div class="pms-header-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-10V4m0 16v-2m0 0a8 8 0 100-16 8 8 0 000 16z"
                            />
                        </svg>

                    </div>

                    <div>

                        <div class="pms-title-row">

                            <h1>
                                Financial Reports
                            </h1>

                            <span class="pms-title-badge">
                                Financial Analytics
                            </span>

                        </div>

                        <p>
                            Analyze revenue, purchase cost, profit, expenses and outstanding balances.
                        </p>

                    </div>

                </div>

                <div class="pms-live-status">

                    <span class="pms-live-dot">
                        <span></span>
                    </span>

                    <span>
                        Live Database
                    </span>

                </div>

            </div>

        </div>


        {{-- =========================================================
             FILTER PANEL
        ========================================================== --}}
        <div class="pms-filter-card">

            <div class="pms-section-heading">

                <div class="pms-section-icon">

                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M3 4h18M6 8h12M10 12h4M11 16h2M12 20v-4"
                        />
                    </svg>

                </div>

                <div>
                    <h3>Report Filters</h3>

                    <p>
                        Filter financial information by date period.
                    </p>
                </div>

            </div>


            <div class="pms-filter-grid">

                {{-- Date Range --}}
                <div class="pms-field">

                    <label>Date Range</label>

                    <select wire:model.live="dateRange">

                        <option value="all">
                            All Time
                        </option>

                        <option value="today">
                            Today
                        </option>

                        <option value="7_days">
                            Last 7 Days
                        </option>

                        <option value="this_month">
                            This Month
                        </option>

                        <option value="last_month">
                            Last Month
                        </option>

                        <option value="custom">
                            Custom Range
                        </option>

                    </select>

                </div>


                {{-- From --}}
                <div class="pms-field">

                    <label>From Date</label>

                    <input
                        type="date"
                        wire:model.live="fromDate"
                        @disabled($dateRange !== 'custom')
                    >

                </div>


                {{-- To --}}
                <div class="pms-field">

                    <label>To Date</label>

                    <input
                        type="date"
                        wire:model.live="toDate"
                        @disabled($dateRange !== 'custom')
                    >

                </div>


                {{-- Period --}}
                <div class="pms-period-box">

                    <div class="pms-period-icon">

                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"
                            />
                        </svg>

                    </div>

                    <div>

                        <span>
                            Current Period
                        </span>

                        <strong>

                            @if($from && $to)

                                {{ $from->format('d M Y') }}
                                -
                                {{ $to->format('d M Y') }}

                            @else

                                All Time

                            @endif

                        </strong>

                    </div>

                </div>

            </div>


            <div class="pms-filter-actions">

                <button
                    type="button"
                    wire:click="resetFilters"
                    class="pms-btn pms-btn-light"
                >

                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
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
                    class="pms-btn pms-btn-primary"
                >

                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
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
             MAIN FINANCIAL CARDS
        ========================================================== --}}
        <div class="pms-stat-grid">

            {{-- Revenue --}}
            <div class="pms-stat-card revenue">

                <div class="pms-stat-top">

                    <div>

                        <span class="pms-stat-label">
                            Revenue
                        </span>

                        <h3>
                            {{ $money($totalRevenue) }}
                        </h3>

                        <small>
                            {{ number_format($totalSales) }} sales
                        </small>

                    </div>

                    <div class="pms-stat-icon">

                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-10V4m0 16v-2m0 0a8 8 0 100-16 8 8 0 000 16z"
                            />
                        </svg>

                    </div>

                </div>

                <div class="pms-card-line"></div>

            </div>


            {{-- Purchase --}}
            <div class="pms-stat-card purchase">

                <div class="pms-stat-top">

                    <div>

                        <span class="pms-stat-label">
                            Purchase Cost
                        </span>

                        <h3>
                            {{ $money($totalPurchaseCost) }}
                        </h3>

                        <small>
                            {{ number_format($totalPurchases) }} purchases
                        </small>

                    </div>

                    <div class="pms-stat-icon">

                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                            />
                        </svg>

                    </div>

                </div>

                <div class="pms-card-line"></div>

            </div>


            {{-- Gross --}}
            <div class="pms-stat-card profit">

                <div class="pms-stat-top">

                    <div>

                        <span class="pms-stat-label">
                            Gross Profit
                        </span>

                        <h3 class="{{ $grossProfit < 0 ? 'negative' : '' }}">
                            {{ $money($grossProfit) }}
                        </h3>

                        <small>
                            Revenue − Purchase Cost
                        </small>

                    </div>

                    <div class="pms-stat-icon">

                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M3 17l6-6 4 4 8-9M17 6h4v4"
                            />
                        </svg>

                    </div>

                </div>

                <div class="pms-card-line"></div>

            </div>


            {{-- Net --}}
            <div class="pms-stat-card net">

                <div class="pms-stat-top">

                    <div>

                        <span class="pms-stat-label">
                            Net Profit
                        </span>

                        <h3 class="{{ $netProfit < 0 ? 'negative' : '' }}">
                            {{ $money($netProfit) }}
                        </h3>

                        <small>
                            After all expenses
                        </small>

                    </div>

                    <div class="pms-stat-icon">

                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M12 3v18m9-9H3"
                            />
                        </svg>

                    </div>

                </div>

                <div class="pms-card-line"></div>

            </div>

        </div>


        {{-- =========================================================
             SECONDARY FINANCIAL CARDS
        ========================================================== --}}
        <div class="pms-secondary-grid">

            <div class="pms-secondary-card">

                <div class="pms-secondary-icon expense-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M12 9v4m0 4h.01M5.5 19h13a1.5 1.5 0 001.3-2.25l-6.5-11a1.5 1.5 0 00-2.6 0l-6.5 11A1.5 1.5 0 005.5 19z"
                        />
                    </svg>
                </div>

                <div>
                    <span>Total Expenses</span>

                    <strong>
                        {{ $money($totalExpenses) }}
                    </strong>

                    <small>
                        {{ number_format($totalExpenseCount) }} expense records
                    </small>
                </div>

            </div>


            <div class="pms-secondary-card">

                <div class="pms-secondary-icon supplier-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6M9 9h.01M15 9h.01"
                        />
                    </svg>
                </div>

                <div>
                    <span>Supplier Dues</span>

                    <strong>
                        {{ $money($supplierDues) }}
                    </strong>

                    <small>
                        Outstanding payable balance
                    </small>
                </div>

            </div>


            <div class="pms-secondary-card">

                <div class="pms-secondary-icon customer-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M15 19a6 6 0 00-12 0m6-8a4 4 0 100-8 4 4 0 000 8zm5-5h6m-3-3v6"
                        />
                    </svg>
                </div>

                <div>
                    <span>Customer Dues</span>

                    <strong>
                        {{ $money($customerDues) }}
                    </strong>

                    <small>
                        Outstanding receivables
                    </small>
                </div>

            </div>

        </div>


        {{-- =========================================================
             FINANCIAL OVERVIEW
        ========================================================== --}}
        <div class="pms-panel">

            <div class="pms-panel-header">

                <div>
                    <h3>
                        Financial Overview
                    </h3>

                    <p>
                        Complete breakdown of the selected financial period.
                    </p>
                </div>

                <div class="pms-panel-badge">
                    Overview
                </div>

            </div>


            <div class="pms-overview-grid">

                <div class="pms-overview-box teal">

                    <span>Revenue</span>

                    <strong>
                        {{ $money($totalRevenue) }}
                    </strong>

                </div>


                <div class="pms-overview-box sky">

                    <span>Purchase Cost</span>

                    <strong>
                        {{ $money($totalPurchaseCost) }}
                    </strong>

                </div>


                <div class="pms-overview-box rose">

                    <span>Expenses</span>

                    <strong>
                        {{ $money($totalExpenses) }}
                    </strong>

                </div>

            </div>


            <div class="pms-profit-grid">

                <div class="pms-profit-box gross">

                    <div>
                        <span>Gross Profit</span>

                        <small>
                            Revenue minus purchase cost
                        </small>
                    </div>

                    <strong class="{{ $grossProfit < 0 ? 'negative' : '' }}">
                        {{ $money($grossProfit) }}
                    </strong>

                </div>


                <div class="pms-profit-box net">

                    <div>
                        <span>Net Profit</span>

                        <small>
                            Gross profit minus expenses
                        </small>
                    </div>

                    <strong class="{{ $netProfit < 0 ? 'negative' : '' }}">
                        {{ $money($netProfit) }}
                    </strong>

                </div>

            </div>

        </div>


        {{-- =========================================================
             DAILY REVENUE
        ========================================================== --}}
        <div class="pms-panel">

            <div class="pms-panel-header">

                <div>
                    <h3>Daily Revenue</h3>

                    <p>
                        Sales revenue grouped by date.
                    </p>
                </div>

                <div class="pms-table-badge teal-badge">
                    Revenue
                </div>

            </div>


            <div class="pms-table-wrapper">

                <table class="pms-table">

                    <thead>
                        <tr>
                            <th>Date</th>
                            <th class="center">Sales</th>
                            <th class="right">Revenue</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($dailyRevenue as $row)

                            <tr>

                                <td>
                                    <div class="pms-date-cell">
                                        <span class="pms-date-dot"></span>

                                        {{ Carbon\Carbon::parse($row->report_date)->format('d M Y') }}
                                    </div>
                                </td>

                                <td class="center">

                                    <span class="pms-count-badge">
                                        {{ number_format($row->sale_count) }}
                                    </span>

                                </td>

                                <td class="right strong">
                                    {{ $money($row->total_amount) }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="3" class="empty-row">
                                    No revenue data available.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =========================================================
             EXPENSE + BALANCE
        ========================================================== --}}
        <div class="pms-two-column">


            {{-- Expense Categories --}}
            <div class="pms-panel">

                <div class="pms-panel-header">

                    <div>
                        <h3>Expense Categories</h3>

                        <p>
                            Expenses grouped by category.
                        </p>
                    </div>

                    <div class="pms-table-badge rose-badge">
                        Expenses
                    </div>

                </div>


                <div class="pms-table-wrapper">

                    <table class="pms-table">

                        <thead>

                            <tr>
                                <th>Category</th>
                                <th class="center">Records</th>
                                <th class="right">Amount</th>
                            </tr>

                        </thead>

                        <tbody>

                            @forelse($expenseCategories as $expense)

                                <tr>

                                    <td class="strong">
                                        {{ $expense->category_name ?? 'Uncategorized' }}
                                    </td>

                                    <td class="center">

                                        <span class="pms-count-badge">
                                            {{ number_format($expense->expense_count) }}
                                        </span>

                                    </td>

                                    <td class="right strong">
                                        {{ $money($expense->total_amount) }}
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="3" class="empty-row">
                                        No expense data available.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- Balance Summary --}}
            <div class="pms-panel pms-balance-panel">

                <div class="pms-panel-header">

                    <div>
                        <h3>Balance Summary</h3>

                        <p>
                            Outstanding business balances.
                        </p>
                    </div>

                    <div class="pms-table-badge teal-badge">
                        Balance
                    </div>

                </div>


                <div class="pms-balance-list">

                    <div class="pms-balance-item supplier">

                        <div>
                            <span>Supplier Payables</span>
                            <small>Amount owed to suppliers</small>
                        </div>

                        <strong>
                            {{ $money($supplierDues) }}
                        </strong>

                    </div>


                    <div class="pms-balance-item customer">

                        <div>
                            <span>Customer Receivables</span>
                            <small>Amount receivable from customers</small>
                        </div>

                        <strong>
                            {{ $money($customerDues) }}
                        </strong>

                    </div>


                    <div class="pms-balance-item payment">

                        <div>
                            <span>Supplier Payments</span>
                            <small>Payments made in selected period</small>
                        </div>

                        <strong>
                            {{ $money($supplierPayments) }}
                        </strong>

                    </div>


                    <div class="pms-balance-item collected">

                        <div>
                            <span>Customer Payments</span>
                            <small>Amount collected from sales</small>
                        </div>

                        <strong>
                            {{ $money($customerPaid) }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
             RECENT SALES
        ========================================================== --}}
        <div class="pms-panel">

            <div class="pms-panel-header">

                <div>
                    <h3>Recent Sales</h3>

                    <p>
                        Latest sales and revenue activity.
                    </p>
                </div>

                <span class="pms-table-badge teal-badge">
                    Latest 10
                </span>

            </div>


            <div class="pms-table-wrapper">

                <table class="pms-table">

                    <thead>

                        <tr>
                            <th>Invoice</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th class="right">Amount</th>
                            <th class="right">Paid</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($recentSales as $sale)

                            <tr>

                                <td class="strong">
                                    {{ $sale->invoice_number }}
                                </td>

                                <td>
                                    {{ $sale->customer?->name ?? 'Walk-in Customer' }}
                                </td>

                                <td>
                                    {{ $sale->created_at?->format('d M Y') ?? 'N/A' }}
                                </td>

                                <td class="right strong">
                                    {{ $money($sale->grand_total) }}
                                </td>

                                <td class="right paid-text">
                                    {{ $money($sale->paid_amount) }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="empty-row">
                                    No sales found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =========================================================
             RECENT EXPENSES
        ========================================================== --}}
        <div class="pms-panel">

            <div class="pms-panel-header">

                <div>
                    <h3>Recent Expenses</h3>

                    <p>
                        Latest business expenses.
                    </p>
                </div>

                <span class="pms-table-badge rose-badge">
                    Latest 10
                </span>

            </div>


            <div class="pms-table-wrapper">

                <table class="pms-table">

                    <thead>

                        <tr>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Payment</th>
                            <th class="right">Amount</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($recentExpenses as $expense)

                            <tr>

                                <td>
                                    {{ $expense->expense_date?->format('d M Y') ?? 'N/A' }}
                                </td>

                                <td class="strong">
                                    {{ $expense->category?->name ?? 'Uncategorized' }}
                                </td>

                                <td class="description-cell">
                                    {{ $expense->description ?? 'No description' }}
                                </td>

                                <td>
                                    {{ $expense->payment_method ?? 'N/A' }}
                                </td>

                                <td class="right strong">
                                    {{ $money($expense->amount) }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="empty-row">
                                    No expenses found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- =============================================================
         PREMIUM FINANCIAL REPORTS CSS
    ============================================================= --}}
    <style>

        /* =========================================================
           THEME
        ========================================================== */

        .pms-financial-page {

            --pms-primary: #0097a7;
            --pms-primary-dark: #007f8c;
            --pms-primary-light: #e8f9fb;

            --pms-text: #102a43;
            --pms-muted: #718096;

            --pms-border: #e5edf0;

            color: var(--pms-text);

            animation: pmsFadeIn .45s ease-out;
        }


        /* =========================================================
           HEADER
        ========================================================== */

        .pms-report-header {

            position: relative;

            overflow: hidden;

            border: 1px solid #cdecef;

            border-radius: 24px;

            padding: 26px 30px;

            background:
                linear-gradient(
                    135deg,
                    #eefbfc 0%,
                    #ffffff 48%,
                    #f0fcfa 100%
                );

            box-shadow:
                0 10px 30px rgba(0, 151, 167, .07);
        }


        .pms-header-content {

            position: relative;
            z-index: 2;

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 24px;
        }


        .pms-header-left {

            display: flex;

            align-items: center;

            gap: 17px;
        }


        .pms-header-icon {

            width: 58px;
            height: 58px;

            flex-shrink: 0;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 17px;

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #00a5b5,
                    #008c99
                );

            box-shadow:
                0 10px 24px rgba(0, 151, 167, .25);
        }


        .pms-header-icon svg {

            width: 28px;
            height: 28px;
        }


        .pms-title-row {

            display: flex;

            align-items: center;

            flex-wrap: wrap;

            gap: 10px;
        }


        .pms-title-row h1 {

            margin: 0;

            color: #102a43;

            font-size: 27px;

            font-weight: 800;

            letter-spacing: -.025em;
        }


        .pms-title-badge {

            padding: 5px 11px;

            border-radius: 999px;

            background: #dff7f9;

            color: #007d8b;

            font-size: 11px;

            font-weight: 700;

            letter-spacing: .02em;
        }


        .pms-header-left p {

            margin: 5px 0 0;

            color: #718096;

            font-size: 13px;
        }


        .pms-live-status {

            display: inline-flex;

            align-items: center;

            gap: 9px;

            padding: 9px 14px;

            border: 1px solid rgba(255,255,255,.9);

            border-radius: 12px;

            background: rgba(255,255,255,.82);

            box-shadow: 0 5px 18px rgba(15,23,42,.05);

            color: #52616b;

            font-size: 12px;

            font-weight: 700;

            white-space: nowrap;

            backdrop-filter: blur(8px);
        }


        .pms-live-dot {

            position: relative;

            display: flex;

            width: 9px;
            height: 9px;
        }


        .pms-live-dot::before {

            content: '';

            position: absolute;

            inset: -2px;

            border-radius: 50%;

            background: #10b981;

            opacity: .25;

            animation: pmsPing 1.7s infinite;
        }


        .pms-live-dot span {

            position: relative;

            width: 9px;
            height: 9px;

            border-radius: 50%;

            background: #10b981;
        }


        .pms-header-glow {

            position: absolute;

            border-radius: 999px;

            filter: blur(35px);

            pointer-events: none;
        }


        .pms-glow-one {

            width: 170px;
            height: 170px;

            right: -45px;
            top: -75px;

            background: rgba(0,151,167,.13);
        }


        .pms-glow-two {

            width: 150px;
            height: 150px;

            left: 35%;

            bottom: -90px;

            background: rgba(16,185,129,.09);
        }


        /* =========================================================
           FILTER
        ========================================================== */

        .pms-filter-card {

            margin-top: 22px;

            padding: 24px;

            border: 1px solid var(--pms-border);

            border-radius: 20px;

            background: white;

            box-shadow:
                0 7px 25px rgba(15,23,42,.045);
        }


        .pms-section-heading {

            display: flex;

            align-items: center;

            gap: 12px;

            margin-bottom: 20px;
        }


        .pms-section-icon {

            width: 40px;
            height: 40px;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 12px;

            background: #e8f9fb;

            color: var(--pms-primary);
        }


        .pms-section-icon svg {

            width: 20px;
            height: 20px;
        }


        .pms-section-heading h3 {

            margin: 0;

            font-size: 15px;

            font-weight: 800;

            color: #102a43;
        }


        .pms-section-heading p {

            margin: 2px 0 0;

            color: #8795a1;

            font-size: 12px;
        }


        .pms-filter-grid {

            display: grid;

            grid-template-columns:
                repeat(4, minmax(0, 1fr));

            gap: 16px;
        }


        .pms-field label {

            display: block;

            margin-bottom: 8px;

            color: #52616b;

            font-size: 12px;

            font-weight: 700;
        }


        .pms-field select,
        .pms-field input {

            width: 100%;

            height: 44px;

            padding: 0 13px;

            border: 1px solid #dce7eb;

            border-radius: 11px;

            outline: none;

            background: #f8fafb;

            color: #34495e;

            font-size: 13px;

            font-weight: 600;

            transition: .2s ease;
        }


        .pms-field select:focus,
        .pms-field input:focus {

            border-color: var(--pms-primary);

            background: white;

            box-shadow:
                0 0 0 3px rgba(0,151,167,.09);
        }


        .pms-field input:disabled {

            cursor: not-allowed;

            opacity: .48;
        }


        .pms-period-box {

            display: flex;

            align-items: center;

            gap: 11px;

            min-height: 44px;

            margin-top: 24px;

            padding: 9px 12px;

            border: 1px solid #d9f0f2;

            border-radius: 11px;

            background: #effbfc;
        }


        .pms-period-icon {

            width: 34px;
            height: 34px;

            flex-shrink: 0;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 9px;

            background: white;

            color: var(--pms-primary);

            box-shadow: 0 2px 7px rgba(15,23,42,.05);
        }


        .pms-period-icon svg {

            width: 17px;
            height: 17px;
        }


        .pms-period-box span {

            display: block;

            color: #00818e;

            font-size: 10px;

            font-weight: 800;

            text-transform: uppercase;

            letter-spacing: .04em;
        }


        .pms-period-box strong {

            display: block;

            margin-top: 1px;

            color: #34495e;

            font-size: 12px;
        }


        .pms-filter-actions {

            display: flex;

            justify-content: flex-end;

            gap: 10px;

            margin-top: 20px;

            padding-top: 18px;

            border-top: 1px solid #eef2f4;
        }


        .pms-btn {

            display: inline-flex;

            align-items: center;
            justify-content: center;

            gap: 8px;

            height: 42px;

            padding: 0 18px;

            border-radius: 11px;

            font-size: 12px;

            font-weight: 700;

            cursor: pointer;

            transition: .2s ease;
        }


        .pms-btn svg {

            width: 16px;
            height: 16px;
        }


        .pms-btn-light {

            border: 1px solid #dce5e9;

            background: white;

            color: #62727c;
        }


        .pms-btn-light:hover {

            background: #f8fafb;

            border-color: #cbd8dd;

            transform: translateY(-1px);
        }


        .pms-btn-primary {

            border: 1px solid var(--pms-primary);

            background:
                linear-gradient(
                    135deg,
                    #00a2b2,
                    #008c99
                );

            color: white;

            box-shadow:
                0 7px 17px rgba(0,151,167,.19);
        }


        .pms-btn-primary:hover {

            transform: translateY(-1px);

            box-shadow:
                0 10px 22px rgba(0,151,167,.25);
        }


        /* =========================================================
           MAIN STATS
        ========================================================== */

        .pms-stat-grid {

            display: grid;

            grid-template-columns:
                repeat(4, minmax(0, 1fr));

            gap: 18px;

            margin-top: 22px;
        }


        .pms-stat-card {

            position: relative;

            overflow: hidden;

            min-height: 164px;

            padding: 21px;

            border: 1px solid #e4ecef;

            border-radius: 18px;

            background: white;

            box-shadow:
                0 6px 20px rgba(15,23,42,.04);

            transition:
                transform .25s ease,
                box-shadow .25s ease,
                border-color .25s ease;
        }


        .pms-stat-card:hover {

            transform: translateY(-4px);

            box-shadow:
                0 15px 32px rgba(15,23,42,.09);
        }


        .pms-stat-card::after {

            content: '';

            position: absolute;

            right: -35px;
            top: -35px;

            width: 100px;
            height: 100px;

            border-radius: 50%;

            opacity: .45;
        }


        .pms-stat-card.revenue::after {
            background: #dff8fa;
        }

        .pms-stat-card.purchase::after {
            background: #e8f5ff;
        }

        .pms-stat-card.profit::after {
            background: #e7f9ef;
        }

        .pms-stat-card.net::after {
            background: #f1eafd;
        }


        .pms-stat-top {

            position: relative;

            z-index: 2;

            display: flex;

            justify-content: space-between;

            gap: 15px;
        }


        .pms-stat-label {

            display: block;

            color: #718096;

            font-size: 12px;

            font-weight: 700;
        }


        .pms-stat-card h3 {

            margin: 8px 0 0;

            color: #102a43;

            font-size: 24px;

            font-weight: 800;

            letter-spacing: -.03em;
        }


        .pms-stat-card h3.negative {

            color: #e05252;
        }


        .pms-stat-card small {

            display: block;

            margin-top: 8px;

            color: #008796;

            font-size: 11px;

            font-weight: 700;
        }


        .pms-stat-card.purchase small {
            color: #3182ce;
        }

        .pms-stat-card.profit small {
            color: #159957;
        }

        .pms-stat-card.net small {
            color: #805ad5;
        }


        .pms-stat-icon {

            width: 48px;
            height: 48px;

            flex-shrink: 0;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 13px;

            background: #e8f9fb;

            color: var(--pms-primary);
        }


        .pms-stat-icon svg {

            width: 23px;
            height: 23px;
        }


        .purchase .pms-stat-icon {

            background: #edf7ff;
            color: #3182ce;
        }


        .profit .pms-stat-icon {

            background: #eaf9f0;
            color: #159957;
        }


        .net .pms-stat-icon {

            background: #f2edfc;
            color: #805ad5;
        }


        .pms-card-line {

            position: absolute;

            left: 21px;
            right: 21px;
            bottom: 0;

            height: 3px;

            border-radius: 10px 10px 0 0;

            background: linear-gradient(
                90deg,
                #00a5b5,
                #66d4dc
            );
        }


        .purchase .pms-card-line {
            background: linear-gradient(90deg,#3182ce,#90cdf4);
        }

        .profit .pms-card-line {
            background: linear-gradient(90deg,#159957,#68d391);
        }

        .net .pms-card-line {
            background: linear-gradient(90deg,#805ad5,#b794f4);
        }


        /* =========================================================
           SECONDARY
        ========================================================== */

        .pms-secondary-grid {

            display: grid;

            grid-template-columns:
                repeat(3, minmax(0, 1fr));

            gap: 18px;

            margin-top: 18px;
        }


        .pms-secondary-card {

            display: flex;

            align-items: center;

            gap: 15px;

            padding: 19px;

            border: 1px solid #e5ecef;

            border-radius: 17px;

            background: white;

            box-shadow: 0 5px 18px rgba(15,23,42,.035);

            transition: .22s ease;
        }


        .pms-secondary-card:hover {

            transform: translateY(-3px);

            box-shadow: 0 12px 25px rgba(15,23,42,.07);
        }


        .pms-secondary-icon {

            width: 48px;
            height: 48px;

            flex-shrink: 0;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 13px;
        }


        .pms-secondary-icon svg {

            width: 22px;
            height: 22px;
        }


        .expense-icon {
            background: #fff0f1;
            color: #e05252;
        }


        .supplier-icon {
            background: #edf1ff;
            color: #5367c9;
        }


        .customer-icon {
            background: #fff7df;
            color: #d69e2e;
        }


        .pms-secondary-card span {

            display: block;

            color: #718096;

            font-size: 12px;

            font-weight: 700;
        }


        .pms-secondary-card strong {

            display: block;

            margin-top: 4px;

            color: #102a43;

            font-size: 20px;

            font-weight: 800;
        }


        .pms-secondary-card small {

            display: block;

            margin-top: 4px;

            color: #9aa7b0;

            font-size: 10px;
        }


        /* =========================================================
           PANELS
        ========================================================== */

        .pms-panel {

            margin-top: 22px;

            overflow: hidden;

            border: 1px solid #e4ecef;

            border-radius: 19px;

            background: white;

            box-shadow:
                0 6px 22px rgba(15,23,42,.035);
        }


        .pms-panel-header {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 15px;

            padding: 20px 22px;

            border-bottom: 1px solid #edf1f3;
        }


        .pms-panel-header h3 {

            margin: 0;

            color: #102a43;

            font-size: 16px;

            font-weight: 800;
        }


        .pms-panel-header p {

            margin: 4px 0 0;

            color: #8795a1;

            font-size: 11px;
        }


        .pms-panel-badge,
        .pms-table-badge {

            padding: 6px 10px;

            border-radius: 8px;

            font-size: 10px;

            font-weight: 800;

            white-space: nowrap;
        }


        .teal-badge {

            background: #e7f9fb;

            color: #008796;
        }


        .rose-badge {

            background: #fff0f1;

            color: #d94c5a;
        }


        /* =========================================================
           OVERVIEW
        ========================================================== */

        .pms-overview-grid {

            display: grid;

            grid-template-columns:
                repeat(3, minmax(0, 1fr));

            gap: 14px;

            padding: 20px 22px 0;
        }


        .pms-overview-box {

            padding: 18px;

            border-radius: 13px;
        }


        .pms-overview-box.teal {
            background: #edfafa;
        }

        .pms-overview-box.sky {
            background: #eff8ff;
        }

        .pms-overview-box.rose {
            background: #fff2f3;
        }


        .pms-overview-box span {

            color: #64748b;

            font-size: 11px;

            font-weight: 700;
        }


        .pms-overview-box strong {

            display: block;

            margin-top: 6px;

            color: #102a43;

            font-size: 21px;

            font-weight: 800;
        }


        .pms-profit-grid {

            display: grid;

            grid-template-columns:
                repeat(2, minmax(0, 1fr));

            gap: 14px;

            padding: 14px 22px 22px;
        }


        .pms-profit-box {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 15px;

            padding: 18px;

            border-radius: 13px;

            border: 1px solid;
        }


        .pms-profit-box.gross {

            border-color: #d4f1df;

            background: #f0fbf4;
        }


        .pms-profit-box.net {

            border-color: #e4dafa;

            background: #f7f3fd;
        }


        .pms-profit-box span {

            display: block;

            font-size: 12px;

            font-weight: 800;
        }


        .gross span {
            color: #159957;
        }

        .net span {
            color: #805ad5;
        }


        .pms-profit-box small {

            display: block;

            margin-top: 3px;

            color: #8795a1;

            font-size: 10px;
        }


        .pms-profit-box strong {

            color: #159957;

            font-size: 21px;

            font-weight: 800;

            white-space: nowrap;
        }


        .pms-profit-box.net strong {
            color: #805ad5;
        }


        .pms-profit-box strong.negative {

            color: #e05252 !important;
        }


        /* =========================================================
           TABLE
        ========================================================== */

        .pms-table-wrapper {

            overflow-x: auto;
        }


        .pms-table {

            width: 100%;

            min-width: 650px;

            border-collapse: collapse;
        }


        .pms-table th {

            padding: 13px 22px;

            border-bottom: 1px solid #edf1f3;

            background: #f8fafb;

            color: #84919a;

            font-size: 10px;

            font-weight: 800;

            text-align: left;

            text-transform: uppercase;

            letter-spacing: .055em;
        }


        .pms-table td {

            padding: 15px 22px;

            border-bottom: 1px solid #f0f3f5;

            color: #65747d;

            font-size: 12px;
        }


        .pms-table tbody tr {

            transition: background .18s ease;
        }


        .pms-table tbody tr:hover {

            background: #f2fbfc;
        }


        .pms-table tbody tr:last-child td {

            border-bottom: 0;
        }


        .pms-table .center {
            text-align: center;
        }


        .pms-table .right {
            text-align: right;
        }


        .pms-table .strong {

            color: #304a5e;

            font-weight: 700;
        }


        .pms-table .paid-text {

            color: #159957;

            font-weight: 800;
        }


        .pms-count-badge {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            min-width: 28px;

            padding: 4px 8px;

            border-radius: 7px;

            background: #eef3f5;

            color: #52616b;

            font-size: 10px;

            font-weight: 800;
        }


        .pms-date-cell {

            display: flex;

            align-items: center;

            gap: 9px;

            font-weight: 700;

            color: #52616b;
        }


        .pms-date-dot {

            width: 7px;
            height: 7px;

            border-radius: 50%;

            background: var(--pms-primary);

            box-shadow:
                0 0 0 4px #e6f8fa;
        }


        .description-cell {

            max-width: 280px;

            overflow: hidden;

            text-overflow: ellipsis;

            white-space: nowrap;
        }


        .empty-row {

            padding: 45px 20px !important;

            color: #9aa7b0 !important;

            text-align: center !important;
        }


        /* =========================================================
           TWO COLUMNS
        ========================================================== */

        .pms-two-column {

            display: grid;

            grid-template-columns:
                minmax(0, 1.1fr)
                minmax(0, .9fr);

            gap: 22px;
        }


        .pms-two-column .pms-panel {

            margin-top: 22px;
        }


        /* =========================================================
           BALANCE
        ========================================================== */

        .pms-balance-list {

            display: flex;

            flex-direction: column;

            gap: 10px;

            padding: 18px 22px 22px;
        }


        .pms-balance-item {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 15px;

            padding: 14px;

            border-radius: 12px;
        }


        .pms-balance-item.supplier {
            background: #f1f3ff;
        }

        .pms-balance-item.customer {
            background: #fff8e6;
        }

        .pms-balance-item.payment {
            background: #edfafa;
        }

        .pms-balance-item.collected {
            background: #eefaf4;
        }


        .pms-balance-item span {

            display: block;

            font-size: 11px;

            font-weight: 800;
        }


        .supplier span {
            color: #5367c9;
        }

        .customer span {
            color: #c28a20;
        }

        .payment span {
            color: #008796;
        }

        .collected span {
            color: #159957;
        }


        .pms-balance-item small {

            display: block;

            margin-top: 3px;

            color: #8795a1;

            font-size: 9px;
        }


        .pms-balance-item strong {

            color: #304a5e;

            font-size: 13px;

            font-weight: 800;

            white-space: nowrap;
        }


        /* =========================================================
           ANIMATION
        ========================================================== */

        @keyframes pmsFadeIn {

            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }

        }


        @keyframes pmsPing {

            0%,
            100% {
                transform: scale(1);
                opacity: .25;
            }

            50% {
                transform: scale(1.8);
                opacity: 0;
            }

        }


        /* =========================================================
           RESPONSIVE
        ========================================================== */

        @media (max-width: 1200px) {

            .pms-filter-grid {

                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }


            .pms-stat-grid {

                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }

        }


        @media (max-width: 900px) {

            .pms-two-column {

                grid-template-columns: 1fr;
            }


            .pms-secondary-grid {

                grid-template-columns: 1fr;
            }


            .pms-overview-grid {

                grid-template-columns: 1fr;
            }

        }


        @media (max-width: 700px) {

            .pms-report-header {

                padding: 21px;
            }


            .pms-header-content {

                align-items: flex-start;

                flex-direction: column;
            }


            .pms-header-left {

                align-items: flex-start;
            }


            .pms-title-row h1 {

                font-size: 22px;
            }


            .pms-live-status {

                align-self: flex-start;
            }


            .pms-filter-grid {

                grid-template-columns: 1fr;
            }


            .pms-period-box {

                margin-top: 0;
            }


            .pms-filter-actions {

                flex-direction: column;
            }


            .pms-btn {

                width: 100%;
            }


            .pms-profit-grid {

                grid-template-columns: 1fr;
            }


            .pms-stat-grid {

                grid-template-columns: 1fr;
            }


            .pms-panel-header {

                align-items: flex-start;

                flex-direction: column;
            }


            .pms-balance-item {

                align-items: flex-start;

                flex-direction: column;
            }

        }


        @media (prefers-reduced-motion: reduce) {

            .pms-financial-page {

                animation: none;
            }

            .pms-financial-page * {

                transition: none !important;
                animation: none !important;
            }

        }

    </style>

</x-filament-panels::page>
