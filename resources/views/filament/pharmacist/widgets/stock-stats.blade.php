<x-filament-widgets::widget class="stock-stats-widget">
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; margin: 0;">

    <div style="background: white; border: 1px solid #E2EEEE; border-radius: 0.75rem; padding: 0.85rem 1rem; display: flex; align-items: center; gap: 0.75rem;">
        <div style="width: 2.25rem; height: 2.25rem; min-width: 2.25rem; background: {{ $lowStock > 0 ? '#FFF7E6' : '#F0FAFA' }}; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="{{ $lowStock > 0 ? '#F59E0B' : '#00707A' }}" stroke-width="2" style="width: 1.1rem; height: 1.1rem;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
        </div>
        <div style="min-width: 0;">
            <p style="margin: 0; font-size: 0.7rem; color: #718792; text-transform: uppercase; letter-spacing: 0.02em; white-space: nowrap;">Low Stock</p>
            <p style="margin: 0.1rem 0 0 0; font-size: 1.15rem; font-weight: 700; color: {{ $lowStock > 0 ? '#F59E0B' : '#123047' }};">{{ $lowStock }}</p>
        </div>
    </div>

    <div style="background: white; border: 1px solid #E2EEEE; border-radius: 0.75rem; padding: 0.85rem 1rem; display: flex; align-items: center; gap: 0.75rem;">
        <div style="width: 2.25rem; height: 2.25rem; min-width: 2.25rem; background: {{ $outOfStock > 0 ? '#FDECEC' : '#F0FAFA' }}; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="{{ $outOfStock > 0 ? '#DC4C4C' : '#00707A' }}" stroke-width="2" style="width: 1.1rem; height: 1.1rem;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </div>
        <div style="min-width: 0;">
            <p style="margin: 0; font-size: 0.7rem; color: #718792; text-transform: uppercase; letter-spacing: 0.02em; white-space: nowrap;">Out of Stock</p>
            <p style="margin: 0.1rem 0 0 0; font-size: 1.15rem; font-weight: 700; color: {{ $outOfStock > 0 ? '#DC4C4C' : '#123047' }};">{{ $outOfStock }}</p>
        </div>
    </div>

    <div style="background: white; border: 1px solid #E2EEEE; border-radius: 0.75rem; padding: 0.85rem 1rem; display: flex; align-items: center; gap: 0.75rem;">
        <div style="width: 2.25rem; height: 2.25rem; min-width: 2.25rem; background: {{ $expiringSoon > 0 ? '#FFF7E6' : '#F0FAFA' }}; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="{{ $expiringSoon > 0 ? '#F59E0B' : '#00707A' }}" stroke-width="2" style="width: 1.1rem; height: 1.1rem;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div style="min-width: 0;">
            <p style="margin: 0; font-size: 0.7rem; color: #718792; text-transform: uppercase; letter-spacing: 0.02em; white-space: nowrap;">Expiring Soon</p>
            <p style="margin: 0.1rem 0 0 0; font-size: 1.15rem; font-weight: 700; color: {{ $expiringSoon > 0 ? '#F59E0B' : '#123047' }};">{{ $expiringSoon }}</p>
        </div>
    </div>

</div>
</x-filament-widgets::widget>