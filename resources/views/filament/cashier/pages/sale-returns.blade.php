<x-filament-panels::page heading="">

    <style>
        /* ============================================================
           SALE RETURNS PAGE
        ============================================================ */

        .fi-page-header,
        .fi-page-header-ctn,
        .fi-header,
        .fi-header-heading,
        .fi-header-main-ctn {
            display: none !important;
        }

        .fi-page-content,
        .fi-page {
            padding-top: 0 !important;
        }

        .pms-sr-page {
            width: 100%;
            padding: 18px 24px 40px;
        }

        /* ============================================================
           HEADER
        ============================================================ */

        .pms-sr-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 24px;
        }

        .pms-sr-title-wrap {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .pms-sr-title-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e0f2f1;
            color: #00838f;
        }

        .pms-sr-title-icon svg {
            width: 25px;
            height: 25px;
        }

        .pms-sr-title {
            margin: 0;
            font-size: 24px;
            line-height: 1.2;
            font-weight: 750;
            color: #172033;
        }

        .pms-sr-subtitle {
            margin: 4px 0 0;
            font-size: 13px;
            color: #6b7280;
        }

        .pms-sr-add-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 0;
            border-radius: 11px;
            padding: 11px 18px;
            background: #00838f;
            color: white;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: .2s ease;
            box-shadow: 0 5px 14px rgba(0, 131, 143, .18);
        }

        .pms-sr-add-btn:hover {
            background: #006b73;
            transform: translateY(-1px);
        }

        .pms-sr-add-btn svg {
            width: 18px;
            height: 18px;
        }

        /* ============================================================
           STATS
        ============================================================ */

        .pms-sr-stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 22px;
        }

        .pms-sr-stat {
            background: white;
            border: 1px solid #e6e9ee;
            border-radius: 15px;
            padding: 18px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .pms-sr-stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #eefafa;
            color: #00838f;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .pms-sr-stat-icon svg {
            width: 21px;
            height: 21px;
        }

        .pms-sr-stat-label {
            font-size: 12px;
            color: #737b87;
            margin-bottom: 3px;
        }

        .pms-sr-stat-value {
            font-size: 21px;
            line-height: 1;
            font-weight: 750;
            color: #182230;
        }

        /* ============================================================
           HISTORY
        ============================================================ */

        .pms-sr-history {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            overflow: hidden;
        }

        .pms-sr-history-head {
            padding: 18px 20px;
            border-bottom: 1px solid #edf0f3;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }

        .pms-sr-history-title {
            font-size: 16px;
            font-weight: 750;
            color: #172033;
        }

        .pms-sr-history-desc {
            font-size: 12px;
            color: #7a818c;
            margin-top: 3px;
        }

        .pms-sr-search {
            width: 280px;
            border: 1px solid #dfe3e8;
            border-radius: 9px;
            padding: 9px 12px;
            font-size: 13px;
            outline: none;
            background: #fff;
        }

        .pms-sr-search:focus {
            border-color: #00838f;
            box-shadow: 0 0 0 3px rgba(0, 131, 143, .08);
        }

        .pms-sr-table-wrap {
            overflow-x: auto;
        }

        .pms-sr-table {
            width: 100%;
            border-collapse: collapse;
        }

        .pms-sr-table th {
            padding: 12px 18px;
            background: #f8fafb;
            color: #68717d;
            font-size: 11px;
            font-weight: 750;
            text-transform: uppercase;
            letter-spacing: .04em;
            text-align: left;
            white-space: nowrap;
        }

        .pms-sr-table td {
            padding: 14px 18px;
            border-top: 1px solid #edf0f3;
            color: #344054;
            font-size: 13px;
            white-space: nowrap;
        }

        .pms-sr-invoice {
            color: #00838f;
            font-weight: 700;
        }

        .pms-sr-refund {
            color: #087443;
            font-weight: 750;
        }

        .pms-sr-reason {
            display: inline-flex;
            padding: 5px 9px;
            border-radius: 7px;
            background: #f1f5f9;
            color: #475569;
            font-size: 11px;
            font-weight: 650;
        }

        .pms-sr-empty {
            padding: 55px 20px;
            text-align: center;
            color: #8a929d;
        }

        /* ============================================================
           MODAL
        ============================================================ */

        .pms-sr-modal-overlay {
    position: fixed !important;
    inset: 0 !important;
    width: 100%;
    height: 100vh;
    z-index: 999999 !important;

    background: rgba(15, 23, 42, .58);

    display: flex;
    align-items: center;
    justify-content: center;

    padding: 24px;
}
        .pms-sr-modal {
            width: min(1050px, 100%);
            max-height: calc(100vh - 48px);
            background: white;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 25px 70px rgba(15, 23, 42, .25);
            display: flex;
            flex-direction: column;
        }

        .pms-sr-modal-head {
            padding: 18px 22px;
            background: #00838f;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .pms-sr-modal-title {
            margin: 0;
            font-size: 18px;
            font-weight: 750;
        }

        .pms-sr-modal-desc {
            margin: 4px 0 0;
            font-size: 12px;
            opacity: .85;
        }

        .pms-sr-close {
            width: 34px;
            height: 34px;
            border: 0;
            border-radius: 9px;
            background: rgba(255,255,255,.13);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .pms-sr-close:hover {
            background: rgba(255,255,255,.22);
        }

        .pms-sr-close svg {
            width: 19px;
            height: 19px;
        }

        .pms-sr-modal-body {
            overflow-y: auto;
            padding: 22px;
        }

        .pms-sr-field-label {
            display: block;
            margin-bottom: 7px;
            font-size: 12px;
            font-weight: 700;
            color: #374151;
        }

        .pms-sr-input-row {
            display: flex;
            gap: 9px;
        }

        .pms-sr-modal-input,
        .pms-sr-modal-select {
            width: 100%;
            height: 42px;
            border: 1px solid #dfe3e8;
            border-radius: 9px;
            padding: 0 12px;
            background: white;
            color: #1f2937;
            font-size: 13px;
            outline: none;
        }

        .pms-sr-modal-input:focus,
        .pms-sr-modal-select:focus {
            border-color: #00838f;
            box-shadow: 0 0 0 3px rgba(0, 131, 143, .08);
        }

        .pms-sr-search-btn {
            height: 42px;
            flex-shrink: 0;
            border: 0;
            border-radius: 9px;
            padding: 0 17px;
            background: #00838f;
            color: white;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
        }

        .pms-sr-search-btn:hover {
            background: #006b73;
        }

        .pms-sr-sale-card {
            margin-top: 20px;
            border: 1px solid #dcebed;
            border-radius: 13px;
            background: #f8ffff;
            padding: 16px;
        }

        .pms-sr-sale-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 14px;
        }

        .pms-sr-sale-invoice {
            color: #00838f;
            font-size: 16px;
            font-weight: 750;
        }

        .pms-sr-sale-meta {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
        }

        .pms-sr-meta-label {
            color: #7a818c;
            font-size: 10px;
            text-transform: uppercase;
            font-weight: 750;
            margin-bottom: 3px;
        }

        .pms-sr-meta-value {
            color: #263241;
            font-size: 12px;
            font-weight: 650;
        }

        .pms-sr-items {
            margin-top: 20px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
        }

        .pms-sr-item-row {
            display: grid;
            grid-template-columns: 2fr .8fr .8fr 1fr 1.2fr;
            gap: 12px;
            align-items: center;
            padding: 14px;
            border-bottom: 1px solid #edf0f3;
        }

        .pms-sr-item-row:last-child {
            border-bottom: 0;
        }

        .pms-sr-item-name {
            font-size: 13px;
            font-weight: 700;
            color: #263241;
        }

        .pms-sr-item-small {
            font-size: 11px;
            color: #7a818c;
            margin-top: 3px;
        }

        .pms-sr-qty-input {
            width: 100%;
            height: 38px;
            border: 1px solid #dfe3e8;
            border-radius: 8px;
            padding: 0 9px;
            font-size: 13px;
            outline: none;
        }

        .pms-sr-qty-input:focus {
            border-color: #00838f;
        }

        .pms-sr-no-return {
            padding: 15px;
            background: #fff8e8;
            color: #8a5b00;
            font-size: 12px;
            border-radius: 10px;
            margin-top: 15px;
        }

        .pms-sr-modal-footer {
            border-top: 1px solid #edf0f3;
            padding: 16px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .pms-sr-refund-box {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .pms-sr-refund-label {
            font-size: 11px;
            color: #7a818c;
        }

        .pms-sr-refund-total {
            font-size: 20px;
            color: #00838f;
            font-weight: 800;
        }

        .pms-sr-footer-actions {
            display: flex;
            gap: 9px;
        }

        .pms-sr-cancel-btn,
        .pms-sr-process-btn {
            height: 40px;
            border-radius: 9px;
            padding: 0 17px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
        }

        .pms-sr-cancel-btn {
            border: 1px solid #dfe3e8;
            background: white;
            color: #4b5563;
        }

        .pms-sr-process-btn {
            border: 0;
            background: #00838f;
            color: white;
        }

        .pms-sr-process-btn:hover {
            background: #006b73;
        }

        .pms-sr-process-btn:disabled,
        .pms-sr-search-btn:disabled {
            opacity: .55;
            cursor: not-allowed;
        }

        @media (max-width: 900px) {
            .pms-sr-stats {
                grid-template-columns: 1fr;
            }

            .pms-sr-sale-meta {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .pms-sr-item-row {
                grid-template-columns: 1fr 1fr;
            }

            .pms-sr-header,
            .pms-sr-history-head {
                align-items: flex-start;
                flex-direction: column;
            }

            .pms-sr-search {
                width: 100%;
            }
        }
    </style>

    <div class="pms-sr-page">

        {{-- ============================================================
             HEADER
        ============================================================= --}}

        <div class="pms-sr-header">

            <div class="pms-sr-title-wrap">

                <div class="pms-sr-title-icon">
                    <x-heroicon-o-arrow-uturn-left />
                </div>

                <div>
                    <h1 class="pms-sr-title">
                        Sale Returns
                    </h1>

                    <p class="pms-sr-subtitle">
                        View and process returned medicines
                    </p>
                </div>

            </div>

            <button
                type="button"
                wire:click="openReturnModal"
                class="pms-sr-add-btn"
            >
                <x-heroicon-o-plus />
                Sale Return
            </button>

        </div>

        {{-- ============================================================
             STATISTICS
        ============================================================= --}}

        <div class="pms-sr-stats">

            <div class="pms-sr-stat">

                <div class="pms-sr-stat-icon">
                    <x-heroicon-o-arrow-uturn-left />
                </div>

                <div>
                    <div class="pms-sr-stat-label">
                        Total Returns
                    </div>

                    <div class="pms-sr-stat-value">
                        {{ $this->totalReturns }}
                    </div>
                </div>

            </div>

            <div class="pms-sr-stat">

                <div class="pms-sr-stat-icon">
                    <x-heroicon-o-calendar-days />
                </div>

                <div>
                    <div class="pms-sr-stat-label">
                        This Month
                    </div>

                    <div class="pms-sr-stat-value">
                        {{ $this->monthlyReturns }}
                    </div>
                </div>

            </div>

            <div class="pms-sr-stat">

                <div class="pms-sr-stat-icon">
                    <x-heroicon-o-banknotes />
                </div>

                <div>
                    <div class="pms-sr-stat-label">
                        Total Refunds
                    </div>

                    <div class="pms-sr-stat-value">
                        {{ currency_symbol() }}{{ number_format($this->totalRefundAmount, 2) }}
                    </div>
                </div>

            </div>

        </div>

        {{-- ============================================================
             RETURN HISTORY
        ============================================================= --}}

        <div class="pms-sr-history">

            <div class="pms-sr-history-head">

                <div>
                    <div class="pms-sr-history-title">
                        Return History
                    </div>

                    <div class="pms-sr-history-desc">
                        Recent processed sale returns
                    </div>
                </div>

                <input
                    type="text"
                    wire:model.live.debounce.300ms="returnSearch"
                    class="pms-sr-search"
                    placeholder="Search invoice or medicine..."
                >

            </div>

            <div class="pms-sr-table-wrap">

                <table class="pms-sr-table">

                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Medicine</th>
                            <th>Qty</th>
                            <th>Refund</th>
                            <th>Reason</th>
                            <th>Date</th>
                            <th>Processed By</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($this->returnRows as $row)

                            <tr>

                                <td>
                                    <span class="pms-sr-invoice">
                                        {{ $row['invoice_number'] }}
                                    </span>
                                </td>

                                <td>
                                    {{ $row['medicine_name'] }}
                                </td>

                                <td>
                                    {{ $row['quantity'] }}
                                </td>

                                <td>
                                    <span class="pms-sr-refund">
                                        {{ currency_symbol() }}{{ number_format($row['refund_amount'], 2) }}
                                    </span>
                                </td>

                                <td>
                                    <span class="pms-sr-reason">
                                        {{ $row['reason'] }}
                                    </span>
                                </td>

                                <td>
                                    {{ $row['return_date'] }}
                                </td>

                                <td>
                                    {{ $row['processed_by'] }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7">
                                    <div class="pms-sr-empty">
                                        No sale returns found.
                                    </div>
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


   {{-- ================================================================
     SALE RETURN MODAL
================================================================ --}}

@if($showReturnModal)

    <template x-teleport="body">

        <div
            class="pms-sr-modal-overlay"
            wire:keydown.escape="closeReturnModal"
        >

            <div
                class="pms-sr-modal"
                wire:click.stop
            >

                {{-- ====================================================
                     MODAL HEADER
                ===================================================== --}}

                <div class="pms-sr-modal-head">

                    <div>

                        <h2 class="pms-sr-modal-title">
                            Create Sale Return
                        </h2>

                        <p class="pms-sr-modal-desc">
                            Search an invoice and select the medicine quantity to return
                        </p>

                    </div>

                    <button
                        type="button"
                        wire:click="closeReturnModal"
                        class="pms-sr-close"
                        wire:loading.attr="disabled"
                    >
                        <x-heroicon-o-x-mark />
                    </button>

                </div>


                {{-- ====================================================
                     MODAL BODY
                ===================================================== --}}

                <div class="pms-sr-modal-body">

                    {{-- =================================================
                         INVOICE SELECT
                    ================================================== --}}

                    <div>

                        <label class="pms-sr-field-label">
                            Invoice
                        </label>

                        <div style="position: relative;">

                            {{-- Main Invoice Field --}}
                            <div
                                style="
                                    display:flex;
                                    align-items:center;
                                    width:100%;
                                    height:36px;
                                    border:1px solid #008b99;
                                    border-radius:9px;
                                    background:#fff;
                                    overflow:hidden;
                                "
                            >

                                {{-- Search / Selected Invoice --}}
                                <input
                                    type="text"
                                    wire:model.live.debounce.250ms="invoiceSearch"
                                    wire:focus="openInvoiceDropdown"
                                    placeholder="Select an invoice"
                                    autocomplete="off"
                                    style="
                                        flex:1;
                                        width:100%;
                                        height:100%;
                                        border:0;
                                        outline:none;
                                        background:white;
                                        padding:0 10px;
                                        font-size:12px;
                                        color:#374151;
                                    "
                                >

                                {{-- Arrow Button --}}
                                <button
                                    type="button"
                                    wire:click="toggleInvoiceDropdown"
                                    style="
                                        width:36px;
                                        height:100%;
                                        border:0;
                                        background:white;
                                        display:flex;
                                        align-items:center;
                                        justify-content:center;
                                        cursor:pointer;
                                        flex-shrink:0;
                                    "
                                >
                                    <svg
                                        width="14"
                                        height="14"
                                        viewBox="0 0 20 20"
                                        fill="none"
                                    >
                                        <path
                                            d="M5 7.5L10 12.5L15 7.5"
                                            stroke="#667085"
                                            stroke-width="1.6"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                    </svg>
                                </button>

                            </div>


                            {{-- =================================================
                                 DROPDOWN
                            ================================================== --}}

                            @if($showInvoiceDropdown)

                                <div
                                    style="
                                        position:absolute;
                                        z-index:9999;
                                        top:42px;
                                        left:0;
                                        width:100%;
                                        background:#fff;
                                        border:1px solid #dfe3e8;
                                        border-radius:8px;
                                        overflow:hidden;
                                        box-shadow:0 8px 20px rgba(15,23,42,.12);
                                    "
                                >

                                    <div
                                        style="
                                            padding:8px 10px;
                                            border-bottom:1px solid #edf0f3;
                                            background:#fafbfc;
                                            font-size:11px;
                                            color:#8a929d;
                                        "
                                    >
                                        Start typing to search...
                                    </div>


                                    <div
                                        style="
                                            max-height:220px;
                                            overflow-y:auto;
                                        "
                                    >

                                        @forelse($this->invoiceOptions as $invoice)

                                            <button
                                                type="button"
                                                wire:click="selectInvoice({{ $invoice['id'] }})"
                                                wire:key="invoice-option-{{ $invoice['id'] }}"
                                                style="
                                                    width:100%;
                                                    display:flex;
                                                    align-items:center;
                                                    justify-content:space-between;
                                                    gap:12px;
                                                    padding:10px;
                                                    border:0;
                                                    border-bottom:1px solid #edf0f3;
                                                    background:#fff;
                                                    text-align:left;
                                                    cursor:pointer;
                                                "
                                            >

                                                <div style="min-width:0;">

                                                    <div
                                                        style="
                                                            font-size:12px;
                                                            font-weight:700;
                                                            color:#00838f;
                                                        "
                                                    >
                                                        {{ $invoice['invoice_number'] }}
                                                    </div>

                                                    <div
                                                        style="
                                                            margin-top:3px;
                                                            font-size:10px;
                                                            color:#7a818c;
                                                        "
                                                    >
                                                        {{ $invoice['customer'] }}
                                                        ·
                                                        {{ $invoice['date'] }}
                                                    </div>

                                                </div>

                                                <div
                                                    style="
                                                        flex-shrink:0;
                                                        font-size:11px;
                                                        font-weight:700;
                                                        color:#374151;
                                                    "
                                                >
                                                    {{ currency_symbol() }}
                                                    {{ number_format($invoice['total'], 2) }}
                                                </div>

                                            </button>

                                        @empty

                                            <div
                                                style="
                                                    padding:14px 10px;
                                                    font-size:11px;
                                                    color:#7a818c;
                                                    text-align:center;
                                                "
                                            >
                                                No invoices found.
                                            </div>

                                        @endforelse

                                    </div>

                                </div>

                            @endif

                        </div>

                    </div>


                    {{-- =================================================
                         SALE FOUND
                    ================================================== --}}

                    @if(!empty($saleData))

                        <div class="pms-sr-sale-card">

                            <div class="pms-sr-sale-top">

                                <div class="pms-sr-sale-invoice">
                                    {{ $saleData['invoice_number'] }}
                                </div>

                                <div>
                                    {{ $saleData['date'] }}
                                </div>

                            </div>

                            <div class="pms-sr-sale-meta">

                                <div>
                                    <div class="pms-sr-meta-label">
                                        Customer
                                    </div>

                                    <div class="pms-sr-meta-value">
                                        {{ $saleData['customer'] }}
                                    </div>
                                </div>

                                <div>
                                    <div class="pms-sr-meta-label">
                                        Phone
                                    </div>

                                    <div class="pms-sr-meta-value">
                                        {{ $saleData['phone'] ?: 'N/A' }}
                                    </div>
                                </div>

                                <div>
                                    <div class="pms-sr-meta-label">
                                        Sale Total
                                    </div>

                                    <div class="pms-sr-meta-value">
                                        {{ currency_symbol() }}{{ number_format($saleData['grand_total'], 2) }}
                                    </div>
                                </div>

                                <div>
                                    <div class="pms-sr-meta-label">
                                        Cashier
                                    </div>

                                    <div class="pms-sr-meta-value">
                                        {{ $saleData['cashier'] }}
                                    </div>
                                </div>

                            </div>

                        </div>


                        {{-- ITEMS --}}

                        <div class="pms-sr-items">

                            @forelse($saleData['items'] as $item)

                                <div class="pms-sr-item-row">

                                    <div>

                                        <div class="pms-sr-item-name">
                                            {{ $item['medicine_name'] }}
                                        </div>

                                        <div class="pms-sr-item-small">
                                            Batch:
                                            {{ $item['batch_number'] }}
                                        </div>

                                        <div class="pms-sr-item-small">
                                            Sold:
                                            {{ $item['sold_quantity'] }}
                                            |
                                            Returned:
                                            {{ $item['returned_quantity'] }}
                                            |
                                            Available:
                                            {{ $item['returnable_quantity'] }}
                                        </div>

                                    </div>


                                    <div>

                                        <div class="pms-sr-meta-label">
                                            Unit Price
                                        </div>

                                        <div class="pms-sr-meta-value">
                                            {{ currency_symbol() }}{{ number_format($item['unit_price'], 2) }}
                                        </div>

                                    </div>


                                    <div>

                                        <div class="pms-sr-meta-label">
                                            Returnable
                                        </div>

                                        <div class="pms-sr-meta-value">
                                            {{ $item['returnable_quantity'] }}
                                        </div>

                                    </div>


                                    <div>

                                        <label class="pms-sr-field-label">
                                            Return Qty
                                        </label>

                                        <input
                                            type="number"
                                            min="0"
                                            max="{{ $item['returnable_quantity'] }}"
                                            wire:model.live="returnQuantities.{{ $item['id'] }}"
                                            class="pms-sr-qty-input"
                                            @disabled($item['returnable_quantity'] <= 0)
                                        >

                                    </div>


                                    <div>

                                        <label class="pms-sr-field-label">
                                            Reason
                                        </label>

                                        <select
                                            wire:model="reasons.{{ $item['id'] }}"
                                            class="pms-sr-modal-select"
                                            @disabled($item['returnable_quantity'] <= 0)
                                        >
                                            <option value="defective">
                                                Defective / Damaged
                                            </option>

                                            <option value="wrong_item">
                                                Wrong Item Sold
                                            </option>

                                            <option value="customer_request">
                                                Customer Changed Mind
                                            </option>

                                            <option value="expired">
                                                Expired
                                            </option>

                                            <option value="other">
                                                Other
                                            </option>

                                        </select>

                                    </div>

                                </div>

                            @empty

                                <div class="pms-sr-no-return">
                                    This invoice has no sale items available.
                                </div>

                            @endforelse

                        </div>


                        @if(
                            collect($saleData['items'] ?? [])
                                ->every(fn ($item) => $item['returnable_quantity'] <= 0)
                        )

                            <div class="pms-sr-no-return">
                                All quantities from this invoice have already been returned.
                            </div>

                        @endif

                    @else

                        <div class="pms-sr-no-return">
                            Search an invoice above to start a sale return.
                        </div>

                    @endif

                </div>


                {{-- ====================================================
                     MODAL FOOTER
                ===================================================== --}}

                <div class="pms-sr-modal-footer">

                    <div class="pms-sr-refund-box">

                        <div>

                            <div class="pms-sr-refund-label">
                                Selected Lines
                            </div>

                            <strong>
                                {{ $this->selectedReturnCount }}
                            </strong>

                        </div>

                        <div>

                            <div class="pms-sr-refund-label">
                                Refund Amount
                            </div>

                            <div class="pms-sr-refund-total">
                                {{ currency_symbol() }}{{ number_format($this->returnTotal, 2) }}
                            </div>

                        </div>

                    </div>


                    <div class="pms-sr-footer-actions">

                        <button
                            type="button"
                            wire:click="closeReturnModal"
                            wire:loading.attr="disabled"
                            class="pms-sr-cancel-btn"
                        >
                            Cancel
                        </button>

                        <button
                            type="button"
                            wire:click="processReturn"
                            wire:loading.attr="disabled"
                            wire:target="processReturn"
                            class="pms-sr-process-btn"
                            @disabled(!$selectedSaleId || $this->selectedReturnCount <= 0)
                        >

                            <span
                                wire:loading.remove
                                wire:target="processReturn"
                            >
                                Process Return
                            </span>

                            <span
                                wire:loading
                                wire:target="processReturn"
                            >
                                Processing...
                            </span>

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </template>

@endif

</x-filament-panels::page>