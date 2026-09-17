<x-filament-panels::page>

    <div class="pms-daily-sales">

        {{-- =========================================================
            PAGE HEADER
        ========================================================== --}}
        <div class="pms-report-header">

            <div class="pms-report-title">

                <div class="pms-report-icon">
                    <x-heroicon-o-chart-bar-square />
                </div>

                <div>
                    <h1>Daily Sales Report</h1>

                    <p>
                        View cashier sales and payment summary for the selected date.
                    </p>
                </div>

            </div>

            <div class="pms-date-filter">

                <label for="sales-date">
                    Sales Date
                </label>

                <div class="pms-date-input">

                    <x-heroicon-o-calendar-days />

                    <input
                        id="sales-date"
                        type="date"
                        wire:model.live="selectedDate"
                    >

                </div>

            </div>

        </div>


        {{-- =========================================================
            SUMMARY CARDS
        ========================================================== --}}
        <div class="pms-summary-grid">

            {{-- TOTAL SALES --}}
            <div class="pms-summary-card">

                <div class="pms-card-icon">
                    <x-heroicon-o-shopping-cart />
                </div>

                <div class="pms-card-content">

                    <span>Total Sales</span>

                    <strong>
                        {{ format_currency($this->totalSales) }}
                    </strong>

                    <small>
                        Today's revenue
                    </small>

                </div>

            </div>


            {{-- TOTAL PAID --}}
            <div class="pms-summary-card">

                <div class="pms-card-icon">
                    <x-heroicon-o-banknotes />
                </div>

                <div class="pms-card-content">

                    <span>Total Paid</span>

                    <strong>
                        {{ format_currency($this->totalPaid) }}
                    </strong>

                    <small>
                        Collected amount
                    </small>

                </div>

            </div>


            {{-- TOTAL DUE --}}
            <div class="pms-summary-card">

                <div class="pms-card-icon warning">
                    <x-heroicon-o-clock />
                </div>

                <div class="pms-card-content">

                    <span>Total Due</span>

                    <strong>
                        {{ format_currency($this->totalDue) }}
                    </strong>

                    <small>
                        Outstanding amount
                    </small>

                </div>

            </div>


            {{-- INVOICES --}}
            <div class="pms-summary-card">

                <div class="pms-card-icon">
                    <x-heroicon-o-document-text />
                </div>

                <div class="pms-card-content">

                    <span>Invoices</span>

                    <strong>
                        {{ $this->totalInvoices }}
                    </strong>

                    <small>
                        Sales transactions
                    </small>

                </div>

            </div>

        </div>


        {{-- =========================================================
            PAYMENT SUMMARY
        ========================================================== --}}
        <div class="pms-section">

            <div class="pms-section-header">

                <div class="pms-section-title">

                    <div class="pms-section-icon">
                        <x-heroicon-o-credit-card />
                    </div>

                    <div>
                        <h2>Payment Summary</h2>

                        <p>
                            Collected amount by payment method.
                        </p>
                    </div>

                </div>

            </div>


            <div class="pms-payment-grid">

                {{-- CASH --}}
                <div class="pms-payment-item">

                    <div class="pms-payment-icon">
                        <x-heroicon-o-banknotes />
                    </div>

                    <div>
                        <span>Cash</span>

                        <strong>
                            {{ format_currency($this->cashSales) }}
                        </strong>
                    </div>

                </div>


                {{-- CARD --}}
                <div class="pms-payment-item">

                    <div class="pms-payment-icon">
                        <x-heroicon-o-credit-card />
                    </div>

                    <div>
                        <span>Card</span>

                        <strong>
                            {{ format_currency($this->cardSales) }}
                        </strong>
                    </div>

                </div>


                {{-- BANK --}}
                <div class="pms-payment-item">

                    <div class="pms-payment-icon">
                        <x-heroicon-o-building-library />
                    </div>

                    <div>
                        <span>Bank Transfer</span>

                        <strong>
                            {{ format_currency($this->bankSales) }}
                        </strong>
                    </div>

                </div>


                {{-- OTHER --}}
                <div class="pms-payment-item">

                    <div class="pms-payment-icon">
                        <x-heroicon-o-wallet />
                    </div>

                    <div>
                        <span>Other</span>

                        <strong>
                            {{ format_currency($this->otherSales) }}
                        </strong>
                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
            SALES TRANSACTIONS
        ========================================================== --}}
        <div class="pms-section">

            <div class="pms-section-header">

                <div class="pms-section-title">

                    <div class="pms-section-icon">
                        <x-heroicon-o-document-chart-bar />
                    </div>

                    <div>

                        <h2>Sales Transactions</h2>

                        <p>
                            {{ $this->totalInvoices }}
                            invoice(s) found for the selected date.
                        </p>

                    </div>

                </div>

                <div class="pms-invoice-count">

                    <x-heroicon-o-document-text />

                    <span>
                        {{ $this->totalInvoices }}
                    </span>

                    Invoices

                </div>

            </div>


            <div class="pms-table-wrapper">

                <table class="pms-sales-table">

                    <thead>

                        <tr>

                            <th>Invoice</th>
                            <th>Customer</th>
                            <th>Time</th>
                            <th>Payment</th>
                            <th>Total</th>
                            <th>Paid</th>
                            <th>Due</th>
                            <th>Status</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($this->sales as $sale)

                            <tr>

                                {{-- INVOICE --}}
                                <td>

                                    <div class="pms-invoice">

                                        <div class="pms-invoice-icon">
                                            <x-heroicon-o-document-text />
                                        </div>

                                        <strong>
                                            {{ $sale->invoice_number }}
                                        </strong>

                                    </div>

                                </td>


                                {{-- CUSTOMER --}}
                                <td>

                                    <div class="pms-customer">

                                        <div class="pms-customer-avatar">

                                            {{ strtoupper(
                                                substr(
                                                    $sale->customer?->name ?? 'Walk-in Customer',
                                                    0,
                                                    1
                                                )
                                            ) }}

                                        </div>

                                        <span>
                                            {{ $sale->customer?->name ?? 'Walk-in Customer' }}
                                        </span>

                                    </div>

                                </td>


                                {{-- TIME --}}
                                <td>

                                    <div class="pms-time">

                                        <x-heroicon-o-clock />

                                       {{ $sale->created_at?->timezone('Europe/London')->format('h:i A') }}

                                    </div>

                                </td>


                                {{-- PAYMENT --}}
                                <td>

                                    <span class="pms-payment-badge">

                                        {{ ucfirst(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $sale->payment_method
                                            )
                                        ) }}

                                    </span>

                                </td>


                                {{-- TOTAL --}}
                                <td>

                                    <strong class="pms-money">
                                        {{ format_currency($sale->grand_total) }}
                                    </strong>

                                </td>


                                {{-- PAID --}}
                                <td>

                                    <span class="pms-paid">
                                        {{ format_currency($sale->paid_amount) }}
                                    </span>

                                </td>


                                {{-- DUE --}}
                                <td>

                                    <span
                                        class="{{ $sale->due_amount > 0
                                            ? 'pms-due'
                                            : 'pms-zero-due' }}"
                                    >
                                        {{ format_currency($sale->due_amount) }}
                                    </span>

                                </td>


                                {{-- STATUS --}}
                                <td>

                                    @if($sale->due_amount > 0)

                                        <span class="pms-status due">

                                            <span class="pms-status-dot"></span>

                                            Due

                                        </span>

                                    @else

                                        <span class="pms-status paid">

                                            <span class="pms-status-dot"></span>

                                            Paid

                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="pms-empty"
                                >

                                    <div class="pms-empty-icon">

                                        <x-heroicon-o-document-magnifying-glass />

                                    </div>

                                    <strong>
                                        No sales found
                                    </strong>

                                    <span>
                                        No sales transactions were recorded for this date.
                                    </span>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    <style>

        /* =========================================================
           MEDICARE DAILY SALES
           Premium Cashier Theme
        ========================================================== */

        .pms-daily-sales {

            --pms-primary: #00838F;
            --pms-primary-dark: #006B73;
            --pms-primary-light: #EAF7F8;

            --pms-border: #DCEBED;
            --pms-border-light: #EDF4F5;

            --pms-text: #173B3F;
            --pms-muted: #71898D;

            width: 100%;
            max-width: none;

            padding: 4px 0 32px;

            box-sizing: border-box;

        }


        /* =========================================================
           PAGE HEADER
        ========================================================== */

        .pms-report-header {

            width: 100%;

            min-height: 76px;

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 24px;

            margin-bottom: 20px;

            padding: 16px 20px;

            background: #00838F;

            border-radius: 14px;

            box-shadow:
                0 6px 18px rgba(0, 131, 143, 0.16);

            box-sizing: border-box;

        }


        .pms-report-title {

            display: flex;

            align-items: center;

            gap: 13px;

            min-width: 0;

        }


        .pms-report-icon {

            width: 45px;
            height: 45px;

            flex: 0 0 45px;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 11px;

            background:
                rgba(255, 255, 255, 0.15);

            border:
                1px solid rgba(255, 255, 255, 0.25);

        }


        .pms-report-icon svg {

            width: 24px;
            height: 24px;

            color: white;

        }


        .pms-report-header h1 {

            margin: 0;

            color: white;

            font-size: 22px;

            line-height: 1.2;

            font-weight: 700;

            letter-spacing: -0.02em;

        }


        .pms-report-header p {

            margin: 5px 0 0;

            color:
                rgba(255, 255, 255, 0.80);

            font-size: 12px;

            line-height: 1.4;

        }


        /* =========================================================
           DATE FILTER
        ========================================================== */

        .pms-date-filter {

            display: flex;

            align-items: center;

            gap: 10px;

            flex-shrink: 0;

        }


        .pms-date-filter label {

            color: white;

            font-size: 11px;

            font-weight: 600;

            white-space: nowrap;

        }


        .pms-date-input {

            position: relative;

            display: flex;

            align-items: center;

        }


        .pms-date-input svg {

            position: absolute;

            left: 11px;

            width: 17px;
            height: 17px;

            color: #00838F;

            pointer-events: none;

        }


        .pms-date-input input {

            width: 170px;

            height: 39px;

            padding:
                0 11px 0 36px;

            border: 0;

            border-radius: 9px;

            outline: none;

            background: white;

            color: var(--pms-text);

            font-size: 12px;

            font-weight: 600;

            box-shadow:
                0 2px 7px rgba(0, 0, 0, 0.08);

        }


        .pms-date-input input:focus {

            box-shadow:
                0 0 0 3px
                rgba(255, 255, 255, 0.22);

        }


        /* =========================================================
           SUMMARY GRID
        ========================================================== */

        .pms-summary-grid {

            width: 100%;

            display: grid;

            grid-template-columns:
                repeat(4, minmax(0, 1fr));

            gap: 14px;

            margin-bottom: 18px;

        }


        .pms-summary-card {

            min-width: 0;

            display: flex;

            align-items: center;

            gap: 13px;

            padding: 16px;

            background: white;

            border:
                1px solid var(--pms-border);

            border-radius: 12px;

            box-shadow:
                0 2px 8px
                rgba(0, 131, 143, 0.035);

            transition:
                transform .15s ease,
                box-shadow .15s ease;

        }


        .pms-summary-card:hover {

            transform: translateY(-2px);

            box-shadow:
                0 7px 18px
                rgba(0, 131, 143, 0.08);

        }


        .pms-card-icon {

            width: 42px;
            height: 42px;

            flex: 0 0 42px;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 10px;

            background: var(--pms-primary-light);

            color: var(--pms-primary);

        }


        .pms-card-icon.warning {

            background: #FFF5E6;

            color: #C77A00;

        }


        .pms-card-icon svg {

            width: 21px;
            height: 21px;

        }


        .pms-card-content {

            min-width: 0;

        }


        .pms-summary-card span {

            display: block;

            color: var(--pms-muted);

            font-size: 11px;

            font-weight: 600;

            margin-bottom: 3px;

        }


        .pms-summary-card strong {

            display: block;

            color: var(--pms-text);

            font-size: 18px;

            line-height: 1.2;

            font-weight: 700;

            white-space: nowrap;

            overflow: hidden;

            text-overflow: ellipsis;

        }


        .pms-summary-card small {

            display: block;

            margin-top: 3px;

            color: #9AAEB1;

            font-size: 10px;

        }


        /* =========================================================
           SECTION
        ========================================================== */

        .pms-section {

            width: 100%;

            margin-bottom: 18px;

            overflow: hidden;

            background: white;

            border:
                1px solid var(--pms-border);

            border-radius: 13px;

            box-shadow:
                0 2px 8px
                rgba(0, 131, 143, 0.035);

        }


        .pms-section-header {

            min-height: 65px;

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 15px;

            padding: 13px 18px;

            border-bottom:
                1px solid var(--pms-border);

            box-sizing: border-box;

        }


        .pms-section-title {

            display: flex;

            align-items: center;

            gap: 11px;

        }


        .pms-section-icon {

            width: 36px;
            height: 36px;

            flex: 0 0 36px;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 9px;

            background: var(--pms-primary-light);

            color: var(--pms-primary);

        }


        .pms-section-icon svg {

            width: 18px;
            height: 18px;

        }


        .pms-section-header h2 {

            margin: 0;

            color: var(--pms-text);

            font-size: 15px;

            line-height: 1.2;

            font-weight: 700;

        }


        .pms-section-header p {

            margin: 4px 0 0;

            color: var(--pms-muted);

            font-size: 11px;

        }


        /* =========================================================
           INVOICE COUNT
        ========================================================== */

        .pms-invoice-count {

            display: inline-flex;

            align-items: center;

            gap: 6px;

            padding: 6px 9px;

            border-radius: 7px;

            background: var(--pms-primary-light);

            color: var(--pms-primary-dark);

            font-size: 10px;

            font-weight: 600;

            white-space: nowrap;

        }


        .pms-invoice-count svg {

            width: 14px;
            height: 14px;

        }


        /* =========================================================
           PAYMENT SUMMARY
        ========================================================== */

        .pms-payment-grid {

            width: 100%;

            display: grid;

            grid-template-columns:
                repeat(4, minmax(0, 1fr));

        }


        .pms-payment-item {

            min-width: 0;

            display: flex;

            align-items: center;

            gap: 11px;

            padding: 17px 18px;

            border-right:
                1px solid var(--pms-border);

        }


        .pms-payment-item:last-child {

            border-right: 0;

        }


        .pms-payment-icon {

            width: 36px;
            height: 36px;

            flex: 0 0 36px;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 9px;

            background: #F3FAFB;

            color: var(--pms-primary);

        }


        .pms-payment-icon svg {

            width: 18px;
            height: 18px;

        }


        .pms-payment-item span {

            display: block;

            color: var(--pms-muted);

            font-size: 11px;

            margin-bottom: 3px;

        }


        .pms-payment-item strong {

            display: block;

            color: var(--pms-primary-dark);

            font-size: 15px;

            font-weight: 700;

        }


        /* =========================================================
           TABLE
        ========================================================== */

        .pms-table-wrapper {

            width: 100%;

            overflow-x: auto;

            scrollbar-width: thin;

        }


        .pms-sales-table {

            width: 100%;

            min-width: 900px;

            border-collapse: collapse;

        }


        .pms-sales-table th {

            height: 42px;

            padding: 0 14px;

            background: #F6FBFB;

            color: #6E8589;

            font-size: 10px;

            font-weight: 700;

            text-align: left;

            text-transform: uppercase;

            letter-spacing: .045em;

            border-bottom:
                1px solid var(--pms-border);

            white-space: nowrap;

        }


        .pms-sales-table td {

            height: 57px;

            padding: 7px 14px;

            color: var(--pms-text);

            font-size: 12px;

            border-bottom:
                1px solid var(--pms-border-light);

            white-space: nowrap;

        }


        .pms-sales-table tbody tr {

            transition:
                background .15s ease;

        }


        .pms-sales-table tbody tr:hover {

            background: #FAFEFE;

        }


        .pms-sales-table tbody tr:last-child td {

            border-bottom: 0;

        }


        /* =========================================================
           INVOICE
        ========================================================== */

        .pms-invoice {

            display: flex;

            align-items: center;

            gap: 8px;

        }


        .pms-invoice-icon {

            width: 29px;
            height: 29px;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 7px;

            background: var(--pms-primary-light);

            color: var(--pms-primary);

        }


        .pms-invoice-icon svg {

            width: 15px;
            height: 15px;

        }


        .pms-invoice strong {

            color: var(--pms-primary-dark);

            font-size: 12px;

        }


        /* =========================================================
           CUSTOMER
        ========================================================== */

        .pms-customer {

            display: flex;

            align-items: center;

            gap: 8px;

        }


        .pms-customer-avatar {

            width: 29px;
            height: 29px;

            flex: 0 0 29px;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: #EAF7F8;

            color: var(--pms-primary-dark);

            font-size: 10px;

            font-weight: 700;

        }


        /* =========================================================
           TIME
        ========================================================== */

        .pms-time {

            display: inline-flex;

            align-items: center;

            gap: 5px;

            color: var(--pms-muted);

        }


        .pms-time svg {

            width: 14px;
            height: 14px;

        }


        /* =========================================================
           PAYMENT BADGE
        ========================================================== */

        .pms-payment-badge {

            display: inline-flex;

            align-items: center;

            padding: 5px 9px;

            border-radius: 6px;

            background: var(--pms-primary-light);

            color: var(--pms-primary-dark);

            font-size: 10px;

            font-weight: 700;

        }


        /* =========================================================
           MONEY
        ========================================================== */

        .pms-money {

            color: var(--pms-text);

            font-weight: 700;

        }


        .pms-paid {

            color: #248452;

            font-weight: 600;

        }


        .pms-due {

            color: #B76A00;

            font-weight: 600;

        }


        .pms-zero-due {

            color: #71898D;

            font-weight: 500;

        }


        /* =========================================================
           STATUS
        ========================================================== */

        .pms-status {

            display: inline-flex;

            align-items: center;

            gap: 5px;

            padding: 5px 9px;

            border-radius: 6px;

            font-size: 10px;

            font-weight: 700;

        }


        .pms-status-dot {

            width: 5px;
            height: 5px;

            border-radius: 50%;

            background: currentColor;

        }


        .pms-status.paid {

            background: #EAF8EF;

            color: #238553;

        }


        .pms-status.due {

            background: #FFF3DF;

            color: #B76A00;

        }


        /* =========================================================
           EMPTY STATE
        ========================================================== */

        .pms-empty {

            height: 210px;

            padding: 25px !important;

            text-align: center !important;

            vertical-align: middle;

        }


        .pms-empty-icon {

            width: 46px;
            height: 46px;

            margin: 0 auto 10px;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 12px;

            background: var(--pms-primary-light);

            color: var(--pms-primary);

        }


        .pms-empty-icon svg {

            width: 23px;
            height: 23px;

        }


        .pms-empty strong {

            display: block;

            color: var(--pms-text);

            font-size: 13px;

        }


        .pms-empty span {

            display: block;

            margin-top: 4px;

            color: var(--pms-muted);

            font-size: 11px;

        }


        /* =========================================================
           RESPONSIVE
        ========================================================== */

        @media (max-width: 1100px) {

            .pms-summary-grid {

                grid-template-columns:
                    repeat(2, minmax(0, 1fr));

            }

            .pms-payment-grid {

                grid-template-columns:
                    repeat(2, minmax(0, 1fr));

            }

            .pms-payment-item:nth-child(2) {

                border-right: 0;

            }

            .pms-payment-item:nth-child(-n + 2) {

                border-bottom:
                    1px solid var(--pms-border);

            }

        }


        @media (max-width: 700px) {

            .pms-daily-sales {

                padding-bottom: 20px;

            }

            .pms-report-header {

                align-items: stretch;

                flex-direction: column;

                gap: 13px;

                padding: 15px;

            }

            .pms-report-header h1 {

                font-size: 20px;

            }

            .pms-date-filter {

                justify-content: space-between;

            }

            .pms-date-input {

                flex: 1;

            }

            .pms-date-input input {

                width: 100%;

            }

            .pms-summary-grid {

                grid-template-columns: 1fr;

            }

            .pms-payment-grid {

                grid-template-columns: 1fr;

            }

            .pms-payment-item {

                border-right: 0 !important;

                border-bottom:
                    1px solid var(--pms-border);

            }

            .pms-payment-item:last-child {

                border-bottom: 0;

            }

            .pms-section-header {

                padding: 13px 14px;

            }

            .pms-invoice-count {

                display: none;

            }

        }

    </style>

</x-filament-panels::page>