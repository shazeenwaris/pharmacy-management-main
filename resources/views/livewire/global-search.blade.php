<div x-data="{ open: false }" @click.outside="open = false" style="position: relative; flex: 1; max-width: 420px; margin: 0 24px;">
    <div class="pms-topbar-search" style="margin: 0; max-width: none;">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
        </svg>
        <input
            type="text"
            wire:model.live.debounce.300ms="query"
            @focus="open = true"
            placeholder="Search medicines, customers, invoices..."
        />
        @if ($query)
            <button wire:click="clear" @click="open = false" style="background: none; border: none; cursor: pointer; color: #A9B7BC;">✕</button>
        @endif
    </div>

    @if ($query && strlen($query) >= 2)
        <div
            x-show="open"
            x-cloak
            style="position: absolute; top: calc(100% + 8px); left: 0; right: 0; background: white; border: 1px solid #E2EEEE; border-radius: 0.75rem; box-shadow: 0 12px 28px rgba(0,60,65,0.12); max-height: 400px; overflow-y: auto; z-index: 60;"
        >
            @php
                $medicines = $this->medicines;
                $customers = $this->customers;
                $sales = $this->sales;
                $hasResults = $medicines->count() || $customers->count() || $sales->count();
            @endphp

            @if (! $hasResults)
                <div style="padding: 1.25rem; text-align: center; color: #A9B7BC; font-size: 0.85rem;">No results found</div>
            @endif

            @if ($medicines->count())
                <div style="padding: 0.5rem 1rem; font-size: 0.7rem; font-weight: 700; color: #718792; text-transform: uppercase; letter-spacing: 0.04em;">Medicines</div>
                @foreach ($medicines as $medicine)
                    <a href="/admin/medicines/{{ $medicine->id }}/edit" style="display: flex; justify-content: space-between; padding: 0.6rem 1rem; text-decoration: none; color: #123047; font-size: 0.85rem; border-bottom: 1px solid #F2F6F6;">
                        <span>{{ $medicine->name }}</span>
                        <span style="color: #A9B7BC;">{{ $medicine->sku }}</span>
                    </a>
                @endforeach
            @endif

            @if ($customers->count())
                <div style="padding: 0.5rem 1rem; font-size: 0.7rem; font-weight: 700; color: #718792; text-transform: uppercase; letter-spacing: 0.04em;">Customers</div>
                @foreach ($customers as $customer)
                    <a href="/admin/customers/{{ $customer->id }}/edit" style="display: flex; justify-content: space-between; padding: 0.6rem 1rem; text-decoration: none; color: #123047; font-size: 0.85rem; border-bottom: 1px solid #F2F6F6;">
                        <span>{{ $customer->name }}</span>
                        <span style="color: #A9B7BC;">{{ $customer->phone }}</span>
                    </a>
                @endforeach
            @endif

            @if ($sales->count())
                <div style="padding: 0.5rem 1rem; font-size: 0.7rem; font-weight: 700; color: #718792; text-transform: uppercase; letter-spacing: 0.04em;">Invoices</div>
                @foreach ($sales as $sale)
                    <a href="/admin/sales-history/{{ $sale->id }}" style="display: flex; justify-content: space-between; padding: 0.6rem 1rem; text-decoration: none; color: #123047; font-size: 0.85rem; border-bottom: 1px solid #F2F6F6;">
                        <span>{{ $sale->invoice_number }}</span>
                        <span style="color: #A9B7BC;">{{ $sale->created_at->format('d M Y') }}</span>
                    </a>
                @endforeach
            @endif
        </div>
    @endif
</div>