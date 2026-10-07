@persist('pms-sidebar-pharmacist')
<script>
    (function () {
        var collapsed = localStorage.getItem('pms_sidebar_collapsed') === 'true';
        document.documentElement.setAttribute('data-pms-sidebar', collapsed ? 'collapsed' : 'expanded');
        document.documentElement.style.setProperty('--pms-sidebar-width', collapsed ? '84px' : '280px');
    })();

    function pmsUpdateActiveNavLinks() {
        const currentPath = window.location.pathname.replace(/\/$/, '') || '/pharmacist';

        document.querySelectorAll('[data-nav-item], .pms-submenu-item').forEach(el => {
            el.classList.remove('pms-nav-item-active', 'pms-submenu-item-active', 'pms-supplier-parent-active');
        });

        document.querySelectorAll('a[data-nav-item], a.pms-submenu-item').forEach(link => {
            const href = link.getAttribute('href');
            if (!href) return;

            const linkPath = href.replace(/\/$/, '');
            const isActive = currentPath === linkPath || (linkPath !== '/pharmacist' && currentPath.startsWith(linkPath + '/'));

            if (isActive) {
                if (link.classList.contains('pms-submenu-item')) {
                    link.classList.add('pms-submenu-item-active');
                    const group = link.closest('[data-nav-group]');
                    if (group) {
                        const parentButton = group.querySelector('button[data-nav-item]');
                        if (parentButton) parentButton.classList.add('pms-supplier-parent-active');
                    }
                } else {
                    link.classList.add('pms-nav-item-active');
                }
            }
        });
    }

    document.addEventListener('livewire:navigated', pmsUpdateActiveNavLinks);
    document.addEventListener('DOMContentLoaded', pmsUpdateActiveNavLinks);

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

/* =========================================================
   Active nav item colors — matches Admin panel exactly
========================================================= */

/* .pms-nav-item-active {
    background: #00838F !important;
    color: #ffffff !important;
    font-weight: 600 !important;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05) !important;
}

.pms-nav-item-active .pms-nav-icon,
.pms-nav-item-active .pms-nav-chevron {
    color: #ffffff !important;
    opacity: 0.9;
}

.pms-submenu-item-active {
    background: #D4F3F4 !important;
    color: #006064 !important;
    font-weight: 600 !important;
}

.pms-submenu-item-active .pms-submenu-dot {
    background: #00838F !important;
    box-shadow: 0 0 6px rgba(0, 131, 143, 0.35) !important;
}

.pms-supplier-parent-active {
    background: #D4F3F4 !important;
    color: #006064 !important;
    font-weight: 600 !important;
}

.pms-supplier-parent-active .pms-nav-icon,
.pms-supplier-parent-active .pms-nav-chevron {
    color: #00838F !important;
} */
</style>

<div class="pms-sidebar">
    @php
        $pharmacySettings = \App\Models\PharmacySetting::current();

        // Helper: exact match for the dashboard root, otherwise exact-or-slash-boundary
        // wildcard so 'sales' never accidentally matches 'sales-history', etc.
        $isLinkActive = function (?string $url) {
            if (! $url) {
                return false;
            }

            $cleanUrl = ltrim($url, '/');

            if ($cleanUrl === 'pharmacist') {
                return request()->is('pharmacist');
            }

            return request()->is($cleanUrl) || request()->is($cleanUrl . '/*');
        };
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
                <p class="pms-sidebar-brand-subtitle">Pharmacist Panel</p>
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

    <nav class="pms-sidebar-nav" x-ref="navList">

        @php
            // 'url' = null means the page isn't built yet — shown disabled/greyed out
            $sections = [
                'Main Menu' => [
                    ['label' => 'Dashboard', 'url' => '/pharmacist', 'icon' => 'home'],
                ],
                'Medicines' => [
                    ['label' => 'Search Medicines', 'url' => '/pharmacist/medicines'],
                ],
               'Sales / POS' => [

    ['label' => 'Held Sales', 'url' => '/pharmacist/held-sales'],
    ['label' => 'Sales History', 'url' => '/pharmacist/sales-history'],
    ['label' => 'Sales Returns', 'url' => '/pharmacist/sale-returns'],
],
             'Customers' => [
    ['label' => 'All Customers', 'url' => '/pharmacist/customers'],
],
       'Prescriptions' => [
    ['label' => 'All Prescriptions', 'url' => '/pharmacist/prescriptions'],
],
               'Stock' => [
    ['label' => 'Current Stock', 'url' => '/pharmacist/medicine-batches'],
],
                'Alerts' => [
    ['label' => 'Low Stock Alerts', 'url' => '/pharmacist/alerts/low-stock'],
    ['label' => 'Expiry Alerts', 'url' => '/pharmacist/alerts/expiry'],
],
'My Account' => [
    ['label' => 'My Profile', 'url' => '/pharmacist/my-profile'],
    ['label' => 'Change Password', 'url' => '/pharmacist/change-password'],
],
            ];

            $icons = [
                'home'          => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
                'beaker'        => 'M9.75 3v4.5a3.375 3.375 0 01-.673 2.014L5.25 15.75A2.25 2.25 0 007 19.5h10a2.25 2.25 0 001.75-3.736l-3.827-6.236A3.375 3.375 0 0114.25 7.5V3M8.25 3h7.5',
                'card'          => 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a1.5 1.5 0 001.5-1.5V6.75a1.5 1.5 0 00-1.5-1.5h-15a1.5 1.5 0 00-1.5 1.5v10.5a1.5 1.5 0 001.5 1.5z',
                'users'         => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z',
                'prescription'  => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                'box'           => 'M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9',
                'bell'          => 'M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0',
                'cog'           => 'M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.38.138.752.43.992l1.005.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.542-.56.94-1.11.94h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.38-.138-.752-.43-.992l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.28z',
            ];

            $sectionIcon = [
                'Main Menu' => 'home',
                'Medicines' => 'beaker',
                'Sales / POS' => 'card',
                'Customers' => 'users',
                'Prescriptions' => 'prescription',
                'Stock' => 'box',
                'Alerts' => 'bell',
                'My Account' => 'cog',
            ];
        @endphp

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

        @foreach ($sections as $sectionName => $links)
            @php
                $isSingle = count($links) === 1;
                $sectionHasActive = collect($links)->contains(fn ($l) => $isLinkActive($l['url']));
                $icon = $sectionIcon[$sectionName] ?? 'box';
            @endphp

            <p class="pms-nav-section-label">{{ strtoupper($sectionName) }}</p>

            @if ($isSingle)
                @php $link = $links[0]; @endphp
                @if ($link['url'])
                    <a
                        href="{{ $link['url'] }}"
                        wire:navigate
                        data-nav-item
                        data-nav-label="{{ $link['label'] }}"
                        class="pms-nav-item {{ $isLinkActive($link['url']) ? 'pms-nav-item-active' : '' }}"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="pms-nav-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$icon] }}" />
                        </svg>
                        <span class="pms-nav-label">{{ $link['label'] }}</span>
                    </a>
                @else
                    <div class="pms-nav-item" style="color: #b0bec5; cursor: not-allowed;" title="Coming soon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="pms-nav-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$icon] }}" />
                        </svg>
                        <span class="pms-nav-label" style="flex:1;">{{ $link['label'] }}</span>
                        <span style="font-size: 0.6rem; background: #eceff1; padding: 1px 6px; border-radius: 999px;">soon</span>
                    </div>
                @endif
            @else
                <div x-data="{ open: {{ $sectionHasActive ? 'true' : 'false' }} }" data-nav-group>
                    <button
                        type="button"
                        data-nav-item
                        data-nav-label="{{ $sectionName }}"
                        @click="
                            if ($store.sidebarState.collapsed) {
                                $store.sidebarState.expand();
                                open = true;
                            } else {
                                open = !open;
                            }
                        "
                        class="pms-nav-item {{ $sectionHasActive ? 'pms-supplier-parent-active' : '' }}"
                        style="width: 100%; border: 0; background: transparent; cursor: pointer;"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="pms-nav-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$icon] }}" />
                        </svg>

                        <span class="pms-nav-label">{{ $sectionName }}</span>

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="pms-nav-chevron" :style="open ? 'transform: rotate(90deg)' : ''" style="transition: transform 0.2s ease;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <div x-show="open" x-collapse class="pms-submenu">
                        @foreach ($links as $link)
                            @if ($link['url'])
                                <a href="{{ $link['url'] }}" wire:navigate data-nav-item data-nav-label="{{ $link['label'] }}" class="pms-submenu-item {{ $isLinkActive($link['url']) ? 'pms-submenu-item-active' : '' }}">
                                    <span class="pms-submenu-dot"></span>
                                    <span>{{ $link['label'] }}</span>
                                </a>
                            @else
                                <div class="pms-submenu-item" style="color: #b0bec5; cursor: not-allowed; justify-content: space-between;" title="Coming soon">
                                    <span style="display:flex; align-items:center; gap:10px;">
                                        <span class="pms-submenu-dot"></span>
                                        <span>{{ $link['label'] }}</span>
                                    </span>
                                    <span style="font-size: 0.6rem; background: #eceff1; padding: 1px 6px; border-radius: 999px;">soon</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach

    </nav>
</div>
<style>
    /* Sidebar ko column banao: upar brand, neeche nav jo scroll hota hai */
    .pms-sidebar {
        display: flex !important;
        flex-direction: column !important;
        height: 100vh !important;
        max-height: 100vh !important;
        overflow: hidden !important;
    }

    .pms-sidebar-brand {
        flex: 0 0 auto !important;
    }

    .pms-sidebar-nav {
        flex: 1 1 auto !important;
        min-height: 0 !important;
        height: auto !important;
        overflow-y: auto !important;
        overflow-x: hidden !important;
        padding-bottom: 32px !important;
        scrollbar-width: none !important;
    }

    .pms-sidebar-nav::-webkit-scrollbar {
        display: none !important;
    }
</style>
@endpersist