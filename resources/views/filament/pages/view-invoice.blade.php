<x-filament-panels::page>
    <div style="max-width: 700px; margin: 0 auto; background: white; padding: 2rem; border: 1px solid #E2EEEE; border-radius: 0.5rem;">

        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1.5rem;">
            <div>
                <h2 style="font-size: 1.3rem; font-weight: bold; margin: 0;">MediCare Pharmacy</h2>
                <p style="margin: 0; color: #718792;">Invoice / Receipt</p>
            </div>
            <div style="text-align: right;">
                <p style="margin: 0;"><strong>Invoice #:</strong> {{ $sale->invoice_number }}</p>
                <p style="margin: 0;"><strong>Date:</strong> {{ $sale->created_at->format('d M Y, h:i A') }}</p>
            </div>
        </div>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 1.5rem;">
            <thead>
                <tr style="border-bottom: 2px solid #E2EEEE; text-align: left;">
                    <th style="padding: 0.5rem;">Medicine</th>
                    <th style="padding: 0.5rem; text-align: center;">Qty</th>
                    <th style="padding: 0.5rem; text-align: right;">Price</th>
                    <th style="padding: 0.5rem; text-align: right;">Discount</th>
                    <th style="padding: 0.5rem; text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sale->items as $item)
                    <tr style="border-bottom: 1px solid #EEF2F3;">
                        <td style="padding: 0.5rem;">{{ $item->medicine->name ?? 'N/A' }}</td>
                        <td style="padding: 0.5rem; text-align: center;">{{ $item->quantity }}</td>
                        <td style="padding: 0.5rem; text-align: right;">{{ currency_symbol() }} {{ number_format($item->unit_price, 2) }}</td>
                        <td style="padding: 0.5rem; text-align: right;">{{ currency_symbol() }} {{ number_format($item->discount, 2) }}</td>
                        <td style="padding: 0.5rem; text-align: right;">{{ currency_symbol() }} {{ number_format($item->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="max-width: 300px; margin-left: auto;">
            <div style="display: flex; justify-content: space-between; padding: 0.25rem 0;">
                <span>Subtotal</span>
                <span>{{ currency_symbol() }} {{ number_format($sale->subtotal, 2) }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 0.25rem 0;">
                <span>Discount</span>
                <span>{{ currency_symbol() }} {{ number_format($sale->discount, 2) }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 0.25rem 0;">
                <span>Tax</span>
                <span>{{ currency_symbol() }} {{ number_format($sale->tax, 2) }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-top: 2px solid #E2EEEE; font-weight: bold; font-size: 1.1rem;">
                <span>Grand Total</span>
                <span>{{ currency_symbol() }} {{ number_format($sale->grand_total, 2) }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 0.25rem 0;">
                <span>Paid ({{ ucfirst($sale->payment_method) }})</span>
                <span>{{ currency_symbol() }} {{ number_format($sale->paid_amount, 2) }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 0.25rem 0;">
                <span>Change</span>
                <span>{{ currency_symbol() }} {{ number_format($sale->change_amount, 2) }}</span>
            </div>
        </div>

        <div style="margin-top: 2rem; text-align: center; color: #718792; font-size: 0.85rem;">
            Thank you for your purchase!
        </div>

        <div style="margin-top: 1.5rem; text-align: center;" class="no-print">
            <button onclick="window.print()" class="mp-btn-primary" style="padding: 0.5rem 1.5rem; border-radius: 0.5rem;">
                🖨 Print Invoice
            </button>
        </div>
    </div>

    <style>
        @media print {
            .no-print, nav, .fi-sidebar, .fi-topbar {
                display: none !important;
            }
        }
    </style>
</x-filament-panels::page>