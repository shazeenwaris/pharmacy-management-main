<div class="pms-cashier-pos">

    {{-- ============================================================
        HEADER
    ============================================================= --}}

    <div class="pms-page-header">
        <div>
            <h1>Sales / POS</h1>
            <p>Process customer sales quickly and easily</p>
        </div>

        <div class="pms-pos-status">
            <span class="pms-status-dot"></span>
            POS Ready
        </div>
    </div>

{{-- ============================================================
    HELD SALES
============================================================= --}}

@if ($heldSales->count())

    <div class="pms-held-sales-card">

        <div class="pms-held-sales-header">

            <div>
                <h2>Held Sales</h2>

                <p>
                    Sales waiting to be resumed
                </p>
            </div>

            <span class="pms-held-count">
                {{ $heldSales->count() }}
            </span>

        </div>


        <div class="pms-held-sales-list">

            @foreach ($heldSales as $heldSale)

                <div
                    class="pms-held-sale"
                    wire:key="held-sale-{{ $heldSale->id }}"
                >

                    <div class="pms-held-main">

                        <div class="pms-held-icon">
                            ▣
                        </div>

                        <div>

                            <strong>
                                {{ $heldSale->hold_number }}
                            </strong>

                            <span>
                                {{ $heldSale->customer?->name ?? 'Walk-in Customer' }}
                            </span>

                            <small>
                                {{ count($heldSale->cart ?? []) }}
                                {{ count($heldSale->cart ?? []) === 1 ? 'item' : 'items' }}
                                •
                                {{ $heldSale->created_at?->format('d M Y h:i A') }}
                            </small>

                        </div>

                    </div>


                    <div class="pms-held-total">

                        <span>
                            Total
                        </span>

                        <strong>
                            {{ currency_symbol() }}
                            {{ number_format((float) $heldSale->grand_total, 2) }}
                        </strong>

                    </div>


                    <div class="pms-held-actions">

                        <button
                            type="button"
                            wire:click="resumeHeldSale({{ $heldSale->id }})"
                            class="pms-resume-btn"
                        >
                            Resume
                        </button>

                        <button
                            type="button"
                            wire:click="cancelHeldSale({{ $heldSale->id }})"
                            wire:confirm="Are you sure you want to remove this held sale?"
                            class="pms-cancel-held-btn"
                        >
                            ×
                        </button>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

@endif
    <div class="pms-pos-container">

        {{-- ========================================================
            MEDICINE SEARCH + CART
        ========================================================= --}}

        <div class="pms-pos-card">

            <div class="pms-card-header">
                <div>
                    <h2>Find Medicine</h2>
                    <p>Search by medicine name, generic name, SKU, barcode or batch</p>
                </div>

                <div class="pms-shortcut">
                    Scan / Enter
                </div>
            </div>


            {{-- SEARCH --}}

            <div class="pms-search-wrap">

                <svg
                    class="pms-search-icon"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
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
                    placeholder="Search medicine, generic name, SKU, barcode or batch..."
                    autocomplete="off"
                    class="pms-search-input"
                >

                @if ($search)
                    <button
                        type="button"
                        wire:click="$set('search', '')"
                        class="pms-clear-search"
                    >
                        ×
                    </button>
                @endif


                {{-- SEARCH RESULTS --}}

                {{-- SEARCH RESULTS --}}
@if ($search && $searchResults->count())

    <div class="pms-search-results">

        @foreach ($searchResults as $medicine)

            <button
                type="button"
                wire:click="addToCart({{ $medicine->id }})"
                class="pms-search-result"
            >

                {{-- MEDICINE --}}
                <div class="pms-result-main">

                    <div class="pms-result-icon">
                        💊
                    </div>

                    <div>
                        <div class="pms-result-name">
                            {{ $medicine->medicine?->name ?? 'Medicine not found' }}
                        </div>

                        @if ($medicine->medicine?->generic_name)
                            <div class="pms-result-generic">
                                {{ $medicine->medicine->generic_name }}
                            </div>
                        @endif
                    </div>

                </div>


                {{-- BATCH / STOCK / EXPIRY --}}
                <div class="pms-result-info">

                    <span>
                        Batch:
                        <strong>
                            {{ $medicine->batch_number }}
                        </strong>
                    </span>

                    <span>
                        Stock:
                        <strong>
                            {{ $medicine->quantity }}
                        </strong>
                    </span>

                    <span>
                        Exp:
                        <strong>
                            {{ \Carbon\Carbon::parse($medicine->expiry_date)->format('d M Y') }}
                        </strong>
                    </span>

                </div>


                {{-- PRICE + ADD --}}
                <div class="pms-result-price">

                    <span>
                        {{ currency_symbol() }}
                        {{ number_format((float) ($medicine->sale_price ?? 0), 2) }}
                    </span>

                    <span class="pms-add-icon">
                        +
                    </span>

                </div>

            </button>

        @endforeach

    </div>

@elseif ($search)

    <div class="pms-no-results">

        <div class="pms-no-results-icon">
            🔍
        </div>

        <strong>
            No medicine found
        </strong>

        <span>
            Try medicine name, generic name, SKU, barcode or batch number.
        </span>

    </div>

@endif

            </div>


            {{-- ====================================================
                CART
            ===================================================== --}}

            <div class="pms-cart-heading">

                <div>
                    <h3>Current Cart</h3>

                    <span>
                        {{ count($cart) }}
                        {{ count($cart) === 1 ? 'item' : 'items' }}
                    </span>
                </div>

                @if (count($cart))
                    <button
                        type="button"
                        wire:click="$set('cart', [])"
                        class="pms-clear-cart"
                    >
                        Clear Cart
                    </button>
                @endif

            </div>


            <div class="pms-table-wrap">

                <table class="pms-pos-table">

                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Batch</th>
                            <th>Expiry</th>
                            <th>Available</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>

                  <tbody>
    @forelse ($cart as $key => $item)

        <tr wire:key="cart-{{ $key }}">

            {{-- =====================================================
                MEDICINE
            ====================================================== --}}
            <td>
                <div class="pms-medicine-cell">

                    <div class="pms-medicine-icon">
                        💊
                    </div>

                    <div>
                        <strong>
                            {{ $item['name'] }}
                        </strong>

                        @if (!empty($item['generic_name']))
                            <small>
                                {{ $item['generic_name'] }}
                            </small>
                        @endif
                    </div>

                </div>
            </td>


            {{-- =====================================================
                BATCH
            ====================================================== --}}
            <td>
                <span class="pms-batch">
                    {{ $item['batch_number'] }}
                </span>
            </td>


            {{-- =====================================================
                EXPIRY
            ====================================================== --}}
            <td>
                {{ \Carbon\Carbon::parse($item['expiry_date'])->format('d M Y') }}
            </td>


            {{-- =====================================================
                AVAILABLE STOCK
            ====================================================== --}}
            <td>
                <span class="pms-stock">
                    {{ rtrim(rtrim(number_format(
                        (float) ($item['stock_quantity'] ?? 0),
                        3
                    ), '0'), '.') }}
                </span>

                <small class="pms-stock-base-label">
                    base units
                </small>
            </td>


            {{-- =====================================================
                QUANTITY
            ====================================================== --}}
            <td>
                <div class="pms-qty-control">

                    <button
                        type="button"
                        wire:click="decreaseQuantity('{{ $key }}')"
                    >
                        −
                    </button>

                    <span>
                        {{ $item['quantity'] }}
                    </span>

                    <button
                        type="button"
                        wire:click="increaseQuantity('{{ $key }}')"
                    >
                        +
                    </button>

                </div>
            </td>


            {{-- =====================================================
                SALE UNIT
            ====================================================== --}}
    <td>
    <div class="pms-unit-wrapper">
        <select
            class="pms-unit-select"
            wire:change="updateSaleUnit('{{ $key }}', $event.target.value)"
        >
            @foreach(($item['available_units'] ?? []) as $unit)
                <option
                    value="{{ $unit['unit_id'] }}"
                   @selected((int) ($item['unit_id'] ?? 0) === (int) $unit['unit_id'])
                >
                    {{ $unit['name'] }}
                </option>
            @endforeach
        </select>

        <div class="pms-conversion-info">
            1 {{ $item['sale_unit'] ?? 'Unit' }}
            =
            {{ rtrim(rtrim(number_format((float) ($item['conversion_factor'] ?? 1), 4), '0'), '.') }}
            base
        </div>
    </div>
</td>
            {{-- =====================================================
                PRICE
            ====================================================== --}}
            <td>
                <span class="pms-price">
                    {{ currency_symbol() }}
                    {{ number_format(
                        (float) ($item['price'] ?? 0),
                        2
                    ) }}
                </span>
            </td>


            {{-- =====================================================
                TOTAL
            ====================================================== --}}
            <td>
                <strong class="pms-line-total">
                    {{ currency_symbol() }}
                    {{ number_format(
                        (float) ($item['total'] ?? 0),
                        2
                    ) }}
                </strong>
            </td>


            {{-- =====================================================
                REMOVE
            ====================================================== --}}
            <td>
                <button
                    type="button"
                    wire:click="removeFromCart('{{ $key }}')"
                    class="pms-remove-btn"
                    title="Remove"
                >
                    ×
                </button>
            </td>

        </tr>

    @empty

        <tr>
            <td
                colspan="9"
                class="pms-empty"
            >
                <div class="pms-empty-content">

                    <div class="pms-empty-icon">
                        🛒
                    </div>

                    <strong>
                        Cart is empty
                    </strong>

                    <span>
                        Search for a medicine above to add it to the sale.
                    </span>

                </div>
            </td>
        </tr>

    @endforelse
</tbody>

                </table>

            </div>

        </div>


        {{-- ========================================================
            PAYMENT
        ========================================================= --}}

        <div class="pms-pos-card pms-payment-card">

            <div class="pms-card-header">

                <div>
                    <h2>Payment</h2>
                    <p>Customer and payment information</p>
                </div>

            </div>


            <div class="pms-payment-content">

                <div class="pms-payment-grid">

                    {{-- CUSTOMER --}}

                    <div class="pms-field">

                        <label>
                            Customer
                        </label>

                        <select
                            wire:model="customerId"
                            class="pms-input"
                        >

                            <option value="">
                                Walk-in Customer
                            </option>

                            @foreach ($customers as $customer)

                                <option value="{{ $customer->id }}">
                                    {{ $customer->name }}

                                    @if ($customer->phone)
                                        — {{ $customer->phone }}
                                    @endif
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- DISCOUNT --}}

                    <div class="pms-field">

                        <label>
                            Discount
                        </label>

                        <div class="pms-input-prefix">

                            <span>
                                {{ currency_symbol() }}
                            </span>

                            <input
                                type="number"
                                min="0"
                                step="0.01"
                                wire:model.live="discount"
                                class="pms-input"
                            >

                        </div>

                    </div>


                    {{-- TAX --}}

                    <div class="pms-field">

                        <label>
                            Tax
                        </label>

                        <div class="pms-input-prefix">

                            <span>
                                {{ currency_symbol() }}
                            </span>

                            <input
                                type="number"
                                min="0"
                                step="0.01"
                                wire:model.live="tax"
                                class="pms-input"
                            >

                        </div>

                    </div>


                    {{-- PAYMENT METHOD --}}

                    <div class="pms-field">

                        <label>
                            Payment Method
                        </label>

                        <select
                            wire:model="paymentMethod"
                            class="pms-input"
                        >

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


                {{-- PAID AMOUNT --}}

                <div class="pms-paid-section">

                    <div class="pms-field pms-paid-field">

                        <label>
                            Paid Amount
                        </label>

                        <input
                            type="number"
                            min="0"
                            step="0.01"
                            wire:model.live="paidAmount"
                            class="pms-input pms-paid-input"
                            placeholder="0.00"
                        >

                    </div>

                </div>


                {{-- TOTALS --}}

                <div class="pms-totals">

                    <div class="pms-total-row">
                        <span>Subtotal</span>

                        <strong>
                            {{ currency_symbol() }}
                            {{ number_format($totals['subtotal'], 2) }}
                        </strong>
                    </div>


                    <div class="pms-total-row">
                        <span>Discount</span>

                        <strong class="pms-discount">
                            − {{ currency_symbol() }}
                            {{ number_format($totals['discount'], 2) }}
                        </strong>
                    </div>


                    <div class="pms-total-row">
                        <span>Tax</span>

                        <strong>
                            + {{ currency_symbol() }}
                            {{ number_format($totals['tax'], 2) }}
                        </strong>
                    </div>


                    <div class="pms-grand-total-row">

                        <span>
                            Grand Total
                        </span>

                        <strong>
                            {{ currency_symbol() }}
                            {{ number_format($totals['grandTotal'], 2) }}
                        </strong>

                    </div>


                    <div class="pms-total-row pms-change-row">

                        <span>
                            Change
                        </span>

                        <strong>
                            {{ currency_symbol() }}
                            {{ number_format($totals['change'], 2) }}
                        </strong>

                    </div>


                    @if ($totals['due'] > 0)

                        <div class="pms-total-row pms-due-row">

                            <span>
                                Due
                            </span>

                            <strong>
                                {{ currency_symbol() }}
                                {{ number_format($totals['due'], 2) }}
                            </strong>

                        </div>

                    @endif

                </div>


                {{-- ACTIONS --}}

                <div class="pms-actions">

                    <button
                        type="button"
                        wire:click="holdSale"
                        class="pms-hold-btn"
                    >
                        <span>▣</span>
                        Hold Sale
                    </button>


                    <button
                        type="button"
                        wire:click="completeSale"
                        class="pms-complete-btn"
                    >
                        Complete Sale
                    </button>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        CSS
    ============================================================= --}}

    <style>

        .pms-cashier-pos {
            width: 100%;
            min-height: calc(100vh - 65px);
            background: #f4fafa;
            color: #17374a;
            box-sizing: border-box;
        }

        .pms-cashier-pos *,
        .pms-cashier-pos *::before,
        .pms-cashier-pos *::after {
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
            line-height: 1.3;
            font-weight: 800;
            color: #17374a;
        }

        .pms-page-header p {
            margin: 5px 0 0;
            color: #789097;
            font-size: 13px;
        }

        .pms-pos-status {
            display: flex;
            align-items: center;
            gap: 8px;

            padding: 8px 13px;

            border: 1px solid #d7eaea;
            border-radius: 999px;

            background: #ffffff;

            color: #087b7e;
            font-size: 12px;
            font-weight: 700;
        }

        .pms-status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #18a47a;
        }


        /* CONTAINER */

        .pms-pos-container {
            width: 100%;
            padding: 24px 28px 35px;
        }


        /* CARD */

        .pms-pos-card {
            width: 100%;

            background: #ffffff;

            border: 1px solid #dfeaea;
            border-radius: 12px;

            box-shadow:
                0 3px 12px rgba(23, 55, 74, 0.04);

            overflow: visible;
        }

        .pms-pos-card + .pms-pos-card {
            margin-top: 18px;
        }


        /* CARD HEADER */

        .pms-card-header {
            padding: 20px 22px 14px;

            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .pms-card-header h2 {
            margin: 0;
            font-size: 17px;
            font-weight: 800;
            color: #17374a;
        }

        .pms-card-header p {
            margin: 5px 0 0;
            color: #83949a;
            font-size: 12px;
        }

        .pms-shortcut {
            padding: 7px 10px;

            border-radius: 6px;

            background: #f3f8f8;
            color: #6e8389;

            font-size: 11px;
            font-weight: 600;
        }


        /* SEARCH */

        .pms-search-wrap {
            position: relative;
            margin: 0 22px 20px;
        }

        .pms-search-input {
            display: block;

            width: 100%;
            height: 52px;

            padding: 0 50px 0 48px;

            border: 1px solid #cfdede;
            border-radius: 9px;

            outline: none;

            background: #ffffff;
            color: #17374a;

            font-size: 14px;

            transition: all .15s ease;
        }

        .pms-search-input::placeholder {
            color: #9aa9ae;
        }

        .pms-search-input:focus {
            border-color: #087b7e;

            box-shadow:
                0 0 0 3px rgba(8, 123, 126, .08);
        }

        .pms-search-icon {
            position: absolute;

            left: 17px;
            top: 50%;

            width: 19px;
            height: 19px;

            transform: translateY(-50%);

            color: #718b91;

            pointer-events: none;

            z-index: 3;
        }

        .pms-clear-search {
            position: absolute;

            right: 15px;
            top: 50%;

            transform: translateY(-50%);

            width: 28px;
            height: 28px;

            border: 0;
            border-radius: 50%;

            background: #edf4f4;
            color: #71858b;

            cursor: pointer;

            font-size: 18px;
            line-height: 1;
        }


        /* SEARCH RESULTS */

        .pms-search-results {
            position: absolute;

            left: 0;
            right: 0;
            top: calc(100% + 7px);

            z-index: 50;

            background: #ffffff;

            border: 1px solid #d7e5e5;
            border-radius: 10px;

            box-shadow:
                0 12px 35px rgba(23, 55, 74, .13);

            overflow: hidden;
        }

        .pms-search-result {
            width: 100%;

            padding: 13px 15px;

            display: grid;

            grid-template-columns: 1.5fr 2fr auto;

            gap: 16px;

            align-items: center;

            border: 0;
            border-bottom: 1px solid #edf2f2;

            background: #ffffff;

            text-align: left;

            cursor: pointer;

            transition: background .12s ease;
        }

        .pms-search-result:last-child {
            border-bottom: 0;
        }

        .pms-search-result:hover {
            background: #f4fafa;
        }

        .pms-result-main {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .pms-result-icon {
            width: 38px;
            height: 38px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 8px;

            background: #eaf7f7;

            font-size: 18px;
        }

        .pms-result-name {
            font-size: 13px;
            font-weight: 800;
            color: #17374a;
        }

        .pms-result-generic {
            margin-top: 3px;

            color: #83959b;
            font-size: 11px;
        }

        .pms-result-info {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;

            color: #7a8d93;
            font-size: 11px;
        }

        .pms-result-info strong {
            color: #36545f;
        }

        .pms-result-price {
            display: flex;
            align-items: center;
            gap: 12px;

            color: #087b7e;
            font-size: 14px;
            font-weight: 800;
        }

        .pms-add-icon {
            width: 30px;
            height: 30px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 7px;

            background: #087b7e;
            color: #ffffff;

            font-size: 19px;
        }

        .pms-no-results {
            position: absolute;

            left: 0;
            right: 0;
            top: calc(100% + 7px);

            z-index: 50;

            padding: 28px;

            display: flex;
            flex-direction: column;
            align-items: center;

            gap: 5px;

            background: #ffffff;

            border: 1px solid #d7e5e5;
            border-radius: 10px;

            box-shadow:
                0 12px 35px rgba(23, 55, 74, .12);
        }

        .pms-no-results-icon {
            font-size: 25px;
            margin-bottom: 5px;
        }

        .pms-no-results strong {
            color: #36545f;
            font-size: 13px;
        }

        .pms-no-results span {
            color: #91a0a5;
            font-size: 11px;
        }


        /* CART HEADER */

        .pms-cart-heading {
            padding: 0 22px 12px;

            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .pms-cart-heading > div {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .pms-cart-heading h3 {
            margin: 0;

            font-size: 14px;
            font-weight: 800;
            color: #36545f;
        }

        .pms-cart-heading span {
            padding: 3px 8px;

            border-radius: 999px;

            background: #edf7f7;
            color: #087b7e;

            font-size: 10px;
            font-weight: 700;
        }

        .pms-clear-cart {
            border: 0;
            background: transparent;

            color: #b04d4d;

            font-size: 11px;
            font-weight: 700;

            cursor: pointer;
        }


        /* TABLE */

        .pms-table-wrap {
            width: 100%;
            overflow-x: auto;

            border-top: 1px solid #edf2f2;
        }

        .pms-pos-table {
            width: 100%;
            min-width: 900px;

            border-collapse: collapse;
        }

        .pms-pos-table thead {
            background: #f7fafa;
        }

        .pms-pos-table th {
            padding: 13px 16px;

            border-bottom: 1px solid #e2ebeb;

            color: #70858c;

            font-size: 11px;
            font-weight: 800;

            text-align: left;
            text-transform: uppercase;

            letter-spacing: .03em;
            white-space: nowrap;
        }

        .pms-pos-table td {
            padding: 14px 16px;

            border-bottom: 1px solid #edf2f2;

            color: #526b74;

            font-size: 13px;

            vertical-align: middle;
        }

        .pms-medicine-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .pms-medicine-icon {
            width: 36px;
            height: 36px;

            display: flex;
            align-items: center;
            justify-content: center;

            flex: 0 0 auto;

            border-radius: 8px;

            background: #edf8f8;
        }

        .pms-medicine-cell strong {
            display: block;

            color: #17374a;
            font-size: 13px;
            font-weight: 800;
        }

        .pms-medicine-cell small {
            display: block;

            margin-top: 3px;

            color: #8b9ca1;
            font-size: 10px;
        }

        .pms-batch {
            padding: 5px 8px;

            border-radius: 5px;

            background: #f2f6f6;

            color: #526b74;

            font-size: 11px;
            font-weight: 700;
        }

        .pms-stock {
            font-weight: 700;
            color: #087b7e;
        }
.pms-unit-select {
    min-width: 105px;
    height: 36px;
    padding: 0 30px 0 10px;
    border: 1px solid #d6e4e4;
    border-radius: 7px;
    outline: none;
    background: #ffffff;
    color: #17374a;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
}

.pms-unit-select:focus {
    border-color: #087b7e;
    box-shadow:
        0 0 0 3px rgba(8, 123, 126, .07);
}

.pms-conversion-info {
    display: block;
    margin-top: 4px;
    color: #91a0a5;
    font-size: 9px;
    white-space: nowrap;
}

.pms-stock-base-label {
    display: block;
    margin-top: 3px;
    color: #9aa8ac;
    font-size: 9px;
}
        .pms-line-total {
            color: #17374a;
            font-size: 13px;
        }


        /* QUANTITY */

        .pms-qty-control {
            display: inline-flex;
            align-items: center;

            height: 36px;

            border: 1px solid #d6e4e4;
            border-radius: 7px;

            overflow: hidden;
        }

        .pms-qty-control button {
            width: 34px;
            height: 100%;

            border: 0;

            background: #f5f9f9;

            color: #087b7e;

            font-size: 17px;
            font-weight: 700;

            cursor: pointer;
        }

        .pms-qty-control button:hover {
            background: #eaf6f6;
        }

        .pms-qty-control span {
            width: 34px;

            text-align: center;

            color: #17374a;

            font-size: 13px;
            font-weight: 800;
        }

        .pms-remove-btn {
            width: 30px;
            height: 30px;

            border: 0;
            border-radius: 6px;

            background: #fff3f3;

            color: #c25454;

            font-size: 20px;
            line-height: 1;

            cursor: pointer;
        }

        .pms-remove-btn:hover {
            background: #ffe7e7;
        }


        /* EMPTY */

        .pms-empty {
            height: 190px;

            text-align: center !important;
        }

        .pms-empty-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;

            gap: 6px;
        }

        .pms-empty-icon {
            width: 52px;
            height: 52px;

            display: flex;
            align-items: center;
            justify-content: center;

            margin-bottom: 5px;

            border-radius: 50%;

            background: #f1f7f7;

            font-size: 23px;
        }

        .pms-empty-content strong {
            color: #526b74;
            font-size: 14px;
        }

        .pms-empty-content span {
            color: #9aa8ac;
            font-size: 12px;
        }


        /* PAYMENT */

        .pms-payment-content {
            padding: 0 22px 22px;
        }

        .pms-payment-grid {
            display: grid;

            grid-template-columns:
                repeat(4, minmax(0, 1fr));

            gap: 16px;
        }

        .pms-field label {
            display: block;

            margin-bottom: 7px;

            color: #61767d;

            font-size: 12px;
            font-weight: 700;
        }

        .pms-input {
            display: block;

            width: 100%;
            height: 44px;

            padding: 0 12px;

            border: 1px solid #d3e1e1;
            border-radius: 7px;

            outline: none;

            background: #ffffff;

            color: #17374a;

            font-size: 13px;

            transition: all .15s ease;
        }

        .pms-input:focus {
            border-color: #087b7e;

            box-shadow:
                0 0 0 3px rgba(8, 123, 126, .07);
        }

        .pms-input-prefix {
            position: relative;
        }

        .pms-input-prefix > span {
            position: absolute;

            left: 12px;
            top: 50%;

            transform: translateY(-50%);

            color: #7d9197;

            font-size: 12px;

            z-index: 2;
        }

        .pms-input-prefix .pms-input {
            padding-left: 30px;
        }


        /* PAID */

        .pms-paid-section {
            margin-top: 18px;
        }

        .pms-paid-field {
            width: 25%;
        }

        .pms-paid-input {
            height: 46px;

            font-size: 15px;
            font-weight: 700;
        }


        /* TOTALS */

        .pms-totals {
            width: 100%;

            max-width: 480px;

            margin: 22px 0 0 auto;

            padding: 16px 18px;

            border: 1px solid #e0ebeb;
            border-radius: 9px;

            background: #f9fbfb;
        }

        .pms-total-row {
            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 6px 0;

            color: #73868c;
            font-size: 13px;
        }

        .pms-total-row strong {
            color: #36545f;
            font-weight: 700;
        }

        .pms-discount {
            color: #c45a5a !important;
        }

        .pms-grand-total-row {
            display: flex;
            align-items: center;
            justify-content: space-between;

            margin-top: 8px;
            padding-top: 13px;

            border-top: 1px solid #dce7e7;

            color: #17374a;

            font-size: 15px;
            font-weight: 800;
        }

        .pms-grand-total-row strong {
            color: #087b7e;

            font-size: 19px;
        }

        .pms-change-row {
            margin-top: 5px;
        }

        .pms-change-row strong {
            color: #087b7e;
        }

        .pms-due-row strong {
            color: #c05b4f;
        }


        /* ACTIONS */

        .pms-actions {
            display: grid;

            grid-template-columns: 1fr 2fr;

            gap: 12px;

            margin-top: 20px;
        }

        .pms-hold-btn,
        .pms-complete-btn {
            min-height: 48px;

            border-radius: 8px;

            font-size: 14px;
            font-weight: 800;

            cursor: pointer;

            transition: all .15s ease;
        }

        .pms-hold-btn {
            border: 1px solid #d4c08a;

            background: #fffdf6;

            color: #a07300;
        }

        .pms-hold-btn:hover {
            background: #fff8df;
        }

        .pms-complete-btn {
            border: 0;

            background: #087b7e;

            color: #ffffff;

            box-shadow:
                0 4px 10px rgba(8, 123, 126, .15);
        }

        .pms-complete-btn:hover {
            background: #066c6f;
        }


        /* RESPONSIVE */

        @media (max-width: 1100px) {

            .pms-payment-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .pms-paid-field {
                width: 50%;
            }

            .pms-search-result {
                grid-template-columns: 1fr auto;
            }

            .pms-result-info {
                display: none;
            }

        }


        @media (max-width: 700px) {

            .pms-page-header {
                padding: 18px 15px;
            }

            .pms-page-header p {
                display: none;
            }

            .pms-pos-container {
                padding: 15px;
            }

            .pms-card-header {
                padding: 16px;
            }

            .pms-search-wrap {
                margin-left: 16px;
                margin-right: 16px;
            }

            .pms-payment-content {
                padding-left: 16px;
                padding-right: 16px;
            }

            .pms-payment-grid {
                grid-template-columns: 1fr;
            }

            .pms-paid-field {
                width: 100%;
            }

            .pms-actions {
                grid-template-columns: 1fr;
            }

            .pms-totals {
                max-width: 100%;
            }

            .pms-shortcut,
            .pms-pos-status {
                display: none;
            }
        }
/* ============================================================
   HELD SALES
============================================================ */

.pms-held-sales-card {
    width: 100%;
    margin: 0 0 18px;

    background: #ffffff;

    border: 1px solid #dfeaea;
    border-radius: 12px;

    box-shadow:
        0 3px 12px rgba(23, 55, 74, 0.04);

    overflow: hidden;
}

.pms-held-sales-header {
    padding: 17px 22px;

    display: flex;
    align-items: center;
    justify-content: space-between;

    border-bottom: 1px solid #edf2f2;
}

.pms-held-sales-header h2 {
    margin: 0;

    color: #17374a;

    font-size: 15px;
    font-weight: 800;
}

.pms-held-sales-header p {
    margin: 4px 0 0;

    color: #83949a;

    font-size: 11px;
}

.pms-held-count {
    min-width: 28px;
    height: 28px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    padding: 0 9px;

    border-radius: 999px;

    background: #eaf7f7;
    color: #087b7e;

    font-size: 11px;
    font-weight: 800;
}

.pms-held-sales-list {
    width: 100%;
}

.pms-held-sale {
    padding: 14px 22px;

    display: grid;

    grid-template-columns:
        minmax(260px, 1fr)
        auto
        auto;

    gap: 22px;

    align-items: center;

    border-bottom: 1px solid #edf2f2;
}

.pms-held-sale:last-child {
    border-bottom: 0;
}

.pms-held-main {
    display: flex;
    align-items: center;

    gap: 11px;
}

.pms-held-icon {
    width: 38px;
    height: 38px;

    flex: 0 0 auto;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 8px;

    background: #eaf7f7;
    color: #087b7e;

    font-size: 17px;
}

.pms-held-main strong {
    display: block;

    color: #17374a;

    font-size: 12px;
    font-weight: 800;
}

.pms-held-main span {
    display: block;

    margin-top: 3px;

    color: #526b74;

    font-size: 11px;
}

.pms-held-main small {
    display: block;

    margin-top: 3px;

    color: #91a0a5;

    font-size: 10px;
}

.pms-held-total {
    text-align: right;
}

.pms-held-total span {
    display: block;

    color: #91a0a5;

    font-size: 10px;
}

.pms-held-total strong {
    display: block;

    margin-top: 3px;

    color: #087b7e;

    font-size: 13px;
    font-weight: 800;
}

.pms-held-actions {
    display: flex;
    align-items: center;

    gap: 7px;
}

.pms-resume-btn {
    min-width: 75px;

    padding: 8px 12px;

    border: 0;
    border-radius: 7px;

    background: #087b7e;
    color: #ffffff;

    font-size: 11px;
    font-weight: 800;

    cursor: pointer;

    transition: all .15s ease;
}

.pms-resume-btn:hover {
    opacity: .9;
}

.pms-cancel-held-btn {
    width: 31px;
    height: 31px;

    display: flex;
    align-items: center;
    justify-content: center;

    border: 1px solid #eadede;
    border-radius: 7px;

    background: #ffffff;
    color: #b04d4d;

    font-size: 18px;

    cursor: pointer;

    transition: all .15s ease;
}

.pms-cancel-held-btn:hover {
    background: #fff6f6;
}


/* ============================================================
   HELD SALES RESPONSIVE
============================================================ */

@media (max-width: 900px) {

    .pms-held-sale {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .pms-held-total {
        text-align: left;
    }

    .pms-held-actions {
        justify-content: flex-start;
    }
}
    </style>

</div>