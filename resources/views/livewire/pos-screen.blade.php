<div class="pos-wrapper" x-data="{}" x-on:focus-search.window="$refs.searchInput.focus()">
    <div class="pos-grid">

        {{-- LEFT: Search + Cart --}}
        <div>
            <div class="pos-search-wrap">
                <input
                    type="text"
                    x-ref="searchInput"
                    wire:model.live.debounce.300ms="search"
                    wire:keydown.enter="scanBarcode"
                    placeholder="Scan barcode, or search by name, generic name or SKU..."
                    autofocus
                    class="pos-search-input"
                >
                <span class="pos-search-hint">⌨ scan or press Enter</span>
            </div>

            @if ($search && count($results))
                <div class="pos-results">
                    @foreach ($results as $medicine)
                        <div
                            wire:key="search-result-{{ $medicine->id }}"
                            wire:click="addToCart({{ $medicine->id }})"
                            class="pos-result-item"
                        >
                            <strong>{{ $medicine->name }}</strong>
                            <span class="pos-muted">({{ $medicine->generic_name }})</span>
                            — {{ currency_symbol() }} {{ number_format($medicine->sale_price, 2) }}
                            @if ($medicine->barcode)
                                <span class="pos-muted"> · {{ $medicine->barcode }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="pos-table-wrap">
                <table class="pos-table">
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Batch</th>
                            <th>Expiry</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cart as $batchId => $item)
                            <tr>
                                <td class="pos-strong">{{ $item['medicine_name'] }}</td>
                                <td>{{ $item['batch_number'] }}</td>
                                <td>{{ $item['expiry_date'] }}</td>
                                <td>
                                    <input
                                        type="number"
                                        min="1"
                                        max="{{ $item['available_qty'] }}"
                                        value="{{ $item['quantity'] }}"
                                        wire:change="updateQuantity({{ $batchId }}, $event.target.value)"
                                        class="pos-qty-input"
                                    >
                                </td>
                                <td>{{ currency_symbol() }} {{ number_format($item['unit_price'], 2) }}</td>
                                <td class="pos-strong">{{ currency_symbol() }} {{ number_format($item['unit_price'] * $item['quantity'], 2) }}</td>
                                <td>
                                    <button wire:click="removeFromCart({{ $batchId }})" class="pos-remove-btn">✕</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="pos-empty">Cart is empty</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- RIGHT: Payment Panel --}}
        <div class="pos-payment-panel">
            <h3 class="pos-payment-title">Payment</h3>

            <label class="pos-label">Customer</label>
            <select wire:model="customerId" class="pos-select">
                <option value="">Walk-in Customer</option>
                @foreach ($this->customers as $customer)
                    <option value="{{ $customer->id }}">
                        {{ $customer->name }}{{ $customer->phone ? ' — ' . $customer->phone : '' }}
                    </option>
                @endforeach
            </select>

            <div class="pos-row">
                <span class="pos-muted">Subtotal</span>
                <span class="pos-strong">{{ currency_symbol() }} {{ number_format($this->subtotal, 2) }}</span>
            </div>

            <label class="pos-label">Discount</label>
            <input type="number" wire:model.live="discount" class="pos-input">

            <label class="pos-label">Tax</label>
            <input type="number" wire:model.live="tax" class="pos-input">

            <div class="pos-total-box">
                <span>Grand Total</span>
                <span class="pos-total-amount">{{ currency_symbol() }} {{ number_format($this->grandTotal, 2) }}</span>
            </div>

            <label class="pos-label">Payment Method</label>
            <select wire:model="paymentMethod" class="pos-select">
                <option value="cash">Cash</option>
                <option value="card">Card</option>
                <option value="bank">Bank Transfer</option>
                <option value="other">Other</option>
            </select>

            <label class="pos-label">Paid Amount</label>
            <input type="number" wire:model.live="paidAmount" class="pos-input">

            <div class="pos-row" style="margin-bottom: 16px;">
                <span class="pos-muted">Change</span>
                <span class="pos-strong">{{ currency_symbol() }} {{ number_format($this->change, 2) }}</span>
            </div>

            <button wire:click="completeSale" class="pos-complete-btn">
                Complete Sale
            </button>
        </div>
    </div>

    <style>
        .pos-wrapper {
            background: white;
            border-radius: 14px;
            border: 1px solid #e5e7eb;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }

        .pos-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 24px;
        }

        @media (max-width: 900px) {
            .pos-grid { grid-template-columns: 1fr; }
        }

        .pos-search-wrap { position: relative; }

        .pos-search-input {
            width: 100%;
            padding: 12px 16px;
            border-radius: 10px;
            border: 1.5px solid #d1e0e0;
            background: white;
            color: #123047;
            font-size: 14px;
            outline: none;
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .pos-search-input:focus {
            border-color: #08766e;
            box-shadow: 0 0 0 3px rgba(8,118,110,0.12);
        }

        .pos-search-hint {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 11px;
            color: #9ca3af;
            pointer-events: none;
        }

        .pos-results {
            margin-top: 8px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
        }

        .pos-result-item {
            padding: 12px 16px;
            cursor: pointer;
            border-bottom: 1px solid #f1f5f5;
            font-size: 13.5px;
            transition: background .12s ease;
        }

        .pos-result-item:last-child { border-bottom: none; }
        .pos-result-item:hover { background: #f0fdfa; }

        .pos-muted { color: #78909c; font-size: 12px; }

        .pos-table-wrap {
            margin-top: 24px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
        }

        .pos-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
        }

        .pos-table thead tr {
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }

        .pos-table th {
            text-align: left;
            padding: 12px 16px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #6b7280;
            font-weight: 600;
        }

        .pos-table td {
            padding: 12px 16px;
            border-bottom: 1px solid #f1f5f5;
            color: #374151;
        }

        .pos-table tbody tr:last-child td { border-bottom: none; }

        .pos-strong { font-weight: 600; color: #17374a; }

        .pos-qty-input {
            width: 60px;
            border: 1px solid #d1e0e0;
            border-radius: 6px;
            padding: 4px 6px;
            font-size: 13px;
        }

        .pos-remove-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 6px;
            color: #9ca3af;
            background: transparent;
            border: none;
            cursor: pointer;
            transition: all .15s ease;
        }

        .pos-remove-btn:hover { background: #fee2e2; color: #dc2626; }

        .pos-empty {
            text-align: center;
            padding: 32px;
            color: #9ca3af;
        }

        .pos-payment-panel {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
        }

        .pos-payment-title {
            font-size: 15px;
            font-weight: 700;
            color: #17374a;
            margin: 0 0 16px;
        }

        .pos-label {
            display: block;
            font-size: 11.5px;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 5px;
        }

        .pos-input, .pos-select {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #d1e0e0;
            border-radius: 8px;
            font-size: 13.5px;
            color: #17374a;
            background: white;
            margin-bottom: 14px;
            outline: none;
        }

        .pos-input:focus, .pos-select:focus {
            border-color: #08766e;
            box-shadow: 0 0 0 3px rgba(8,118,110,0.12);
        }

        .pos-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 13.5px;
        }

        .pos-total-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #e6f7f5;
            border: 1px solid #b8e6e0;
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 14px;
            font-weight: 600;
            color: #17374a;
        }

        .pos-total-amount {
            font-size: 18px;
            font-weight: 800;
            color: #08766e;
        }

        .pos-complete-btn {
            width: 100%;
            padding: 13px;
            border-radius: 10px;
            border: none;
            background: #08766e;
            color: white;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            transition: background .15s ease, transform .1s ease;
        }

        .pos-complete-btn:hover { background: #066a62; }
        .pos-complete-btn:active { transform: translateY(1px); }
    </style>
</div>