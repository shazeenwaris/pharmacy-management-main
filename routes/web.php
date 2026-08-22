<?php

use Illuminate\Support\Facades\Route;

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