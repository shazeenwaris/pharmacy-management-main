<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.5rem;">

    <div style="background: white; border: 1px solid #E2EEEE; border-radius: 0.75rem; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
        <div style="width: 2.75rem; height: 2.75rem; min-width: 2.75rem; background: #E0F7FA; border-radius: 0.6rem; display: flex; align-items: center; justify-content: center;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#00707A" stroke-width="2" style="width: 1.4rem; height: 1.4rem;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3v4.5a3.375 3.375 0 01-.673 2.014L5.25 15.75A2.25 2.25 0 007 19.5h10a2.25 2.25 0 001.75-3.736l-3.827-6.236A3.375 3.375 0 0114.25 7.5V3M8.25 3h7.5" />
            </svg>
        </div>
        <div>
            <p style="margin: 0; font-size: 0.8rem; color: #718792; text-transform: uppercase; letter-spacing: 0.03em;">Total Medicines</p>
            <p style="margin: 0.2rem 0 0 0; font-size: 1.5rem; font-weight: 700; color: #123047;">{{ $total }}</p>
        </div>
    </div>

    <div style="background: white; border: 1px solid #E2EEEE; border-radius: 0.75rem; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
        <div style="width: 2.75rem; height: 2.75rem; min-width: 2.75rem; background: {{ $lowStock > 0 ? '#FDECEC' : '#F0FAFA' }}; border-radius: 0.6rem; display: flex; align-items: center; justify-content: center;">
           <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="{{ $expiringSoon > 0 ? '#F59E0B' : '#00707A' }}" stroke-width="2" style="width: 1.4rem; height: 1.4rem;">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
</svg>
</div>
        <div>
            <p style="margin: 0; font-size: 0.8rem; color: #718792; text-transform: uppercase; letter-spacing: 0.03em;">Low Stock</p>
            <p style="margin: 0.2rem 0 0 0; font-size: 1.5rem; font-weight: 700; color: {{ $lowStock > 0 ? '#DC4C4C' : '#123047' }};">{{ $lowStock }}</p>
        </div>
    </div>

    <div style="background: white; border: 1px solid #E2EEEE; border-radius: 0.75rem; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
        <div style="width: 2.75rem; height: 2.75rem; min-width: 2.75rem; background: {{ $expiringSoon > 0 ? '#FFF7E6' : '#F0FAFA' }}; border-radius: 0.6rem; display: flex; align-items: center; justify-content: center;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="{{ $expiringSoon > 0 ? '#F59E0B' : '#00707A' }}" stroke-width="2" style="width: 1.4rem; height: 1.4rem;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div>
            <p style="margin: 0; font-size: 0.8rem; color: #718792; text-transform: uppercase; letter-spacing: 0.03em;">Expiring Soon</p>
            <p style="margin: 0.2rem 0 0 0; font-size: 1.5rem; font-weight: 700; color: {{ $expiringSoon > 0 ? '#F59E0B' : '#123047' }};">{{ $expiringSoon }}</p>
        </div>
    </div>

</div>