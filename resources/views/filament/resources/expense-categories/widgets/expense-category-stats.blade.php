<div>
    @php
        $stats = $this->getStatsData();
    @endphp

    <style>
        .ec-stat-card {
            transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
            cursor: default;
        }
        .ec-stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(18, 48, 71, 0.08);
            border-color: #C9DEDE;
        }
    </style>

    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1rem;">

        <div class="ec-stat-card" style="background: white; border: 1px solid #E2EEEE; border-radius: 0.75rem; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
            <div style="width: 2.75rem; height: 2.75rem; background: #E3F3F0; border-radius: 0.65rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0F7B6C" stroke-width="1.8" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
                </svg>
            </div>
            <div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #123047; line-height: 1;">{{ $stats['total'] }}</div>
                <div style="font-size: 0.8rem; color: #718792; margin-top: 0.25rem;">Total Categories</div>
            </div>
        </div>

        <div class="ec-stat-card" style="background: white; border: 1px solid #E2EEEE; border-radius: 0.75rem; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
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

        <div class="ec-stat-card" style="background: white; border: 1px solid #E2EEEE; border-radius: 0.75rem; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
            <div style="width: 2.75rem; height: 2.75rem; background: #E3EAF8; border-radius: 0.65rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#3B5BDB" stroke-width="1.8" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>
            <div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #123047; line-height: 1;">{{ $stats['inactive'] }}</div>
                <div style="font-size: 0.8rem; color: #718792; margin-top: 0.25rem;">Inactive</div>
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
            <strong style="display: block; color: #123047; font-size: 0.85rem; font-weight: 600;">Organize your expense structure</strong>
            <span style="color: #718792; font-size: 0.78rem;">Keep categories well-defined for clearer expense tracking and reporting.</span>
        </div>
    </div>

</div>