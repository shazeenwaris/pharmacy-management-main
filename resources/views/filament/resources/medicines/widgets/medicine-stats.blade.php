 @php
    $stats = $this->getStatsData();
@endphp

<!-- <style>
    .medicine-stat-card {
        transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
        cursor: default;
    }
    .medicine-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(18, 48, 71, 0.08);
        border-color: #C9DEDE;
    }
</style> -->

<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">

    <div class="medicine-stat-card" style="background: white; border: 1px solid #E2EEEE; border-radius: 0.75rem; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
        <div style="width: 2.75rem; height: 2.75rem; background: #E3F3F0; border-radius: 0.65rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#0F7B6C" stroke-width="1.8" style="width: 1.4rem; height: 1.4rem;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3v4.5a3.375 3.375 0 01-.673 2.014L5.25 15.75A2.25 2.25 0 007 19.5h10a2.25 2.25 0 001.75-3.736l-3.827-6.236A3.375 3.375 0 0114.25 7.5V3M8.25 3h7.5" />
            </svg>
        </div>
        <div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #123047; line-height: 1;">{{ $stats['total'] }}</div>
            <div style="font-size: 0.8rem; color: #718792; margin-top: 0.25rem;">Total Medicines</div>
        </div>
    </div>

    <div class="medicine-stat-card" style="background: white; border: 1px solid #E2EEEE; border-radius: 0.75rem; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
        <div style="width: 2.75rem; height: 2.75rem; background: #FDF1DF; border-radius: 0.65rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#C8791B" stroke-width="1.8" style="width: 1.4rem; height: 1.4rem;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
        </div>
        <div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #123047; line-height: 1;">{{ $stats['lowStock'] }}</div>
            <div style="font-size: 0.8rem; color: #718792; margin-top: 0.25rem;">Low Stock</div>
        </div>
    </div>

    <div class="medicine-stat-card" style="background: white; border: 1px solid #E2EEEE; border-radius: 0.75rem; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
        <div style="width: 2.75rem; height: 2.75rem; background: #FBE7E7; border-radius: 0.65rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#C0392B" stroke-width="1.8" style="width: 1.4rem; height: 1.4rem;"> 
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /> 
            </svg> 
        </div> 
        <div> 
            <div style="font-size: 1.5rem; font-weight: 700; color: #123047; line-height: 1;">{{ $stats['expiringSoon'] }}</div> 
            <div style="font-size: 0.8rem; color: #718792; margin-top: 0.25rem;">Expiring Soon (30 days)</div> 
        </div> 
    </div> 
 
</div>