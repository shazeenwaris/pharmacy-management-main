
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; margin-bottom: 1.5rem;">

    <div style="background: white; border: 1px solid #E2EEEE; border-radius: 0.75rem; padding: 0.85rem 1rem; display: flex; align-items: center; gap: 0.75rem;">
        <div style="width: 2.25rem; height: 2.25rem; min-width: 2.25rem; background: {{ $held > 0 ? '#FFF7E6' : '#F0FAFA' }}; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="{{ $held > 0 ? '#F59E0B' : '#00707A' }}" stroke-width="2" style="width: 1.1rem; height: 1.1rem;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z" />
            </svg>
        </div>
        <div style="min-width: 0;">
            <p style="margin: 0; font-size: 0.7rem; color: #718792; text-transform: uppercase; letter-spacing: 0.02em; white-space: nowrap;">Held Sales</p>
            <p style="margin: 0.1rem 0 0 0; font-size: 1.15rem; font-weight: 700; color: {{ $held > 0 ? '#F59E0B' : '#123047' }};">{{ $held }}</p>
        </div>
    </div>

    <div style="background: white; border: 1px solid #E2EEEE; border-radius: 0.75rem; padding: 0.85rem 1rem; display: flex; align-items: center; gap: 0.75rem;">
        <div style="width: 2.25rem; height: 2.25rem; min-width: 2.25rem; background: #F0FAFA; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#00707A" stroke-width="2" style="width: 1.1rem; height: 1.1rem;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
            </svg>
        </div>
        <div style="min-width: 0;">
            <p style="margin: 0; font-size: 0.7rem; color: #718792; text-transform: uppercase; letter-spacing: 0.02em; white-space: nowrap;">Held Today</p>
            <p style="margin: 0.1rem 0 0 0; font-size: 1.15rem; font-weight: 700; color: #123047;">{{ $heldToday }}</p>
        </div>
    </div>

    <div style="background: white; border: 1px solid #E2EEEE; border-radius: 0.75rem; padding: 0.85rem 1rem; display: flex; align-items: center; gap: 0.75rem;">
        <div style="width: 2.25rem; height: 2.25rem; min-width: 2.25rem; background: {{ $oldestMinutes > 60 ? '#FDECEC' : '#F0FAFA' }}; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="{{ $oldestMinutes > 60 ? '#DC4C4C' : '#00707A' }}" stroke-width="2" style="width: 1.1rem; height: 1.1rem;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div style="min-width: 0;">
            <p style="margin: 0; font-size: 0.7rem; color: #718792; text-transform: uppercase; letter-spacing: 0.02em; white-space: nowrap;">Oldest Held</p>
            <p style="margin: 0.1rem 0 0 0; font-size: 1.15rem; font-weight: 700; color: {{ $oldestMinutes > 60 ? '#DC4C4C' : '#123047' }};">{{ $oldestMinutes }}m</p>
        </div>
    </div>

</div>
