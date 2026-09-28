<x-filament-panels::page>
    @livewire(\App\Filament\Pharmacist\Widgets\HeldSalesBanner::class)
    @livewire(\App\Filament\Pharmacist\Widgets\HeldSalesStats::class)

    {{ $this->table }}
</x-filament-panels::page>