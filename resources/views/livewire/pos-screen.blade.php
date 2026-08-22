<div class="mp-card" style="padding: 1.5rem;">
    <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 1.5rem;">

   
        {{-- LEFT: Search + Cart --}}
        <div>
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Search by name, generic, barcode or SKU..."
                class="mp-btn-primary"
                style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #E2EEEE; background: white; color: #123047;"
            >

            @if ($search && count($results))
                <div style="margin-top: 0.5rem; border: 1px solid #E2EEEE; border-radius: 0.5rem; overflow: hidden;">
                    @foreach ($results as $medicine)
                        <div
                            wire:key="search-result-{{ $medicine->id }}"
                            wire:click="addToCart({{ $medicine->id }})"
                            style="padding: 0.75rem; cursor: pointer; border-bottom: 1px solid #EEF2F3;"
                            class="hover:bg-gray-50"
                        >
                            <strong>{{ $medicine->name }}</strong> ({{ $medicine->generic_name }})
                            — {{ currency_symbol() }} {{ number_format($medicine->sale_price, 2) }}
                        </div>
                    @endforeach
                </div>
            @endif

            <table style="width: 100%; margin-top: 1.5rem; border-collapse: collapse;">
                <thead>
                    <tr style="text-align: left; border-bottom: 2px solid #E2EEEE;">
                        <th style="padding: 0.5rem;">Medicine</th>
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
                        <tr style="border-bottom: 1px solid #EEF2F3;">
                            <td style="padding: 0.5rem;">{{ $item['medicine_name'] }}</td>
                            <td>{{ $item['batch_number'] }}</td>
                            <td>{{ $item['expiry_date'] }}</td>
                            <td>
                                <input
                                    type="number"
                                    min="1"
                                    max="{{ $item['available_qty'] }}"
                                    value="{{ $item['quantity'] }}"
                                    wire:change="updateQuantity({{ $batchId }}, $event.target.value)"
                                    style="width: 60px; border: 1px solid #E2EEEE; border-radius: 4px; padding: 2px;"
                                >
                            </td>
                            <td>{{ currency_symbol() }} {{ number_format($item['unit_price'], 2) }}</td>
                            <td>{{ currency_symbol() }} {{ number_format($item['unit_price'] * $item['quantity'], 2) }}</td>
                            <td>
                                <button wire:click="removeFromCart({{ $batchId }})" style="color: #DC4C4C;">✕</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" style="padding: 1rem; text-align: center; color: #718792;">Cart is empty</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- RIGHT: Payment Panel --}}
        <div class="mp-card">
            <h3 class="mp-card-title" style="margin-bottom: 1rem;">Payment</h3>

            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span class="mp-text-muted">Subtotal</span>
                <span>{{ currency_symbol() }} {{ number_format($this->subtotal, 2) }}</span>
            </div>

            <label class="mp-text-muted">Discount</label>
            <input type="number" wire:model.live="discount" style="width: 100%; margin-bottom: 0.5rem; border: 1px solid #E2EEEE; border-radius: 4px; padding: 4px;">

            <label class="mp-text-muted">Tax</label>
            <input type="number" wire:model.live="tax" style="width: 100%; margin-bottom: 0.5rem; border: 1px solid #E2EEEE; border-radius: 4px; padding: 4px;">

            <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 1.1rem; margin: 0.75rem 0;">
                <span>Grand Total</span>
                <span>{{ currency_symbol() }} {{ number_format($this->grandTotal, 2) }}</span>
            </div>

            <label class="mp-text-muted">Payment Method</label>
            <select wire:model="paymentMethod" style="width: 100%; margin-bottom: 0.5rem; border: 1px solid #E2EEEE; border-radius: 4px; padding: 4px;">
                <option value="cash">Cash</option>
                <option value="card">Card</option>
                <option value="bank">Bank Transfer</option>
                <option value="other">Other</option>
            </select>

            <label class="mp-text-muted">Paid Amount</label>
            <input type="number" wire:model.live="paidAmount" style="width: 100%; margin-bottom: 0.5rem; border: 1px solid #E2EEEE; border-radius: 4px; padding: 4px;">

            <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                <span class="mp-text-muted">Change</span>
                <span>{{ currency_symbol() }} {{ number_format($this->change, 2) }}</span>
            </div>

            <button wire:click="completeSale" class="mp-btn-primary" style="width: 100%;">
                Complete Sale
            </button>
        </div>
    </div>
</div>