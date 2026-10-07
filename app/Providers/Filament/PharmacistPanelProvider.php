<?php

namespace App\Providers\Filament;

use App\Filament\Resources\AppNotifications\AppNotificationResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class PharmacistPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('pharmacist')
            ->path('pharmacist')
            ->login()
            ->globalSearch(\App\Filament\Pharmacist\Support\PharmacistGlobalSearch::class)
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->renderHook(
                PanelsRenderHook::BODY_START,
                fn (): string => request()->is('pharmacist/login')
                    ? ''
                    : view('filament.custom-navbar')->render(),
            )
            ->renderHook(
                PanelsRenderHook::SIDEBAR_START,
                fn (): string => view('filament.pharmacist-sidebar')->render(),
            )
            ->colors([
                'primary' => Color::hex('#00838F'),
            ])
            ->discoverResources(in: app_path('Filament/Pharmacist/Resources'), for: 'App\Filament\Pharmacist\Resources')
            ->resources([
                AppNotificationResource::class,
            ])
            ->discoverPages(in: app_path('Filament/Pharmacist/Pages'), for: 'App\Filament\Pharmacist\Pages')
            ->pages([
                \App\Filament\Pharmacist\Pages\Dashboard::class,
            ])
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}