<x-filament-panels::page>

    <style>
        /* =========================================================
           MEDICARE CASHIER PAYMENTS
           Scoped styles — won't affect other Filament pages
        ========================================================= */

        .pms-payments-page {
            --pay-primary: #00838f;
            --pay-primary-dark: #006b73;
            --pay-primary-soft: #e8f8f8;
            --pay-border: #d8eeee;
            --pay-text: #173b40;
            --pay-muted: #6b7f83;
            --pay-bg: #f4fafa;

            width: 100%;
        }

        .pms-payments-page * {
            box-sizing: border-box;
        }

        /* PAGE HEADER */
        .pms-payment-header {
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;

            padding: 24px 28px;

            background: #ffffff;
            border: 1px solid var(--pay-border);
            border-radius: 22px;

            box-shadow:
                0 8px 30px rgba(0, 96, 100, 0.06);
        }

        .pms-payment-header::after {
            content: "";
            position: absolute;
            width: 210px;
            height: 210px;
            right: -90px;
            top: -110px;

            background: #e9f8f8;
            border-radius: 999px;
            pointer-events: none;
        }

        .pms-payment-title-wrap {
            position: relative;
            z-index: 2;

            display: flex;
            align-items: center;
            gap: 16px;
        }

        .pms-payment-icon {
            width: 54px;
            height: 54px;

            display: flex;
            align-items: center;
            justify-content: center;

            flex-shrink: 0;

            color: #ffffff;
            background: var(--pay-primary);

            border-radius: 16px;

            box-shadow:
                0 8px 18px rgba(0, 131, 143, 0.20);
        }

        .pms-payment-icon svg {
            width: 27px;
            height: 27px;
        }

        .pms-payment-title {
            margin: 0;

            color: #102f34;

            font-size: 22px;
            line-height: 1.2;
            font-weight: 750;
            letter-spacing: -0.02em;
        }

        .pms-payment-subtitle {
            margin-top: 5px;

            color: var(--pay-muted);

            font-size: 13px;
            line-height: 1.5;
        }

        /* HEADER STATS */
        .pms-payment-stats {
            position: relative;
            z-index: 2;

            display: flex;
            align-items: center;
            gap: 12px;
        }

        .pms-payment-stat {
            min-width: 150px;

            padding: 12px 16px;

            background: #f8fcfc;
            border: 1px solid var(--pay-border);
            border-radius: 14px;
        }

        .pms-payment-stat-label {
            color: #72868a;

            font-size: 11px;
            font-weight: 600;
        }

        .pms-payment-stat-value {
            margin-top: 4px;

            color: var(--pay-primary);

            font-size: 17px;
            line-height: 1.2;
            font-weight: 750;
        }

        /* SEARCH CARD */
        .pms-payment-search-card {
            padding: 20px 22px;

            background: #ffffff;
            border: 1px solid var(--pay-border);
            border-radius: 20px;

            box-shadow:
                0 7px 25px rgba(0, 96, 100, 0.045);
        }

        .pms-payment-search-label {
            display: block;
            margin-bottom: 8px;

            color: #29464a;

            font-size: 12px;
            font-weight: 700;
        }

        .pms-payment-search {
            position: relative;
            max-width: 650px;
        }

        .pms-payment-search svg {
            position: absolute;
            left: 15px;
            top: 50%;

            width: 18px;
            height: 18px;

            color: #8ba0a4;

            transform: translateY(-50%);
            pointer-events: none;
        }

        .pms-payment-search input {
            width: 100%;
            height: 46px;

            padding: 0 16px 0 43px;

            color: #173b40;
            background: #f8fbfb;

            border: 1px solid #d9e7e8;
            border-radius: 13px;

            outline: none;

            font-size: 13px;

            transition:
                border-color .2s ease,
                box-shadow .2s ease,
                background .2s ease;
        }

        .pms-payment-search input::placeholder {
            color: #9aaeb1;
        }

        .pms-payment-search input:focus {
            background: #ffffff;
            border-color: var(--pay-primary);

            box-shadow:
                0 0 0 4px rgba(0, 131, 143, 0.08);
        }

        /* MAIN TABLE CARD */
        .pms-payment-table-card {
            overflow: hidden;

            background: #ffffff;
            border: 1px solid var(--pay-border);
            border-radius: 22px;

            box-shadow:
                0 8px 30px rgba(0, 96, 100, 0.055);
        }

        .pms-payment-table-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;

            padding: 20px 24px;

            border-bottom: 1px solid #edf2f2;
        }

        .pms-payment-section-title {
            margin: 0;

            color: #16363b;

            font-size: 16px;
            font-weight: 750;
        }

        .pms-payment-section-subtitle {
            margin-top: 4px;

            color: #809195;

            font-size: 12px;
        }

        .pms-payment-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            min-width: 32px;
            height: 28px;
            padding: 0 10px;

            color: var(--pay-primary);
            background: var(--pay-primary-soft);

            border-radius: 999px;

            font-size: 12px;
            font-weight: 750;
        }

        /* TABLE */
        .pms-payment-table-wrap {
            overflow-x: auto;
        }

        .pms-payment-table {
            width: 100%;
            min-width: 900px;

            border-collapse: collapse;
        }

        .pms-payment-table thead {
            background: #f7fbfb;
        }

        .pms-payment-table th {
            padding: 13px 20px;

            color: #708387;

            border-bottom: 1px solid #eaf0f0;

            font-size: 10px;
            font-weight: 750;
            text-align: left;
            text-transform: uppercase;
            letter-spacing: .07em;
            white-space: nowrap;
        }

        .pms-payment-table th.amount,
        .pms-payment-table td.amount {
            text-align: right;
        }

        .pms-payment-table td {
            padding: 17px 20px;

            color: #425b5f;

            border-bottom: 1px solid #edf2f2;

            font-size: 13px;

            vertical-align: middle;
        }

        .pms-payment-table tbody tr {
            transition: background .18s ease;
        }

        .pms-payment-table tbody tr:hover {
            background: #f9fcfc;
        }

        .pms-payment-table tbody tr:last-child td {
            border-bottom: 0;
        }

        /* INVOICE */
        .pms-invoice-number {
            color: #173b40;

            font-size: 13px;
            font-weight: 750;
        }

        .pms-invoice-date {
            margin-top: 4px;

            color: #94a4a7;

            font-size: 11px;
        }

        /* CUSTOMER */
        .pms-customer-name {
            color: #314d51;

            font-weight: 650;
        }

        .pms-customer-phone {
            margin-top: 3px;

            color: #91a1a4;

            font-size: 11px;
        }

        .pms-walkin {
            display: inline-flex;
            align-items: center;

            padding: 5px 9px;

            color: #687b7f;
            background: #f1f4f4;

            border-radius: 999px;

            font-size: 11px;
            font-weight: 650;
        }

        /* MONEY */
        .pms-money {
            color: #334f53;
            font-weight: 650;
        }

        .pms-paid {
            color: #16805b;
            font-weight: 700;
        }

        .pms-due {
            display: inline-flex;
            align-items: center;

            padding: 6px 10px;

            color: #a86b00;
            background: #fff7e7;

            border: 1px solid #f7e5bd;
            border-radius: 999px;

            font-size: 12px;
            font-weight: 750;
        }

        /* RECEIVE BUTTON */
        .pms-receive-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;

            height: 37px;
            padding: 0 14px;

            color: #ffffff;
            background: var(--pay-primary);

            border: 0;
            border-radius: 10px;

            cursor: pointer;

            font-size: 12px;
            font-weight: 700;

            box-shadow:
                0 4px 10px rgba(0, 131, 143, 0.14);

            transition:
                transform .15s ease,
                background .15s ease,
                box-shadow .15s ease;
        }

        .pms-receive-btn:hover {
            background: var(--pay-primary-dark);

            transform: translateY(-1px);

            box-shadow:
                0 7px 15px rgba(0, 131, 143, 0.18);
        }

        .pms-receive-btn svg {
            width: 15px;
            height: 15px;
        }

        /* EMPTY */
        .pms-payment-empty {
            padding: 65px 25px;

            text-align: center;
        }

        .pms-payment-empty-icon {
            width: 58px;
            height: 58px;

            display: flex;
            align-items: center;
            justify-content: center;

            margin: 0 auto;

            color: var(--pay-primary);
            background: var(--pay-primary-soft);

            border-radius: 17px;
        }

        .pms-payment-empty-icon svg {
            width: 28px;
            height: 28px;
        }

        .pms-payment-empty-title {
            margin-top: 15px;

            color: #18383d;

            font-size: 15px;
            font-weight: 750;
        }

        .pms-payment-empty-text {
            margin-top: 5px;

            color: #829397;

            font-size: 12px;
        }

        /* RESPONSIVE */
        @media (max-width: 900px) {

            .pms-payment-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .pms-payment-stats {
                width: 100%;
            }

            .pms-payment-stat {
                flex: 1;
            }
        }

        @media (max-width: 600px) {

            .pms-payment-header {
                padding: 20px;
            }

            .pms-payment-title {
                font-size: 19px;
            }

            .pms-payment-stats {
                width: 100%;
                flex-direction: column;
            }

            .pms-payment-stat {
                width: 100%;
            }

            .pms-payment-search-card {
                padding: 17px;
            }

            .pms-payment-table-heading {
                padding: 17px;
            }
        }

        /* =========================================================
   RECEIVE PAYMENT MODAL
   Fixed to viewport — independent of Filament page layout
========================================================= */

.pms-payment-modal-overlay {
    position: fixed !important;
    inset: 0 !important;
    width: 100vw !important;
    height: 100vh !important;

    display: flex !important;
    align-items: center !important;
    justify-content: center !important;

    padding: 24px !important;

    z-index: 99999 !important;
}

.pms-payment-modal-backdrop {
    position: absolute !important;
    inset: 0 !important;

    width: 100% !important;
    height: 100% !important;

    background: rgba(15, 23, 42, 0.45) !important;
    backdrop-filter: blur(2px);

    cursor: pointer;
}

.pms-payment-modal {
    position: relative !important;
    z-index: 2 !important;

    width: min(520px, calc(100vw - 48px)) !important;
    max-width: 520px !important;
    max-height: calc(100vh - 48px) !important;

    overflow-y: auto !important;
    overflow-x: hidden !important;

    background: #ffffff !important;

    border-radius: 24px !important;

    box-shadow:
        0 25px 70px rgba(15, 23, 42, 0.22) !important;

    animation: pmsPaymentModalIn .18s ease-out;
}

@keyframes pmsPaymentModalIn {
    from {
        opacity: 0;
        transform: translateY(10px) scale(.98);
    }

    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Modal scrollbar */

.pms-payment-modal::-webkit-scrollbar {
    width: 7px;
}

.pms-payment-modal::-webkit-scrollbar-track {
    background: transparent;
}

.pms-payment-modal::-webkit-scrollbar-thumb {
    background: #cfe3e4;
    border-radius: 999px;
}

.pms-payment-modal::-webkit-scrollbar-thumb:hover {
    background: #9fc9cb;
}

/* Mobile */

@media (max-width: 640px) {

    .pms-payment-modal-overlay {
        padding: 12px !important;
    }

    .pms-payment-modal {
        width: calc(100vw - 24px) !important;
        max-width: none !important;
        max-height: calc(100vh - 24px) !important;

        border-radius: 20px !important;
    }
}
/* =========================================================
   RECEIVE AMOUNT FIELD
   Clean single-line currency + amount alignment
========================================================= */

.pms-payment-amount-field {
    position: relative !important;
    display: flex !important;
    align-items: center !important;

    width: 100% !important;
    height: 48px !important;

    background: #f8fafc !important;

    border: 1px solid #e2e8f0 !important;
    border-radius: 12px !important;

    overflow: hidden !important;

    transition:
        border-color .18s ease,
        background-color .18s ease,
        box-shadow .18s ease !important;
}

.pms-payment-amount-field:focus-within {
    background: #ffffff !important;

    border-color: #00838f !important;

    box-shadow:
        0 0 0 4px rgba(0, 131, 143, 0.10) !important;
}


/* Currency */

.pms-payment-currency {
    flex: 0 0 auto !important;

    display: flex !important;
    align-items: center !important;

    height: 100% !important;

    padding-left: 16px !important;
    padding-right: 6px !important;

    font-size: 14px !important;
    line-height: 1 !important;
    font-weight: 600 !important;

    color: #64748b !important;

    pointer-events: none !important;
}


/* Amount input */

.pms-payment-amount-input {
    flex: 1 1 auto !important;

    width: 100% !important;
    height: 100% !important;

    min-width: 0 !important;

    border: 0 !important;
    outline: none !important;

    background: transparent !important;

    padding: 0 16px 0 2px !important;

    font-size: 15px !important;
    line-height: 1 !important;
    font-weight: 700 !important;

    color: #173b40 !important;
}

.pms-payment-amount-input:focus {
    border: 0 !important;
    outline: none !important;

    box-shadow: none !important;

    background: transparent !important;
}


/* Remove browser number arrows */

.pms-payment-amount-input::-webkit-outer-spin-button,
.pms-payment-amount-input::-webkit-inner-spin-button {
    margin: 0 !important;
    -webkit-appearance: none !important;
}

.pms-payment-amount-input[type="number"] {
    -moz-appearance: textfield !important;
}


/* =========================================================
   RECEIVE PAYMENT SUBMIT BUTTON
========================================================= */

.pms-payment-submit-btn {
    display: inline-flex !important;

    align-items: center !important;
    justify-content: center !important;

    gap: 8px !important;

    height: 44px !important;
    min-width: 160px !important;

    padding: 0 22px !important;

    border: 0 !important;
    border-radius: 12px !important;

    background: #00838f !important;
    color: #ffffff !important;

    font-size: 14px !important;
    line-height: 1 !important;
    font-weight: 700 !important;

    box-shadow:
        0 4px 12px rgba(0, 131, 143, 0.18) !important;

    cursor: pointer !important;

    transition:
        background-color .18s ease,
        box-shadow .18s ease,
        transform .18s ease !important;
}

.pms-payment-submit-btn:hover {
    background: #006b73 !important;

    box-shadow:
        0 6px 16px rgba(0, 131, 143, 0.24) !important;

    transform: translateY(-1px) !important;
}

.pms-payment-submit-btn:active {
    transform: translateY(0) !important;
}

.pms-payment-submit-btn:disabled {
    background: #8abfc3 !important;
    color: #ffffff !important;

    cursor: not-allowed !important;

    opacity: 0.7 !important;

    transform: none !important;
}


/* Make everything inside button visible */

.pms-payment-submit-btn svg {
    width: 16px !important;
    height: 16px !important;

    flex-shrink: 0 !important;

    color: #ffffff !important;
    stroke: #ffffff !important;
}

.pms-payment-submit-btn span {
    color: #ffffff !important;
}


/* =========================================================
   MAXIMUM ALLOWED
========================================================= */

.pms-payment-modal .pms-payment-amount-field + .text-red-600 {
    color: #dc2626 !important;
}


/* Maximum allowed amount */

.pms-payment-modal button.font-semibold.text-\[\#00838f\] {
    color: #00838f !important;
}

.pms-payment-modal button.font-semibold.text-\[\#00838f\]:hover {
    color: #006b73 !important;
}
    </style>


    <div class="pms-payments-page">

        {{-- =====================================================
             HEADER
        ====================================================== --}}
        <div class="pms-payment-header">

            <div class="pms-payment-title-wrap">

                <div class="pms-payment-icon">

                    <svg
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        viewBox="0 0 24 24"
                    >
                        <rect
                            x="3"
                            y="5"
                            width="18"
                            height="14"
                            rx="2"
                        />

                        <path
                            stroke-linecap="round"
                            d="M3 10h18"
                        />

                        <path
                            stroke-linecap="round"
                            d="M7 15h3"
                        />
                    </svg>

                </div>

                <div>

                    <h1 class="pms-payment-title">
                        Payments
                    </h1>

                    <p class="pms-payment-subtitle">
                        Receive outstanding customer payments
                    </p>

                </div>

            </div>


            <div class="pms-payment-stats">

                <div class="pms-payment-stat">

                    <div class="pms-payment-stat-label">
                        Outstanding Sales
                    </div>

                    <div class="pms-payment-stat-value">
                        {{ number_format($this->outstandingSalesCount) }}
                    </div>

                </div>


                <div class="pms-payment-stat">

                    <div class="pms-payment-stat-label">
                        Outstanding Amount
                    </div>

                    <div class="pms-payment-stat-value">
                        Rs. {{ number_format($this->totalOutstanding, 2) }}
                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             SEARCH
        ====================================================== --}}
        <div class="pms-payment-search-card" style="margin-top: 18px;">

            <label class="pms-payment-search-label">
                Search Invoice or Customer
            </label>

            <div class="pms-payment-search">

                <svg
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    viewBox="0 0 24 24"
                >
                    <circle
                        cx="11"
                        cy="11"
                        r="7"
                    />

                    <path
                        stroke-linecap="round"
                        d="m20 20-4-4"
                    />
                </svg>

                <input
                    type="text"
                    wire:model.live.debounce.350ms="search"
                    placeholder="Search invoice, customer name or phone..."
                >

            </div>

        </div>


        {{-- =====================================================
             OUTSTANDING PAYMENTS
        ====================================================== --}}
        <div
            class="pms-payment-table-card"
            style="margin-top: 18px;"
        >

            <div class="pms-payment-table-heading">

                <div>

                    <h2 class="pms-payment-section-title">
                        Outstanding Payments
                    </h2>

                    <p class="pms-payment-section-subtitle">
                        Sales that still have an unpaid balance
                    </p>

                </div>

                <span class="pms-payment-count">
                    {{ $this->dueSales->count() }}
                </span>

            </div>


            <div class="pms-payment-table-wrap">

                <table class="pms-payment-table">

                    <thead>

                        <tr>

                            <th>
                                Invoice
                            </th>

                            <th>
                                Customer
                            </th>

                            <th class="amount">
                                Total
                            </th>

                            <th class="amount">
                                Paid
                            </th>

                            <th class="amount">
                                Due
                            </th>

                            <th style="text-align: right;">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($this->dueSales as $sale)

                            @php
                                $due = $this->getDueAmount($sale);
                            @endphp

                            <tr>

                                {{-- Invoice --}}
                                <td>

                                    <div class="pms-invoice-number">
                                        {{ $sale->invoice_number }}
                                    </div>

                                    <div class="pms-invoice-date">
                                        {{ $sale->created_at?->format('d M Y, h:i A') }}
                                    </div>

                                </td>


                                {{-- Customer --}}
                                <td>

                                    @if($sale->customer)

                                        <div class="pms-customer-name">
                                            {{ $sale->customer->name }}
                                        </div>

                                        @if($sale->customer->phone)

                                            <div class="pms-customer-phone">
                                                {{ $sale->customer->phone }}
                                            </div>

                                        @endif

                                    @else

                                        <span class="pms-walkin">
                                            Walk-in Customer
                                        </span>

                                    @endif

                                </td>


                                {{-- Total --}}
                                <td class="amount">

                                    <span class="pms-money">
                                        Rs. {{ number_format((float) $sale->grand_total, 2) }}
                                    </span>

                                </td>


                                {{-- Paid --}}
                                <td class="amount">

                                    <span class="pms-paid">
                                        Rs. {{ number_format((float) $sale->paid_amount, 2) }}
                                    </span>

                                </td>


                                {{-- Due --}}
                                <td class="amount">

                                    <span class="pms-due">
                                        Rs. {{ number_format($due, 2) }}
                                    </span>

                                </td>


                                {{-- Action --}}
                                <td style="text-align: right;">

                                    <button
                                        type="button"
                                        wire:click="openPaymentModal({{ $sale->id }})"
                                        class="pms-receive-btn"
                                    >

                                        <svg
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                d="M12 5v14"
                                            />

                                            <path
                                                stroke-linecap="round"
                                                d="M5 12h14"
                                            />
                                        </svg>

                                        Receive

                                    </button>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6">

                                    <div class="pms-payment-empty">

                                        <div class="pms-payment-empty-icon">

                                            <svg
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="m9 12 2 2 4-4"
                                                />

                                                <circle
                                                    cx="12"
                                                    cy="12"
                                                    r="9"
                                                />
                                            </svg>

                                        </div>

                                        <div class="pms-payment-empty-title">
                                            No outstanding payments
                                        </div>

                                        <div class="pms-payment-empty-text">
                                            All sales are fully paid.
                                        </div>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    
    </div>
    {{-- =========================================================
         RECEIVE PAYMENT MODAL
    ========================================================== --}}

   @if($showPaymentModal)
    <div
        class="pms-payment-modal-overlay"
        wire:key="receive-payment-modal"
    >

            {{-- Backdrop --}}
           <div
    class="pms-payment-modal-backdrop"
    wire:click="closePaymentModal"
></div>


            {{-- Modal --}}
           <div
    class="pms-payment-modal"
    wire:click.stop
>

                {{-- Modal Header --}}
                <div class="border-b border-[#e3eeee] bg-[#f8fcfc] px-6 py-5">

                    <div class="flex items-start justify-between gap-4">

                        <div class="flex items-center gap-3">

                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#00838f] text-white">

                                <svg
                                    class="h-5 w-5"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    viewBox="0 0 24 24"
                                >
                                    <rect
                                        x="3"
                                        y="5"
                                        width="18"
                                        height="14"
                                        rx="2"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        d="M3 10h18"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        d="M7 15h3"
                                    />

                                </svg>

                            </div>

                            <div>

                                <h3 class="text-lg font-bold text-[#173b40]">
                                    Receive Payment
                                </h3>

                                <p class="mt-0.5 text-xs text-slate-500">
                                    Record payment against this sale
                                </p>

                            </div>

                        </div>


                        <button
                            type="button"
                            wire:click="closePaymentModal"
                            class="flex h-9 w-9 items-center justify-center rounded-xl text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                        >

                            <svg
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    d="M6 6l12 12"
                                />

                                <path
                                    stroke-linecap="round"
                                    d="M18 6L6 18"
                                />

                            </svg>

                        </button>

                    </div>

                </div>


                @if($this->selectedSale)

                    @php
                        $selectedSale = $this->selectedSale;

                        $selectedDue = $this->getDueAmount($selectedSale);
                    @endphp


                    {{-- Sale Summary --}}
                    <div class="border-b border-slate-100 px-6 py-5">

                        <div class="rounded-2xl border border-[#d9eeee] bg-[#f7fbfb] p-4">

                            <div class="flex items-start justify-between gap-4">

                                <div>

                                    <div class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">
                                        Invoice
                                    </div>

                                    <div class="mt-1 text-sm font-bold text-[#173b40]">
                                        {{ $selectedSale->invoice_number }}
                                    </div>

                                </div>


                                <div class="text-right">

                                    <div class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">
                                        Outstanding
                                    </div>

                                    <div class="mt-1 text-base font-bold text-amber-600">
                                        Rs. {{ number_format($selectedDue, 2) }}
                                    </div>

                                </div>

                            </div>


                            <div class="mt-4 grid grid-cols-3 gap-2">

                                <div class="rounded-xl bg-white p-3">

                                    <div class="text-[10px] text-slate-400">
                                        Total
                                    </div>

                                    <div class="mt-1 text-sm font-bold text-slate-700">
                                        Rs. {{ number_format((float) $selectedSale->grand_total, 2) }}
                                    </div>

                                </div>


                                <div class="rounded-xl bg-white p-3">

                                    <div class="text-[10px] text-slate-400">
                                        Paid
                                    </div>

                                    <div class="mt-1 text-sm font-bold text-emerald-600">
                                        Rs. {{ number_format((float) $selectedSale->paid_amount, 2) }}
                                    </div>

                                </div>


                                <div class="rounded-xl bg-white p-3">

                                    <div class="text-[10px] text-slate-400">
                                        Due
                                    </div>

                                    <div class="mt-1 text-sm font-bold text-amber-600">
                                        Rs. {{ number_format($selectedDue, 2) }}
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Form --}}
                    <form
                        wire:submit.prevent="receivePayment"
                        class="space-y-5 px-6 py-6"
                    >

                        {{-- Amount --}}
                        <div>

                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Receive Amount
                            </label>

                          <div class="pms-payment-amount-field">

    <span class="pms-payment-currency">
        Rs.
    </span>

    <input
        type="number"
        step="0.01"
        min="0.01"
        max="{{ $selectedDue }}"
        wire:model.live="paymentAmount"
        class="pms-payment-amount-input"
        placeholder="0.00"
    />

</div>
                            @error('paymentAmount')
                                <p class="mt-1.5 text-xs font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                            <div class="mt-2 flex items-center justify-between text-xs">

                                <span class="text-slate-400">
                                    Maximum allowed
                                </span>

                                <button
                                    type="button"
                                    wire:click="$set('paymentAmount', '{{ number_format($selectedDue, 2, '.', '') }}')"
                                    class="font-semibold text-[#00838f] hover:underline"
                                >
                                    Rs. {{ number_format($selectedDue, 2) }}
                                </button>

                            </div>

                        </div>


                        {{-- Payment Method --}}
                        <div>

                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Payment Method
                            </label>

                            <select
                                wire:model="paymentMethod"
                                class="block h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm text-slate-800 outline-none transition focus:border-[#00838f] focus:bg-white focus:ring-4 focus:ring-[#00838f]/10"
                            >

                                <option value="cash">
                                    Cash
                                </option>

                                <option value="card">
                                    Card
                                </option>

                                <option value="bank_transfer">
                                    Bank Transfer
                                </option>

                                <option value="easypaisa">
                                    Easypaisa
                                </option>

                                <option value="jazzcash">
                                    JazzCash
                                </option>

                                <option value="other">
                                    Other
                                </option>

                            </select>

                            @error('paymentMethod')
                                <p class="mt-1.5 text-xs font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Reference --}}
                        <div>

                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Reference Number
                                <span class="font-normal text-slate-400">
                                    (Optional)
                                </span>
                            </label>

                            <input
                                type="text"
                                wire:model="referenceNumber"
                                maxlength="100"
                                placeholder="Transaction / reference number"
                                class="block h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm text-slate-800 outline-none transition focus:border-[#00838f] focus:bg-white focus:ring-4 focus:ring-[#00838f]/10"
                            />

                            @error('referenceNumber')
                                <p class="mt-1.5 text-xs font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Notes --}}
                        <div>

                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Notes
                                <span class="font-normal text-slate-400">
                                    (Optional)
                                </span>
                            </label>

                            <textarea
                                wire:model="paymentNotes"
                                rows="3"
                                maxlength="500"
                                placeholder="Add any payment note..."
                                class="block w-full resize-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-[#00838f] focus:bg-white focus:ring-4 focus:ring-[#00838f]/10"
                            ></textarea>

                            @error('paymentNotes')
                                <p class="mt-1.5 text-xs font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Actions --}}
                        <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-5">

                            <button
                                type="button"
                                wire:click="closePaymentModal"
                                class="h-11 rounded-xl border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
                            >
                                Cancel
                            </button>


                           <button
    type="submit"
    wire:loading.attr="disabled"
    wire:target="receivePayment"
    class="pms-payment-submit-btn"
>

                                <svg
                                    wire:loading
                                    wire:target="receivePayment"
                                    class="h-4 w-4 animate-spin"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <circle
                                        class="opacity-25"
                                        cx="12"
                                        cy="12"
                                        r="10"
                                        stroke="currentColor"
                                        stroke-width="4"
                                    />

                                    <path
                                        class="opacity-75"
                                        fill="currentColor"
                                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                                    />
                                </svg>


                                <svg
                                    wire:loading.remove
                                    wire:target="receivePayment"
                                    class="h-4 w-4"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M5 12h14"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M13 6l6 6-6 6"
                                    />
                                </svg>


                                <span wire:loading.remove wire:target="receivePayment">
                                    Receive Payment
                                </span>

                                <span wire:loading wire:target="receivePayment">
                                    Processing...
                                </span>

                            </button>

                        </div>

                    </form>

                @endif

            </div>

        </div>

    @endif
</x-filament-panels::page>