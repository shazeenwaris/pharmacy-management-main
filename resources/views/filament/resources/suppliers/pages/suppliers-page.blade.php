<x-filament-panels::page>

    {{-- =====================================================
        SUPPLIER PAGE HEADER
    ====================================================== --}}

    <div class="supplier-page-header">

        <div class="supplier-page-header-left">

            <div class="supplier-page-icon">
                <x-heroicon-o-truck />
            </div>

            <div>
                <div class="supplier-page-title-row">

                    <h1 class="supplier-page-title">
                        Suppliers
                    </h1>

                    <span class="supplier-page-badge">
                        Supplier Management
                    </span>

                </div>

                <p class="supplier-page-description">
                    Manage your pharmacy suppliers, accounts and payable information
                </p>
            </div>

        </div>


        <div class="supplier-page-header-right">

            <div class="supplier-total-mini">

                <div class="supplier-total-icon">
                    <x-heroicon-o-users />
                </div>

                <div>
                    <strong>{{ \App\Models\Supplier::count() }}</strong>
                    <span>Total Suppliers</span>
                </div>

            </div>


            <a
                href="{{ \App\Filament\Resources\Suppliers\Pages\CreateSupplier::getUrl() }}"
                class="supplier-add-button"
            >

                <x-heroicon-o-plus />

                <span>Add Supplier</span>

            </a>

        </div>

    </div>


    {{-- =====================================================
        SUPPLIER STATS
    ====================================================== --}}

    <div class="supplier-stats-grid">

        {{-- TOTAL SUPPLIERS --}}
        <div class="supplier-stat-card">

            <div class="supplier-stat-top">

                <div class="supplier-stat-icon total">
                    <x-heroicon-o-building-storefront />
                </div>

                <span class="supplier-stat-label">
                    Total Suppliers
                </span>

            </div>

            <div class="supplier-stat-value">
                {{ \App\Models\Supplier::count() }}
            </div>

            <div class="supplier-stat-bottom">

                <span class="supplier-stat-description">
                    All registered suppliers
                </span>

                <x-heroicon-o-truck class="supplier-stat-corner-icon" />

            </div>

        </div>


        {{-- ACTIVE SUPPLIERS --}}
        <div class="supplier-stat-card">

            <div class="supplier-stat-top">

                <div class="supplier-stat-icon active">
                    <x-heroicon-o-check-badge />
                </div>

                <span class="supplier-stat-label">
                    Active Suppliers
                </span>

            </div>

            <div class="supplier-stat-value">
                {{ \App\Models\Supplier::where('status', 'active')->count() }}
            </div>

            <div class="supplier-stat-bottom">

                <span class="supplier-stat-description active-text">
                    Currently active suppliers
                </span>

                <x-heroicon-o-check-circle class="supplier-stat-corner-icon active-icon" />

            </div>

        </div>


        {{-- OUTSTANDING PAYABLE --}}
        <div class="supplier-stat-card">

            <div class="supplier-stat-top">

                <div class="supplier-stat-icon payable">
                    <x-heroicon-o-currency-dollar />
                </div>

                <span class="supplier-stat-label">
                    Outstanding Payable
                </span>

            </div>

            @php
    $totalOpeningBalance = \App\Models\Supplier::sum('opening_balance');

    $totalPayments = \App\Models\SupplierPayment::sum('amount');

    $totalPayable = $totalOpeningBalance - $totalPayments;
@endphp

<div class="supplier-stat-value">
    $ {{ number_format($totalPayable, 2) }}
</div>

            <div class="supplier-stat-bottom">

                <span class="supplier-stat-description payable-text">
                    Total amount payable
                </span>

                <x-heroicon-o-banknotes class="supplier-stat-corner-icon payable-icon" />

            </div>

        </div>


        {{-- INACTIVE SUPPLIERS --}}
        <div class="supplier-stat-card">

            <div class="supplier-stat-top">

                <div class="supplier-stat-icon inactive">
                    <x-heroicon-o-user-minus />
                </div>

                <span class="supplier-stat-label">
                    Inactive Suppliers
                </span>

            </div>

            <div class="supplier-stat-value">
                {{ \App\Models\Supplier::where('status', 'inactive')->count() }}
            </div>

            <div class="supplier-stat-bottom">

                <span class="supplier-stat-description inactive-text">
                    Currently inactive suppliers
                </span>

                <x-heroicon-o-x-circle class="supplier-stat-corner-icon inactive-icon" />

            </div>

        </div>

    </div>


    {{-- =====================================================
        TABLE AREA
    ====================================================== --}}

    <div class="supplier-table-wrapper">

        <div class="supplier-table-header">

            <div>

                <h2 class="supplier-table-title">
                    All Suppliers
                </h2>

                <p class="supplier-table-subtitle">
                    View and manage your supplier records
                </p>

            </div>

        </div>


        {{-- FILAMENT TABLE --}}

        <div class="supplier-filament-table">

            {{ $this->table }}

        </div>

    </div>

</x-filament-panels::page>