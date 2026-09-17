<div class="pms-sale-history">

    {{-- ============================================================
        HEADER
    ============================================================= --}}

    <div class="pms-page-header">

        <div>
            <h1>Sale History</h1>

            <p>
                View and manage your completed customer sales
            </p>
        </div>

        <div class="pms-history-badge">
            <span class="pms-history-dot"></span>
            Sales History
        </div>

    </div>


    {{-- ============================================================
        CONTENT
    ============================================================= --}}

    <div class="pms-history-container">

        {{-- ========================================================
            STAT CARDS
        ========================================================= --}}

        <div class="pms-stat-grid">

            {{-- SALES --}}

            <div class="pms-stat-card">

                <div class="pms-stat-icon pms-icon-blue">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"
                        />
                    </svg>
                </div>

                <div>
                    <span>Total Sales</span>

                    <strong>
                        {{ number_format($this->stats['sales']) }}
                    </strong>
                </div>

            </div>


            {{-- TOTAL --}}

            <div class="pms-stat-card">

                <div class="pms-stat-icon pms-icon-teal">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 6v2m0 4v-2m0 0c-1.11 0-2.08-.402-2.599-1M12 18c-1.657 0-3-.895-3-2"
                        />
                    </svg>
                </div>

                <div>
                    <span>Grand Total</span>

                    <strong>
                        {{ currency_symbol() }}
                        {{ number_format($this->stats['total'], 2) }}
                    </strong>
                </div>

            </div>


            {{-- PAID --}}

            <div class="pms-stat-card">

                <div class="pms-stat-icon pms-icon-green">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 3c-2.755 0-5.29.93-7.31 2.49m14.618 0A11.955 11.955 0 0112 21c-2.755 0-5.29-.93-7.31-2.49"
                        />
                    </svg>
                </div>

                <div>
                    <span>Paid Amount</span>

                    <strong>
                        {{ currency_symbol() }}
                        {{ number_format($this->stats['paid'], 2) }}
                    </strong>
                </div>

            </div>


            {{-- DUE --}}

            <div class="pms-stat-card">

                <div class="pms-stat-icon pms-icon-orange">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.732-3L13.732 4a2 2 0 00-3.464 0L3.338 16a2 2 0 001.732 3z"
                        />
                    </svg>
                </div>

                <div>
                    <span>Due Amount</span>

                    <strong>
                        {{ currency_symbol() }}
                        {{ number_format($this->stats['due'], 2) }}
                    </strong>
                </div>

            </div>

        </div>


        {{-- ========================================================
            FILTER CARD
        ========================================================= --}}

        <div class="pms-filter-card">

            <div class="pms-filter-header">

                <div>
                    <h2>Search & Filter</h2>

                    <p>
                        Find a sale by invoice, customer or payment details
                    </p>
                </div>

                <button
                    type="button"
                    wire:click="clearFilters"
                    class="pms-clear-filter"
                >
                    Clear Filters
                </button>

            </div>


            <div class="pms-filter-grid">

                {{-- SEARCH --}}

                <div class="pms-filter-field pms-search-field">

                    <label>Search</label>

                    <div class="pms-filter-search">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="m21 21-4.35-4.35m1.35-5.65a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"
                            />
                        </svg>

                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Invoice number or customer..."
                        >

                    </div>

                </div>


                {{-- DATE FROM --}}

                <div class="pms-filter-field">

                    <label>Date From</label>

                    <input
                        type="date"
                        wire:model.live="dateFrom"
                        class="pms-filter-input"
                    >

                </div>


                {{-- DATE TO --}}

                <div class="pms-filter-field">

                    <label>Date To</label>

                    <input
                        type="date"
                        wire:model.live="dateTo"
                        class="pms-filter-input"
                    >

                </div>


                {{-- PAYMENT STATUS --}}

                <div class="pms-filter-field">

                    <label>Payment Status</label>

                    <select
                        wire:model.live="paymentStatus"
                        class="pms-filter-input"
                    >

                        <option value="">
                            All Statuses
                        </option>

                        <option value="paid">
                            Paid
                        </option>

                        <option value="partial">
                            Partial
                        </option>

                        <option value="credit">
                            Credit
                        </option>

                    </select>

                </div>


                {{-- PAYMENT METHOD --}}

                <div class="pms-filter-field">

                    <label>Payment Method</label>

                    <select
                        wire:model.live="paymentMethod"
                        class="pms-filter-input"
                    >

                        <option value="">
                            All Methods
                        </option>

                        <option value="cash">
                            Cash
                        </option>

                        <option value="card">
                            Card
                        </option>

                        <option value="bank">
                            Bank Transfer
                        </option>

                        <option value="other">
                            Other
                        </option>

                    </select>

                </div>

            </div>

        </div>


        {{-- ========================================================
            SALES TABLE
        ========================================================= --}}

        <div class="pms-history-card">

            <div class="pms-history-card-header">

                <div>

                    <h2>Sales</h2>

                    <p>
                        Your completed sales
                    </p>

                </div>

                <div class="pms-results-count">

                    {{ $this->sales->total() }}

                    {{ $this->sales->total() === 1 ? 'sale' : 'sales' }}

                </div>

            </div>


            <div class="pms-history-table-wrap">

                <table class="pms-history-table">

                    <thead>

                        <tr>

                            <th>Invoice</th>

                            <th>Customer</th>

                            <th>Items</th>

                            <th>Total</th>

                            <th>Paid</th>

                            <th>Due</th>

                            <th>Payment</th>

                            <th>Status</th>

                            <th>Date</th>

                            <th></th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($this->sales as $sale)

                            <tr wire:key="sale-{{ $sale->id }}">

                                {{-- INVOICE --}}

                                <td>

                                    <div class="pms-invoice-cell">

                                        <div class="pms-invoice-icon">
                                            #
                                        </div>

                                        <div>

                                            <strong>
                                                {{ $sale->invoice_number }}
                                            </strong>

                                            <small>
                                                Sale #{{ $sale->id }}
                                            </small>

                                        </div>

                                    </div>

                                </td>


                                {{-- CUSTOMER --}}

                                <td>

                                    @if ($sale->customer)

                                        <div class="pms-customer-cell">

                                            <strong>
                                                {{ $sale->customer->name }}
                                            </strong>

                                            @if ($sale->customer->phone)

                                                <small>
                                                    {{ $sale->customer->phone }}
                                                </small>

                                            @endif

                                        </div>

                                    @else

                                        <span class="pms-walkin">
                                            Walk-in Customer
                                        </span>

                                    @endif

                                </td>


                                {{-- ITEMS --}}

                                <td>

                                    <span class="pms-items-badge">

                                        {{ $sale->items()->count() }}

                                        {{ $sale->items()->count() === 1 ? 'item' : 'items' }}

                                    </span>

                                </td>


                                {{-- TOTAL --}}

                                <td>

                                    <strong class="pms-total-value">

                                        {{ currency_symbol() }}
                                        {{ number_format((float) $sale->grand_total, 2) }}

                                    </strong>

                                </td>


                                {{-- PAID --}}

                                <td>

                                    <span class="pms-paid-value">

                                        {{ currency_symbol() }}
                                        {{ number_format((float) $sale->paid_amount, 2) }}

                                    </span>

                                </td>


                                {{-- DUE --}}

                                <td>

                                    @if ($sale->due_amount > 0)

                                        <span class="pms-due-value">

                                            {{ currency_symbol() }}
                                            {{ number_format($sale->due_amount, 2) }}

                                        </span>

                                    @else

                                        <span class="pms-no-due">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- PAYMENT METHOD --}}

                                <td>

                                    <span class="pms-payment-method">

                                        @switch($sale->payment_method)

                                            @case('cash')
                                                Cash
                                                @break

                                            @case('card')
                                                Card
                                                @break

                                            @case('bank')
                                                Bank
                                                @break

                                            @default
                                                Other

                                        @endswitch

                                    </span>

                                </td>


                                {{-- STATUS --}}

                                <td>

                                    @if ($sale->payment_status === 'paid')

                                        <span class="pms-status pms-status-paid">
                                            Paid
                                        </span>

                                    @elseif ($sale->payment_status === 'partial')

                                        <span class="pms-status pms-status-partial">
                                            Partial
                                        </span>

                                    @else

                                        <span class="pms-status pms-status-credit">
                                            Credit
                                        </span>

                                    @endif

                                </td>


                                {{-- DATE --}}

                                <td>

                                    <div class="pms-date-cell">

                                        <strong>
                                            {{ $sale->created_at->format('d M Y') }}
                                        </strong>

                                        <small>
                                            {{ $sale->created_at->format('h:i A') }}
                                        </small>

                                    </div>

                                </td>


                                {{-- ACTION --}}

                                <td>

                                    <button
                                        type="button"
                                        wire:click="viewSale({{ $sale->id }})"
                                        class="pms-view-btn"
                                    >
                                        <svg
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                            />

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                            />
                                        </svg>

                                        View

                                    </button>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="10">

                                    <div class="pms-history-empty">

                                        <div class="pms-empty-icon">
                                            📋
                                        </div>

                                        <strong>
                                            No sales found
                                        </strong>

                                        <span>
                                            Try changing your search or filter criteria.
                                        </span>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- PAGINATION --}}

            @if ($this->sales->hasPages())

                <div class="pms-pagination">

                    {{ $this->sales->links() }}

                </div>

            @endif

        </div>

    </div>


    {{-- ============================================================
        SALE DETAILS MODAL
    ============================================================= --}}

    @if ($showSaleModal && $this->selectedSale)

        <div
            class="pms-modal-overlay"
            wire:click.self="closeSale"
        >

            <div class="pms-sale-modal">

                {{-- MODAL HEADER --}}

                <div class="pms-modal-header">

                    <div>

                        <span class="pms-modal-label">
                            SALE DETAILS
                        </span>

                        <h2>
                            {{ $this->selectedSale->invoice_number }}
                        </h2>

                    </div>

                    <button
                        type="button"
                        wire:click="closeSale"
                        class="pms-modal-close"
                    >
                        ×
                    </button>

                </div>


                {{-- SALE INFO --}}

                <div class="pms-modal-info-grid">

                    <div>
                        <span>Customer</span>

                        <strong>
                            {{ $this->selectedSale->customer?->name ?? 'Walk-in Customer' }}
                        </strong>
                    </div>

                    <div>
                        <span>Date</span>

                        <strong>
                            {{ $this->selectedSale->created_at->format('d M Y, h:i A') }}
                        </strong>
                    </div>

                    <div>
                        <span>Cashier</span>

                        <strong>
                            {{ $this->selectedSale->creator?->name ?? '—' }}
                        </strong>
                    </div>

                    <div>
                        <span>Payment</span>

                        <strong>
                            {{ ucfirst($this->selectedSale->payment_method) }}
                        </strong>
                    </div>

                </div>


                {{-- ITEMS --}}

                <div class="pms-modal-section">

                    <div class="pms-modal-section-title">
                        <h3>Sale Items</h3>
                    </div>

                    <div class="pms-modal-items-wrap">

                        <table class="pms-modal-items">

                            <thead>

                                <tr>
                                    <th>Medicine</th>
                                    <th>Batch</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>

                            </thead>

                            <tbody>

                                @foreach ($this->selectedSale->items as $item)

                                    <tr>

                                        <td>

                                            <strong>
                                                {{ $item->medicine?->name ?? 'Medicine' }}
                                            </strong>

                                        </td>

                                        <td>

                                            <span class="pms-modal-batch">

                                                {{ $item->batch?->batch_number ?? '—' }}

                                            </span>

                                        </td>

                                        <td>
                                            {{ $item->quantity }}
                                        </td>

                                        <td>

                                            {{ currency_symbol() }}
                                            {{ number_format((float) $item->unit_price, 2) }}

                                        </td>

                                        <td>

                                            <strong>

                                                {{ currency_symbol() }}
                                                {{ number_format((float) $item->total, 2) }}

                                            </strong>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>


                {{-- PAYMENT HISTORY --}}

                @if ($this->selectedSale->payments->count())

                    <div class="pms-modal-section">

                        <div class="pms-modal-section-title">

                            <h3>Payment History</h3>

                        </div>

                        <div class="pms-payment-list">

                            @foreach ($this->selectedSale->payments as $payment)

                                <div class="pms-payment-row">

                                    <div>

                                        <strong>
                                            {{ ucfirst($payment->payment_method) }}
                                        </strong>

                                        <small>

                                            {{ $payment->paid_at?->format('d M Y, h:i A') }}

                                            @if ($payment->reference_number)
                                                • {{ $payment->reference_number }}
                                            @endif

                                        </small>

                                    </div>

                                    <strong>

                                        {{ currency_symbol() }}
                                        {{ number_format((float) $payment->amount, 2) }}

                                    </strong>

                                </div>

                            @endforeach

                        </div>

                    </div>

                @endif


                {{-- TOTALS --}}

                <div class="pms-modal-totals">

                    <div>
                        <span>Subtotal</span>

                        <strong>
                            {{ currency_symbol() }}
                            {{ number_format((float) $this->selectedSale->subtotal, 2) }}
                        </strong>
                    </div>

                    <div>
                        <span>Discount</span>

                        <strong class="pms-modal-discount">
                            − {{ currency_symbol() }}
                            {{ number_format((float) $this->selectedSale->discount, 2) }}
                        </strong>
                    </div>

                    <div>
                        <span>Tax</span>

                        <strong>
                            + {{ currency_symbol() }}
                            {{ number_format((float) $this->selectedSale->tax, 2) }}
                        </strong>
                    </div>

                    <div class="pms-modal-grand">

                        <span>Grand Total</span>

                        <strong>
                            {{ currency_symbol() }}
                            {{ number_format((float) $this->selectedSale->grand_total, 2) }}
                        </strong>

                    </div>

                    <div>
                        <span>Paid</span>

                        <strong class="pms-modal-paid">
                            {{ currency_symbol() }}
                            {{ number_format((float) $this->selectedSale->paid_amount, 2) }}
                        </strong>
                    </div>

                    <div>
                        <span>Due</span>

                        <strong class="pms-modal-due">
                            {{ currency_symbol() }}
                            {{ number_format($this->selectedSale->due_amount, 2) }}
                        </strong>
                    </div>

                    @if ($this->selectedSale->change_amount > 0)

                        <div>

                            <span>Change</span>

                            <strong class="pms-modal-change">
                                {{ currency_symbol() }}
                                {{ number_format((float) $this->selectedSale->change_amount, 2) }}
                            </strong>

                        </div>

                    @endif

                </div>


                {{-- MODAL ACTIONS --}}

                <div class="pms-modal-actions">

                    <button
                        type="button"
                        wire:click="closeSale"
                        class="pms-modal-cancel"
                    >
                        Close
                    </button>

                    <button
                        type="button"
                        onclick="window.print()"
                        class="pms-modal-print"
                    >
                        🖨 Print
                    </button>

                </div>

            </div>

        </div>

    @endif


    {{-- ============================================================
        CSS
    ============================================================= --}}

    <style>

        .pms-sale-history {
            width: 100%;
            min-height: calc(100vh - 65px);
            background: #f4fafa;
            color: #17374a;
        }

        .pms-sale-history * {
            box-sizing: border-box;
        }


        /* HEADER */

        .pms-page-header {
            width: 100%;
            padding: 22px 28px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #e0ebeb;
            background: #f4fafa;
        }

        .pms-page-header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 800;
            color: #17374a;
        }

        .pms-page-header p {
            margin: 5px 0 0;
            color: #789097;
            font-size: 13px;
        }

        .pms-history-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 13px;
            border: 1px solid #d7eaea;
            border-radius: 999px;
            background: #fff;
            color: #087b7e;
            font-size: 12px;
            font-weight: 700;
        }

        .pms-history-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #18a47a;
        }


        /* CONTAINER */

        .pms-history-container {
            width: 100%;
            padding: 24px 28px 40px;
        }


        /* STATS */

        .pms-stat-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 18px;
        }

        .pms-stat-card {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 18px;
            background: #fff;
            border: 1px solid #dfeaea;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(23,55,74,.04);
        }

        .pms-stat-icon {
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            flex: 0 0 auto;
        }

        .pms-stat-icon svg {
            width: 22px;
            height: 22px;
        }

        .pms-icon-blue {
            background: #edf5ff;
            color: #3977bd;
        }

        .pms-icon-teal {
            background: #eaf8f8;
            color: #087b7e;
        }

        .pms-icon-green {
            background: #ebf9f1;
            color: #198754;
        }

        .pms-icon-orange {
            background: #fff6e8;
            color: #bd7b22;
        }

        .pms-stat-card span {
            display: block;
            color: #81939a;
            font-size: 11px;
            font-weight: 700;
        }

        .pms-stat-card strong {
            display: block;
            margin-top: 4px;
            color: #17374a;
            font-size: 18px;
            font-weight: 800;
        }


        /* FILTER */

        .pms-filter-card,
        .pms-history-card {
            width: 100%;
            background: #fff;
            border: 1px solid #dfeaea;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(23,55,74,.04);
        }

        .pms-filter-card {
            margin-bottom: 18px;
        }

        .pms-filter-header {
            padding: 19px 22px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .pms-filter-header h2,
        .pms-history-card-header h2 {
            margin: 0;
            font-size: 16px;
            font-weight: 800;
            color: #17374a;
        }

        .pms-filter-header p,
        .pms-history-card-header p {
            margin: 4px 0 0;
            color: #8a9ba0;
            font-size: 11px;
        }

        .pms-clear-filter {
            border: 0;
            background: transparent;
            color: #087b7e;
            font-size: 11px;
            font-weight: 800;
            cursor: pointer;
        }

        .pms-filter-grid {
            display: grid;
            grid-template-columns: 2fr repeat(4, 1fr);
            gap: 14px;
            padding: 0 22px 20px;
        }

        .pms-filter-field label {
            display: block;
            margin-bottom: 6px;
            color: #63777e;
            font-size: 11px;
            font-weight: 700;
        }

        .pms-filter-input,
        .pms-filter-search input {
            width: 100%;
            height: 42px;
            border: 1px solid #d3e1e1;
            border-radius: 7px;
            outline: none;
            background: #fff;
            color: #17374a;
            font-size: 12px;
        }

        .pms-filter-input {
            padding: 0 11px;
        }

        .pms-filter-search {
            position: relative;
        }

        .pms-filter-search svg {
            position: absolute;
            left: 12px;
            top: 50%;
            width: 17px;
            height: 17px;
            transform: translateY(-50%);
            color: #718b91;
            pointer-events: none;
        }

        .pms-filter-search input {
            padding: 0 12px 0 38px;
        }

        .pms-filter-input:focus,
        .pms-filter-search input:focus {
            border-color: #087b7e;
            box-shadow: 0 0 0 3px rgba(8,123,126,.07);
        }


        /* TABLE HEADER */

        .pms-history-card-header {
            padding: 19px 22px 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .pms-results-count {
            padding: 5px 10px;
            border-radius: 999px;
            background: #edf7f7;
            color: #087b7e;
            font-size: 10px;
            font-weight: 800;
        }

        .pms-history-table-wrap {
            width: 100%;
            overflow-x: auto;
            border-top: 1px solid #edf2f2;
        }

        .pms-history-table {
            width: 100%;
            min-width: 1250px;
            border-collapse: collapse;
        }

        .pms-history-table thead {
            background: #f7fafa;
        }

        .pms-history-table th {
            padding: 12px 14px;
            border-bottom: 1px solid #e2ebeb;
            color: #71858b;
            font-size: 10px;
            font-weight: 800;
            text-align: left;
            text-transform: uppercase;
            letter-spacing: .03em;
            white-space: nowrap;
        }

        .pms-history-table td {
            padding: 13px 14px;
            border-bottom: 1px solid #edf2f2;
            color: #526b74;
            font-size: 12px;
            vertical-align: middle;
        }

        .pms-history-table tbody tr:hover {
            background: #fbfdfd;
        }


        /* INVOICE */

        .pms-invoice-cell,
        .pms-customer-cell,
        .pms-date-cell {
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .pms-invoice-icon {
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: #eaf7f7;
            color: #087b7e;
            font-size: 12px;
            font-weight: 900;
        }

        .pms-invoice-cell strong,
        .pms-customer-cell strong {
            display: block;
            color: #17374a;
            font-size: 12px;
            font-weight: 800;
        }

        .pms-invoice-cell small,
        .pms-customer-cell small,
        .pms-date-cell small {
            display: block;
            margin-top: 2px;
            color: #94a2a6;
            font-size: 10px;
        }

        .pms-customer-cell {
            display: block;
        }

        .pms-walkin {
            color: #75888e;
            font-size: 11px;
            font-weight: 700;
        }


        /* VALUES */

        .pms-items-badge {
            padding: 5px 8px;
            border-radius: 5px;
            background: #f2f6f6;
            color: #526b74;
            font-size: 10px;
            font-weight: 800;
        }

        .pms-total-value {
            color: #17374a;
            font-weight: 800;
        }

        .pms-paid-value {
            color: #198754;
            font-weight: 700;
        }

        .pms-due-value {
            color: #c05b4f;
            font-weight: 700;
        }

        .pms-no-due {
            color: #a5b0b3;
        }

        .pms-payment-method {
            color: #526b74;
            font-size: 11px;
            font-weight: 700;
        }


        /* STATUS */

        .pms-status {
            display: inline-flex;
            padding: 5px 9px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 800;
        }

        .pms-status-paid {
            background: #eaf8f0;
            color: #198754;
        }

        .pms-status-partial {
            background: #fff6df;
            color: #a97916;
        }

        .pms-status-credit {
            background: #fff0ef;
            color: #b5504a;
        }


        /* DATE */

        .pms-date-cell {
            display: block;
        }

        .pms-date-cell strong {
            display: block;
            color: #526b74;
            font-size: 11px;
        }


        /* VIEW */

        .pms-view-btn {
            height: 32px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 0 10px;
            border: 1px solid #d5e4e4;
            border-radius: 6px;
            background: #fff;
            color: #087b7e;
            font-size: 10px;
            font-weight: 800;
            cursor: pointer;
        }

        .pms-view-btn:hover {
            background: #f1f9f9;
            border-color: #bcdada;
        }

        .pms-view-btn svg {
            width: 15px;
            height: 15px;
        }


        /* EMPTY */

        .pms-history-empty {
            min-height: 230px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
            text-align: center;
        }

        .pms-empty-icon {
            width: 55px;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 5px;
            border-radius: 50%;
            background: #f1f7f7;
            font-size: 23px;
        }

        .pms-history-empty strong {
            color: #526b74;
            font-size: 14px;
        }

        .pms-history-empty span {
            color: #9aa8ac;
            font-size: 11px;
        }


        /* PAGINATION */

        .pms-pagination {
            padding: 15px 20px;
            border-top: 1px solid #edf2f2;
        }


        /* MODAL */

        .pms-modal-overlay {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 25px;
            background: rgba(23,55,74,.48);
        }

        .pms-sale-modal {
            width: 100%;
            max-width: 900px;
            max-height: 90vh;
            overflow-y: auto;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 25px 70px rgba(23,55,74,.25);
        }

        .pms-modal-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            padding: 22px;
            border-bottom: 1px solid #e6eeee;
        }

        .pms-modal-label {
            color: #087b7e;
            font-size: 9px;
            font-weight: 900;
            letter-spacing: .08em;
        }

        .pms-modal-header h2 {
            margin: 4px 0 0;
            color: #17374a;
            font-size: 19px;
            font-weight: 900;
        }

        .pms-modal-close {
            width: 34px;
            height: 34px;
            border: 0;
            border-radius: 7px;
            background: #f1f6f6;
            color: #71858b;
            font-size: 22px;
            line-height: 1;
            cursor: pointer;
        }

        .pms-modal-info-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            padding: 18px 22px;
            background: #f8fbfb;
            border-bottom: 1px solid #e6eeee;
        }

        .pms-modal-info-grid span {
            display: block;
            color: #8b9ba0;
            font-size: 10px;
        }

        .pms-modal-info-grid strong {
            display: block;
            margin-top: 4px;
            color: #36545f;
            font-size: 12px;
        }


        /* MODAL ITEMS */

        .pms-modal-section {
            padding: 20px 22px 0;
        }

        .pms-modal-section-title {
            margin-bottom: 10px;
        }

        .pms-modal-section-title h3 {
            margin: 0;
            color: #36545f;
            font-size: 13px;
            font-weight: 800;
        }

        .pms-modal-items-wrap {
            overflow-x: auto;
            border: 1px solid #e2ebeb;
            border-radius: 8px;
        }

        .pms-modal-items {
            width: 100%;
            min-width: 600px;
            border-collapse: collapse;
        }

        .pms-modal-items th {
            padding: 10px;
            background: #f7fafa;
            color: #7c8e94;
            font-size: 9px;
            text-align: left;
            text-transform: uppercase;
        }

        .pms-modal-items td {
            padding: 11px 10px;
            border-top: 1px solid #edf2f2;
            color: #526b74;
            font-size: 11px;
        }

        .pms-modal-items td strong {
            color: #17374a;
        }

        .pms-modal-batch {
            padding: 4px 6px;
            border-radius: 4px;
            background: #f1f6f6;
            font-size: 9px;
            font-weight: 700;
        }


        /* PAYMENT LIST */

        .pms-payment-list {
            border: 1px solid #e2ebeb;
            border-radius: 8px;
            overflow: hidden;
        }

        .pms-payment-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 11px 13px;
            border-bottom: 1px solid #edf2f2;
        }

        .pms-payment-row:last-child {
            border-bottom: 0;
        }

        .pms-payment-row strong {
            display: block;
            color: #36545f;
            font-size: 11px;
        }

        .pms-payment-row small {
            display: block;
            margin-top: 3px;
            color: #93a1a5;
            font-size: 9px;
        }

        .pms-payment-row > strong {
            color: #087b7e;
        }


        /* MODAL TOTALS */

        .pms-modal-totals {
            max-width: 430px;
            margin: 20px 22px 0 auto;
            padding: 15px;
            border: 1px solid #e0ebeb;
            border-radius: 9px;
            background: #f9fbfb;
        }

        .pms-modal-totals > div {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            color: #75878d;
            font-size: 11px;
        }

        .pms-modal-totals strong {
            color: #36545f;
        }

        .pms-modal-grand {
            margin-top: 5px;
            padding-top: 10px !important;
            border-top: 1px solid #dce7e7;
            color: #17374a !important;
            font-weight: 800;
        }

        .pms-modal-grand strong {
            color: #087b7e;
            font-size: 16px;
        }

        .pms-modal-discount {
            color: #c45a5a !important;
        }

        .pms-modal-paid {
            color: #198754 !important;
        }

        .pms-modal-due {
            color: #c05b4f !important;
        }

        .pms-modal-change {
            color: #087b7e !important;
        }


        /* MODAL ACTIONS */

        .pms-modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 9px;
            padding: 20px 22px;
            margin-top: 20px;
            border-top: 1px solid #e6eeee;
        }

        .pms-modal-cancel,
        .pms-modal-print {
            height: 40px;
            padding: 0 16px;
            border-radius: 7px;
            font-size: 11px;
            font-weight: 800;
            cursor: pointer;
        }

        .pms-modal-cancel {
            border: 1px solid #d4e1e1;
            background: #fff;
            color: #64777d;
        }

        .pms-modal-print {
            border: 0;
            background: #087b7e;
            color: #fff;
        }


        /* RESPONSIVE */

        @media (max-width: 1200px) {

            .pms-stat-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .pms-filter-grid {
                grid-template-columns: repeat(2, 1fr);
            }

        }

        @media (max-width: 700px) {

            .pms-page-header {
                padding: 18px 15px;
            }

            .pms-page-header p,
            .pms-history-badge {
                display: none;
            }

            .pms-history-container {
                padding: 15px;
            }

            .pms-stat-grid {
                grid-template-columns: 1fr;
            }

            .pms-filter-grid {
                grid-template-columns: 1fr;
                padding-left: 16px;
                padding-right: 16px;
            }

            .pms-filter-header {
                padding: 16px;
            }

            .pms-modal-overlay {
                padding: 10px;
            }

            .pms-modal-info-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .pms-modal-totals {
                max-width: none;
                margin-left: 22px;
            }

        }

        @media print {

            body * {
                visibility: hidden;
            }

            .pms-sale-modal,
            .pms-sale-modal * {
                visibility: visible;
            }

            .pms-sale-modal {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                max-width: none;
                max-height: none;
                box-shadow: none;
            }

            .pms-modal-actions {
                display: none;
            }

        }

    </style>

</div>