<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('name', 'Anibal Schulist')->first();

if (! $user) {
    echo "User not found!\n";
    exit;
}

echo "User ID: {$user->id}\n";
echo "Name: {$user->name}\n";
echo "Uses HasRoles trait: " . (in_array(\Spatie\Permission\Traits\HasRoles::class, class_uses_recursive($user)) ? 'YES' : 'NO') . "\n";
echo "Assigned Roles: " . $user->roles->pluck('name')->implode(', ') . "\n";
echo "Can 'create sales': " . ($user->can('create sales') ? 'YES' : 'NO') . "\n";
echo "Can 'manage medicines': " . ($user->can('manage medicines') ? 'YES' : 'NO') . "\n";
echo "All Permissions: " . $user->getAllPermissions()->pluck('name')->implode(', ') . "\n";