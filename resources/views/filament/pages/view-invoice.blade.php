<x-filament-panels::page>
<div id="invoice-print-area" style="width: 100%; background: white; padding: 0; border: 1px solid #E2EEEE; border-radius: 0.85rem; box-shadow: 0 8px 30px rgba(0, 96, 100, 0.06); overflow: hidden;">
{{-- Header Band --}}
<div style="background: linear-gradient(135deg, #00707A  0%, #00707A 100%); padding: 1.75rem 2rem; color: white; -webkit-print-color-adjust: exact; print-color-adjust: exact;">
<div style="display: flex; justify-content: space-between; align-items: start; gap: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 44px; height: 44px; border-radius: 0.6rem; background: rgba(255,255,255,0.15); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" style="width: 22px; height: 22px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3v4.5a3.375 3.375 0 01-.673 2.014L5.25 15.75A2.25 2.25 0 007 19.5h10a2.25 2.25 0 001.75-3.736l-3.827-6.236A3.375 3.375 0 0114.25 7.5V3M8.25 3h7.5" />
                        </svg>
                    </div>
                    <div>
                        <h2 style="font-size: 1.35rem; font-weight: 800; margin: 0; letter-spacing: -0.02em;">MediCare Pharmacy</h2>
                        <p style="margin: 0.15rem 0 0; color: rgba(255,255,255,0.85); font-size: 0.8rem; font-weight: 500;">Invoice / Receipt</p>
                    </div>
                </div>
                <div style="text-align: right; font-size: 0.8rem; color: rgba(255,255,255,0.92);">
                    <p style="margin: 0; font-weight: 700; font-size: 0.95rem;">{{ $sale->invoice_number }}</p>
                    <p style="margin: 0.2rem 0 0;">{{ $sale->created_at->format('d M Y, h:i A') }}</p>
                </div>
            </div>
        </div>

        {{-- Body --}}
        <div style="padding: 2rem;">

            <table style="width: 100%; border-collapse: collapse; margin-bottom: 1.75rem;">
                <thead>
                    <tr style="border-bottom: 2px solid #E2EEEE; text-align: left;">
                        <th style="padding: 0.6rem 0.5rem; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.04em; color: #718792;">Medicine</th>
                        <th style="padding: 0.6rem 0.5rem; text-align: center; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.04em; color: #718792;">Qty</th>
                        <th style="padding: 0.6rem 0.5rem; text-align: right; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.04em; color: #718792;">Price</th>
                        <th style="padding: 0.6rem 0.5rem; text-align: right; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.04em; color: #718792;">Discount</th>
                        <th style="padding: 0.6rem 0.5rem; text-align: right; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.04em; color: #718792;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sale->items as $item)
                        <tr style="border-bottom: 1px solid #EEF2F3;">
                            <td style="padding: 0.65rem 0.5rem; font-weight: 600; color: #123047;">{{ $item->medicine->name ?? 'N/A' }}</td>
                            <td style="padding: 0.65rem 0.5rem; text-align: center; color: #405A68;">{{ $item->quantity }}</td>
                            <td style="padding: 0.65rem 0.5rem; text-align: right; color: #405A68;">{{ currency_symbol() }} {{ number_format($item->unit_price, 2) }}</td>
                            <td style="padding: 0.65rem 0.5rem; text-align: right; color: #405A68;">{{ currency_symbol() }} {{ number_format($item->discount, 2) }}</td>
                            <td style="padding: 0.65rem 0.5rem; text-align: right; font-weight: 700; color: #00838F;">{{ currency_symbol() }} {{ number_format($item->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="display: flex; justify-content: flex-end;">
                <div style="width: 100%; max-width: 320px; background: #F8FAFA; border: 1px solid #E2EEEE; border-radius: 0.65rem; padding: 1.1rem 1.3rem;">
                    <div style="display: flex; justify-content: space-between; padding: 0.3rem 0; font-size: 0.88rem; color: #405A68;">
                        <span>Subtotal</span>
                        <span>{{ currency_symbol() }} {{ number_format($sale->subtotal, 2) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 0.3rem 0; font-size: 0.88rem; color: #405A68;">
                        <span>Discount</span>
                        <span>{{ currency_symbol() }} {{ number_format($sale->discount, 2) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 0.3rem 0; font-size: 0.88rem; color: #405A68;">
                        <span>Tax</span>
                        <span>{{ currency_symbol() }} {{ number_format($sale->tax, 2) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 0.65rem 0; margin-top: 0.35rem; border-top: 2px solid #E2EEEE; font-weight: 800; font-size: 1.15rem; color: #00636C;">
                        <span>Grand Total</span>
                        <span>{{ currency_symbol() }} {{ number_format($sale->grand_total, 2) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 0.3rem 0; font-size: 0.88rem; color: #405A68;">
                        <span>Paid ({{ ucfirst($sale->payment_method) }})</span>
                        <span>{{ currency_symbol() }} {{ number_format($sale->paid_amount, 2) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 0.3rem 0; font-size: 0.88rem; color: #405A68;">
                        <span>Change</span>
                        <span>{{ currency_symbol() }} {{ number_format($sale->change_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px dashed #E2EEEE; text-align: center; color: #98A7AF; font-size: 0.85rem; font-style: italic;">
                Thank you for your purchase!
            </div>

            <div style="margin-top: 1.5rem; text-align: center;" class="no-print">
                <button onclick="window.print()" style="padding: 0.6rem 1.75rem; border-radius: 0.6rem; background: #00707A; color: white; border: none; font-weight: 600; font-size: 0.88rem; cursor: pointer; box-shadow: 0 4px 12px rgba(0,131,143,0.25); transition: background 0.2s;" onmouseover="this.style.background='#006064'" onmouseout="this.style.background='#00838F'">
                    🖨 Print Invoice
                </button>
            </div>
        </div>
    </div>

    <style>
    @media print {
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }

        body * {
            visibility: hidden !important;
        }

        #invoice-print-area, #invoice-print-area * {
            visibility: visible !important;
        }

        #invoice-print-area {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            margin: 0 !important;
            box-shadow: none !important;
            border: none !important;
        }

        .no-print {
            display: none !important;
        }
    }
</style>
</x-filament-panels::page>