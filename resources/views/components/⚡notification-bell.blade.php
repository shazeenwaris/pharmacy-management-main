<?php

use App\Models\AppNotification;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    private function baseQuery()
    {
        return AppNotification::query()
            ->where('notifiable_type', User::class)
            ->where('notifiable_id', auth()->id());
    }

    #[Computed]
    public function unread(): int
    {
        return $this->baseQuery()->whereNull('read_at')->count();
    }

    #[Computed]
    public function items()
    {
        return $this->baseQuery()
            ->orderByRaw('read_at IS NOT NULL')
            ->latest()
            ->limit(8)
            ->get();
    }

    #[Computed]
    public function viewAllUrl(): ?string
    {
        $panelId = \Filament\Facades\Filament::getCurrentPanel()?->getId();
        $routeName = "filament.{$panelId}.resources.app-notifications.index";

        return \Illuminate\Support\Facades\Route::has($routeName)
            ? route($routeName)
            : null;
    }

    public function markAsRead(string $id): void
    {
        $this->baseQuery()->whereKey($id)->whereNull('read_at')->update(['read_at' => now()]);
        unset($this->unread, $this->items);
    }

    public function markAllAsRead(): void
    {
        $this->baseQuery()->whereNull('read_at')->update(['read_at' => now()]);
        unset($this->unread, $this->items);
    }
};
?>

<div x-data="{ open: false }" @click.outside="open = false" wire:poll.30s style="position: relative;">

    <button type="button" @click="open = !open" class="pms-topbar-action pms-notification-btn" title="Notifications" style="position: relative;">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
        </svg>
        @if ($this->unread > 0)
            <span class="pms-notification-count">{{ $this->unread > 99 ? '99+' : $this->unread }}</span>
        @endif
    </button>

    <div x-show="open" x-cloak x-transition
         style="position:absolute; right:0; top:calc(100% + 8px); width:360px; max-width:92vw; background:#fff; border:1px solid #e4eeee; border-radius:12px; box-shadow:0 12px 28px rgba(0,60,65,0.12); overflow:hidden; z-index:9999;">

        <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 14px; border-bottom:1px solid #eef2f3;">
            <strong style="font-size:14px; color:#0b3b40;">Notifications</strong>
            @if ($this->unread > 0)
                <button type="button" wire:click="markAllAsRead"
                        style="font-size:12px; color:#00838f; background:transparent; border:0; cursor:pointer;">
                    Mark all as read
                </button>
            @endif
        </div>

        <div style="max-height:360px; overflow-y:auto;">
            @forelse ($this->items as $n)
                <div wire:key="bell-{{ $n->id }}"
                     wire:click="markAsRead('{{ $n->id }}')"
                     style="padding:10px 14px; border-bottom:1px solid #f1f5f5; cursor:pointer; background: {{ $n->read_at ? '#fff' : '#effafb' }};">
                    <div style="display:flex; align-items:center; gap:6px;">
                        @if (! $n->read_at)
                            <span style="width:8px; height:8px; border-radius:50%; background:#ef5350; flex-shrink:0;"></span>
                        @endif
                        <span style="font-size:13px; color:#0b3b40; font-weight: {{ $n->read_at ? '400' : '700' }}; opacity: {{ $n->read_at ? '0.65' : '1' }};">{{ $n->title }}</span>
                    </div>
                    <div style="font-size:12px; color:#4b6b70; margin-top:2px; opacity: {{ $n->read_at ? '0.65' : '1' }};">{{ $n->message }}</div>
                    <div style="font-size:11px; color:#8aa3a6; margin-top:4px;">{{ $n->created_at?->diffForHumans() }}</div>
                </div>
            @empty
                <div style="padding:24px 14px; text-align:center; font-size:13px; color:#8aa3a6;">
                    Koi notification nahi hai
                </div>
            @endforelse
        </div>

        @if ($this->viewAllUrl)
            <a href="{{ $this->viewAllUrl }}"
               style="display:block; text-align:center; padding:10px; font-size:13px; color:#00838f; text-decoration:none; border-top:1px solid #eef2f3;">
                View all
            </a>
        @endif
    </div>
</div>