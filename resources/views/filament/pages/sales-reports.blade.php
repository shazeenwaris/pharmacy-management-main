
<x-filament-panels::page>

    <div class="sales-report-page">

        {{-- =====================================================
             PAGE HEADER
        ====================================================== --}}
        <div class="sr-page-header">

            <div class="sr-header-icon">
                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1.8"
                          d="M3 13.5V19a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-5.5
                             M3 13.5l4-4 4 3 5-6 5 4
                             M7 21V17
                             M12 21v-5
                             M17 21v-8"/>
                </svg>
            </div>

            <div class="sr-header-content">
                <h1>Sales Reports</h1>
                <p>
                    Analyze sales, revenue, sold medicines and cashier performance.
                </p>
            </div>

        </div>


        {{-- =====================================================
             DATE FILTER
        ====================================================== --}}
        <div class="sr-filter-card">

            <div class="sr-section-header">

                <div class="sr-section-icon">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.8"
                              d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/>
                    </svg>
                </div>

                <div>
                    <h3>Report Period</h3>
                    <p>Select the date range for your sales report.</p>
                </div>

            </div>


            <div class="sr-filter-body">

                <div class="sr-field">

                    <label>From Date</label>

                    <input
                        type="date"
                        wire:model="fromDate"
                    >

                </div>


                <div class="sr-field">

                    <label>To Date</label>

                    <input
                        type="date"
                        wire:model="toDate"
                    >

                </div>


                <div class="sr-filter-actions">

                    <button
                        type="button"
                        wire:click="applyFilters"
                        class="sr-btn sr-btn-primary"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L14 12.414V19a1 1 0 01-.553.894l-4 2A1 1 0 018 21v-8.586L3.293 6.707A1 1 0 013 6V4z"/>
                        </svg>

                        Apply Filter
                    </button>


                    <button
                        type="button"
                        wire:click="resetFilters"
                        class="sr-btn sr-btn-secondary"
                    >
                        Reset
                    </button>

                </div>

            </div>

        </div>


        {{-- =====================================================
             KPI CARDS
        ====================================================== --}}
        <div class="sr-kpi-grid">

            {{-- Revenue --}}
            <div class="sr-kpi-card">

                <div>
                    <p class="sr-kpi-label">
                        Total Revenue
                    </p>

                    <h3>
                        Rs. {{ number_format($this->totalRevenue, 2) }}
                    </h3>

                    <span class="sr-kpi-note">
                        Sales revenue
                    </span>
                </div>

                <div class="sr-kpi-icon revenue">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.8"
                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2
                                 3 .895 3 2-1.343 2-3 2m0-8c1.11 0
                                 2.08.402 2.599 1M12 8V6m0 12v-2
                                 m0 2c-1.11 0-2.08-.402-2.599-1
                                 M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>

            </div>


            {{-- Sales --}}
            <div class="sr-kpi-card">

                <div>
                    <p class="sr-kpi-label">
                        Total Sales
                    </p>

                    <h3>
                        {{ number_format($this->totalSales) }}
                    </h3>

                    <span class="sr-kpi-note">
                        Completed bills
                    </span>
                </div>

                <div class="sr-kpi-icon sales">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.8"
                              d="M9 14h6m-6 4h6M7 3h10a2 2 0 012 2v14
                                 a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                    </svg>
                </div>

            </div>


            {{-- Quantity --}}
            <div class="sr-kpi-card">

                <div>
                    <p class="sr-kpi-label">
                        Sold Quantity
                    </p>

                    <h3>
                        {{ number_format($this->soldQuantity, 2) }}
                    </h3>

                    <span class="sr-kpi-note">
                        Units sold
                    </span>
                </div>

                <div class="sr-kpi-icon quantity">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.8"
                              d="M20 7l-8-4-8 4m16 0v10l-8 4-8-4V7
                                 m16 0l-8 4m0 0L4 7m8 4v10"/>
                    </svg>
                </div>

            </div>


            {{-- Average --}}
            <div class="sr-kpi-card">

                <div>
                    <p class="sr-kpi-label">
                        Average Sale
                    </p>

                    <h3>
                        Rs. {{ number_format($this->averageSale, 2) }}
                    </h3>

                    <span class="sr-kpi-note">
                        Average bill value
                    </span>
                </div>

                <div class="sr-kpi-icon average">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.8"
                              d="M3 3v18h18M7 16l4-5 3 3 5-7"/>
                    </svg>
                </div>

            </div>

        </div>


        {{-- =====================================================
             DAILY SALES
        ====================================================== --}}
        <div class="sr-table-card">

            <div class="sr-table-header">

                <div>
                    <h3>Daily Sales</h3>
                    <p>Sales performance by date.</p>
                </div>

                <span class="sr-badge">
                    Daily Overview
                </span>

            </div>


            <div class="sr-table-wrapper">

                <table>

                    <thead>
                        <tr>
                            <th>Date</th>
                            <th class="text-center">Bills</th>
                            <th class="text-right">Revenue</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($this->dailySales as $sale)

                            <tr>

                                <td class="font-semibold">
                                    {{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y') }}
                                </td>

                                <td class="text-center">
                                    <span class="sr-number-badge">
                                        {{ number_format($sale->total_bills) }}
                                    </span>
                                </td>

                                <td class="text-right font-semibold">
                                    Rs. {{ number_format($sale->total_revenue, 2) }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="3" class="sr-empty">
                                    No sales found for the selected period.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =====================================================
             SALES BY MEDICINE
        ====================================================== --}}
        <div class="sr-table-card">

            <div class="sr-table-header">

                <div>
                    <h3>Sales by Medicine</h3>
                    <p>Best-selling medicines during the selected period.</p>
                </div>

                <span class="sr-badge">
                    Medicines
                </span>

            </div>


            <div class="sr-table-wrapper">

                <table>

                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th class="text-right">Sold Quantity</th>
                            <th class="text-right">Revenue</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($this->salesByMedicine as $medicine)

                            <tr>

                                <td>
                                    <div class="sr-name-cell">

                                        <div class="sr-small-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke="currentColor">
                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      stroke-width="1.8"
                                                      d="M20 7l-8-4-8 4m16 0v10l-8 4-8-4V7
                                                         m16 0l-8 4m0 0L4 7m8 4v10"/>
                                            </svg>
                                        </div>

                                        <span class="font-semibold">
                                            {{ $medicine->name }}
                                        </span>

                                    </div>
                                </td>

                                <td class="text-right">
                                    {{ number_format($medicine->sold_quantity, 2) }}
                                </td>

                                <td class="text-right font-semibold">
                                    Rs. {{ number_format($medicine->revenue, 2) }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="3" class="sr-empty">
                                    No medicine sales found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =====================================================
             SALES BY CATEGORY
        ====================================================== --}}
        <div class="sr-table-card">

            <div class="sr-table-header">

                <div>
                    <h3>Sales by Category</h3>
                    <p>Revenue and quantity grouped by medicine category.</p>
                </div>

                <span class="sr-badge">
                    Categories
                </span>

            </div>


            <div class="sr-table-wrapper">

                <table>

                    <thead>
                        <tr>
                            <th>Category</th>
                            <th class="text-right">Sold Quantity</th>
                            <th class="text-right">Revenue</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($this->salesByCategory as $category)

                            <tr>

                                <td>
                                    <span class="sr-category">
                                        {{ $category->name ?? 'Uncategorized' }}
                                    </span>
                                </td>

                                <td class="text-right">
                                    {{ number_format($category->sold_quantity, 2) }}
                                </td>

                                <td class="text-right font-semibold">
                                    Rs. {{ number_format($category->revenue, 2) }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="3" class="sr-empty">
                                    No category sales found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =====================================================
             SALES BY CASHIER
        ====================================================== --}}
        <div class="sr-table-card">

            <div class="sr-table-header">

                <div>
                    <h3>Sales by Cashier</h3>
                    <p>Compare sales performance across cashiers.</p>
                </div>

                <span class="sr-badge">
                    Cashiers
                </span>

            </div>


            <div class="sr-table-wrapper">

                <table>

                    <thead>
                        <tr>
                            <th>Cashier</th>
                            <th class="text-center">Bills</th>
                            <th class="text-right">Revenue</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($this->salesByCashier as $cashier)

                            <tr>

                                <td>

                                    <div class="sr-name-cell">

                                        <div class="sr-avatar">
                                            {{ strtoupper(substr($cashier->name ?? 'U', 0, 1)) }}
                                        </div>

                                        <span class="font-semibold">
                                            {{ $cashier->name ?? 'Unknown' }}
                                        </span>

                                    </div>

                                </td>

                                <td class="text-center">

                                    <span class="sr-number-badge">
                                        {{ number_format($cashier->total_bills) }}
                                    </span>

                                </td>

                                <td class="text-right font-semibold">
                                    Rs. {{ number_format($cashier->revenue, 2) }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="3" class="sr-empty">
                                    No cashier sales found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- =====================================================
         SALES REPORT THEME
    ====================================================== --}}
    <style>
/* =========================================================
   SALES REPORT — COMPLETE ALIGNED THEME
========================================================= */

.sales-report-page {
    --sr-primary: #0097a7;
    --sr-primary-dark: #007f8d;
    --sr-primary-light: #e6f8fa;
    --sr-navy: #092c4c;
    --sr-text: #243447;
    --sr-muted: #718096;
    --sr-border: #e4e9ed;
    --sr-bg: #f7fafb;

    width: 100%;
    max-width: 100%;
    margin: 0 auto;
    padding: 0;

    color: var(--sr-text);

    display: flex;
    flex-direction: column;
    gap: 20px;

    box-sizing: border-box;
}

.sales-report-page *,
.sales-report-page *::before,
.sales-report-page *::after {
    box-sizing: border-box;
}


/* =========================================================
   PAGE HEADER
========================================================= */

.sr-page-header {
    position: relative;

    width: 100%;
    min-height: 105px;

    display: flex;
    align-items: center;

    gap: 15px;

    padding: 22px 26px;

    background: #ffffff;

    border: 1px solid var(--sr-border);
    border-radius: 18px;

    box-shadow:
        0 6px 24px rgba(0, 60, 70, 0.055);

    overflow: hidden;
}

.sr-page-header::before {
    content: "";

    position: absolute;

    left: 0;
    top: 0;
    bottom: 0;

    width: 4px;

    background: var(--sr-primary);
}

.sr-header-icon {
    width: 46px;
    height: 46px;

    flex: 0 0 46px;

    display: flex;
    align-items: center;
    justify-content: center;

    color: #ffffff;
    background: var(--sr-primary);

    border-radius: 11px;

    box-shadow:
        0 6px 16px rgba(0, 151, 167, 0.20);
}

.sr-header-icon svg {
    width: 24px;
    height: 24px;
}

.sr-header-content {
    min-width: 0;
}

.sr-header-content h1 {
    margin: 0;

    color: var(--sr-navy);

    font-size: 25px;
    line-height: 1.25;
    font-weight: 750;

    letter-spacing: -0.4px;
}

.sr-header-content p {
    margin: 5px 0 0;

    color: var(--sr-muted);

    font-size: 13px;
    line-height: 1.5;
}


/* =========================================================
   FILTER CARD
========================================================= */

.sr-filter-card {
    width: 100%;

    overflow: hidden;

    background: #ffffff;

    border: 1px solid var(--sr-border);
    border-radius: 18px;

    box-shadow:
        0 5px 20px rgba(0, 60, 70, 0.045);
}

.sr-section-header {
    min-height: 76px;

    display: flex;
    align-items: center;

    gap: 12px;

    padding: 17px 22px;

    background: #fbfcfd;

    border-bottom: 1px solid #edf1f3;
}

.sr-section-icon {
    width: 38px;
    height: 38px;

    flex: 0 0 38px;

    display: flex;
    align-items: center;
    justify-content: center;

    color: var(--sr-primary);
    background: var(--sr-primary-light);

    border-radius: 10px;
}

.sr-section-icon svg {
    width: 20px;
    height: 20px;
}

.sr-section-header h3 {
    margin: 0;

    color: var(--sr-navy);

    font-size: 14px;
    line-height: 1.3;
    font-weight: 700;
}

.sr-section-header p {
    margin: 3px 0 0;

    color: var(--sr-muted);

    font-size: 12px;
    line-height: 1.4;
}


/* =========================================================
   FILTER BODY
========================================================= */

.sr-filter-body {
    width: 100%;

    display: grid;

    grid-template-columns:
        minmax(0, 1fr)
        minmax(0, 1fr)
        auto;

    align-items: end;

    gap: 18px;

    padding: 22px;
}

.sr-field {
    min-width: 0;
}

.sr-field label {
    display: block;

    margin: 0 0 7px;

    color: var(--sr-text);

    font-size: 12px;
    line-height: 1.3;
    font-weight: 650;
}

.sr-field input {
    display: block;

    width: 100%;
    height: 43px;

    margin: 0;

    padding: 0 13px;

    color: var(--sr-text);
    background: #ffffff;

    border: 1px solid #d8e0e4;
    border-radius: 10px;

    outline: none;

    font-family: inherit;
    font-size: 13px;

    transition:
        border-color 0.2s ease,
        box-shadow 0.2s ease,
        background 0.2s ease;
}

.sr-field input:hover {
    border-color: #c6d0d5;
}

.sr-field input:focus {
    border-color: var(--sr-primary);

    background: #ffffff;

    box-shadow:
        0 0 0 3px rgba(0, 151, 167, 0.10);
}


/* =========================================================
   FILTER BUTTONS
========================================================= */

.sr-filter-actions {
    height: 43px;

    display: flex;
    align-items: stretch;

    gap: 10px;

    white-space: nowrap;
}

.sr-btn {
    height: 43px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    gap: 7px;

    padding: 0 18px;

    border-radius: 10px;

    font-family: inherit;
    font-size: 12px;
    font-weight: 650;

    line-height: 1;

    cursor: pointer;

    transition:
        background 0.2s ease,
        border-color 0.2s ease,
        box-shadow 0.2s ease,
        transform 0.2s ease;
}

.sr-btn svg {
    width: 17px;
    height: 17px;

    flex: 0 0 17px;
}

.sr-btn-primary {
    color: #ffffff;

    background: var(--sr-primary);

    border: 1px solid var(--sr-primary);

    box-shadow:
        0 5px 13px rgba(0, 151, 167, 0.16);
}

.sr-btn-primary:hover {
    color: #ffffff;

    background: var(--sr-primary-dark);

    border-color: var(--sr-primary-dark);

    transform: translateY(-1px);

    box-shadow:
        0 7px 16px rgba(0, 151, 167, 0.20);
}

.sr-btn-secondary {
    color: #52606d;

    background: #ffffff;

    border: 1px solid #d8e0e4;
}

.sr-btn-secondary:hover {
    color: var(--sr-text);

    background: #f7fafb;

    border-color: #bdc8ce;
}


/* =========================================================
   KPI GRID
========================================================= */

.sr-kpi-grid {
    width: 100%;

    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 18px;
}

.sr-kpi-card {
    position: relative;

    width: 100%;
    min-width: 0;
    min-height: 142px;

    display: flex;
    align-items: flex-start;
    justify-content: space-between;

    gap: 15px;

    padding: 21px;

    background: #ffffff;

    border: 1px solid var(--sr-border);
    border-radius: 17px;

    box-shadow:
        0 5px 20px rgba(0, 60, 70, 0.045);

    overflow: hidden;

    transition:
        transform 0.2s ease,
        box-shadow 0.2s ease;
}

.sr-kpi-card::before {
    content: "";

    position: absolute;

    left: 0;
    top: 0;
    bottom: 0;

    width: 3px;

    background: var(--sr-primary);
}

.sr-kpi-card:hover {
    transform: translateY(-2px);

    box-shadow:
        0 11px 27px rgba(0, 60, 70, 0.085);
}

.sr-kpi-card > div:first-child {
    min-width: 0;
}

.sr-kpi-label {
    margin: 0;

    color: var(--sr-muted);

    font-size: 12px;
    line-height: 1.4;
    font-weight: 600;
}

.sr-kpi-card h3 {
    margin: 7px 0 0;

    color: var(--sr-navy);

    font-size: 21px;
    line-height: 1.25;
    font-weight: 750;

    letter-spacing: -0.3px;

    white-space: nowrap;
}

.sr-kpi-note {
    display: block;

    margin-top: 8px;

    color: var(--sr-primary);

    font-size: 11px;
    line-height: 1.4;
    font-weight: 650;
}

.sr-kpi-icon {
    width: 44px;
    height: 44px;

    flex: 0 0 44px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 11px;
}

.sr-kpi-icon svg {
    width: 22px;
    height: 22px;
}

.sr-kpi-icon.revenue {
    color: var(--sr-primary);
    background: var(--sr-primary-light);
}

.sr-kpi-icon.sales {
    color: #1775aa;
    background: #eaf5fb;
}

.sr-kpi-icon.quantity {
    color: #7356a8;
    background: #f1edfa;
}

.sr-kpi-icon.average {
    color: #c27a22;
    background: #fff5e7;
}


/* =========================================================
   TABLE CARDS
========================================================= */

.sr-table-card {
    width: 100%;

    overflow: hidden;

    background: #ffffff;

    border: 1px solid var(--sr-border);
    border-radius: 18px;

    box-shadow:
        0 5px 20px rgba(0, 60, 70, 0.045);
}

.sr-table-header {
    width: 100%;
    min-height: 70px;

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 15px;

    padding: 17px 22px;

    border-bottom: 1px solid #edf1f3;
}

.sr-table-header > div:first-child {
    min-width: 0;
}

.sr-table-header h3 {
    margin: 0;

    color: var(--sr-navy);

    font-size: 15px;
    line-height: 1.35;
    font-weight: 720;
}

.sr-table-header p {
    margin: 4px 0 0;

    color: var(--sr-muted);

    font-size: 12px;
    line-height: 1.4;
}

.sr-badge {
    flex: 0 0 auto;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    min-height: 26px;

    padding: 5px 11px;

    color: var(--sr-primary-dark);

    background: var(--sr-primary-light);

    border-radius: 999px;

    font-size: 10px;
    line-height: 1;
    font-weight: 700;

    white-space: nowrap;
}


/* =========================================================
   TABLE
========================================================= */

.sr-table-wrapper {
    width: 100%;

    overflow-x: auto;

    -webkit-overflow-scrolling: touch;
}

.sr-table-wrapper table {
    width: 100%;
    min-width: 600px;

    margin: 0;

    border-collapse: collapse;
    border-spacing: 0;

    table-layout: fixed;
}

.sr-table-wrapper thead {
    background: #f8fafb;
}

.sr-table-wrapper th {
    height: 42px;

    padding: 0 22px;

    color: #7b8794;

    background: #f8fafb;

    border-bottom: 1px solid #edf1f3;

    font-size: 10px;
    line-height: 1.2;
    font-weight: 750;

    letter-spacing: 0.7px;
    text-transform: uppercase;

    vertical-align: middle;
}

.sr-table-wrapper td {
    height: 58px;

    padding: 10px 22px;

    color: #52606d;

    background: #ffffff;

    border-bottom: 1px solid #f0f3f5;

    font-size: 13px;
    line-height: 1.4;

    vertical-align: middle;
}

.sr-table-wrapper tbody tr {
    transition:
        background 0.15s ease;
}

.sr-table-wrapper tbody tr:hover td {
    background: #f8fcfc;
}

.sr-table-wrapper tbody tr:last-child td {
    border-bottom: 0;
}


/* =========================================================
   COLUMN ALIGNMENT
========================================================= */

.sr-table-wrapper th:first-child,
.sr-table-wrapper td:first-child {
    text-align: left;
}

.sr-table-wrapper th:nth-child(2),
.sr-table-wrapper td:nth-child(2) {
    text-align: center;
}

.sr-table-wrapper th:nth-child(3),
.sr-table-wrapper td:nth-child(3) {
    text-align: right;
}


/* =========================================================
   TEXT HELPERS
========================================================= */

.text-center {
    text-align: center !important;
}

.text-right {
    text-align: right !important;
}

.font-semibold {
    color: var(--sr-text) !important;

    font-weight: 650 !important;
}


/* =========================================================
   NUMBER BADGE
========================================================= */

.sr-number-badge {
    min-width: 42px;
    min-height: 25px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    padding: 4px 9px;

    color: var(--sr-primary-dark);

    background: var(--sr-primary-light);

    border-radius: 7px;

    font-size: 11px;
    line-height: 1;
    font-weight: 700;
}


/* =========================================================
   NAME CELL
========================================================= */

.sr-name-cell {
    min-width: 0;

    display: flex;
    align-items: center;

    gap: 10px;
}

.sr-name-cell > span {
    min-width: 0;

    overflow: hidden;

    text-overflow: ellipsis;
    white-space: nowrap;
}


/* =========================================================
   MEDICINE ICON
========================================================= */

.sr-small-icon {
    width: 34px;
    height: 34px;

    flex: 0 0 34px;

    display: flex;
    align-items: center;
    justify-content: center;

    color: var(--sr-primary);

    background: var(--sr-primary-light);

    border-radius: 9px;
}

.sr-small-icon svg {
    width: 17px;
    height: 17px;
}


/* =========================================================
   CASHIER AVATAR
========================================================= */

.sr-avatar {
    width: 34px;
    height: 34px;

    flex: 0 0 34px;

    display: flex;
    align-items: center;
    justify-content: center;

    color: #ffffff;

    background: var(--sr-primary);

    border-radius: 50%;

    font-size: 12px;
    line-height: 1;
    font-weight: 750;
}


/* =========================================================
   CATEGORY
========================================================= */

.sr-category {
    display: inline-flex;
    align-items: center;

    min-height: 25px;

    padding: 5px 10px;

    color: var(--sr-primary-dark);

    background: var(--sr-primary-light);

    border-radius: 7px;

    font-size: 11px;
    line-height: 1;
    font-weight: 700;
}


/* =========================================================
   EMPTY STATE
========================================================= */

.sr-empty {
    height: 120px !important;

    padding: 30px 20px !important;

    color: #8a97a3 !important;

    text-align: center !important;

    font-size: 13px !important;
    line-height: 1.5 !important;
}


/* =========================================================
   TABLE NUMBER / MONEY ALIGNMENT
========================================================= */

.sr-table-wrapper td.text-right {
    white-space: nowrap;
}

.sr-table-wrapper td.text-center {
    white-space: nowrap;
}


/* =========================================================
   TABLET — 1100px
========================================================= */

@media (max-width: 1100px) {

    .sr-kpi-grid {
        grid-template-columns:
            repeat(2, minmax(0, 1fr));
    }

    .sr-filter-body {
        grid-template-columns:
            repeat(2, minmax(0, 1fr));
    }

    .sr-filter-actions {
        grid-column: 1 / -1;

        justify-content: flex-start;
    }

}


/* =========================================================
   TABLET — 800px
========================================================= */

@media (max-width: 800px) {

    .sales-report-page {
        gap: 16px;
    }

    .sr-page-header {
        min-height: 95px;

        padding: 19px 20px;
    }

    .sr-filter-body {
        gap: 15px;

        padding: 18px;
    }

    .sr-table-header {
        padding: 16px 18px;
    }

    .sr-table-wrapper th,
    .sr-table-wrapper td {
        padding-left: 18px;
        padding-right: 18px;
    }

}


/* =========================================================
   MOBILE — 700px
========================================================= */

@media (max-width: 700px) {

    .sales-report-page {
        gap: 14px;
    }


    /* Header */

    .sr-page-header {
        min-height: auto;

        align-items: flex-start;

        padding: 18px;
    }

    .sr-header-icon {
        width: 42px;
        height: 42px;

        flex-basis: 42px;
    }

    .sr-header-icon svg {
        width: 22px;
        height: 22px;
    }

    .sr-header-content h1 {
        font-size: 21px;
    }

    .sr-header-content p {
        margin-top: 4px;

        font-size: 11px;
    }


    /* Filter */

    .sr-section-header {
        padding: 15px 17px;
    }

    .sr-filter-body {
        grid-template-columns: 1fr;

        padding: 17px;

        gap: 14px;
    }

    .sr-filter-actions {
        width: 100%;

        grid-column: auto;

        display: grid;
        grid-template-columns: 1fr 1fr;

        gap: 9px;
    }

    .sr-filter-actions .sr-btn {
        width: 100%;

        padding-left: 12px;
        padding-right: 12px;
    }


    /* KPI */

    .sr-kpi-grid {
        grid-template-columns: 1fr;

        gap: 14px;
    }

    .sr-kpi-card {
        min-height: 125px;

        padding: 18px;
    }

    .sr-kpi-card h3 {
        font-size: 20px;
    }


    /* Tables */

    .sr-table-header {
        align-items: flex-start;

        flex-direction: column;

        padding: 16px 17px;
    }

    .sr-table-header h3 {
        font-size: 14px;
    }

    .sr-table-header p {
        font-size: 11px;
    }

    .sr-badge {
        display: none;
    }

    .sr-table-wrapper th {
        height: 40px;

        padding-left: 15px;
        padding-right: 15px;
    }

    .sr-table-wrapper td {
        height: 55px;

        padding-left: 15px;
        padding-right: 15px;

        font-size: 12px;
    }

}


/* =========================================================
   SMALL MOBILE — 480px
========================================================= */

@media (max-width: 480px) {

    .sr-page-header {
        gap: 11px;

        padding: 16px;
    }

    .sr-header-icon {
        width: 40px;
        height: 40px;

        flex-basis: 40px;
    }

    .sr-header-content h1 {
        font-size: 19px;
    }

    .sr-header-content p {
        font-size: 10px;
    }

    .sr-section-header {
        gap: 9px;
    }

    .sr-section-icon {
        width: 35px;
        height: 35px;

        flex-basis: 35px;
    }

    .sr-section-icon svg {
        width: 18px;
        height: 18px;
    }

    .sr-filter-actions {
        grid-template-columns: 1fr;
    }

    .sr-kpi-card {
        min-height: 118px;

        padding: 16px;
    }

    .sr-kpi-icon {
        width: 40px;
        height: 40px;

        flex-basis: 40px;
    }

    .sr-kpi-card h3 {
        font-size: 18px;
    }

}


/* =========================================================
   FILAMENT DARK MODE — KEEP REPORT CLEAN
========================================================= */

.dark .sales-report-page {
    --sr-text: #243447;
    --sr-muted: #718096;
}

.dark .sales-report-page .sr-page-header,
.dark .sales-report-page .sr-filter-card,
.dark .sales-report-page .sr-kpi-card,
.dark .sales-report-page .sr-table-card {
    background: #ffffff;
}

</style>

</x-filament-panels::page>

