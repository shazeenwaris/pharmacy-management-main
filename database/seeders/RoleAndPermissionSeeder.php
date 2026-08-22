<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ---- Permissions (grouped by module) ----
        $permissions = [
            // Users & Settings (Admin)
            'manage users',
            'manage roles',
            'manage settings',
            'view activity logs',
            'backup database',

            // Reports
            'view reports',

            // Medicines / Inventory
            'manage medicines',
            'manage categories',
            'manage manufacturers',
            'manage suppliers',
            'view stock',
            'manage stock adjustments',
            'track batches',
            'track expiry',

            // Purchases
            'manage purchases',
            'receive stock',

            // Sales / POS
            'create sales',
            'process payments',
            'print receipts',
            'handle returns',
            'view daily sales',

            // Prescriptions
            'manage prescriptions',
            'check medicine availability',

            // Customers
            'manage customers',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ---- Roles ----
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->syncPermissions(Permission::all());

        $pharmacist = Role::firstOrCreate(['name' => 'Pharmacist']);
        $pharmacist->syncPermissions([
            'create sales',
            'process payments',
            'print receipts',
            'manage prescriptions',
            'check medicine availability',
            'handle returns',
            'view stock',
            'track expiry',
        ]);

        $cashier = Role::firstOrCreate(['name' => 'Cashier']);
        $cashier->syncPermissions([
            'create sales',
            'process payments',
            'print receipts',
            'handle returns',
            'view daily sales',
        ]);

        $inventoryManager = Role::firstOrCreate(['name' => 'Inventory Manager']);
        $inventoryManager->syncPermissions([
            'manage medicines',
            'manage suppliers',
            'manage purchases',
            'receive stock',
            'track batches',
            'track expiry',
            'manage stock adjustments',
        ]);
    }
}