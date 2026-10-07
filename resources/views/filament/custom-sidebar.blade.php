@persist('pms-sidebar')
<script>
    (function () {
        var collapsed = localStorage.getItem('pms_sidebar_collapsed') === 'true';
        document.documentElement.setAttribute('data-pms-sidebar', collapsed ? 'collapsed' : 'expanded');
        document.documentElement.style.setProperty('--pms-sidebar-width', collapsed ? '84px' : '280px');
    })();

    document.addEventListener('alpine:init', () => {
        if (!Alpine.store('sidebarState')) {
            Alpine.store('sidebarState', {
                collapsed: localStorage.getItem('pms_sidebar_collapsed') === 'true',

                setState(value) {
                    this.collapsed = value;
                    localStorage.setItem('pms_sidebar_collapsed', value);
                    document.documentElement.setAttribute('data-pms-sidebar', value ? 'collapsed' : 'expanded');
                    document.documentElement.style.setProperty('--pms-sidebar-width', value ? '84px' : '280px');
                },

                toggle() {
                    this.setState(!this.collapsed);
                },

                expand() {
                    this.setState(false);
                },
            });
        }
    });
</script>

 <style>
   :root { --pms-sidebar-width: 280px; }

.fi-sidebar {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    height: 100vh !important;
    width: var(--pms-sidebar-width) !important;
    min-width: var(--pms-sidebar-width) !important;
    overflow: visible !important;
    z-index: 40 !important;
    transition: width 0.2s ease, min-width 0.2s ease !important;
}

.fi-main {
    margin-inline-start: var(--pms-sidebar-width) !important;
    width: calc(100% - var(--pms-sidebar-width)) !important;
    max-width: calc(100% - var(--pms-sidebar-width)) !important;
    box-sizing: border-box !important;
    transition: margin-inline-start 0.2s ease, width 0.2s ease !important;
}

.pms-sidebar {
    width: var(--pms-sidebar-width) !important;
    min-width: var(--pms-sidebar-width) !important;
    overflow: hidden;
    transition: width 0.2s ease, min-width 0.2s ease;
}

@media (max-width: 1024px) {
    .fi-main {
        margin-inline-start: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
    }
}

   html[data-pms-sidebar="collapsed"] .pms-sidebar-brand-title,
html[data-pms-sidebar="collapsed"] .pms-sidebar-brand-subtitle,
html[data-pms-sidebar="collapsed"] .pms-nav-label,
html[data-pms-sidebar="collapsed"] .pms-nav-chevron,
html[data-pms-sidebar="collapsed"] .pms-nav-badge,
html[data-pms-sidebar="collapsed"] .pms-submenu,
html[data-pms-sidebar="collapsed"] .pms-search-box {
    display: none !important;
}

    html[data-pms-sidebar="collapsed"] .pms-sidebar-brand {
        flex-direction: column;
        justify-content: center;
        gap: 0.5rem;
        padding: 1rem 0;
    }

    html[data-pms-sidebar="collapsed"] .pms-nav-item {
        justify-content: center;
        padding-left: 0;
        padding-right: 0;
    }

    .pms-nav-item,
    .pms-nav-label,
    .pms-sidebar-brand-title,
    .pms-sidebar-brand-subtitle {
        white-space: nowrap;
    }

.fi-sidebar,
.fi-sidebar *,
.fi-sidebar-nav,
.fi-sidebar-nav * {
    scrollbar-width: none !important;
    -ms-overflow-style: none !important;
}

.fi-sidebar::-webkit-scrollbar,
.fi-sidebar *::-webkit-scrollbar,
.fi-sidebar-nav::-webkit-scrollbar,
.fi-sidebar-nav *::-webkit-scrollbar {
    width: 0 !important;
    height: 0 !important;
    display: none !important;
}

</style>


<div class="pms-sidebar">
    @php
        $pharmacySettings = \App\Models\PharmacySetting::current();
        $currentUser = auth()->user();
    @endphp

    <div class="pms-sidebar-brand" style="display: flex; align-items: center; justify-content: space-between; padding: 1rem;">
        <div style="display: flex; align-items: center; gap: 0.6rem;">
            @if ($pharmacySettings->logo)
                <img src="{{ \Illuminate\Support\Facades\Storage::url($pharmacySettings->logo) }}" alt="Logo" style="width: 3.5rem; height: 3.5rem; border-radius: 0.65rem; object-fit: cover; flex-shrink: 0; box-shadow: 0 1px 3px rgba(0,0,0,0.12);">
            @else
                <div class="pms-sidebar-brand-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
            @endif
            <div>
                <p class="pms-sidebar-brand-title">{{ $pharmacySettings->pharmacy_name ?? 'Pharmacy' }}</p>
                <p class="pms-sidebar-brand-subtitle">Pharmacy System</p>
            </div>
        </div>

        <button
            type="button"
            @click="$store.sidebarState.toggle()"
            style="background: transparent; border: none; cursor: pointer; padding: 0.35rem; color: #718792; flex-shrink: 0;"
            title="Collapse sidebar"
        >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 1.25rem; height: 1.25rem;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
            </svg>
        </button>
    </div>

    {{-- ===== NAVIGATION ===== --}}
    <nav class="pms-sidebar-nav" x-ref="navList">

        {{-- Menu Search (client-side filter) --}}
        <div class="px-3 pb-3" x-data="{ query: '' }">
            <div class="relative">
                <svg class="pointer-events-none absolute left-2.5 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                </svg>
                <input
    type="text"
    x-model="query"
    @input="
        document.querySelector('.pms-sidebar-nav').querySelectorAll('[data-nav-item]').forEach(el => {
            const label = (el.dataset.navLabel || '').toLowerCase();
            const match = label.includes(query.toLowerCase());
            el.style.display = match || query === '' ? '' : 'none';

            const group = el.closest('[data-nav-group]');
            if (group) {
                const anyVisible = [...group.querySelectorAll('[data-nav-item]')].some(i => i.style.display !== 'none');
                group.style.display = anyVisible || query === '' ? '' : 'none';
                if (query !== '' && anyVisible) {
                    $store.sidebarState.expand();
                }
            }
        });

        document.querySelector('.pms-sidebar-nav').querySelectorAll('.pms-nav-section-label').forEach(label => {
            let sibling = label.nextElementSibling;
            let anyVisible = false;
            while (sibling && !sibling.classList.contains('pms-nav-section-label')) {
                if (sibling.style.display !== 'none') { anyVisible = true; }
                sibling = sibling.nextElementSibling;
            }
            label.style.display = anyVisible || query === '' ? '' : 'none';
        });
    "
    placeholder="Search menu..."
    class="w-full rounded-lg border border-gray-200 bg-gray-50 py-2 pl-9 pr-3 text-sm text-gray-700 placeholder-gray-400 focus:border-[#00838F] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#00838F] dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200"
>
            </div>
        </div>

       @php
           
             $unreadNotifications = \App\Models\AppNotification::query()
    ->where('notifiable_type', \App\Models\User::class)
    ->where('notifiable_id', auth()->id())
    ->whereNull('read_at')
    ->count(); 
          
            $items = [
                ['label' => 'Dashboard',      'icon' => 'home',      'route' => '/admin',                'active' => request()->is('admin'), 'section' => 'Main Menu', 'permission' => null],
                ['label' => 'Medicines',      'icon' => 'beaker',    'route' => '/admin/medicines',       'active' => request()->is('admin/medicines*'), 'section' => 'Inventory', 'permission' => 'manage medicines'],
                ['label' => 'Purchases',      'icon' => 'cart',      'route' => '/admin/purchases',       'active' => request()->is('admin/purchases*'), 'section' => 'Inventory', 'permission' => 'manage purchases'],
                ['label' => 'Stock',          'icon' => 'box',       'route' => '/admin/stock',           'active' => request()->is('admin/stock*'), 'section' => 'Inventory', 'permission' => 'manage stock adjustments'],
               
                ['label' => 'Suppliers',      'icon' => 'truck',     'route' => '/admin/suppliers',       'active' => request()->is('admin/suppliers*'), 'section' => 'People', 'permission' => 'manage suppliers'],
                ['label' => 'Expenses',       'icon' => 'expense',   'route' => '/admin/expenses',        'active' => request()->is('admin/expenses*') || request()->is('admin/expense-categories*'), 'section' => 'Finance', 'permission' => 'process payments'],
                ['label' => 'Reports',        'icon' => 'chart',     'route' => '/admin/reports',         'active' => request()->is('admin/reports*'), 'section' => 'Insights', 'permission' => 'view reports'],
                ['label' => 'Alerts',         'icon' => 'bell',      'route' => '/admin/app-notifications', 'active' => request()->is('admin/app-notifications*'), 'badge' => $unreadNotifications > 0 ? $unreadNotifications : null, 'section' => 'Insights', 'permission' => null],
                ['label' => 'Expiry Tracker', 'icon' => 'clock',     'route' => '/admin/expiry-tracker',  'active' => request()->is('admin/expiry-tracker*'), 'section' => 'Insights', 'permission' => 'track expiry'],
                ['label' => 'Settings',       'icon' => 'cog',       'route' => '/admin/settings',        'active' => request()->is('admin/settings*'), 'section' => 'System', 'permission' => 'manage settings'],
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
                'finance' => 'M12 6v12m-3-3l3 3 3-3M6.75 9.75h10.5M6.75 14.25h10.5M4.5 4.5h15A2.25 2.25 0 0121.75 6.75v10.5A2.25 2.25 0 0119.5 19.5h-15a2.25 2.25 0 01-2.25-2.25V6.75A2.25 2.25 0 014.5 4.5z',
                'expense' => 'M12 6v12m-3-3l3 3 3-3M6.75 9.75h10.5M6.75 14.25h10.5',
            ];

            $groups = [
            'Medicines' => [
                    'icon' => 'beaker',
                    'active' => request()->is('admin/medicines*') || request()->is('admin/categories*') || request()->is('admin/manufacturers*') || request()->is('admin/units*') || request()->is('admin/dosage-forms*') || request()->is('admin/medicine-batches*'),
                    'links' => [
                        ['url' => '/admin/medicines', 'label' => 'All Medicines', 'active' => request()->is('admin/medicines*'), 'permission' => 'manage medicines'],
                         ['url' => '/admin/categories', 'label' => 'Categories', 'active' => request()->is('admin/categories*'), 'permission' => 'manage categories'],
                        ['url' => '/admin/manufacturers', 'label' => 'Manufacturers', 'active' => request()->is('admin/manufacturers*'), 'permission' => 'manage manufacturers'],
                        ['url' => '/admin/units', 'label' => 'Units', 'active' => request()->is('admin/units*'), 'permission' => 'manage medicines'],
                        ['url' => '/admin/dosage-forms', 'label' => 'Dosage Forms', 'active' => request()->is('admin/dosage-forms*'), 'permission' => 'manage medicines'],
                        ['url' => '/admin/medicine-batches', 'label' => 'Batches & Expiry', 'active' => request()->is('admin/medicine-batches*'), 'permission' => 'track batches'],
                    ],
                ],  
               
                'Purchases' => [
                    'icon' => 'cart',
                    'active' => request()->is('admin/purchases*') || request()->is('admin/purchase-returns*'),
                    'links' => [
                        ['url' => '/admin/purchases', 'label' => 'All Purchases', 'active' => request()->is('admin/purchases*') && !request()->is('admin/purchase-returns*'), 'permission' => 'manage purchases'],
                        ['url' => '/admin/purchase-returns', 'label' => 'Purchase Returns', 'active' => request()->is('admin/purchase-returns*'), 'permission' => 'handle returns'],
                    ],
                ],
                'Sales / POS' => [
                    'icon' => 'card',
                    'active' => request()->is('admin/sales') || request()->is('admin/sales-history*') || request()->is('admin/sale-returns*'),
                    'links' => [
                        ['url' => '/admin/sales', 'label' => 'New Sale', 'active' => request()->is('admin/sales'), 'permission' => 'create sales'],
                        ['url' => '/admin/sales-history', 'label' => 'Sales History', 'active' => request()->is('admin/sales-history*'), 'permission' => 'view daily sales'],
                        ['url' => '/admin/sale-returns', 'label' => 'Sales Returns', 'active' => request()->is('admin/sale-returns*'), 'permission' => 'handle returns'],
                    ],
                ],
                'Customers' => [
                    'icon' => 'users',
                    'active' => request()->is('admin/customers*') || request()->is('admin/customer-payments*'),
                    'links' => [
                        ['url' => '/admin/customers', 'label' => 'All Customers', 'active' => request()->is('admin/customers*') && !request()->is('admin/customer-payments*'), 'permission' => 'manage customers'],
                        ['url' => '/admin/customer-payments', 'label' => 'Customer Payments', 'active' => request()->is('admin/customer-payments*'), 'permission' => 'process payments'],
                    ],
                ],
                'Prescriptions' => [
                    'icon' => 'prescription',
                    'active' => request()->is('admin/prescriptions*'),
                    'links' => [
                        ['url' => '/admin/prescriptions', 'label' => 'All Prescriptions', 'active' => request()->is('admin/prescriptions'), 'permission' => 'manage prescriptions'],
                        ['url' => '/admin/prescriptions?status=active', 'label' => 'Pending / Active', 'active' => false, 'permission' => 'manage prescriptions'],
                        ['url' => '/admin/prescriptions?status=dispensed', 'label' => 'Dispensed', 'active' => false, 'permission' => 'manage prescriptions'],
                    ],
                ],
                'Stock' => [
                    'icon' => 'box',
                    'active' => request()->is('admin/stock*') || request()->is('admin/inventory-overview*'),
                    'links' => [
                        ['url' => '/admin/inventory-overview', 'label' => 'Inventory Overview', 'active' => request()->is('admin/inventory-overview*'), 'permission' => 'view stock'],
                        ['url' => '/admin/stock', 'label' => 'Stock Adjustments', 'active' => request()->is('admin/stock'), 'permission' => 'manage stock adjustments'],
                    ],
                ],
                'Accounting & Finance' => [
                    'icon' => 'finance',
                    'active' => request()->is('admin/accounts*') || request()->is('admin/financial-transactions*'),
                    'links' => [
                        ['url' => '/admin/accounts', 'label' => 'Cash / Bank Accounts', 'active' => request()->is('admin/accounts*'), 'permission' => 'process payments'],
                        ['url' => '/admin/financial-transactions', 'label' => 'Transactions', 'active' => request()->is('admin/financial-transactions*'), 'permission' => 'process payments'],
                    ],
                ],
                'Expenses' => [
                    'icon' => 'expense',
                    'active' => request()->is('admin/expenses*') || request()->is('admin/expense-categories*'),
                    'links' => [
                        ['url' => '/admin/expenses', 'label' => 'All Expenses', 'active' => request()->is('admin/expenses*') && !request()->is('admin/expense-categories*'), 'permission' => 'process payments'],
                        ['url' => '/admin/expense-categories', 'label' => 'Expense Categories', 'active' => request()->is('admin/expense-categories*'), 'permission' => 'process payments'],
                    ],
                ],
                'Reports' => [
                    'icon' => 'chart',
                    'active' => request()->is('admin/reports*'),
                    'links' => [
                        [
                            'url' => \App\Filament\Pages\SalesReports::getUrl(),
                            'label' => 'Sales Reports',
                            'active' => request()->is('admin/reports/sales*'),
                            'permission' => 'view reports',
                        ],
                        [
                            'url' => \App\Filament\Pages\PurchaseReports::getUrl(),
                            'label' => 'Purchase Reports',
                            'active' => request()->is('admin/reports/purchases*'),
                            'permission' => 'view reports',
                        ],
                        [
                            'url' => \App\Filament\Pages\InventoryReports::getUrl(),
                            'label' => 'Inventory Reports',
                            'active' => request()->is('admin/reports/inventory*'),
                            'permission' => 'view reports',
                        ],
                        [
                            'url' => \App\Filament\Pages\FinancialReports::getUrl(),
                            'label' => 'Financial Reports',
                            'active' => request()->is('admin/reports/financial*'),
                            'permission' => 'view reports',
                        ],
                    ],
                ],
                'Settings' => [
                    'icon' => 'cog',
                    'active' => request()->is('admin/settings*') || request()->is('admin/users*') || request()->is('admin/roles*') || request()->is('admin/audit-logs*') || request()->is('admin/backup*'),
                    'links' => [
                        ['url' => '/admin/settings', 'label' => 'General Settings', 'active' => request()->is('admin/settings*'), 'permission' => 'manage settings'],
                        ['url' => '/admin/users', 'label' => 'Users', 'active' => request()->is('admin/users*'), 'permission' => 'manage users'],
                        ['url' => '/admin/roles', 'label' => 'Roles & Permissions', 'active' => request()->is('admin/roles*'), 'permission' => 'manage roles'],
                        ['url' => '/admin/audit-logs', 'label' => 'Audit Logs', 'active' => request()->is('admin/audit-logs*'), 'permission' => 'view activity logs'],
                    ],
                ],
            ];
        @endphp

        @php $lastSection = null; @endphp

@foreach ($items as $item)

    @continue(!is_null($item['permission']) && !$currentUser->can($item['permission']))

    @if ($item['section'] !== $lastSection)
        @php $lastSection = $item['section']; @endphp
        <p class="pms-nav-section-label">{{ strtoupper($item['section']) }}</p>
    @endif

    @if (array_key_exists($item['label'], $groups) && isset($groups[$item['label']]['links']))

                @php
                    $group = $groups[$item['label']];
                    $visibleLinks = collect($group['links'])
                        ->filter(fn ($link) => !isset($link['permission']) || is_null($link['permission']) || $currentUser->can($link['permission']))
                        ->values();
                @endphp

                @continue($visibleLinks->isEmpty())

                <div x-data="{ open: {{ $group['active'] ? 'true' : 'false' }} }" data-nav-group>

                    <button
                        type="button"
                        data-nav-item
                        data-nav-label="{{ $item['label'] }}"
                        @click="
                            if ($store.sidebarState.collapsed) {
                                $store.sidebarState.expand();
                                open = true;
                            } else {
                                open = !open;
                            }
                        "
                        class="pms-nav-item {{ $group['active'] ? 'pms-supplier-parent-active' : '' }}"
                        style="width: 100%; border: 0; background: transparent; cursor: pointer;"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="pms-nav-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$group['icon']] }}" />
                        </svg>

                        <span class="pms-nav-label">{{ $item['label'] }}</span>

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="pms-nav-chevron" :style="open ? 'transform: rotate(90deg)' : ''" style="transition: transform 0.2s ease;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <div x-show="open" x-collapse class="pms-submenu">
                        @foreach ($visibleLinks as $link)
                            <a href="{{ $link['url'] }}" wire:navigate class="pms-submenu-item {{ $link['active'] ? 'pms-submenu-item-active' : '' }}">
                                <span class="pms-submenu-dot"></span>
                                <span>{{ $link['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

    @else

        {{-- ===== NORMAL SIDEBAR ITEMS (Dashboard, Stock, Suppliers, Alerts, Expiry Tracker, etc.) ===== --}}
        <a href="{{ $item['route'] }}" wire:navigate data-nav-item data-nav-label="{{ $item['label'] }}" class="pms-nav-item {{ $item['active'] ? 'pms-nav-item-active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="pms-nav-icon">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$item['icon']] }}" />
            </svg>

            <span class="pms-nav-label">
                {{ $item['label'] }}
            </span>

            @if (isset($item['badge']) && $item['badge'])
                <span class="pms-nav-badge">
                    {{ $item['badge'] }}
                </span>
            @endif
        </a>

    @endif

@endforeach

    </nav>

</div>


<style>
    /* =========================================================
   PROFESSIONAL STICKY FOOTER
   ========================================================= */

.pms-sidebar {
    position: relative !important;
    height: 100vh !important;
    max-height: 100vh !important;
    overflow: hidden !important;
}

.pms-sidebar-nav {
    height: 100% !important;
    overflow-y: auto !important;
    overflow-x: hidden !important;
    padding-bottom: 92px !important;
    scrollbar-width: none !important;
}

.pms-sidebar-nav::-webkit-scrollbar {
    display: none !important;
}

.pms-sidebar-footer {
    position: absolute !important;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    z-index: 999;
    padding: 9px 10px 11px;
    box-sizing: border-box;
    background:
        linear-gradient(
            180deg,
            rgba(239, 250, 251, 0.18),
            rgba(239, 250, 251, 0.82) 45%,
            rgba(239, 250, 251, 0.96)
        );
    border-top: 1px solid rgba(255, 255, 255, 0.72);
    backdrop-filter: blur(20px) saturate(140%);
    -webkit-backdrop-filter: blur(20px) saturate(140%);
    box-shadow: 0 -10px 30px rgba(0, 105, 115, 0.055);
}

.pms-footer-card {
    position: relative;
    width: 100%;
    height: 70px;
    min-height: 70px;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 10px;
    box-sizing: border-box;
    overflow: hidden;
    border-radius: 14px;
    border: 1px solid rgba(255, 255, 255, 0.92);
    background:
        linear-gradient(
            135deg,
            rgba(255, 255, 255, 0.84),
            rgba(229, 248, 250, 0.62)
        );
    backdrop-filter: blur(24px) saturate(150%);
    -webkit-backdrop-filter: blur(24px) saturate(150%);
    box-shadow:
        0 10px 28px rgba(0, 100, 110, 0.08),
        0 2px 7px rgba(0, 100, 110, 0.035),
        inset 0 1px 0 rgba(255, 255, 255, 0.98);
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.pms-footer-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 12px;
    right: 12px;
    height: 1px;
    background: rgba(255, 255, 255, 1);
    pointer-events: none;
}

.pms-footer-card:hover {
    transform: translateY(-2px);
    box-shadow:
        0 14px 32px rgba(0, 100, 110, 0.11),
        0 3px 8px rgba(0, 100, 110, 0.045),
        inset 0 1px 0 rgba(255, 255, 255, 1);
}

.pms-footer-logo {
    position: relative;
    z-index: 2;
    width: 42px;
    height: 42px;
    min-width: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    border-radius: 11px;
    background: rgba(255, 255, 255, 0.78);
    border: 1px solid rgba(255, 255, 255, 0.95);
    box-shadow:
        0 5px 14px rgba(0, 131, 143, 0.09),
        inset 0 1px 0 rgba(255, 255, 255, 1);
}

.pms-footer-logo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.pms-footer-info {
    position: relative;
    z-index: 2;
    flex: 1;
    min-width: 0;
    overflow: hidden;
}

.pms-footer-title {
    color: #006b73;
    font-size: 0.72rem;
    font-weight: 700;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.pms-footer-subtitle {
    margin-top: 3px;
    color: #78909c;
    font-size: 0.56rem;
    font-weight: 500;
    white-space: nowrap;
}

.pms-footer-tagline {
    margin-top: 4px;
    color: #90a4ae;
    font-size: 0.49rem;
    white-space: nowrap;
}

html[data-pms-sidebar="collapsed"] .pms-sidebar-footer {
    padding: 7px 6px 9px;
}

html[data-pms-sidebar="collapsed"] .pms-sidebar-nav {
    padding-bottom: 70px !important;
}

html[data-pms-sidebar="collapsed"] .pms-footer-card {
    width: 52px;
    height: 52px;
    min-height: 52px;
    padding: 5px;
    justify-content: center;
    border-radius: 13px;
}

html[data-pms-sidebar="collapsed"] .pms-footer-logo {
    width: 40px;
    height: 40px;
    min-width: 40px;
}
    </style>

 <style>
/* =========================================================
   TOPBAR — WELCOME GREETING + SEARCH BAR
   ========================================================= */

.pms-welcome-avatar {
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: linear-gradient(135deg, #00838f, #006064);
    color: #ffffff;
    font-size: 15px;
    font-weight: 750;
    box-shadow: 0 5px 12px rgba(5, 143, 148, 0.18);
    flex-shrink: 0;
}

.pms-topbar-search {
    display: flex;
    align-items: center;
    gap: 8px;
    flex: 1;
    max-width: 420px;
    margin: 0 24px;
    padding: 0 14px;
    height: 42px;
    background: #f7fbfb;
    border: 1px solid #e4eeee;
    border-radius: 12px;
    color: #90a4ae;
}

.pms-topbar-search svg {
    width: 17px;
    height: 17px;
    flex-shrink: 0;
}

.pms-topbar-search input {
    flex: 1;
    border: 0;
    background: transparent;
    outline: none;
    font-size: 13px;
    color: #263238;
}

.pms-topbar-search input::placeholder {
    color: #a3b3b8;
}

.pms-notification-count {
    position: absolute;
    top: 4px;
    right: 4px;
    min-width: 16px;
    height: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 4px;
    border-radius: 999px;
    background: #ef5350;
    color: #fff;
    font-size: 9px;
    font-weight: 700;
    border: 2px solid #fff;
}

.pms-online-dot {
    position: absolute;
    bottom: -1px;
    right: -1px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #2ecc71;
    border: 2px solid #ffffff;
}

.pms-online-text-dot {
    display: inline-block;
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #2ecc71;
    margin-right: 4px;
}

@media (max-width: 900px) {
    .pms-topbar-search {
        display: none;
    }
}

.pms-nav-section-label {
    margin: 14px 16px 6px;
    font-size: 0.62rem;
    font-weight: 700;
    letter-spacing: 0.05em;
    color: #90a4ae;
    text-transform: uppercase;
}

.pms-nav-section-label:first-child {
    margin-top: 4px;
}

html[data-pms-sidebar="collapsed"] .pms-nav-section-label {
    display: none !important;
}
</style>
<script>
(function () {
    function syncSidebarActive() {
        const nav = document.querySelector('.pms-sidebar-nav');
        if (!nav) return;

        const clean = (p) => (p.replace(/\/+$/, '') || '/');
        const current = clean(location.pathname);

        const isActive = (href, exact = false) => {
            if (!href || href.includes('?')) return false;
            const p = clean(new URL(href, location.origin).pathname);
            return exact ? current === p : (current === p || current.startsWith(p + '/'));
        };

        // Top-level links: Dashboard, Suppliers, Alerts, Expiry Tracker...
        nav.querySelectorAll(':scope > a.pms-nav-item').forEach((a) => {
            const href = a.getAttribute('href');
            const exact = clean(new URL(href, location.origin).pathname) === '/admin';
            a.classList.toggle('pms-nav-item-active', isActive(href, exact));
        });

        // Dropdown groups: Medicines, Purchases, Reports...
        nav.querySelectorAll('[data-nav-group]').forEach((group) => {
            let anyActive = false;

            group.querySelectorAll('.pms-submenu-item').forEach((link) => {
                const active = isActive(link.getAttribute('href'));
                link.classList.toggle('pms-submenu-item-active', active);
                if (active) anyActive = true;
            });

            const parent = group.querySelector('button.pms-nav-item');
            if (parent) parent.classList.toggle('pms-supplier-parent-active', anyActive);

            if (anyActive && window.Alpine) {
                try { Alpine.$data(group).open = true; } catch (e) {}
            }
        });
    }

    document.addEventListener('livewire:navigated', syncSidebarActive);
    syncSidebarActive();
})();
</script>
@endpersist
