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
   TOPBAR
   ========================================================= */

.pms-topbar-search-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
    min-width: 0;
}

.pms-topbar-search-wrap > * {
    width: 100% !important;
    max-width: 100% !important;
}

.pms-topbar-search-wrap input {
    width: 100% !important;
    max-width: 100% !important;
    font-size: 13px !important;
    text-overflow: clip !important;
    white-space: nowrap !important;
    overflow: visible !important;
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

.pms-topbar {
    position: sticky !important;
    top: 0 !important;
    z-index: 30 !important;
    display: grid !important;
    grid-template-columns: 1fr auto 1fr !important;
    align-items: center !important;
    margin-left: var(--pms-sidebar-width, 280px) !important;
    width: calc(100% - var(--pms-sidebar-width, 280px)) !important;
    box-sizing: border-box !important;
}

.pms-topbar-left {
    grid-column: 1 !important;
    display: flex !important;
    align-items: center !important;
    gap: 12px !important;
    justify-content: flex-start !important;
    min-width: 0 !important;
    flex: 1;
}

.pms-topbar-center {
    grid-column: 2 !important;
}

.pms-topbar-right {
    grid-column: 3 !important;
    display: flex !important;
    justify-content: flex-end !important;
    min-width: 0 !important;
}

@media (max-width: 900px) {
    .pms-topbar-search-wrap {
        display: none !important;
    }

    .pms-topbar {
        margin-left: 0 !important;
        width: 100% !important;
    }
}
</style>

</div>