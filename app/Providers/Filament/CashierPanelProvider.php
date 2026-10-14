<?php

namespace App\Providers\Filament;

use App\Filament\Cashier\Pages\CashierDashboard;
use App\Filament\Cashier\Pages\DailySalesReport;
use App\Filament\Pages\EditProfile;
use App\Models\PharmacySetting;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class CashierPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('cashier')
            ->path('cashier')
            ->login()

            ->viteTheme('resources/css/filament/cashier/theme.css')
            ->darkMode(false)
            ->sidebarCollapsibleOnDesktop()

            ->brandName(
                fn () =>
                    PharmacySetting::current()->pharmacy_name
                    ?? 'Pharmacy Management System'
            )
            ->brandLogo(function () {
                $logo = PharmacySetting::current()->logo;

                return $logo ? Storage::url($logo) : null;
            })
            ->brandLogoHeight('2.5rem')

            ->colors([
                'primary' => [
                    50 => '224, 247, 250',
                    100 => '178, 235, 242',
                    200 => '128, 222, 234',
                    300 => '77, 208, 225',
                    400 => '38, 166, 154',
                    500 => '0, 131, 143',
                    600 => '0, 121, 133',
                    700 => '0, 105, 116',
                    800 => '0, 96, 100',
                    900 => '0, 77, 85',
                    950 => '0, 51, 56',
                ],
            ])

            ->renderHook(
                PanelsRenderHook::BODY_START,
                fn (): string => request()->is(['cashier/login'])
                    ? ''
                    : view('filament.cashier.custom-navbar')->render(),
            )

            ->renderHook(
                PanelsRenderHook::SIDEBAR_START,
                fn (): string =>
                    view('filament.custom-cashier-sidebar')->render(),
            )

            ->discoverResources(
                in: app_path('Filament/Cashier/Resources'),
                for: 'App\\Filament\\Cashier\\Resources'
            )

            ->discoverPages(
                in: app_path('Filament/Cashier/Pages'),
                for: 'App\\Filament\\Cashier\\Pages'
            )

            ->pages([
                CashierDashboard::class,
                DailySalesReport::class,
                EditProfile::class,
            ])

            ->discoverWidgets(
                in: app_path('Filament/Cashier/Widgets'),
                for: 'App\\Filament\\Cashier\\Widgets'
            )

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