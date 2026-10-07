<?php
use App\Models\Sale;

<<<<<<< HEAD
=======
use App\Http\Controllers\CashierNotificationController;
>>>>>>> 404f8ffa9cd0037be434329645d15733c8b2fee9
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cashier\SaleController;

Route::get('/', function () {
    return view('welcome');
});

// ===== Backup download route =====
Route::get('/admin/backup-download/{path}', function (string $path) {
    $decodedPath = base64_decode($path);
    $backupsDir = storage_path('app/backups');

    // Security: only allow downloading files that are actually inside
    // the backups folder (prevents path traversal to arbitrary files)
    $realPath = realpath($decodedPath);

    abort_unless($realPath !== false, 404);
    abort_unless(str_starts_with($realPath, realpath($backupsDir)), 403);

    return response()->download($realPath);
})->name('backup.download')->middleware(['web', 'auth']);

// ===== Global live search route =====
Route::middleware(['web', 'auth'])->get('/admin/global-search', function (\Illuminate\Http\Request $request) {
    $q = trim($request->get('q', ''));

    if (strlen($q) < 2) {
        return response()->json([]);
    }

    $medicines = \App\Models\Medicine::where('name', 'like', "%{$q}%")
        ->orWhere('sku', 'like', "%{$q}%")
        ->orWhere('generic_name', 'like', "%{$q}%")
        ->limit(5)
        ->get(['id', 'name', 'sku'])
        ->map(fn ($m) => [
            'label' => $m->name,
            'sub'   => $m->sku,
            'url'   => route('filament.admin.resources.medicines.edit', $m->id),
            'type'  => 'Medicine',
        ]);

    $customers = \App\Models\Customer::where('name', 'like', "%{$q}%")
        ->orWhere('phone', 'like', "%{$q}%")
        ->limit(5)
        ->get(['id', 'name', 'phone'])
        ->map(fn ($c) => [
            'label' => $c->name,
            'sub'   => $c->phone,
            'url'   => route('filament.admin.resources.customers.edit', $c->id),
            'type'  => 'Customer',
        ]);

    return response()->json($medicines->concat($customers)->values());
})->name('admin.global-search');

Route::middleware(['auth'])
    ->prefix('cashier')
    ->name('cashier.')
    ->group(function () {

        Route::get('/sales/search-medicines', [SaleController::class, 'searchMedicines'])
            ->name('sales.search-medicines');

        Route::post('/sales', [SaleController::class, 'store'])
            ->name('sales.store');

<<<<<<< HEAD
    });
=======
        
    });

    Route::middleware(['auth'])->prefix('cashier')->group(function () {

    Route::post(
        '/notifications/{notification}/read',
        [CashierNotificationController::class, 'markAsRead']
    )->name('cashier.notifications.read');

    Route::post(
        '/notifications/read-all',
        [CashierNotificationController::class, 'markAllAsRead']
    )->name('cashier.notifications.read-all');

});
>>>>>>> 404f8ffa9cd0037be434329645d15733c8b2fee9
