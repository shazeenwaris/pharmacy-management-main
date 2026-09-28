<x-filament-widgets::widget>
<div>
    {{-- Banner --}}
    <div style="background: linear-gradient(135deg, #00707A, #00707A); border-radius: 0.75rem; padding: 1.5rem 2rem; display: flex; justify-content: space-between; align-items: center; color: white; margin-bottom: 1rem;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" style="width: 1.5rem; height: 1.5rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                </svg>
            </div>
            <div>
                <h2 style="margin: 0; font-size: 1.15rem; font-weight: 700;">Sales Returns</h2>
                <p style="margin: 0.15rem 0 0 0; font-size: 0.85rem; opacity: 0.85;">Process and track medicine returns from customers</p>
            </div>
        </div>

       <a href="/pharmacist/sale-returns/create"
   style="background: white; color: #00707A; padding: 0.6rem 1.1rem; border-radius: 0.5rem; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: flex; align-items: center; gap: 0.4rem;">
    + New Return
</a>
    </div>

    {{-- Stat cards --}}
    <style>
        .sale-return-stat-card {
            transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
            cursor: default;
        }
        .sale-return-stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(18, 48, 71, 0.08);
            border-color: #C9DEDE;
        }
    </style>

    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">

        <div class="sale-return-stat-card" style="background: white; border: 1px solid #E2EEEE; border-radius: 0.75rem; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
            <div style="width: 2.75rem; height: 2.75rem; background: #E3F3F0; border-radius: 0.65rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#0F7B6C" stroke-width="1.8" style="width: 1.4rem; height: 1.4rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                </svg>
            </div>
            <div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #123047; line-height: 1;">{{ $totalReturns }}</div>
                <div style="font-size: 0.8rem; color: #718792; margin-top: 0.25rem;">Total Returns</div>
            </div>
        </div>

        <div class="sale-return-stat-card" style="background: white; border: 1px solid #E2EEEE; border-radius: 0.75rem; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
            <div style="width: 2.75rem; height: 2.75rem; background: #FDF1DF; border-radius: 0.65rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#C8791B" stroke-width="1.8" style="width: 1.4rem; height: 1.4rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                </svg>
            </div>
            <div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #123047; line-height: 1;">{{ $todaysReturns }}</div>
                <div style="font-size: 0.8rem; color: #718792; margin-top: 0.25rem;">Today's Returns</div>
            </div>
        </div>

        <div class="sale-return-stat-card" style="background: white; border: 1px solid #E2EEEE; border-radius: 0.75rem; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
            <div style="width: 2.75rem; height: 2.75rem; background: #FBE7E7; border-radius: 0.65rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#C0392B" stroke-width="1.8" style="width: 1.4rem; height: 1.4rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #123047; line-height: 1;">{{ currency_symbol() }} {{ number_format($totalRefunded, 2) }}</div>
                <div style="font-size: 0.8rem; color: #718792; margin-top: 0.25rem;">Total Refunded</div>
            </div>
        </div>

    </div>
</div>
</x-filament-widgets::widget>