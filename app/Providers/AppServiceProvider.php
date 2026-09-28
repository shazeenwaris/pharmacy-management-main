<?php

namespace App\Providers;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\Medicine;
use App\Models\Prescription;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\PurchaseReturn;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Observers\PurchaseItemObserver;
use App\Observers\PurchaseReturnObserver;
use App\Observers\SaleItemObserver;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Relation::enforceMorphMap([
            'expense'      => Expense::class,
            'user'         => \App\Models\User::class,
            'medicine'     => Medicine::class,
            'purchase'     => Purchase::class,
            'customer'     => Customer::class,
            'supplier'     => \App\Models\Supplier::class,
            'sale'         => Sale::class,
            'prescription' => Prescription::class,
        ]);

        Gate::before(function ($user, $ability) {
            return $user->hasRole('Admin') ? true : null;
        });

        PurchaseItem::observe(PurchaseItemObserver::class);
        PurchaseReturn::observe(PurchaseReturnObserver::class);
        SaleItem::observe(SaleItemObserver::class);
    }
}