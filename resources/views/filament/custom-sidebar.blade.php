<div class="pms-sidebar">

    {{-- ===== LOGO / BRAND ===== --}}
    <div class="pms-sidebar-brand">
        <div class="pms-sidebar-brand-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
        </div>
        <div>
            <p class="pms-sidebar-brand-title">MediCare</p>
            <p class="pms-sidebar-brand-subtitle">Pharmacy System</p>
        </div>
    </div>

    {{-- ===== NAVIGATION ===== --}}
    <nav class="pms-sidebar-nav">

        @php
            $items = [
                ['label' => 'Dashboard',      'icon' => 'home',      'route' => '/admin',                'active' => request()->is('admin')],
                ['label' => 'Medicines',      'icon' => 'beaker',    'route' => '/admin/medicines',       'active' => request()->is('admin/medicines*')],
                ['label' => 'Purchases',      'icon' => 'cart',      'route' => '/admin/purchases',       'active' => request()->is('admin/purchases*')],
                ['label' => 'Sales / POS',    'icon' => 'card',      'route' => '/admin/sales',           'active' => request()->is('admin/sales*')],
                ['label' => 'Customers',      'icon' => 'users',     'route' => '/admin/customers',       'active' => request()->is('admin/customers*')],
                ['label' => 'Suppliers',      'icon' => 'truck',     'route' => '/admin/suppliers',       'active' => request()->is('admin/suppliers*')],
                 ['label' => 'Prescriptions', 'icon' => 'prescription', 'route' => '/admin/prescriptions', 'active' => request()->is('admin/prescriptions*')],

                ['label' => 'Stock',          'icon' => 'box',       'route' => '/admin/stock',           'active' => request()->is('admin/stock*')],
                ['label' => 'Reports',        'icon' => 'chart',     'route' => '/admin/reports',         'active' => request()->is('admin/reports*')],
                ['label' => 'Alerts',         'icon' => 'bell',      'route' => '/admin/alerts',          'active' => request()->is('admin/alerts*'), 'badge' => 2],
                ['label' => 'Expiry Tracker', 'icon' => 'clock',     'route' => '/admin/expiry-tracker',  'active' => request()->is('admin/expiry-tracker*')],
                ['label' => 'Settings',       'icon' => 'cog',       'route' => '/admin/settings',        'active' => request()->is('admin/settings*')],
            ];

            $icons = [
                'home'   => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
                'beaker' => 'M9.75 3v4.5a3.375 3.375 0 01-.673 2.014L5.25 15.75A2.25 2.25 0 007 19.5h10a2.25 2.25 0 001.75-3.736l-3.827-6.236A3.375 3.375 0 0114.25 7.5V3M8.25 3h7.5',
                'cart'   => 'M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 1.94-4.693 2.436-7.152A1.125 1.125 0 0019.7 5.25H4.5M7.5 14.25L5.106 5.272M7.5 14.25L5.4 19.5m3.75-5.25L9.75 19.5',
                'card'   => 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a1.5 1.5 0 001.5-1.5V6.75a1.5 1.5 0 00-1.5-1.5h-15a1.5 1.5 0 00-1.5 1.5v10.5a1.5 1.5 0 001.5 1.5z',
                'users'  => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z',
                'truck'  => 'M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12',
                'box'    => 'M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9',
                'chart'  => 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z',
                'bell'   => 'M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0',
                'clock'  => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z',
                'cog'    => 'M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.38.138.752.43.992l1.005.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.542-.56.94-1.11.94h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.38-.138-.752-.43-.992l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.28z',
                'prescription' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            ];
        @endphp

        @foreach ($items as $item)

            {{-- ===== MEDICINES DROPDOWN ===== --}}
            @if ($item['label'] === 'Medicines')

                @php
                    $medicineActive = request()->is('admin/medicines*')
                        || request()->is('admin/categories*')
                        || request()->is('admin/manufacturers*')
                        || request()->is('admin/units*')
                        || request()->is('admin/dosage-forms*')
                        || request()->is('admin/medicine-batches*');
                @endphp

                <div x-data="{ open: {{ $medicineActive ? 'true' : 'false' }} }">

                    <button
                        type="button"
                        @click="open = !open"
                        class="pms-nav-item {{ $medicineActive ? 'pms-supplier-parent-active' : '' }}"
                        style="width: 100%; border: 0; background: transparent; cursor: pointer;"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="pms-nav-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['beaker'] }}" />
                        </svg>

                        <span class="pms-nav-label">Medicines</span>

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="pms-nav-chevron" :style="open ? 'transform: rotate(90deg)' : ''" style="transition: transform 0.2s ease;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <div x-show="open" x-collapse class="pms-submenu">

                        <a href="/admin/medicines" class="pms-submenu-item {{ request()->is('admin/medicines*') ? 'pms-submenu-item-active' : '' }}">
                            <span class="pms-submenu-dot"></span>
                            <span>All Medicines</span>
                        </a>

                        <a href="/admin/categories" class="pms-submenu-item {{ request()->is('admin/categories*') ? 'pms-submenu-item-active' : '' }}">
                            <span class="pms-submenu-dot"></span>
                            <span>Categories</span>
                        </a>

                        <a href="/admin/manufacturers" class="pms-submenu-item {{ request()->is('admin/manufacturers*') ? 'pms-submenu-item-active' : '' }}">
                            <span class="pms-submenu-dot"></span>
                            <span>Manufacturers</span>
                        </a>

                        <a href="/admin/units" class="pms-submenu-item {{ request()->is('admin/units*') ? 'pms-submenu-item-active' : '' }}">
                            <span class="pms-submenu-dot"></span>
                            <span>Units</span>
                        </a>

                        <a href="/admin/dosage-forms" class="pms-submenu-item {{ request()->is('admin/dosage-forms*') ? 'pms-submenu-item-active' : '' }}">
                            <span class="pms-submenu-dot"></span>
                            <span>Dosage Forms</span>
                        </a>

                        <a href="/admin/medicine-batches" class="pms-submenu-item {{ request()->is('admin/medicine-batches*') ? 'pms-submenu-item-active' : '' }}">
                            <span class="pms-submenu-dot"></span>
                            <span>Batches & Expiry</span>
                        </a>

                    </div>
                </div>

            {{-- ===== PURCHASES DROPDOWN ===== --}}
            @elseif ($item['label'] === 'Purchases')

                @php
                    $purchaseActive = request()->is('admin/purchases*')
                        || request()->is('admin/purchase-returns*');
                @endphp

                <div x-data="{ open: {{ $purchaseActive ? 'true' : 'false' }} }">

                    <button
                        type="button"
                        @click="open = !open"
                        class="pms-nav-item {{ $purchaseActive ? 'pms-supplier-parent-active' : '' }}"
                        style="width: 100%; border: 0; background: transparent; cursor: pointer;"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="pms-nav-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['cart'] }}" />
                        </svg>

                        <span class="pms-nav-label">Purchases</span>

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="pms-nav-chevron" :style="open ? 'transform: rotate(90deg)' : ''" style="transition: transform 0.2s ease;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <div x-show="open" x-collapse class="pms-submenu">

                        <a href="/admin/purchases" class="pms-submenu-item {{ request()->is('admin/purchases*') && !request()->is('admin/purchase-returns*') ? 'pms-submenu-item-active' : '' }}">
                            <span class="pms-submenu-dot"></span>
                            <span>All Purchases</span>
                        </a>

                        <a href="/admin/purchases/create" class="pms-submenu-item">
                            <span class="pms-submenu-dot"></span>
                            <span>New Purchase</span>
                        </a>

                        <a href="/admin/purchase-returns" class="pms-submenu-item {{ request()->is('admin/purchase-returns*') ? 'pms-submenu-item-active' : '' }}">
                            <span class="pms-submenu-dot"></span>
                            <span>Purchase Returns</span>
                        </a>

                    </div>
                </div>

            {{-- ===== SALES / POS DROPDOWN ===== --}}
            @elseif ($item['label'] === 'Sales / POS')

                @php
                    $salesActive = request()->is('admin/sales')
                        || request()->is('admin/sales-history*')
                        || request()->is('admin/sale-returns*');
                @endphp

                <div x-data="{ open: {{ $salesActive ? 'true' : 'false' }} }">

                    <button
                        type="button"
                        @click="open = !open"
                        class="pms-nav-item {{ $salesActive ? 'pms-supplier-parent-active' : '' }}"
                        style="width: 100%; border: 0; background: transparent; cursor: pointer;"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="pms-nav-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['card'] }}" />
                        </svg>

                        <span class="pms-nav-label">Sales / POS</span>

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="pms-nav-chevron" :style="open ? 'transform: rotate(90deg)' : ''" style="transition: transform 0.2s ease;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <div x-show="open" x-collapse class="pms-submenu">

                        <a href="/admin/sales" class="pms-submenu-item {{ request()->is('admin/sales') ? 'pms-submenu-item-active' : '' }}">
                            <span class="pms-submenu-dot"></span>
                            <span>New Sale</span>
                        </a>

                        <a href="/admin/sales-history" class="pms-submenu-item {{ request()->is('admin/sales-history*') ? 'pms-submenu-item-active' : '' }}">
                            <span class="pms-submenu-dot"></span>
                            <span>Sales History</span>
                        </a>

                        <a href="/admin/sale-returns" class="pms-submenu-item {{ request()->is('admin/sale-returns*') ? 'pms-submenu-item-active' : '' }}">
                            <span class="pms-submenu-dot"></span>
                            <span>Sales Returns</span>
                        </a>

                    </div>
                </div>

                {{-- ===== CUSTOMERS DROPDOWN ===== --}}
@elseif ($item['label'] === 'Customers')

    @php
        $customerActive = request()->is('admin/customers*')
            || request()->is('admin/customer-payments*');
    @endphp

    <div x-data="{ open: {{ $customerActive ? 'true' : 'false' }} }">

        <button
            type="button"
            @click="open = !open"
            class="pms-nav-item {{ $customerActive ? 'pms-supplier-parent-active' : '' }}"
            style="width: 100%; border: 0; background: transparent; cursor: pointer;"
        >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="pms-nav-icon">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['users'] }}" />
            </svg>

            <span class="pms-nav-label">Customers</span>

            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="pms-nav-chevron" :style="open ? 'transform: rotate(90deg)' : ''" style="transition: transform 0.2s ease;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
        </button>

        <div x-show="open" x-collapse class="pms-submenu">

            <a href="/admin/customers" class="pms-submenu-item {{ request()->is('admin/customers*') && !request()->is('admin/customer-payments*') ? 'pms-submenu-item-active' : '' }}">
                <span class="pms-submenu-dot"></span>
                <span>All Customers</span>
            </a>

            <a href="/admin/customer-payments" class="pms-submenu-item {{ request()->is('admin/customer-payments*') ? 'pms-submenu-item-active' : '' }}">
                <span class="pms-submenu-dot"></span>
                <span>Customer Payments</span>
            </a>

        </div>
    </div>
            {{-- ===== SUPPLIERS DROPDOWN ===== --}}
            @elseif ($item['label'] === 'Suppliers')

                @php
                    $supplierActive = request()->is('admin/suppliers*')
                        || request()->is('admin/supplier-payments*');
                @endphp

                <div x-data="{ open: {{ $supplierActive ? 'true' : 'false' }} }">

                    <button
                        type="button"
                        @click="open = !open"
                        class="pms-nav-item {{ $supplierActive ? 'pms-supplier-parent-active' : '' }}"
                        style="width: 100%; border: 0; background: transparent; cursor: pointer;"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="pms-nav-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['truck'] }}" />
                        </svg>

                        <span class="pms-nav-label">Suppliers</span>

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="pms-nav-chevron" :style="open ? 'transform: rotate(90deg)' : ''" style="transition: transform 0.2s ease;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <div x-show="open" x-collapse class="pms-submenu">

                        <a href="/admin/suppliers" class="pms-submenu-item {{ request()->is('admin/suppliers*') && !request()->is('admin/supplier-payments*') ? 'pms-submenu-item-active' : '' }}">
                            <span class="pms-submenu-dot"></span>
                            <span>All Suppliers</span>
                        </a>

                        <a href="/admin/supplier-payments" class="pms-submenu-item {{ request()->is('admin/supplier-payments*') ? 'pms-submenu-item-active' : '' }}">
                            <span class="pms-submenu-dot"></span>
                            <span>Supplier Payments</span>
                        </a>

                    </div>
                </div>

                {{-- ===== PRESCRIPTIONS DROPDOWN ===== --}}
@elseif ($item['label'] === 'Prescriptions')

    @php
        $prescriptionActive = request()->is('admin/prescriptions*');
    @endphp

    <div x-data="{ open: {{ $prescriptionActive ? 'true' : 'false' }} }">

        <button
            type="button"
            @click="open = !open"
            class="pms-nav-item {{ $prescriptionActive ? 'pms-supplier-parent-active' : '' }}"
            style="width: 100%; border: 0; background: transparent; cursor: pointer;"
        >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="pms-nav-icon">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['prescription'] }}" />
            </svg>

            <span class="pms-nav-label">Prescriptions</span>

            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="pms-nav-chevron" :style="open ? 'transform: rotate(90deg)' : ''" style="transition: transform 0.2s ease;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
        </button>

        <div x-show="open" x-collapse class="pms-submenu">

            <a href="/admin/prescriptions" class="pms-submenu-item {{ request()->is('admin/prescriptions') ? 'pms-submenu-item-active' : '' }}">
                <span class="pms-submenu-dot"></span>
                <span>All Prescriptions</span>
            </a>

            <a href="/admin/prescriptions/create" class="pms-submenu-item">
                <span class="pms-submenu-dot"></span>
                <span>New Prescription</span>
            </a>

            <a href="/admin/prescriptions?status=active" class="pms-submenu-item">
                <span class="pms-submenu-dot"></span>
                <span>Pending / Active</span>
            </a>

            <a href="/admin/prescriptions?status=dispensed" class="pms-submenu-item">
                <span class="pms-submenu-dot"></span>
                <span>Dispensed</span>
            </a>

        </div>
    </div>


    {{-- ===== STOCK DROPDOWN ===== --}}
@elseif ($item['label'] === 'Stock')

    @php
        $stockActive = request()->is('admin/stock*');
    @endphp

    <div x-data="{ open: {{ $stockActive ? 'true' : 'false' }} }">

        <button
            type="button"
            @click="open = !open"
            class="pms-nav-item {{ $stockActive ? 'pms-supplier-parent-active' : '' }}"
            style="width: 100%; border: 0; background: transparent; cursor: pointer;"
        >

            {{-- Stock Icon --}}
            <svg xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2"
                 class="pms-nav-icon">

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="{{ $icons['stock'] ?? '' }}"
                />

            </svg>

            <span class="pms-nav-label">Stock</span>

            {{-- Chevron --}}
            <svg xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2.5"
                 class="pms-nav-chevron"
                 :style="open ? 'transform: rotate(90deg)' : ''"
                 style="transition: transform 0.2s ease;">

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M9 5l7 7-7 7"
                />

            </svg>

        </button>

        {{-- Stock Submenu --}}
        <div x-show="open" x-collapse class="pms-submenu">

            {{-- Inventory Overview --}}
            <a href="/admin/stock"
               class="pms-submenu-item {{ request()->is('admin/stock') ? 'pms-submenu-item-active' : '' }}">

                <span class="pms-submenu-dot"></span>

                <span>Inventory Overview</span>

            </a>

        </div>

    </div>
            {{-- ===== SETTINGS DROPDOWN ===== --}}
            @elseif ($item['label'] === 'Settings')

                @php
                    $settingsActive = request()->is('admin/settings*')
                        || request()->is('admin/users*')
                        || request()->is('admin/roles*')
                        || request()->is('admin/audit-logs*')
                        || request()->is('admin/backup*');
                @endphp

                <div x-data="{ open: {{ $settingsActive ? 'true' : 'false' }} }">

                    <button
                        type="button"
                        @click="open = !open"
                        class="pms-nav-item {{ $settingsActive ? 'pms-supplier-parent-active' : '' }}"
                        style="width: 100%; border: 0; background: transparent; cursor: pointer;"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="pms-nav-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['cog'] }}" />
                        </svg>

                        <span class="pms-nav-label">Settings</span>

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="pms-nav-chevron" :style="open ? 'transform: rotate(90deg)' : ''" style="transition: transform 0.2s ease;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <div x-show="open" x-collapse class="pms-submenu">

                        <a href="/admin/settings" class="pms-submenu-item {{ request()->is('admin/settings*') ? 'pms-submenu-item-active' : '' }}">
                            <span class="pms-submenu-dot"></span>
                            <span>General Settings</span>
                        </a>

                        <a href="/admin/users" class="pms-submenu-item {{ request()->is('admin/users*') ? 'pms-submenu-item-active' : '' }}">
                            <span class="pms-submenu-dot"></span>
                            <span>Users</span>
                        </a>

                        <a href="/admin/roles" class="pms-submenu-item {{ request()->is('admin/roles*') ? 'pms-submenu-item-active' : '' }}">
                            <span class="pms-submenu-dot"></span>
                            <span>Roles &amp; Permissions</span>
                        </a>

                        <a href="/admin/audit-logs" class="pms-submenu-item {{ request()->is('admin/audit-logs*') ? 'pms-submenu-item-active' : '' }}">
                            <span class="pms-submenu-dot"></span>
                            <span>Audit Logs</span>
                        </a>

                        <a href="/admin/backup" class="pms-submenu-item {{ request()->is('admin/backup*') ? 'pms-submenu-item-active' : '' }}">
                            <span class="pms-submenu-dot"></span>
                            <span>Backup &amp; Restore</span>
                        </a>

                    </div>
                </div>

            @else

                {{-- ===== NORMAL SIDEBAR ITEMS ===== --}}
                <a href="{{ $item['route'] }}" class="pms-nav-item {{ $item['active'] ? 'pms-nav-item-active' : '' }}">

                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="pms-nav-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$item['icon']] }}" />
                    </svg>

                    <span class="pms-nav-label">
                        {{ $item['label'] }}
                    </span>

                    @if (isset($item['badge']))
                        <span class="pms-nav-badge">
                            {{ $item['badge'] }}
                        </span>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="pms-nav-chevron">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    @endif

                </a>

            @endif

        @endforeach
    </nav>

</div>