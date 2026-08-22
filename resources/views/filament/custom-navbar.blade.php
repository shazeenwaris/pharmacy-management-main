<div class="pms-topbar">

    {{-- LEFT --}}
    <div class="pms-topbar-left">

        <div class="pms-mobile-menu">
            <button type="button">
                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        <div class="pms-page-heading">
            <span class="pms-page-title">
                {{ filament()->getCurrentPanel()->getBrandName() }}
            </span>

            <span class="pms-page-subtitle">
                Pharmacy Management
            </span>
        </div>

    </div>


    {{-- RIGHT --}}
    <div class="pms-topbar-right">

        {{-- SEARCH --}}
        <button class="pms-topbar-action pms-search-btn"
                type="button"
                title="Search">

            <svg viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="m21 21-4.35-4.35m1.35-5.65a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
            </svg>

        </button>


        {{-- NOTIFICATIONS --}}
        <button class="pms-topbar-action pms-notification-btn"
                type="button"
                title="Notifications">

            <svg viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31
                         A8.967 8.967 0 0 1 18 9.75V9
                         a6 6 0 0 0-12 0v.75
                         a8.967 8.967 0 0 1-2.312 6.022
                         c1.733.64 3.56 1.085 5.455 1.31
                         m5.714 0a24.255 24.255 0 0 1-5.714 0
                         m5.714 0a3 3 0 1 1-5.714 0"/>
            </svg>

            <span class="pms-notification-dot"></span>

        </button>


        {{-- DIVIDER --}}
        <div class="pms-topbar-divider"></div>


        {{-- USER --}}
        <div class="pms-user">

            <div class="pms-user-avatar">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>

            <div class="pms-user-info">

                <span class="pms-user-name">
                    {{ auth()->user()->name ?? 'Admin' }}
                </span>

                <span class="pms-user-role">
                    Administrator
                </span>

            </div>

            <svg class="pms-user-chevron"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="m6 9 6 6 6-6"/>
            </svg>

        </div>

    </div>

</div>