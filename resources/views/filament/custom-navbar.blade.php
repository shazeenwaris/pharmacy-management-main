
<div class="pms-topbar">

    {{-- LEFT: Avatar + Welcome greeting --}}
    <div class="pms-topbar-left">

        <div class="pms-mobile-menu">
            <button type="button" @click="$store.sidebarState.toggle()">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
                </svg>
            </button>
        </div>

     <div class="pms-welcome-avatar" x-show="!$store.sidebarState.collapsed" x-cloak style="overflow: hidden; padding: 0;">
    @if (auth()->user()->avatar)
        <img src="{{ \Illuminate\Support\Facades\Storage::url(auth()->user()->avatar) }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px;">
    @else
        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
    @endif
</div>

<div class="pms-page-heading" x-show="!$store.sidebarState.collapsed" x-cloak>
    <span class="pms-page-title">Welcome back, {{ auth()->user()->name ?? 'Admin' }}</span>
    <span class="pms-page-subtitle">{{ \App\Models\PharmacySetting::current()->pharmacy_name ?? 'Pharmacy' }}</span>
</div>
    </div>

 {{-- CENTER: Search --}}

    {{-- CENTER: Search --}}
<div class="pms-topbar-center" style="width: 100%; max-width: 420px;">
    @livewire(\Filament\Livewire\GlobalSearch::class)
</div>

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
.pms-topbar {
    position: sticky !important;
    top: 0 !important;
    z-index: 30 !important;
}
</style>
