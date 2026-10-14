@php
    $user = auth()->user();

    $can = function (string $permission) use ($user): bool {
        return $user?->can($permission) ?? false;
    };

    // Which dropdown group contains the current page (opens automatically)
    $salesActive = request()->is('cashier/pos')
        || request()->is('cashier/sale-history*')
        || request()->is('cashier/sale-returns*');
@endphp

<div class="pms-sidebar-wrapper">

    <nav class="pms-sidebar">

        {{-- ============================================================
            BRAND
        ============================================================ --}}

        <div class="pms-brand">

            <div class="pms-brand-logo">
                <img src="{{ asset('images/logo.jpeg') }}" alt="MediCare Health">
            </div>

            <div class="pms-brand-info">
                <div class="pms-brand-title">MediCare Health</div>
                <div class="pms-brand-subtitle">Pharmacy System</div>
            </div>

            <button
                type="button"
                class="pms-sidebar-toggle"
                id="pmsCashierSidebarToggle"
                aria-label="Toggle sidebar"
                title="Collapse sidebar"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16" />
                </svg>
            </button>

        </div>


        {{-- ============================================================
            SEARCH
        ============================================================ --}}

        <div class="pms-sidebar-search">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m1.35-5.65a7 7 0 1 1-14 0 7 7 0 0 1 14 0z" />
            </svg>
            <input type="text" id="pmsSidebarSearch" placeholder="Search menu..." autocomplete="off">
        </div>


        {{-- ============================================================
            MAIN MENU
        ============================================================ --}}

        <div class="pms-nav-section">

            <div class="pms-nav-section-title">Main Menu</div>

            {{-- Dashboard --}}
            <a
                href="{{ url('/cashier') }}"
                title="Dashboard"
                class="pms-nav-item {{ request()->is('cashier') ? 'active' : '' }}"
            >
                <span class="pms-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V10" />
                    </svg>
                </span>
                <span class="pms-nav-label">Dashboard</span>
            </a>

        </div>


        {{-- ============================================================
            SALES  (section title + dropdown group)
        ============================================================ --}}

        @if ($can('create sales') || $can('handle returns'))

            <div class="pms-nav-section">

                <div class="pms-nav-section-title">Sales</div>

                <div class="pms-nav-group {{ $salesActive ? 'is-open' : '' }}" data-group="sales">

                    <button type="button" class="pms-group-toggle" title="Sales &amp; POS" aria-expanded="{{ $salesActive ? 'true' : 'false' }}">
                        <span class="pms-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h2l2.4 11.2a1 1 0 001 .8h8.9a1 1 0 001-.76L20 8H6.2" />
                                <circle cx="9.5" cy="19.5" r="1.2" />
                                <circle cx="16.5" cy="19.5" r="1.2" />
                            </svg>
                        </span>
                        <span class="pms-nav-label">Sales &amp; POS</span>
                        <svg class="pms-chevron" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m9 6 6 6-6 6" />
                        </svg>
                    </button>

                    <div class="pms-group-items">
                        <div class="pms-group-items-inner">

                            {{-- New Sale --}}
                            @if ($can('create sales'))
                                <a
                                    href="{{ url('/cashier/pos') }}"
                                    class="pms-nav-item {{ request()->is('cashier/pos') ? 'active' : '' }}"
                                >
                                    <span class="pms-nav-label">New Sale</span>
                                </a>
                            @endif

                            {{-- Sales History --}}
                            @if ($can('create sales'))
                                <a
                                    href="{{ url('/cashier/sale-history') }}"
                                    class="pms-nav-item {{ request()->is('cashier/sale-history*') ? 'active' : '' }}"
                                >
                                    <span class="pms-nav-label">Sales History</span>
                                </a>
                            @endif

                            {{-- Sale Returns --}}
                            @if ($can('handle returns'))
                                <a
                                    href="{{ url('/cashier/sale-returns') }}"
                                    class="pms-nav-item {{ request()->is('cashier/sale-returns*') ? 'active' : '' }}"
                                >
                                    <span class="pms-nav-label">Sale Returns</span>
                                </a>
                            @endif

                        </div>
                    </div>

                </div>

            </div>

        @endif


        {{-- ============================================================
            REPORTS  (section title + direct link)
        ============================================================ --}}

        @if ($can('view daily sales'))

            <div class="pms-nav-section">

                <div class="pms-nav-section-title">Reports</div>

                {{-- Daily Sales --}}
                <a
                    href="{{ url('/cashier/daily-sales') }}"
                    title="Daily Sales"
                    class="pms-nav-item {{ request()->is('cashier/daily-sales*') ? 'active' : '' }}"
                >
                    <span class="pms-nav-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16l4-5 3 3 5-7" />
                        </svg>
                    </span>
                    <span class="pms-nav-label">Daily Sales</span>
                </a>

            </div>

        @endif

    </nav>

</div>



<style>

/* =========================================================
   1. SIDEBAR VARIABLES
   ========================================================= */

:root {
    --pms-sidebar-expanded: 280px;
    --pms-sidebar-collapsed: 70px;
    --pms-sidebar-width: 280px;

    --pms-bg: #e6f4f4;
    --pms-bg-soft: #d4ebeb;
    --pms-border: #cfe5e6;
    --pms-text: #1d4e5c;
    --pms-text-muted: #6b8f98;
    --pms-accent: #00838f;
    --pms-accent-dark: #006d77;
    --pms-hover: #d2eaea;
}

html[data-pms-sidebar="expanded"] {
    --pms-sidebar-width: 280px;
}

html[data-pms-sidebar="collapsed"] {
    --pms-sidebar-width: 70px;
}


/* =========================================================
   2. FILAMENT LAYOUT
   ========================================================= */

@media (min-width: 1024px) {

    body:has(.pms-sidebar-wrapper) .fi-layout {
        width: 100% !important;
        max-width: 100% !important;

        margin: 0 !important;
        padding: 0 !important;

        grid-template-columns:
            var(--pms-sidebar-width)
            minmax(0, 1fr) !important;

        column-gap: 0 !important;

        box-sizing: border-box !important;

        transition: grid-template-columns 0.2s ease !important;
    }

    /* Hide Filament native navigation, keep the sidebar container */
    body:has(.pms-sidebar-wrapper) .fi-sidebar-nav {
        display: none !important;
    }

    /* ---------- Native Filament sidebar = container only ---------- */

    body:has(.pms-sidebar-wrapper) .fi-sidebar {
        position: fixed !important;

        top: 0 !important;
        left: 0 !important;
        bottom: 0 !important;

        width: var(--pms-sidebar-width) !important;
        min-width: var(--pms-sidebar-width) !important;
        max-width: var(--pms-sidebar-width) !important;

        height: 100vh !important;
        min-height: 100vh !important;

        margin: 0 !important;
        padding: 0 !important;

        overflow: hidden !important;

        transform: none !important;

        z-index: 10000 !important;

        box-sizing: border-box !important;

        transition:
            width 0.2s ease,
            min-width 0.2s ease,
            max-width 0.2s ease !important;
    }

    /* ---------- Custom sidebar wrapper ---------- */

    body:has(.pms-sidebar-wrapper) .pms-sidebar-wrapper {
        position: absolute !important;

        top: 0 !important;
        left: 0 !important;

        width: 100% !important;
        height: 100vh !important;

        margin: 0 !important;
        padding: 0 !important;

        overflow: hidden !important;

        box-sizing: border-box !important;
    }

    /* ---------- Custom sidebar ---------- */

    body:has(.pms-sidebar-wrapper) .pms-sidebar {
        position: absolute !important;

        top: 0 !important;
        left: 0 !important;

        width: 100% !important;
        height: 100vh !important;

        display: flex !important;
        flex-direction: column !important;

        margin: 0 !important;
        padding: 0 !important;

        overflow-x: hidden !important;
        overflow-y: auto !important;

        background: linear-gradient(180deg, #e6f4f4 0%, #dff0f0 100%) !important;

        border-right: 1px solid var(--pms-border) !important;

        box-sizing: border-box !important;
    }

    /* ---------- Main content ---------- */

    body:has(.pms-sidebar-wrapper) .fi-main-ctn {
        position: relative !important;

        width: calc(100% - var(--pms-sidebar-width)) !important;

        max-width: none !important;

        margin-left: var(--pms-sidebar-width) !important;
        margin-right: 0 !important;

        padding-top: 64px !important;

        padding-left: 0 !important;
        padding-right: 0 !important;

        min-width: 0 !important;

        box-sizing: border-box !important;

        transition:
            width 0.2s ease,
            margin-left 0.2s ease !important;
    }

    body:has(.pms-sidebar-wrapper) .fi-main {
        width: 100% !important;
        max-width: none !important;

        margin: 0 !important;
        padding: 0 !important;

        min-width: 0 !important;

        box-sizing: border-box !important;
    }

    body:has(.pms-sidebar-wrapper) .fi-page {
        width: 100% !important;
        max-width: none !important;

        margin: 0 !important;

        box-sizing: border-box !important;
    }

    body:has(.pms-sidebar-wrapper) .fi-page-content {
        width: 100% !important;
        max-width: none !important;

        margin: 0 !important;

        box-sizing: border-box !important;
    }

    /* ---------- Collapsed main ---------- */

    html[data-pms-sidebar="collapsed"]
    body:has(.pms-sidebar-wrapper)
    .fi-main-ctn {
        width: calc(100% - 70px) !important;

        margin-left: 70px !important;
    }

    /* ---------- Expanded main ---------- */

    html[data-pms-sidebar="expanded"]
    body:has(.pms-sidebar-wrapper)
    .fi-main-ctn {
        width: calc(100% - 280px) !important;

        margin-left: 280px !important;
    }
}


/* =========================================================
   3. FILAMENT TOPBAR
   ========================================================= */

body:has(.pms-sidebar-wrapper) .fi-topbar-ctn {
    position: fixed !important;

    top: 0 !important;
    right: 0 !important;

    left: var(--pms-sidebar-width) !important;

    width: calc(100% - var(--pms-sidebar-width)) !important;

    height: 64px !important;

    margin: 0 !important;
    padding: 0 !important;

    z-index: 9999 !important;

    transform: none !important;

    box-sizing: border-box !important;

    transition:
        left 0.2s ease,
        width 0.2s ease !important;
}

body:has(.pms-sidebar-wrapper) .fi-topbar {
    position: relative !important;

    width: 100% !important;
    max-width: none !important;

    height: 64px !important;
    min-height: 64px !important;

    margin: 0 !important;

    padding: 0 16px !important;

    display: flex !important;
    align-items: center !important;

    transform: none !important;

    box-sizing: border-box !important;
}

/* Remove Filament topbar start space */
body:has(.pms-sidebar-wrapper) .fi-topbar-start {
    display: none !important;

    width: 0 !important;
    min-width: 0 !important;
    max-width: 0 !important;

    flex: 0 0 0 !important;

    margin: 0 !important;
    padding: 0 !important;
}

/* Topbar end: global search + notifications + user */
body:has(.pms-sidebar-wrapper) .fi-topbar-end {
    width: 100% !important;
    min-width: 0 !important;

    flex: 1 1 auto !important;

    margin: 0 !important;
    padding: 0 !important;

    display: flex !important;
    align-items: center !important;

    transform: none !important;

    box-sizing: border-box !important;
}

body:has(.pms-sidebar-wrapper) .fi-topbar-end .fi-global-search {
    flex: 0 0 auto !important;

    width: 384px !important;
    max-width: 384px !important;
    min-width: 0 !important;

    margin: 0 auto 0 0 !important;
    padding: 0 !important;

    transform: none !important;
}

body:has(.pms-sidebar-wrapper) .fi-global-search-ctn {
    margin: 0 !important;
    padding: 0 !important;

    transform: none !important;
}

body:has(.pms-sidebar-wrapper) .fi-topbar-end > * {
    flex-shrink: 0 !important;
}

body:has(.pms-sidebar-wrapper) .fi-topbar-end > .fi-global-search {
    margin-left: 0 !important;
}

html[data-pms-sidebar="collapsed"]
body:has(.pms-sidebar-wrapper)
.fi-topbar-ctn {
    left: 70px !important;

    width: calc(100% - 70px) !important;
}

html[data-pms-sidebar="expanded"]
body:has(.pms-sidebar-wrapper)
.fi-topbar-ctn {
    left: 280px !important;

    width: calc(100% - 280px) !important;
}


/* =========================================================
   4. SIDEBAR BASE
   ========================================================= */

.pms-sidebar {
    position: absolute !important;

    top: 0;
    left: 0;

    display: flex;
    flex-direction: column;

    width: 100%;
    height: 100vh;

    margin: 0;
    padding: 0 0 18px;

    overflow-x: hidden;
    overflow-y: auto;

    background: linear-gradient(180deg, #e6f4f4 0%, #dff0f0 100%);

    border-right: 1px solid var(--pms-border);

    box-sizing: border-box;

    scrollbar-width: thin;
    scrollbar-color: #b9d8dc transparent;
}

.pms-sidebar::-webkit-scrollbar {
    width: 5px;
}

.pms-sidebar::-webkit-scrollbar-track {
    background: transparent;
}

.pms-sidebar::-webkit-scrollbar-thumb {
    background: #b9d8dc;
    border-radius: 10px;
}


/* =========================================================
   5. BRAND
   ========================================================= */

.pms-brand {
    position: relative;

    display: flex;
    align-items: center;

    width: 100%;
    min-height: 74px;

    padding: 14px 12px;

    background: transparent;

    border-bottom: 1px solid var(--pms-border);

    box-sizing: border-box;

    flex-shrink: 0;
}

.pms-brand-logo {
    width: 42px;
    min-width: 42px;
    height: 42px;

    display: flex;
    align-items: center;
    justify-content: center;

    overflow: hidden;

    border-radius: 12px;

    background: #ffffff;

    box-shadow: 0 2px 8px rgba(0, 109, 119, 0.12);
}

.pms-brand-logo img {
    display: block;

    width: 42px !important;
    height: 42px !important;

    min-width: 42px !important;
    min-height: 42px !important;

    max-width: 42px !important;
    max-height: 42px !important;

    object-fit: cover;

    border-radius: 12px;
}

.pms-brand-info {
    min-width: 0;

    margin-left: 11px;
    margin-right: 38px;

    overflow: hidden;
}

.pms-brand-title {
    color: #075985;

    font-size: 15px;
    font-weight: 700;
    letter-spacing: -0.01em;

    line-height: 20px;

    white-space: nowrap;
}

.pms-brand-subtitle {
    margin-top: 1px;

    color: var(--pms-text-muted);

    font-size: 11px;
    font-weight: 500;

    line-height: 16px;

    white-space: nowrap;
}


/* =========================================================
   6. SIDEBAR TOGGLE
   ========================================================= */

.pms-sidebar-toggle {
    position: absolute;

    top: 22px;
    right: 10px;

    width: 30px;
    height: 30px;

    display: flex;
    align-items: center;
    justify-content: center;

    padding: 0;

    border: 0;
    border-radius: 8px;

    background: transparent;

    color: #42717c;

    cursor: pointer;

    transition:
        background 0.15s ease,
        color 0.15s ease;
}

.pms-sidebar-toggle:hover {
    background: var(--pms-hover);
    color: var(--pms-accent-dark);
}

.pms-sidebar-toggle svg {
    display: block;

    width: 18px !important;
    height: 18px !important;

    min-width: 18px !important;
    min-height: 18px !important;

    max-width: 18px !important;
    max-height: 18px !important;

    flex-shrink: 0;
}


/* =========================================================
   7. SEARCH
   ========================================================= */

.pms-sidebar-search {
    width: calc(100% - 24px);
    height: 42px;

    display: flex;
    align-items: center;

    box-sizing: border-box;

    margin: 16px 12px 4px;

    padding: 0 12px;
    gap: 9px;

    background: #ffffff;
    border: 1px solid #d7e8ea;
    border-radius: 11px;

    color: var(--pms-text-muted);
    overflow: hidden;

    transition:
        border-color 0.15s ease,
        box-shadow 0.15s ease;
}

.pms-sidebar-search:focus-within {
    border-color: var(--pms-accent);
    box-shadow: 0 0 0 3px rgba(0, 131, 143, 0.12);
}

.pms-sidebar-search svg {
    width: 17px;
    height: 17px;
    min-width: 17px;
    flex: 0 0 17px;
}

.pms-sidebar-search input {
    width: 100%;
    min-width: 0;
    height: 100%;
    padding: 0;
    margin: 0;
    border: 0;
    outline: 0;
    box-shadow: none;
    background: transparent;

    font-size: 13px;
    color: var(--pms-text);
}

.pms-sidebar-search input::placeholder {
    color: #93aeb4;
}


/* =========================================================
   8. NAVIGATION SECTIONS
   ========================================================= */

.pms-nav-section {
    width: 100%;

    padding: 6px 10px 0;

    box-sizing: border-box;

    flex-shrink: 0;
}

.pms-nav-section-title {
    padding: 12px 8px 6px;

    color: #5f8791;

    font-size: 9.5px;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;

    line-height: 12px;
}


/* =========================================================
   9. NAV ITEMS (top level)
   ========================================================= */

.pms-nav-item {
    position: relative;

    display: flex;
    align-items: center;

    width: 100%;
    min-height: 38px;

    padding: 0 10px;

    margin-bottom: 2px;

    border-radius: 9px;

    color: var(--pms-text);

    text-decoration: none !important;

    font-family: inherit;

    font-size: 13px;
    font-weight: 500;

    box-sizing: border-box;

    transition:
        background 0.15s ease,
        color 0.15s ease;
}

.pms-nav-item:hover {
    background: var(--pms-hover);
    color: var(--pms-accent-dark);
}

.pms-nav-item.active {
    background: linear-gradient(135deg, #00919d 0%, #00737d 100%);
    color: #ffffff;
    font-weight: 600;

    box-shadow: 0 3px 10px rgba(0, 131, 143, 0.25);
}

.pms-nav-icon {
    display: flex;
    align-items: center;
    justify-content: center;

    width: 20px;
    min-width: 20px;

    height: 20px;

    margin-right: 11px;

    flex-shrink: 0;
}

.pms-nav-item svg,
.pms-group-toggle .pms-nav-icon svg {
    display: block;

    width: 18px !important;
    height: 18px !important;

    min-width: 18px !important;
    min-height: 18px !important;

    max-width: 18px !important;
    max-height: 18px !important;

    color: #5f8791;

    flex-shrink: 0;
}

.pms-nav-item:hover svg,
.pms-group-toggle:hover .pms-nav-icon svg {
    color: var(--pms-accent-dark);
}

.pms-nav-item.active svg {
    color: #ffffff;
}

.pms-nav-label {
    display: block;

    min-width: 0;

    overflow: hidden;

    text-overflow: ellipsis;

    white-space: nowrap;
}

/* Clean keyboard focus instead of the heavy default outline */
.pms-nav-item:focus,
.pms-group-toggle:focus {
    outline: none;
}

.pms-nav-item:focus-visible,
.pms-group-toggle:focus-visible {
    outline: 2px solid var(--pms-accent);
    outline-offset: 1px;
}


/* =========================================================
   10. DROPDOWN GROUPS
   ========================================================= */

.pms-group-toggle {
    display: flex;
    align-items: center;

    width: 100%;
    min-height: 38px;

    padding: 0 10px;

    margin: 0 0 2px;

    border: 0;
    border-radius: 9px;

    background: transparent;

    color: var(--pms-text);

    font-family: inherit;
    font-size: 13px;
    font-weight: 500;
    text-align: left;

    cursor: pointer;

    box-sizing: border-box;

    transition:
        background 0.15s ease,
        color 0.15s ease;
}

.pms-group-toggle:hover {
    background: var(--pms-hover);
    color: var(--pms-accent-dark);
}

.pms-group-toggle .pms-nav-label {
    flex: 1 1 auto;
}

/* Chevron: points right when closed, down when open */
.pms-chevron {
    display: block;

    width: 14px !important;
    height: 14px !important;

    min-width: 14px !important;
    min-height: 14px !important;

    margin-left: 6px;

    color: #7fa0a8;

    flex-shrink: 0;

    transition: transform 0.22s ease;
}

.pms-nav-group.is-open > .pms-group-toggle .pms-chevron {
    transform: rotate(90deg);
}

/* Smooth open / close */
.pms-group-items {
    display: grid;
    grid-template-rows: 0fr;

    transition: grid-template-rows 0.25s ease;
}

.pms-nav-group.is-open > .pms-group-items {
    grid-template-rows: 1fr;
}

.pms-group-items-inner {
    min-height: 0;

    overflow: hidden;
}

.pms-nav-group.is-open > .pms-group-items > .pms-group-items-inner {
    padding-top: 2px;
    padding-bottom: 4px;
}

/* Sub items: small dot bullet, no icon */
.pms-group-items-inner .pms-nav-item {
    min-height: 33px;

    padding: 0 10px 0 41px;

    margin-bottom: 1px;

    background: transparent;
    box-shadow: none;

    color: #5a7d87;

    font-size: 12px;
    font-weight: 500;
}

.pms-group-items-inner .pms-nav-item::before {
    content: '';

    position: absolute;

    left: 25px;
    top: 50%;

    width: 4px;
    height: 4px;

    margin-top: -2px;

    border-radius: 50%;

    background: #9dbbc2;

    transition:
        background 0.15s ease,
        transform 0.15s ease;
}

.pms-group-items-inner .pms-nav-item:hover {
    background: var(--pms-hover);
    color: var(--pms-accent-dark);
}

.pms-group-items-inner .pms-nav-item:hover::before {
    background: var(--pms-accent);
}

.pms-group-items-inner .pms-nav-item.active {
    background: rgba(0, 131, 143, 0.12);
    color: var(--pms-accent-dark);
    font-weight: 700;

    box-shadow: none;
}

.pms-group-items-inner .pms-nav-item.active::before {
    background: var(--pms-accent);

    transform: scale(1.5);
}

.pms-nav-group.pms-search-hidden,
.pms-nav-item.pms-search-hidden {
    display: none !important;
}


/* =========================================================
   11. COLLAPSED SIDEBAR
   ========================================================= */

html[data-pms-sidebar="collapsed"] .pms-brand {
    flex-direction: column;
    justify-content: center;
    gap: 8px;

    min-height: 100px;
    padding: 12px 8px 8px;
}

html[data-pms-sidebar="collapsed"] .pms-sidebar-toggle {
    position: static;

    top: auto;
    right: auto;

    width: 32px;
    height: 32px;

    margin: 0;
}

html[data-pms-sidebar="collapsed"] .pms-brand-info {
    display: none !important;
}

html[data-pms-sidebar="collapsed"] .pms-brand-logo {
    margin: 0;
}

html[data-pms-sidebar="collapsed"] .pms-sidebar-search {
    display: none !important;
}

html[data-pms-sidebar="collapsed"] .pms-nav-section-title {
    display: none !important;
}

html[data-pms-sidebar="collapsed"] .pms-nav-section {
    padding-left: 7px;
    padding-right: 7px;
}

html[data-pms-sidebar="collapsed"] .pms-nav-item,
html[data-pms-sidebar="collapsed"] .pms-group-toggle {
    justify-content: center;

    width: 100%;

    padding-left: 0;
    padding-right: 0;

    font-size: 0;
}

html[data-pms-sidebar="collapsed"] .pms-nav-icon {
    margin-right: 0;
}

html[data-pms-sidebar="collapsed"] .pms-nav-item svg {
    margin-right: 0;
}

/* Collapsed: group shows only its icon (click expands the sidebar) */
html[data-pms-sidebar="collapsed"] .pms-group-toggle .pms-chevron,
html[data-pms-sidebar="collapsed"] .pms-group-toggle .pms-nav-label {
    display: none !important;
}

html[data-pms-sidebar="collapsed"] .pms-group-items {
    display: none !important;
}


/* =========================================================
   12. NO HORIZONTAL OVERFLOW
   ========================================================= */

body:has(.pms-sidebar-wrapper) {
    overflow-x: hidden !important;
}


/* =========================================================
   13. MOBILE
   ========================================================= */

@media (max-width: 1023px) {

    body:has(.pms-sidebar-wrapper) .fi-main-ctn {
        width: 100% !important;
        max-width: 100% !important;

        margin-left: 0 !important;

        padding-top: 64px !important;
    }

}
</style>


<script>
(function () {

    const STORAGE_KEY = 'pms_sidebar_collapsed';
    const GROUPS_KEY  = 'pms_cashier_groups';

    /* ---------------------------------------------------------
       Sidebar collapse / expand
       --------------------------------------------------------- */

    function setSidebarState(collapsed) {

        const html = document.documentElement;

        const width = collapsed ? '70px' : '280px';

        html.setAttribute(
            'data-pms-sidebar',
            collapsed ? 'collapsed' : 'expanded'
        );

        html.style.setProperty('--pms-sidebar-width', width);
        html.style.setProperty('--sidebar-width', width);

        localStorage.setItem(
            STORAGE_KEY,
            collapsed ? 'true' : 'false'
        );

        updateToggleButton();
    }

    function updateToggleButton() {

        const button = document.getElementById('pmsCashierSidebarToggle');

        if (!button) {
            return;
        }

        const collapsed =
            document.documentElement.getAttribute('data-pms-sidebar') === 'collapsed';

        button.setAttribute('aria-expanded', collapsed ? 'false' : 'true');

        button.setAttribute(
            'title',
            collapsed ? 'Expand sidebar' : 'Collapse sidebar'
        );
    }

    function initializeSidebar() {

        const savedState = localStorage.getItem(STORAGE_KEY);

        setSidebarState(savedState === 'true');
    }

    function attachToggle() {

        const button = document.getElementById('pmsCashierSidebarToggle');

        if (!button) {
            return;
        }

        if (button.dataset.sidebarReady === 'true') {
            updateToggleButton();
            return;
        }

        button.dataset.sidebarReady = 'true';

        button.addEventListener('click', function (event) {

            event.preventDefault();
            event.stopPropagation();

            const collapsed =
                document.documentElement.getAttribute('data-pms-sidebar') === 'collapsed';

            setSidebarState(!collapsed);
        });

        updateToggleButton();
    }

    /* ---------------------------------------------------------
       Dropdown groups
       --------------------------------------------------------- */

    function readGroups() {
        try {
            return JSON.parse(localStorage.getItem(GROUPS_KEY)) || {};
        } catch (e) {
            return {};
        }
    }

    function saveGroup(name, open) {
        const groups = readGroups();
        groups[name] = open;
        localStorage.setItem(GROUPS_KEY, JSON.stringify(groups));
    }

    function setGroup(group, open) {
        group.classList.toggle('is-open', open);

        const toggle = group.querySelector('.pms-group-toggle');

        if (toggle) {
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        }
    }

    function restoreGroups() {

        const saved = readGroups();

        document.querySelectorAll('.pms-nav-group').forEach(function (group) {

            const name = group.dataset.group;

            // A group holding the current page always stays open
            const hasActive = group.querySelector('.pms-nav-item.active');

            if (hasActive) {
                setGroup(group, true);
                return;
            }

            if (Object.prototype.hasOwnProperty.call(saved, name)) {
                setGroup(group, !!saved[name]);
            }
        });
    }

    // One delegated listener survives Livewire page navigation
    if (!window.__pmsCashierGroupsBound) {

        window.__pmsCashierGroupsBound = true;

        document.addEventListener('click', function (event) {

            const toggle = event.target.closest('.pms-group-toggle');

            if (!toggle) {
                return;
            }

            const group = toggle.closest('.pms-nav-group');

            if (!group) {
                return;
            }

            const isCollapsed =
                document.documentElement.getAttribute('data-pms-sidebar') === 'collapsed';

            if (isCollapsed) {
                setSidebarState(false);
                setGroup(group, true);
                saveGroup(group.dataset.group, true);
                return;
            }

            const open = !group.classList.contains('is-open');

            setGroup(group, open);
            saveGroup(group.dataset.group, open);
        });

        // Menu search: filters items, opens matching groups
        document.addEventListener('input', function (event) {

            if (event.target.id !== 'pmsSidebarSearch') {
                return;
            }

            const query = event.target.value.trim().toLowerCase();

            document.querySelectorAll('.pms-nav-group').forEach(function (group) {

                let matches = 0;

                group.querySelectorAll('.pms-nav-item').forEach(function (item) {

                    const label = item.textContent.trim().toLowerCase();
                    const show  = query === '' || label.includes(query);

                    item.classList.toggle('pms-search-hidden', !show);

                    if (show) {
                        matches++;
                    }
                });

                group.classList.toggle(
                    'pms-search-hidden',
                    query !== '' && matches === 0
                );

                if (query !== '' && matches > 0) {
                    setGroup(group, true);
                }
            });

            document
                .querySelectorAll('.pms-nav-section > a.pms-nav-item')
                .forEach(function (item) {

                    const label = item.textContent.trim().toLowerCase();

                    item.classList.toggle(
                        'pms-search-hidden',
                        query !== '' && !label.includes(query)
                    );
                });

            if (query === '') {
                restoreGroups();
            }
        });
    }

    /* ---------------------------------------------------------
       Boot
       --------------------------------------------------------- */

    function boot() {

        initializeSidebar();
        restoreGroups();

        setTimeout(function () {
            attachToggle();
        }, 100);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }

    document.addEventListener('livewire:navigated', function () {

        setTimeout(function () {

            const collapsed =
                document.documentElement.getAttribute('data-pms-sidebar') === 'collapsed';

            setSidebarState(collapsed);

            restoreGroups();
            attachToggle();

        }, 150);
    });

    window.addEventListener('resize', function () {
        updateToggleButton();
    });

})();
</script>