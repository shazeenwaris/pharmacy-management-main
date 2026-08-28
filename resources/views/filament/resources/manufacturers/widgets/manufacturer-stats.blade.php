<div>
    @php
        $stats = $this->getStatsData();
    @endphp

    <style>
        .manufacturer-stat-card {
            transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
            cursor: default;
        }
        .manufacturer-stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(18, 48, 71, 0.08);
            border-color: #C9DEDE;
        }
    </style>
 
    
    {{-- STATS --}}
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1rem;">

        <div class="manufacturer-stat-card" style="background: white; border: 1px solid #E2EEEE; border-radius: 0.75rem; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
            <div style="width: 2.75rem; height: 2.75rem; background: #E3F3F0; border-radius: 0.65rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0F7B6C" stroke-width="1.8" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15" />
                </svg>
            </div>
            <div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #123047; line-height: 1;">{{ $stats['total'] }}</div>
                <div style="font-size: 0.8rem; color: #718792; margin-top: 0.25rem;">Total Manufacturers</div>
            </div>
        </div>

        <div class="manufacturer-stat-card" style="background: white; border: 1px solid #E2EEEE; border-radius: 0.75rem; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
            <div style="width: 2.75rem; height: 2.75rem; background: #E3F3F0; border-radius: 0.65rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0F7B6C" stroke-width="1.8" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #123047; line-height: 1;">{{ $stats['active'] }}</div>
                <div style="font-size: 0.8rem; color: #718792; margin-top: 0.25rem;">Active</div>
            </div>
        </div>

        <div class="manufacturer-stat-card" style="background: white; border: 1px solid #E2EEEE; border-radius: 0.75rem; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
            <div style="width: 2.75rem; height: 2.75rem; background: #E3EAF8; border-radius: 0.65rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#3B5BDB" stroke-width="1.8" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75h.007v.008H12V6.75zm0 3h.007v.008H12v-.008zm0 3h.007v.008H12v-.008zM9.75 21h4.5a2.25 2.25 0 002.25-2.25V5.25A2.25 2.25 0 0014.25 3h-4.5A2.25 2.25 0 007.5 5.25v13.5A2.25 2.25 0 009.75 21z" />
                </svg>
            </div>
            <div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #123047; line-height: 1;">{{ $stats['inactive'] }}</div>
                <div style="font-size: 0.8rem; color: #718792; margin-top: 0.25rem;">Inactive</div>
            </div>
        </div>

    </div>

    {{-- INFO GLASS CARD --}}
    <div style="display: flex; align-items: center; gap: 0.85rem; background: white; border: 1px solid #E2EEEE; border-radius: 0.75rem; padding: 1rem 1.25rem;">
        <div style="width: 2.25rem; height: 2.25rem; background: #E0F7FA; border-radius: 0.6rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#00838F" style="width: 16px; height: 16px;">
                <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.6" />
                <path d="M12 16v-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                <circle cx="12" cy="8" r="1" fill="currentColor" />
            </svg>
        </div>
        <div>
            <strong style="display: block; color: #123047; font-size: 0.85rem; font-weight: 600;">Manage your manufacturer information</strong>
            <span style="color: #718792; font-size: 0.78rem;">Create and maintain organized manufacturer records to streamline your supply chain management.</span>
        </div>
    </div>

</div>