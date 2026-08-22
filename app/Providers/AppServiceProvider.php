<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Models\PurchaseItem;
use App\Observers\PurchaseItemObserver;
use App\Models\PurchaseReturn;
use App\Observers\PurchaseReturnObserver;
use App\Models\SaleItem;
use App\Observers\SaleItemObserver;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Admin') ? true : null;
        });
        PurchaseItem::observe(PurchaseItemObserver::class);
        PurchaseReturn::observe(PurchaseReturnObserver::class);
        SaleItem::observe(SaleItemObserver::class);
    }
}