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
<div class="pms-topbar">

    {{-- LEFT: Mobile menu + Search --}}
    <div class="pms-topbar-left">

        <div class="pms-mobile-menu">
            <button type="button" @click="$store.sidebarState.toggle()">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
                </svg>
            </button>
        </div>

     <div
    class="pms-topbar-search-wrap"
    style="width: 100%; max-width: 380px; flex: initial;"
    x-data
    x-init="
        const setPlaceholder = () => {
            const input = $el.querySelector('input[type=search], input[type=text]');
            if (input && input.placeholder !== 'Search medicine, invoice, customer...') {
                input.placeholder = 'Search medicine, invoice, customer...';
            }
        };

        setPlaceholder();

        const observer = new MutationObserver(() => setPlaceholder());
        observer.observe($el, { childList: true, subtree: true, attributes: true, attributeFilter: ['placeholder'] });

        document.addEventListener('livewire:navigated', () => setTimeout(setPlaceholder, 100));
    "
>
    @livewire(\Filament\Livewire\GlobalSearch::class)
</div>

    </div>

    {{-- CENTER: empty (kept for grid balance) --}}
    <div class="pms-topbar-center"></div>

    {{-- RIGHT: notifications, messages, user --}}
    <div class="pms-topbar-right">

        <a href="/admin/app-notifications" class="pms-topbar-action pms-notification-btn" title="Notifications">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
            </svg>
            @php $unread = \App\Models\AppNotification::unread()->count(); @endphp
            @if ($unread > 0)
                <span class="pms-notification-count">{{ $unread }}</span>
            @endif
        </a>

        <button type="button" class="pms-topbar-action" title="Messages">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
            </svg>
        </button>

        <div class="pms-topbar-divider"></div>

        <div class="pms-user" x-data="{ open: false }" @click.outside="open = false" style="position: relative;">
            <div @click="open = !open" style="display: flex; align-items: center; gap: 10px;">
                <div class="pms-user-avatar" style="position: relative; overflow: hidden; padding: 0;">
                    @if (auth()->user()->avatar)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url(auth()->user()->avatar) }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: inherit;">
                    @else
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    @endif
                    <span class="pms-online-dot"></span>
                </div>

                <div class="pms-user-info">
                    <span class="pms-user-name">{{ auth()->user()->name ?? 'Admin' }}</span>
                    <span class="pms-user-role">
                        <span class="pms-online-text-dot"></span>
                        Online
                    </span>
                </div>

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="pms-user-chevron">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
            </div>

            <div
                x-show="open"
                x-transition
                style="position: absolute; right: 0; top: calc(100% + 8px); width: 200px; background: #fff; border: 1px solid #e4eeee; border-radius: 12px; box-shadow: 0 12px 28px rgba(0,60,65,0.12); overflow: hidden; z-index: 50;"
                x-cloak
            >
                <a href="{{ \App\Filament\Pages\EditProfile::getUrl() }}" style="display: flex; align-items: center; gap: 8px; padding: 10px 14px; font-size: 13px; color: #2b5f79; text-decoration: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                    My Profile
                </a>

                <form method="POST" action="{{ route('filament.admin.auth.logout') }}">
                    @csrf
                    <button type="submit" style="display: flex; align-items: center; gap: 8px; width: 100%; padding: 10px 14px; font-size: 13px; color: #DC4C4C; background: transparent; border: 0; border-top: 1px solid #eef2f3; cursor: pointer; text-align: left;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                        Sign Out
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
<style>

/* =========================================================
   1. HIDE FILAMENT DEFAULT TOPBAR
   ========================================================= */

body:has(.pms-topbar) .fi-topbar-ctn,
body:has(.pms-topbar) .fi-topbar {
    display: none !important;
}


/* =========================================================
   2. CUSTOM NAVBAR
   ========================================================= */

.pms-topbar {
    position: fixed !important;

    top: 0 !important;
    right: 0 !important;
    left: var(--pms-sidebar-width, 280px) !important;

    width: calc(
        100% - var(--pms-sidebar-width, 280px)
    ) !important;

    height: 64px !important;
    min-height: 64px !important;

    margin: 0 !important;
    padding: 0 18px !important;

    z-index: 9999 !important;

    display: grid !important;

    grid-template-columns:
        minmax(0, 1fr)
        auto
        auto !important;

    align-items: center !important;

    background: #ffffff !important;

    border-bottom: 1px solid #e5eeee !important;

    box-sizing: border-box !important;

    transition:
        left 0.2s ease,
        width 0.2s ease !important;
}


/* =========================================================
   3. EXPANDED SIDEBAR
   ========================================================= */

html[data-pms-sidebar="expanded"] .pms-topbar {
    left: 280px !important;

    width: calc(100% - 280px) !important;
}


/* =========================================================
   4. COLLAPSED SIDEBAR
   ========================================================= */

html[data-pms-sidebar="collapsed"] .pms-topbar {
    left: 70px !important;

    width: calc(100% - 70px) !important;
}


/* =========================================================
   5. LEFT SIDE
   ========================================================= */

.pms-topbar-left {
    grid-column: 1 !important;

    display: flex !important;
    align-items: center !important;

    justify-content: flex-start !important;

    min-width: 0 !important;

    margin: 0 !important;
    padding: 0 !important;
}


/* =========================================================
   6. SEARCH WRAPPER
   ========================================================= */

.pms-topbar-search-wrap {
    display: flex !important;

    align-items: center !important;

    width: 100% !important;
    max-width: 380px !important;

    min-width: 0 !important;

    margin: 0 !important;
    padding: 0 !important;

    gap: 8px !important;

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

    text-overflow: clip !important;
    white-space: nowrap !important;
}


/* =========================================================
   7. CENTER
   ========================================================= */

.pms-topbar-center {
    grid-column: 2 !important;

    width: 1px !important;
    min-width: 1px !important;

    margin: 0 !important;
    padding: 0 !important;
}


/* =========================================================
   8. RIGHT SIDE
   ========================================================= */

.pms-topbar-right {
    grid-column: 3 !important;

    display: flex !important;
    align-items: center !important;
    justify-content: flex-end !important;

    min-width: 0 !important;

    margin: 0 !important;
    padding: 0 !important;

    gap: 8px !important;
}


/* =========================================================
   9. TOPBAR ACTION BUTTONS
   ========================================================= */

.pms-topbar-action {
    position: relative !important;

    width: 38px !important;
    height: 38px !important;

    display: flex !important;

    align-items: center !important;
    justify-content: center !important;

    padding: 0 !important;

    border: 0 !important;
    border-radius: 9px !important;

    background: transparent !important;

    color: #507681 !important;

    cursor: pointer !important;

    text-decoration: none !important;

    box-sizing: border-box !important;

    transition:
        background 0.15s ease,
        color 0.15s ease !important;
}


.pms-topbar-action:hover {
    background: #eaf7f8 !important;

    color: #00838f !important;
}


.pms-topbar-action svg {
    width: 19px !important;
    height: 19px !important;

    min-width: 19px !important;
    min-height: 19px !important;

    max-width: 19px !important;
    max-height: 19px !important;
}


/* =========================================================
   10. NOTIFICATION BADGE
   ========================================================= */

.pms-notification-count {
    position: absolute !important;

    top: 2px !important;
    right: 1px !important;

    min-width: 16px !important;
    height: 16px !important;

    display: flex !important;

    align-items: center !important;
    justify-content: center !important;

    padding: 0 4px !important;

    border-radius: 999px !important;

    background: #ef5350 !important;

    color: #ffffff !important;

    font-size: 9px !important;
    font-weight: 700 !important;

    line-height: 1 !important;

    border: 2px solid #ffffff !important;

    box-sizing: border-box !important;
}


/* =========================================================
   11. DIVIDER
   ========================================================= */

.pms-topbar-divider {
    width: 1px !important;

    height: 30px !important;

    margin: 0 8px !important;

    background: #dce9eb !important;

    flex-shrink: 0 !important;
}


/* =========================================================
   12. USER
   ========================================================= */

.pms-user {
    position: relative !important;

    display: flex !important;
    align-items: center !important;

    min-width: 0 !important;

    cursor: pointer !important;
}


.pms-user > div:first-child {
    display: flex !important;

    align-items: center !important;

    gap: 9px !important;
}


/* =========================================================
   13. AVATAR
   ========================================================= */

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

    background: #00838f !important;

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
   14. ONLINE DOT
   ========================================================= */

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
   15. USER INFO
   ========================================================= */

.pms-user-info {
    display: flex !important;

    flex-direction: column !important;

    min-width: 0 !important;
}


.pms-user-name {
    color: #24566a !important;

    font-size: 13px !important;
    font-weight: 600 !important;

    line-height: 18px !important;

    white-space: nowrap !important;
}


.pms-user-role {
    display: flex !important;

    align-items: center !important;

    color: #71909a !important;

    font-size: 10px !important;
    font-weight: 500 !important;

    line-height: 14px !important;
}


/* =========================================================
   16. CHEVRON
   ========================================================= */

.pms-user-chevron {
    width: 15px !important;
    height: 15px !important;

    min-width: 15px !important;

    color: #6e8c94 !important;
}


/* =========================================================
   17. MOBILE MENU
   ========================================================= */

.pms-mobile-menu {
    display: none !important;
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


.pms-mobile-menu svg {
    width: 20px !important;
    height: 20px !important;
}


/* =========================================================
   18. MOBILE
   ========================================================= */

@media (max-width: 1023px) {

    .pms-topbar {
        left: 0 !important;

        width: 100% !important;

        padding: 0 12px !important;

        grid-template-columns:
            auto minmax(0, 1fr) auto !important;
    }


    .pms-mobile-menu {
        display: flex !important;
    }


    .pms-topbar-left {
        grid-column: 1 / 3 !important;

        gap: 8px !important;
    }


    .pms-topbar-search-wrap {
        max-width: 300px !important;
    }


    .pms-topbar-center {
        display: none !important;
    }


    .pms-topbar-right {
        grid-column: 3 !important;
    }


    .pms-user-info,
    .pms-user-chevron {
        display: none !important;
    }
}
</style>

</div>