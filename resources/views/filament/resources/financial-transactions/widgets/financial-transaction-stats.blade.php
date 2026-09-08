<div>
    @php
        $stats = $this->getStatsData();
    @endphp

    <style>
        .ft-stat-card {
            transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
            cursor: default;
        }
        .ft-stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(18, 48, 71, 0.08);
            border-color: #C9DEDE;
        }
    </style>

    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1rem;">

        <div class="ft-stat-card" style="background: white; border: 1px solid #E2EEEE; border-radius: 0.75rem; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
            <div style="width: 2.75rem; height: 2.75rem; background: #E3F3F0; border-radius: 0.65rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0F7B6C" stroke-width="1.8" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                </svg>
            </div>
            <div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #123047; line-height: 1;">{{ $stats['total'] }}</div>
                <div style="font-size: 0.8rem; color: #718792; margin-top: 0.25rem;">Total Transactions</div>
            </div>
        </div>

        <div class="ft-stat-card" style="background: white; border: 1px solid #E2EEEE; border-radius: 0.75rem; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
            <div style="width: 2.75rem; height: 2.75rem; background: #E3F3F0; border-radius: 0.65rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0F7B6C" stroke-width="1.8" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m0 0l-4-4m4 4l4-4" />
                </svg>
            </div>
            <div>
                <!-- <div style="font-size: 1.5rem; font-weight: 700; color: #123047; line-height: 1;">PKR {{ number_format($stats['income'], 2) }}</div> -->
                 <div style="font-size: 1.5rem; font-weight: 700; color: #123047; line-height: 1;">{{ format_currency($stats['income']) }}</div>
                <div style="font-size: 0.8rem; color: #718792; margin-top: 0.25rem;">Total Income</div>
            </div>
        </div>

        <div class="ft-stat-card" style="background: white; border: 1px solid #E2EEEE; border-radius: 0.75rem; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
            <div style="width: 2.75rem; height: 2.75rem; background: #FCE8E8; border-radius: 0.65rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#D9534F" stroke-width="1.8" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18V6m0 0l-4 4m4-4l4 4" />
                </svg>
            </div>
            <div>
                <!-- <div style="font-size: 1.5rem; font-weight: 700; color: #123047; line-height: 1;">PKR {{ number_format($stats['expense'], 2) }}</div> -->
                 <div style="font-size: 1.5rem; font-weight: 700; color: #123047; line-height: 1;">{{ format_currency($stats['expense']) }}</div>
                <div style="font-size: 0.8rem; color: #718792; margin-top: 0.25rem;">Total Expense</div>
            </div>
        </div>

    </div>

    <div style="display: flex; align-items: center; gap: 0.85rem; background: white; border: 1px solid #E2EEEE; border-radius: 0.75rem; padding: 1rem 1.25rem;">
        <div style="width: 2.25rem; height: 2.25rem; background: #E0F7FA; border-radius: 0.6rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#00838F" style="width: 16px; height: 16px;">
                <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.6" />
                <path d="M12 16v-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                <circle cx="12" cy="8" r="1" fill="currentColor" />
            </svg>
        </div>
        <div>
            <strong style="display: block; color: #123047; font-size: 0.85rem; font-weight: 600;">Track your financial activity</strong>
            <span style="color: #718792; font-size: 0.78rem;">Monitor income, expenses and overall cash flow across your pharmacy.</span>
        </div>
    </div>

</div>