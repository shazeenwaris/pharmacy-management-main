<div
    x-data="{ ready: false }"
    x-init="
        window.addEventListener('load', () => { ready = true });
        document.addEventListener('livewire:navigated', () => { ready = true });
    "
    x-show="ready"
    x-cloak
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    style="display: contents;"
>

    {{-- =========================================================
        CUSTOM CASHIER TOPBAR
    ========================================================== --}}

    <div class="pms-topbar">

        {{-- =====================================================
            LEFT SIDE
        ====================================================== --}}
        <div class="pms-topbar-left">

            {{-- Mobile Menu --}}
            <div class="pms-mobile-menu">
                <button
                    type="button"
                    @click="$store.sidebarState.toggle()"
                    aria-label="Toggle sidebar"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5"
                        />
                    </svg>
                </button>
            </div>

            {{-- Global Search --}}
            <div
                class="pms-topbar-search-wrap"
                x-data
                x-init="
                    const setPlaceholder = () => {
                        const input = $el.querySelector(
                            'input[type=search], input[type=text]'
                        );

                        if (
                            input &&
                            input.placeholder !==
                                'Search  invoice, customer...'
                        ) {
                            input.placeholder =
                                'Search  invoice, customer...';
                        }
                    };

                    setPlaceholder();

                    const observer = new MutationObserver(() => {
                        setPlaceholder();
                    });

                    observer.observe($el, {
                        childList: true,
                        subtree: true,
                        attributes: true,
                        attributeFilter: ['placeholder']
                    });

                    document.addEventListener(
                        'livewire:navigated',
                        () => setTimeout(setPlaceholder, 100)
                    );
                "
            >
                @livewire(\Filament\Livewire\GlobalSearch::class)
            </div>

        </div>


        {{-- =====================================================
            CENTER
            Empty intentionally for layout balance
        ====================================================== --}}
        <div class="pms-topbar-center"></div>


        {{-- =====================================================
            RIGHT SIDE
        ====================================================== --}}
        <div class="pms-topbar-right">

            {{-- =================================================
                NOTIFICATIONS
            ================================================== --}}
            @php
                use App\Models\CashierNotification;

                $cashierUnreadNotifications = CashierNotification::unread()
                    ->latest()
                    ->take(8)
                    ->get();

                $cashierUnreadCount = CashierNotification::unread()->count();
            @endphp

            <div
                class="pms-cashier-notification-wrapper"
                x-data="{ open: false }"
                @click.outside="open = false"
            >

                <button
                    type="button"
                    class="pms-topbar-action pms-notification-button"
                    @click="open = !open"
                    title="Notifications"
                    aria-label="Notifications"
                >

                    <x-heroicon-o-bell class="w-5 h-5" />

                    @if ($cashierUnreadCount > 0)
                        <span class="pms-notification-badge">
                            {{ $cashierUnreadCount > 99 ? '99+' : $cashierUnreadCount }}
                        </span>
                    @endif

                </button>


                {{-- Notification Dropdown --}}
                <div
                    x-show="open"
                    x-transition.origin.top.right
                    x-cloak
                    class="pms-cashier-notification-dropdown"
                >

                    {{-- Header --}}
                    <div class="pms-notification-header">

                        <div>
                            <h3>Notifications</h3>

                            <span>
                                {{ $cashierUnreadCount }}
                                {{ $cashierUnreadCount === 1 ? 'unread' : 'unread' }}
                            </span>
                        </div>

                        <div class="pms-notification-header-icon">
                            <x-heroicon-o-bell class="w-5 h-5" />
                        </div>

                    </div>


                    {{-- Notification List --}}
                    <div class="pms-notification-list">

                        @forelse ($cashierUnreadNotifications as $notification)

                            @php
                                $icon = match ($notification->type) {
                                    'sale_completed' => 'heroicon-o-shopping-cart',
                                    'payment_received' => 'heroicon-o-banknotes',
                                    'return_processed' => 'heroicon-o-arrow-uturn-left',
                                    'refund' => 'heroicon-o-arrow-path',
                                    default => 'heroicon-o-information-circle',
                                };

                                $iconClass = match ($notification->level) {
                                    'success' => 'success',
                                    'warning' => 'warning',
                                    'danger' => 'danger',
                                    default => 'info',
                                };
                            @endphp


                            <div class="pms-notification-item">

                                <div class="pms-notification-icon {{ $iconClass }}">
                                    <x-dynamic-component
                                        :component="$icon"
                                        class="w-5 h-5"
                                    />
                                </div>

                                <div class="pms-notification-content">

                                    <div class="pms-notification-title">
                                        {{ $notification->title }}
                                    </div>

                                    <div class="pms-notification-message">
                                        {{ $notification->message }}
                                    </div>

                                    <div class="pms-notification-time">
                                        {{ $notification->created_at?->diffForHumans() }}
                                    </div>

                                </div>

                            </div>

                        @empty

                            <div class="pms-notification-empty">

                                <div class="pms-notification-empty-icon">
                                    <x-heroicon-o-bell-slash class="w-6 h-6" />
                                </div>

                                <h4>No notifications</h4>

                                <p>
                                    You're all caught up.
                                </p>

                            </div>

                        @endforelse

                    </div>

                </div>

            </div>


            {{-- =================================================
                DIVIDER
            ================================================== --}}
            <div class="pms-topbar-divider"></div>


            {{-- =================================================
                USER
            ================================================== --}}
            <div
                class="pms-user"
                x-data="{ open: false }"
                @click.outside="open = false"
            >

                {{-- User Trigger --}}
                <div
                    class="pms-user-trigger"
                    @click="open = !open"
                >

                    {{-- Avatar --}}
                    <div class="pms-user-avatar">

                        @if (auth()->user()->avatar)

                            <img
                                src="{{ \Illuminate\Support\Facades\Storage::url(auth()->user()->avatar) }}"
                                alt="Avatar"
                            >

                        @else

                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}

                        @endif

                        <span class="pms-online-dot"></span>

                    </div>


                    {{-- User Information --}}
                    <div class="pms-user-info">

                        <span class="pms-user-name">
                            {{ auth()->user()->name ?? 'Admin' }}
                        </span>

                        <span class="pms-user-role">
                            <span class="pms-online-text-dot"></span>
                            Online
                        </span>

                    </div>


                    {{-- Chevron --}}
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2.5"
                        class="pms-user-chevron"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                        />
                    </svg>

                </div>


                {{-- User Dropdown --}}
                <div
                    x-show="open"
                    x-transition
                    x-cloak
                    class="pms-user-dropdown"
                >

                    {{-- Profile --}}
                    <a
                        href="{{ \App\Filament\Pages\EditProfile::getUrl() }}"
                        class="pms-user-dropdown-item"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"
                            />
                        </svg>

                        My Profile

                    </a>


                    {{-- Logout --}}
                    <form
                        method="POST"
                        action="{{ route('filament.admin.auth.logout') }}"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="pms-user-dropdown-logout"
                        >

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"
                                />
                            </svg>

                            Sign Out

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        CSS
    ========================================================== --}}
    <style>

        /* =========================================================
           VARIABLES
        ========================================================== */

        :root {
            --pms-sidebar-expanded: 280px;
            --pms-sidebar-collapsed: 70px;

            --pms-topbar-height: 64px;

            --pms-primary: #00838f;
            --pms-primary-dark: #006b73;

            --pms-border: #e5eeee;
            --pms-text: #24566a;
            --pms-muted: #71909a;

            --pms-soft: #eaf7f8;
        }


        /* =========================================================
           HIDE FILAMENT DEFAULT TOPBAR
        ========================================================== */

        body:has(.pms-topbar) .fi-topbar-ctn,
        body:has(.pms-topbar) .fi-topbar {
            display: none !important;
        }


        /* =========================================================
           MAIN TOPBAR
        ========================================================== */

        .pms-topbar {
            position: fixed !important;

            top: 0 !important;
            right: 0 !important;

            left: var(--pms-sidebar-expanded) !important;

            width: calc(
                100% - var(--pms-sidebar-expanded)
            ) !important;

            height: var(--pms-topbar-height) !important;
            min-height: var(--pms-topbar-height) !important;

            margin: 0 !important;
            padding: 0 18px !important;

            display: grid !important;

            /*
             * LEFT | CENTER | RIGHT
             */
            grid-template-columns:
                minmax(0, 1fr)
                auto
                auto !important;

            align-items: center !important;

            background: #ffffff !important;

            border-bottom: 1px solid var(--pms-border) !important;

            box-sizing: border-box !important;

            z-index: 9999 !important;

            transition:
                left 0.2s ease,
                width 0.2s ease !important;
        }


        /* =========================================================
           EXPANDED SIDEBAR
        ========================================================== */

        html[data-pms-sidebar="expanded"] .pms-topbar {
            left: var(--pms-sidebar-expanded) !important;

            width: calc(
                100% - var(--pms-sidebar-expanded)
            ) !important;
        }


        /* =========================================================
           COLLAPSED SIDEBAR
        ========================================================== */

        html[data-pms-sidebar="collapsed"] .pms-topbar {
            left: var(--pms-sidebar-collapsed) !important;

            width: calc(
                100% - var(--pms-sidebar-collapsed)
            ) !important;
        }


        /* =========================================================
           LEFT
        ========================================================== */

        .pms-topbar-left {
            grid-column: 1 !important;

            display: flex !important;
            align-items: center !important;

            justify-content: flex-start !important;

            min-width: 0 !important;

            margin: 0 !important;
            padding: 0 !important;

            gap: 10px !important;
        }


        /* =========================================================
           SEARCH
        ========================================================== */

        .pms-topbar-search-wrap {
            display: flex !important;
            align-items: center !important;

            width: 100% !important;
            max-width: 380px !important;

            min-width: 0 !important;

            margin: 0 !important;
            padding: 0 !important;

            box-sizing: border-box !important;
        }

        .pms-topbar-search-wrap > * {
            width: 100% !important;
            max-width: 100% !important;
            min-width: 0 !important;
        }

        .pms-topbar-search-wrap input {
            width: 100% !important;
            max-width: 100% !important;

            font-size: 13px !important;

            text-overflow: ellipsis !important;
            white-space: nowrap !important;
        }


        /* =========================================================
           CENTER
        ========================================================== */

        .pms-topbar-center {
            grid-column: 2 !important;

            width: 1px !important;
            min-width: 1px !important;

            margin: 0 !important;
            padding: 0 !important;
        }


        /* =========================================================
           RIGHT SIDE
           IMPORTANT: Notifications + Divider + User
           are ALL inside this wrapper.
        ========================================================== */

        .pms-topbar-right {
            grid-column: 3 !important;

            display: flex !important;

            align-items: center !important;
            justify-content: flex-end !important;

            min-width: 0 !important;

            margin: 0 !important;
            padding: 0 !important;

            gap: 8px !important;

            height: 100% !important;
        }


        /* =========================================================
           TOPBAR BUTTON
        ========================================================== */

        .pms-topbar-action {
            position: relative !important;

            width: 38px !important;
            height: 38px !important;

            min-width: 38px !important;

            display: flex !important;

            align-items: center !important;
            justify-content: center !important;

            padding: 0 !important;

            border: 0 !important;
            border-radius: 9px !important;

            background: transparent !important;

            color: #507681 !important;

            cursor: pointer !important;

            box-sizing: border-box !important;

            transition:
                background 0.15s ease,
                color 0.15s ease !important;
        }

        .pms-topbar-action:hover {
            background: var(--pms-soft) !important;
            color: var(--pms-primary) !important;
        }

        .pms-topbar-action svg {
            width: 19px !important;
            height: 19px !important;

            min-width: 19px !important;
            min-height: 19px !important;
        }


        /* =========================================================
           NOTIFICATION WRAPPER
        ========================================================== */

        .pms-cashier-notification-wrapper {
            position: relative !important;

            display: flex !important;
            align-items: center !important;

            flex-shrink: 0 !important;
        }


        /* =========================================================
           NOTIFICATION BUTTON
        ========================================================== */

        .pms-notification-button {
            position: relative !important;
        }


        /* =========================================================
           NOTIFICATION BADGE
        ========================================================== */

        .pms-notification-badge {
            position: absolute !important;

            top: -3px !important;
            right: -3px !important;

            min-width: 17px !important;
            height: 17px !important;

            padding: 0 4px !important;

            display: flex !important;
            align-items: center !important;
            justify-content: center !important;

            border-radius: 999px !important;

            background: var(--pms-primary) !important;

            color: #ffffff !important;

            font-size: 9px !important;
            font-weight: 700 !important;

            line-height: 1 !important;

            border: 2px solid #ffffff !important;

            box-sizing: border-box !important;
        }


        /* =========================================================
           NOTIFICATION DROPDOWN
        ========================================================== */

        .pms-cashier-notification-dropdown {
            position: absolute !important;

            top: calc(100% + 12px) !important;
            right: 0 !important;

            width: 370px !important;
            max-width: calc(100vw - 30px) !important;

            background: #ffffff !important;

            border: 1px solid #dceff1 !important;

            border-radius: 14px !important;

            box-shadow:
                0 18px 45px rgba(0, 77, 85, 0.16),
                0 4px 12px rgba(0, 77, 85, 0.08) !important;

            overflow: hidden !important;

            z-index: 99999 !important;
        }


        /* =========================================================
           NOTIFICATION HEADER
        ========================================================== */

        .pms-notification-header {
            display: flex !important;

            align-items: center !important;
            justify-content: space-between !important;

            padding: 16px 18px !important;

            background: var(--pms-soft) !important;

            border-bottom: 1px solid #dceff1 !important;
        }

        .pms-notification-header h3 {
            margin: 0 !important;

            color: var(--pms-primary-dark) !important;

            font-size: 16px !important;
            font-weight: 700 !important;
        }

        .pms-notification-header span {
            display: block !important;

            margin-top: 2px !important;

            color: #6b8589 !important;

            font-size: 11px !important;
        }

        .pms-notification-header-icon {
            width: 38px !important;
            height: 38px !important;

            display: flex !important;

            align-items: center !important;
            justify-content: center !important;

            border-radius: 10px !important;

            background: #ffffff !important;

            color: var(--pms-primary) !important;
        }


        /* =========================================================
           NOTIFICATION LIST
        ========================================================== */

        .pms-notification-list {
            max-height: 390px !important;

            overflow-y: auto !important;
        }


        /* =========================================================
           NOTIFICATION ITEM
        ========================================================== */

        .pms-notification-item {
            display: flex !important;

            align-items: flex-start !important;

            gap: 12px !important;

            padding: 14px 16px !important;

            border-bottom: 1px solid #eef6f7 !important;

            transition:
                background 0.2s ease !important;
        }

        .pms-notification-item:hover {
            background: #f7fcfc !important;
        }


        /* =========================================================
           NOTIFICATION ICON
        ========================================================== */

        .pms-notification-icon {
            flex: 0 0 40px !important;

            width: 40px !important;
            height: 40px !important;

            display: flex !important;

            align-items: center !important;
            justify-content: center !important;

            border-radius: 10px !important;
        }

        .pms-notification-icon.success {
            background: #e7f7f1 !important;
            color: #16835f !important;
        }

        .pms-notification-icon.warning {
            background: #fff7df !important;
            color: #a16b00 !important;
        }

        .pms-notification-icon.danger {
            background: #fff0f0 !important;
            color: #c0392b !important;
        }

        .pms-notification-icon.info {
            background: var(--pms-soft) !important;
            color: var(--pms-primary) !important;
        }


        /* =========================================================
           NOTIFICATION CONTENT
        ========================================================== */

        .pms-notification-content {
            min-width: 0 !important;
            flex: 1 !important;
        }

        .pms-notification-title {
            color: #183b40 !important;

            font-size: 13px !important;
            font-weight: 700 !important;

            line-height: 1.3 !important;
        }

        .pms-notification-message {
            margin-top: 3px !important;

            color: #607b80 !important;

            font-size: 12px !important;

            line-height: 1.45 !important;
        }

        .pms-notification-time {
            margin-top: 5px !important;

            color: #94a9ac !important;

            font-size: 10px !important;
        }


        /* =========================================================
           EMPTY NOTIFICATIONS
        ========================================================== */

        .pms-notification-empty {
            padding: 42px 20px !important;

            text-align: center !important;
        }

        .pms-notification-empty-icon {
            width: 52px !important;
            height: 52px !important;

            margin: 0 auto 12px !important;

            display: flex !important;

            align-items: center !important;
            justify-content: center !important;

            border-radius: 50% !important;

            background: var(--pms-soft) !important;

            color: var(--pms-primary) !important;
        }

        .pms-notification-empty h4 {
            margin: 0 !important;

            color: #29484d !important;

            font-size: 14px !important;
            font-weight: 700 !important;
        }

        .pms-notification-empty p {
            margin: 5px 0 0 !important;

            color: #91a5a8 !important;

            font-size: 12px !important;
        }


        /* =========================================================
           NOTIFICATION SCROLLBAR
        ========================================================== */

        .pms-notification-list::-webkit-scrollbar {
            width: 5px !important;
        }

        .pms-notification-list::-webkit-scrollbar-thumb {
            background: #b8dfe2 !important;
            border-radius: 10px !important;
        }


        /* =========================================================
           DIVIDER
        ========================================================== */

        .pms-topbar-divider {
            width: 1px !important;
            height: 30px !important;

            margin: 0 4px !important;

            background: #dce9eb !important;

            flex-shrink: 0 !important;
        }


        /* =========================================================
           USER
        ========================================================== */

        .pms-user {
            position: relative !important;

            display: flex !important;

            align-items: center !important;

            min-width: 0 !important;

            flex-shrink: 0 !important;
        }


        /* =========================================================
           USER TRIGGER
        ========================================================== */

        .pms-user-trigger {
            display: flex !important;

            align-items: center !important;

            gap: 9px !important;

            cursor: pointer !important;

            user-select: none !important;
        }


        /* =========================================================
           AVATAR
        ========================================================== */

        .pms-user-avatar {
            position: relative !important;

            width: 38px !important;
            height: 38px !important;

            min-width: 38px !important;

            display: flex !important;

            align-items: center !important;
            justify-content: center !important;

            overflow: hidden !important;

            border-radius: 50% !important;

            background: var(--pms-primary) !important;

            color: #ffffff !important;

            font-size: 14px !important;
            font-weight: 700 !important;

            box-sizing: border-box !important;
        }

        .pms-user-avatar img {
            width: 100% !important;
            height: 100% !important;

            object-fit: cover !important;

            border-radius: inherit !important;
        }


        /* =========================================================
           ONLINE DOT
        ========================================================== */

        .pms-online-dot {
            position: absolute !important;

            right: 0 !important;
            bottom: 0 !important;

            width: 10px !important;
            height: 10px !important;

            border-radius: 50% !important;

            background: #2ecc71 !important;

            border: 2px solid #ffffff !important;

            box-sizing: border-box !important;
        }

        .pms-online-text-dot {
            display: inline-block !important;

            width: 6px !important;
            height: 6px !important;

            margin-right: 4px !important;

            border-radius: 50% !important;

            background: #2ecc71 !important;
        }


        /* =========================================================
           USER INFO
        ========================================================== */

        .pms-user-info {
            display: flex !important;

            flex-direction: column !important;

            min-width: 0 !important;
        }

        .pms-user-name {
            color: var(--pms-text) !important;

            font-size: 13px !important;
            font-weight: 600 !important;

            line-height: 18px !important;

            white-space: nowrap !important;
        }

        .pms-user-role {
            display: flex !important;

            align-items: center !important;

            color: var(--pms-muted) !important;

            font-size: 10px !important;
            font-weight: 500 !important;

            line-height: 14px !important;
        }


        /* =========================================================
           CHEVRON
        ========================================================== */

        .pms-user-chevron {
            width: 15px !important;
            height: 15px !important;

            min-width: 15px !important;

            color: #6e8c94 !important;

            transition:
                transform 0.2s ease !important;
        }


        /* =========================================================
           USER DROPDOWN
        ========================================================== */

        .pms-user-dropdown {
            position: absolute !important;

            right: 0 !important;

            top: calc(100% + 8px) !important;

            width: 200px !important;

            background: #ffffff !important;

            border: 1px solid #e4eeee !important;

            border-radius: 12px !important;

            box-shadow:
                0 12px 28px rgba(0, 60, 65, 0.12) !important;

            overflow: hidden !important;

            z-index: 99999 !important;
        }


        /* =========================================================
           USER DROPDOWN ITEMS
        ========================================================== */

        .pms-user-dropdown-item,
        .pms-user-dropdown-logout {
            display: flex !important;

            align-items: center !important;

            gap: 8px !important;

            width: 100% !important;

            padding: 10px 14px !important;

            font-size: 13px !important;

            text-decoration: none !important;

            box-sizing: border-box !important;
        }

        .pms-user-dropdown-item {
            color: #2b5f79 !important;

            background: transparent !important;
        }

        .pms-user-dropdown-item:hover {
            background: var(--pms-soft) !important;

            color: var(--pms-primary-dark) !important;
        }

        .pms-user-dropdown-item svg,
        .pms-user-dropdown-logout svg {
            width: 16px !important;
            height: 16px !important;

            flex-shrink: 0 !important;
        }


        /* =========================================================
           LOGOUT
        ========================================================== */

        .pms-user-dropdown-logout {
            color: #dc4c4c !important;

            background: transparent !important;

            border: 0 !important;

            border-top: 1px solid #eef2f3 !important;

            cursor: pointer !important;

            text-align: left !important;
        }

        .pms-user-dropdown-logout:hover {
            background: #fff5f5 !important;
        }


        /* =========================================================
           MOBILE MENU
        ========================================================== */

        .pms-mobile-menu {
            display: none !important;

            flex-shrink: 0 !important;
        }

        .pms-mobile-menu button {
            width: 38px !important;
            height: 38px !important;

            display: flex !important;

            align-items: center !important;
            justify-content: center !important;

            padding: 0 !important;

            border: 0 !important;
            border-radius: 8px !important;

            background: transparent !important;

            color: #507681 !important;

            cursor: pointer !important;
        }

        .pms-mobile-menu button:hover {
            background: var(--pms-soft) !important;

            color: var(--pms-primary) !important;
        }

        .pms-mobile-menu svg {
            width: 20px !important;
            height: 20px !important;
        }


        /* =========================================================
           TABLET / MOBILE
        ========================================================== */

        @media (max-width: 1023px) {

            .pms-topbar {
                left: 0 !important;

                width: 100% !important;

                padding: 0 12px !important;

                grid-template-columns:
                    minmax(0, 1fr)
                    auto !important;
            }


            .pms-topbar-left {
                grid-column: 1 !important;

                gap: 8px !important;

                min-width: 0 !important;
            }


            .pms-mobile-menu {
                display: flex !important;
            }


            .pms-topbar-search-wrap {
                max-width: 300px !important;
            }


            .pms-topbar-center {
                display: none !important;
            }


            .pms-topbar-right {
                grid-column: 2 !important;

                gap: 5px !important;
            }


            .pms-user-info,
            .pms-user-chevron {
                display: none !important;
            }


            .pms-topbar-divider {
                margin: 0 2px !important;
            }

        }


        /* =========================================================
           SMALL MOBILE
        ========================================================== */

        @media (max-width: 640px) {

            .pms-topbar {
                height: 60px !important;
                min-height: 60px !important;

                padding: 0 8px !important;
            }


            .pms-topbar-search-wrap {
                max-width: 220px !important;
            }


            .pms-topbar-action,
            .pms-mobile-menu button {
                width: 36px !important;
                height: 36px !important;
            }


            .pms-user-avatar {
                width: 36px !important;
                height: 36px !important;

                min-width: 36px !important;
            }


            .pms-cashier-notification-dropdown {
                position: fixed !important;

                top: 60px !important;
                right: 10px !important;

                width: min(
                    370px,
                    calc(100vw - 20px)
                ) !important;
            }


            .pms-user-dropdown {
                right: 0 !important;
            }

        }

    </style>

</div>