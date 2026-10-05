@php
    $user = auth()->user();

    $can = function (string $permission) use ($user): bool {
        return $user?->can($permission) ?? false;
    };
@endphp

<div class="pms-sidebar-wrapper">

    <nav class="pms-sidebar">

        {{-- ============================================================
            BRAND
        ============================================================ --}}

        <div class="pms-brand">

            <div class="pms-brand-logo">
                <img
                    src="{{ asset('images/logo.jpeg') }}"
                    alt="MediCare Health"
                >
            </div>

            <div class="pms-brand-info">
                <div class="pms-brand-title">
                    MediCare Health
                </div>

                <div class="pms-brand-subtitle">
                    Pharmacy System
                </div>
            </div>

            <button
                type="button"
                class="pms-sidebar-toggle"
                id="pmsCashierSidebarToggle"
                aria-label="Toggle sidebar"
                title="Collapse sidebar"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.8"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M4 7h16M4 12h16M4 17h16"
                    />
                </svg>
            </button>

        </div>


        {{-- ============================================================
            SEARCH
        ============================================================ --}}

       <div class="pms-sidebar-search"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true" > <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m1.35-5.65a7 7 0 1 1-14 0 7 7 0 0 1 14 0z" /> </svg> <input type="text" placeholder="Search menu..." autocomplete="off" > </div>
        {{-- ============================================================
            MAIN MENU
        ============================================================ --}}

        <div class="pms-nav-section">

            <div class="pms-nav-section-title">
                Main Menu
            </div>


            {{-- Dashboard --}}
            <a
                href="{{ url('/cashier') }}"
                class="pms-nav-item {{ request()->is('cashier') ? 'active' : '' }}"
            >

                <span class="pms-nav-icon">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.8"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V10"
                        />
                    </svg>

                </span>

                <span class="pms-nav-label">
                    Dashboard
                </span>

            </a>

        </div>


        {{-- ============================================================
            SALES
        ============================================================ --}}

        @if (
            $can('create sales') ||
            $can('handle returns')
        )

            <div class="pms-nav-section">

                <div class="pms-nav-section-title">
                    Sales
                </div>


                {{-- New Sale --}}
                @if ($can('create sales'))

                    <a
                        href="{{ url('/cashier/pos') }}"
                        class="pms-nav-item {{ request()->is('cashier/pos') ? 'active' : '' }}"
                    >

                        <span class="pms-nav-icon">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.8"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 4v16m8-8H4"
                                />
                            </svg>

                        </span>

                        <span class="pms-nav-label">
                            New Sale
                        </span>

                    </a>

                @endif


                {{-- Sales History --}}
                @if ($can('create sales'))

                    <a
                        href="{{ url('/cashier/sale-history') }}"
                        class="pms-nav-item {{ request()->is('cashier/sale-history*') ? 'active' : '' }}"
                    >

                        <span class="pms-nav-icon">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.8"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 5h6M9 9h6M9 13h6M9 17h6M5 5h.01M5 9h.01M5 13h.01M5 17h.01"
                                />
                            </svg>

                        </span>

                        <span class="pms-nav-label">
                            Sales History
                        </span>

                    </a>

                @endif


                {{-- Sale Returns --}}
                @if ($can('handle returns'))

                    <a
                        href="{{ url('/cashier/sale-returns') }}"
                        class="pms-nav-item {{ request()->is('cashier/sale-returns*') ? 'active' : '' }}"
                    >

                        <span class="pms-nav-icon">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.8"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 14l-4-4 4-4"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M5 10h9a5 5 0 010 10h-1"
                                />
                            </svg>

                        </span>

                        <span class="pms-nav-label">
                            Sale Returns
                        </span>

                    </a>

                @endif

            </div>

        @endif



{{-- ============================================================
    CUSTOMERS
============================================================ --}}

<div class="pms-nav-section">

    <div class="pms-nav-section-title">
        Customers
    </div>

    {{-- Customers --}}
    <!-- <a
        href="{{ url('/cashier/customers') }}"
        class="pms-nav-item {{ request()->is('cashier/customers*') ? 'active' : '' }}"
    >

        <span class="pms-nav-icon">

            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.8"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M15 19a4 4 0 00-8 0"
                />

                <circle
                    cx="11"
                    cy="8"
                    r="3"
                />

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M19 19a4 4 0 00-3-3.87"
                />

                <path
                    stroke-linecap="round"
                    stroke-linecap="round"
                    d="M16 5.13a3 3 0 010 5.74"
                />
            </svg>

        </span>

        <span class="pms-nav-label">
            Customers
        </span>

    </a>

</div> -->


        {{-- ============================================================
            REPORTS
        ============================================================ --}}

        @if ($can('view daily sales'))

            <div class="pms-nav-section">

                <div class="pms-nav-section-title">
                    Reports
                </div>


                {{-- Daily Sales --}}
                <a
                    href="{{ url('/cashier/daily-sales') }}"
                    class="pms-nav-item {{ request()->is('cashier/daily-sales*') ? 'active' : '' }}"
                >

                    <span class="pms-nav-icon">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.8"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 3v18h18"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M7 16l4-5 3 3 5-7"
                            />

                        </svg>

                    </span>

                    <span class="pms-nav-label">
                        Daily Sales
                    </span>

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

        transition:
            grid-template-columns 0.2s ease !important;
    }
/* =========================================================
   HIDE FILAMENT NATIVE NAVIGATION
   KEEP NATIVE SIDEBAR CONTAINER
   CUSTOM CASHIER SIDEBAR REMAINS VISIBLE
   ========================================================= */

body:has(.pms-sidebar-wrapper) .fi-sidebar-nav {
    display: none !important;
}

    /* =====================================================
       3. NATIVE FILAMENT SIDEBAR = CONTAINER ONLY
       ===================================================== */

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

/* =========================================================
   HIDE NATIVE FILAMENT NAVIGATION
   ========================================================= */

body:has(.pms-sidebar-wrapper) .fi-sidebar-nav {
    display: none !important;
}
    /* =====================================================
       4. CUSTOM SIDEBAR WRAPPER
       ===================================================== */

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


    /* =====================================================
       5. CUSTOM SIDEBAR
       ===================================================== */

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

        background: #eaf7f8 !important;

        border-right: 1px solid #d5e9eb !important;

        box-sizing: border-box !important;
    }


    /* =====================================================
       6. MAIN CONTENT
       ===================================================== */

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


    /* =====================================================
       7. MAIN
       ===================================================== */

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


    /* =====================================================
       8. COLLAPSED MAIN
       ===================================================== */

    html[data-pms-sidebar="collapsed"]
    body:has(.pms-sidebar-wrapper)
    .fi-main-ctn {
        width: calc(100% - 70px) !important;

        margin-left: 70px !important;
    }


    /* =====================================================
       9. EXPANDED MAIN
       ===================================================== */

    html[data-pms-sidebar="expanded"]
    body:has(.pms-sidebar-wrapper)
    .fi-main-ctn {
        width: calc(100% - 280px) !important;

        margin-left: 280px !important;
    }
}


/* =========================================================
   10. FILAMENT TOPBAR CONTAINER
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


/* =========================================================
   11. FILAMENT TOPBAR
   ========================================================= */

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


/* =========================================================
   12. THE REAL FIX
   REMOVE FILAMENT TOPBAR START SPACE
   ========================================================= */

body:has(.pms-sidebar-wrapper) .fi-topbar-start {
    display: none !important;

    width: 0 !important;
    min-width: 0 !important;
    max-width: 0 !important;

    flex: 0 0 0 !important;

    margin: 0 !important;
    padding: 0 !important;
}


/* =========================================================
   13. TOPBAR END
   Global Search + Notifications + User
   ========================================================= */

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


/* =========================================================
   14. GLOBAL SEARCH
   ========================================================= */

body:has(.pms-sidebar-wrapper) .fi-topbar-end .fi-global-search {
    flex: 0 0 auto !important;

    width: 384px !important;
    max-width: 384px !important;
    min-width: 0 !important;

    margin: 0 auto 0 0 !important;
    padding: 0 !important;

    transform: none !important;
}


/* =========================================================
   15. GLOBAL SEARCH CONTAINER
   ========================================================= */

body:has(.pms-sidebar-wrapper) .fi-global-search-ctn {
    margin: 0 !important;
    padding: 0 !important;

    transform: none !important;
}


/* =========================================================
   16. TOPBAR RIGHT SIDE
   Notifications / Mail / User
   ========================================================= */

body:has(.pms-sidebar-wrapper) .fi-topbar-end > * {
    flex-shrink: 0 !important;
}


/* =========================================================
   17. REMOVE EXTRA LEFT SPACING
   ========================================================= */

body:has(.pms-sidebar-wrapper) .fi-topbar-end > .fi-global-search {
    margin-left: 0 !important;
}


/* =========================================================
   18. COLLAPSED TOPBAR
   ========================================================= */

html[data-pms-sidebar="collapsed"]
body:has(.pms-sidebar-wrapper)
.fi-topbar-ctn {
    left: 70px !important;

    width: calc(100% - 70px) !important;
}


/* =========================================================
   19. EXPANDED TOPBAR
   ========================================================= */

html[data-pms-sidebar="expanded"]
body:has(.pms-sidebar-wrapper)
.fi-topbar-ctn {
    left: 280px !important;

    width: calc(100% - 280px) !important;
}


/* =========================================================
   20. SIDEBAR INTERNAL STYLING
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
    padding: 0;

    overflow-x: hidden;
    overflow-y: auto;

    background: #eaf7f8;

    border-right: 1px solid #d5e9eb;

    box-sizing: border-box;

    scrollbar-width: thin;

    scrollbar-color:
        #b9d8dc
        transparent;
}


/* =========================================================
   21. SCROLLBAR
   ========================================================= */

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
   22. BRAND
   ========================================================= */

.pms-brand {
    position: relative;

    display: flex;
    align-items: center;

    width: 100%;
    min-height: 74px;

    padding: 14px 12px;

    background: #eaf7f8;

    border-bottom: 1px solid #d8ebed;

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

    border-radius: 10px;

    background: #ffffff;

    box-shadow:
        0 1px 4px rgba(0, 0, 0, 0.05);
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

    border-radius: 10px;
}


.pms-brand-info {
    min-width: 0;

    margin-left: 10px;
    margin-right: 38px;

    overflow: hidden;
}


.pms-brand-title {
    color: #075985;

    font-size: 15px;
    font-weight: 700;

    line-height: 20px;

    white-space: nowrap;
}


.pms-brand-subtitle {
    margin-top: 1px;

    color: #5f8190;

    font-size: 11px;
    font-weight: 500;

    line-height: 16px;

    white-space: nowrap;
}


/* =========================================================
   23. SIDEBAR TOGGLE
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
    border-radius: 7px;

    background: transparent;

    color: #42717c;

    cursor: pointer;

    transition:
        background 0.15s ease,
        color 0.15s ease;
}


.pms-sidebar-toggle:hover {
    background: #d4eff1;
    color: #006d77;
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
   24. SEARCH
   ========================================================= */

.pms-sidebar-search {
    width: calc(100% - 24px);
    height: 42px;

    display: flex;
    align-items: center;

    box-sizing: border-box;

    margin: 15px 12px 0;

    padding: 0 10px;
    gap: 9px;

    background: #ffffff;
    border: 1px solid #d7e8ea;
    border-radius: 10px;

    color: #6b8589;
    overflow: hidden;
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
    background: transparent;
}
/* =========================================================
   25. NAVIGATION
   ========================================================= */

.pms-nav-section {
    width: 100%;

    padding: 7px 8px 3px;

    box-sizing: border-box;

    flex-shrink: 0;
}


.pms-nav-section-title {
    padding: 6px 5px 8px;

    color: #5c8490;

    font-size: 11px;
    font-weight: 600;

    line-height: 16px;
}


.pms-nav-item {
    display: flex;
    align-items: center;

    width: 100%;
    min-height: 40px;

    padding: 0 10px;

    margin-bottom: 3px;

    border-radius: 8px;

    color: #155466;

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
    background: #d8f0f2;
    color: #006d77;
}


.pms-nav-item.active {
    background: #00838f;
    color: #ffffff;

    box-shadow:
        0 2px 5px rgba(0, 131, 143, 0.16);
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


.pms-nav-item svg {
    display: block;

    width: 18px !important;
    height: 18px !important;

    min-width: 18px !important;
    min-height: 18px !important;

    max-width: 18px !important;
    max-height: 18px !important;

    color: #668b94;

    flex-shrink: 0;
}


.pms-nav-item:hover svg {
    color: #006d77;
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


/* =========================================================
   26. COLLAPSED SIDEBAR
   ========================================================= */

/* html[data-pms-sidebar="collapsed"] .pms-brand {
    justify-content: center;

    padding-left: 8px;
    padding-right: 8px;
} */

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


/* html[data-pms-sidebar="collapsed"] .pms-sidebar-toggle {
    top: 8px;
    right: 7px;

    width: 26px;
    height: 26px;
} */


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


html[data-pms-sidebar="collapsed"] .pms-nav-item {
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


/* =========================================================
   27. NO HORIZONTAL OVERFLOW
   ========================================================= */

body:has(.pms-sidebar-wrapper) {
    overflow-x: hidden !important;
}


/* =========================================================
   28. MOBILE
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

    function setSidebarState(collapsed) {

        const html = document.documentElement;

        const width = collapsed ? '70px' : '280px';

        html.setAttribute(
            'data-pms-sidebar',
            collapsed ? 'collapsed' : 'expanded'
        );

        html.style.setProperty(
            '--pms-sidebar-width',
            width
        );

        html.style.setProperty(
            '--sidebar-width',
            width
        );

        localStorage.setItem(
            STORAGE_KEY,
            collapsed ? 'true' : 'false'
        );

        updateToggleButton();
    }


    function updateToggleButton() {

        const button = document.getElementById(
            'pmsCashierSidebarToggle'
        );

        if (!button) {
            return;
        }

        const collapsed =
            document.documentElement.getAttribute(
                'data-pms-sidebar'
            ) === 'collapsed';

        button.setAttribute(
            'aria-expanded',
            collapsed ? 'false' : 'true'
        );

        button.setAttribute(
            'title',
            collapsed
                ? 'Expand sidebar'
                : 'Collapse sidebar'
        );
    }


    function initializeSidebar() {

        const savedState =
            localStorage.getItem(STORAGE_KEY);

        const collapsed =
            savedState === 'true';

        setSidebarState(collapsed);
    }


    function attachToggle() {

        const button =
            document.getElementById(
                'pmsCashierSidebarToggle'
            );

        if (!button) {
            return;
        }

        if (
            button.dataset.sidebarReady === 'true'
        ) {
            updateToggleButton();
            return;
        }

        button.dataset.sidebarReady = 'true';

        button.addEventListener(
            'click',
            function (event) {

                event.preventDefault();
                event.stopPropagation();

                const collapsed =
                    document.documentElement.getAttribute(
                        'data-pms-sidebar'
                    ) === 'collapsed';

                setSidebarState(!collapsed);
            }
        );

        updateToggleButton();
    }


    function boot() {

        initializeSidebar();

        setTimeout(function () {
            attachToggle();
        }, 100);
    }


    if (document.readyState === 'loading') {

        document.addEventListener(
            'DOMContentLoaded',
            boot
        );

    } else {

        boot();
    }


    document.addEventListener(
        'livewire:navigated',
        function () {

            setTimeout(function () {

                const collapsed =
                    document.documentElement.getAttribute(
                        'data-pms-sidebar'
                    ) === 'collapsed';

                setSidebarState(collapsed);

                attachToggle();

            }, 150);
        }
    );


    window.addEventListener(
        'resize',
        function () {
            updateToggleButton();
        }
    );

})();
</script>