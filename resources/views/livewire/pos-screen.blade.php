<div x-data="{}" x-on:focus-search.window="$refs.searchInput.focus()">

    {{-- Find Medicine (full width) --}}
    <div class="mp-card" style="padding: 1.5rem; border-radius: 0.75rem; border: 1px solid #E2EEEE; background: white; margin-bottom: 1.5rem;">
        <h3 style="margin: 0 0 1rem 0; font-size: 1.05rem; font-weight: 600; color: #123047;">Find Medicine</h3>

        <div style="position: relative;">
            <input
                type="text"
                x-ref="searchInput"
                wire:model.live.debounce.300ms="search"
                wire:keydown.enter="scanBarcode"
                placeholder="Scan barcode, or search by name, generic name or SKU..."
                style="width: 100%; padding: 0.7rem 5.5rem 0.7rem 1rem; border-radius: 0.5rem; border: 1px solid #D7E4E4; background: #F9FBFB; color: #123047; font-size: 0.95rem;"
                autofocus
            >
            <span style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #A9B7BC; font-size: 0.75rem; white-space: nowrap;">
                ⌨ scan or press Enter
            </span>
        </div>

        @if ($search && count($results))
            <div style="margin-top: 0.5rem; border: 1px solid #E2EEEE; border-radius: 0.5rem; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                @foreach ($results as $medicine)
                    <div
                        wire:key="search-result-{{ $medicine->id }}"
                        wire:click="addToCart({{ $medicine->id }})"
                        style="padding: 0.75rem 1rem; cursor: pointer; border-bottom: 1px solid #EEF2F3; display: flex; justify-content: space-between; align-items: center;"
                        class="hover:bg-gray-50"
                    >
                        <span>
                            <strong style="color: #123047;">{{ $medicine->name }}</strong>
                            <span style="color: #718792; font-size: 0.85rem;">({{ $medicine->generic_name }})</span>
                            @if ($medicine->barcode)
                                <span style="color: #A9B7BC; font-size: 0.75rem;">&middot; {{ $medicine->barcode }}</span>
                            @endif
                        </span>
                        <span style="font-weight: 600; color: #0F7B6C;">{{ currency_symbol() }} {{ number_format($medicine->sale_price, 2) }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        <div style="margin-top: 1.5rem; border: 1px solid #E2EEEE; border-radius: 0.5rem; overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="text-align: left; background: #F9FBFB; border-bottom: 1px solid #E2EEEE;">
                        <th style="padding: 0.65rem 0.75rem; font-size: 0.8rem; color: #718792; text-transform: uppercase; letter-spacing: 0.03em;">Medicine</th>
                        <th style="padding: 0.65rem 0.75rem; font-size: 0.8rem; color: #718792; text-transform: uppercase; letter-spacing: 0.03em;">Batch</th>
                        <th style="padding: 0.65rem 0.75rem; font-size: 0.8rem; color: #718792; text-transform: uppercase; letter-spacing: 0.03em;">Expiry</th>
                        <th style="padding: 0.65rem 0.75rem; font-size: 0.8rem; color: #718792; text-transform: uppercase; letter-spacing: 0.03em;">Qty</th>
                        <th style="padding: 0.65rem 0.75rem; font-size: 0.8rem; color: #718792; text-transform: uppercase; letter-spacing: 0.03em;">Price</th>
                        <th style="padding: 0.65rem 0.75rem; font-size: 0.8rem; color: #718792; text-transform: uppercase; letter-spacing: 0.03em;">Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cart as $batchId => $item)
                        <tr style="border-bottom: 1px solid #EEF2F3;">
                            <td style="padding: 0.65rem 0.75rem; color: #123047; font-weight: 500;">{{ $item['medicine_name'] }}</td>
                            <td style="padding: 0.65rem 0.75rem; color: #718792;">{{ $item['batch_number'] }}</td>
                            <td style="padding: 0.65rem 0.75rem; color: #718792;">{{ $item['expiry_date'] }}</td>
                            <td style="padding: 0.65rem 0.75rem;">
                                <input
                                    type="number"
                                    min="1"
                                    max="{{ $item['available_qty'] }}"
                                    value="{{ $item['quantity'] }}"
                                    wire:change="updateQuantity({{ $batchId }}, $event.target.value)"
                                    style="width: 60px; border: 1px solid #E2EEEE; border-radius: 4px; padding: 4px 6px;"
                                >
                            </td>
                            <td style="padding: 0.65rem 0.75rem; color: #123047;">{{ currency_symbol() }} {{ number_format($item['unit_price'], 2) }}</td>
                            <td style="padding: 0.65rem 0.75rem; color: #123047; font-weight: 600;">{{ currency_symbol() }} {{ number_format($item['unit_price'] * $item['quantity'], 2) }}</td>
                            <td style="padding: 0.65rem 0.75rem; text-align: right;">
                                <button wire:click="removeFromCart({{ $batchId }})" style="color: #DC4C4C; background: none; border: none; cursor: pointer; font-size: 1.1rem;">✕</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" style="padding: 2rem; text-align: center; color: #A9B7BC;">Cart is empty — search or scan above to add medicines</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Payment (full width) --}}
    <div class="mp-card" style="padding: 1.5rem; border-radius: 0.75rem; border: 1px solid #E2EEEE; background: white;">
        <h3 style="margin: 0 0 1rem 0; font-size: 1.05rem; font-weight: 600; color: #123047;">Payment</h3>

        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem;">
            <div>
                <label style="display: block; font-size: 0.85rem; color: #718792; margin-bottom: 0.25rem;">Customer</label>
                <select wire:model="customerId" style="width: 100%; border: 1px solid #E2EEEE; border-radius: 0.5rem; padding: 0.5rem 0.75rem; background: white;">
                    <option value="">Walk-in Customer</option>
                    @foreach ($this->customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->phone }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="display: block; font-size: 0.85rem; color: #718792; margin-bottom: 0.25rem;">Discount</label>
                <input type="number" wire:model.live="discount" style="width: 100%; border: 1px solid #E2EEEE; border-radius: 0.5rem; padding: 0.5rem 0.75rem;">
            </div>

            <div>
                <label style="display: block; font-size: 0.85rem; color: #718792; margin-bottom: 0.25rem;">Tax</label>
                <input type="number" wire:model.live="tax" style="width: 100%; border: 1px solid #E2EEEE; border-radius: 0.5rem; padding: 0.5rem 0.75rem;">
            </div>

            <div>
                <label style="display: block; font-size: 0.85rem; color: #718792; margin-bottom: 0.25rem;">Payment Method</label>
                <select wire:model="paymentMethod" style="width: 100%; border: 1px solid #E2EEEE; border-radius: 0.5rem; padding: 0.5rem 0.75rem; background: white;">
                    <option value="cash">Cash</option>
                    <option value="card">Card</option>
                    <option value="bank">Bank Transfer</option>
                    <option value="other">Other</option>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-top: 1rem;">
            <div>
                <label style="display: block; font-size: 0.85rem; color: #718792; margin-bottom: 0.25rem;">Paid Amount</label>
                <input type="number" wire:model.live="paidAmount" style="width: 100%; border: 1px solid #E2EEEE; border-radius: 0.5rem; padding: 0.5rem 0.75rem;">
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 2.5rem; margin-top: 1.25rem; padding-top: 1rem; border-top: 1px dashed #E2EEEE;">
            <div style="text-align: right;">
                <div style="color: #718792; font-size: 0.85rem;">Subtotal</div>
                <div style="color: #123047; font-weight: 500;">{{ currency_symbol() }} {{ number_format($this->subtotal, 2) }}</div>
            </div>
            <div style="text-align: right;">
                <div style="color: #718792; font-size: 0.85rem;">Grand Total</div>
                <div style="color: #123047; font-weight: 700; font-size: 1.15rem;">{{ currency_symbol() }} {{ number_format($this->grandTotal, 2) }}</div>
            </div>
            <div style="text-align: right;">
                <div style="color: #718792; font-size: 0.85rem;">Change</div>
                <div style="color: #123047; font-weight: 500;">{{ currency_symbol() }} {{ number_format($this->change, 2) }}</div>
            </div>
        </div>

        <button wire:click="completeSale" style="width: 100%; margin-top: 1.25rem; padding: 0.75rem; background: #00707A; color: white; border: none; border-radius: 0.5rem; font-weight: 600; font-size: 0.95rem; cursor: pointer;">
            Complete Sale
        </button>
    </div>
</div>