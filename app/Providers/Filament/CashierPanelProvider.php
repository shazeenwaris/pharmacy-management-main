<?php

namespace App\Providers\Filament;

use App\Filament\Cashier\Pages\CashierDashboard;
use App\Models\PharmacySetting;
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
use App\Filament\Cashier\Pages\DailySalesReport;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class CashierPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('cashier')
            ->path('cashier')
->pages([
    DailySalesReport::class,
])
            /*
            |--------------------------------------------------------------------------
            | Authentication
            |--------------------------------------------------------------------------
            */
            ->login()

            /*
            |--------------------------------------------------------------------------
            | Theme
            |--------------------------------------------------------------------------
            |
            | We use the same theme as Admin for now.
            |
            */
           ->viteTheme('resources/css/filament/cashier/theme.css')
                // ->viteTheme('resources/css/filament/admin/theme.css')
            ->darkMode(false)

            /*
            |--------------------------------------------------------------------------
            | Sidebar
            |--------------------------------------------------------------------------
            */
            ->sidebarCollapsibleOnDesktop()

            /*
            |--------------------------------------------------------------------------
            | Branding
            |--------------------------------------------------------------------------
            */
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

            /*
            |--------------------------------------------------------------------------
            | Colors
            |--------------------------------------------------------------------------
            */
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

            /*
            |--------------------------------------------------------------------------
            | Custom Navbar
            |--------------------------------------------------------------------------
            */
           ->renderHook(
    PanelsRenderHook::BODY_START,
    fn (): string => request()->is([
        'cashier/login',
        'cashier/profile',
    ])
        ? ''
        : view('filament.cashier.custom-navbar')->render(),
)

            /*
            |--------------------------------------------------------------------------
            | Custom Cashier Sidebar
            |--------------------------------------------------------------------------
            */
            ->renderHook(
                PanelsRenderHook::SIDEBAR_START,
                fn (): string =>
                    view('filament.custom-cashier-sidebar')->render(),
            )

            /*
            |--------------------------------------------------------------------------
            | Cashier Resources
            |--------------------------------------------------------------------------
            |
            | IMPORTANT:
            | We do NOT discover the Admin resources here.
            |
            */
            ->discoverResources(
                in: app_path('Filament/Cashier/Resources'),
                for: 'App\\Filament\\Cashier\\Resources'
            )

            /*
            |--------------------------------------------------------------------------
            | Cashier Pages
            |--------------------------------------------------------------------------
            */
            ->discoverPages(
                in: app_path('Filament/Cashier/Pages'),
                for: 'App\\Filament\\Cashier\\Pages'
            )

            ->pages([
                CashierDashboard::class,
            ])

            /*
            |--------------------------------------------------------------------------
            | Cashier Widgets
            |--------------------------------------------------------------------------
            */
            ->discoverWidgets(
                in: app_path('Filament/Cashier/Widgets'),
                for: 'App\\Filament\\Cashier\\Widgets'
            )

            /*
            |--------------------------------------------------------------------------
            | Middleware
            |--------------------------------------------------------------------------
            */
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