<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create ERP Permissions
        $permissions = [
            'view-dashboard',
            'manage-inventory',
            'manage-orders',
            'manage-customers',
            'manage-settings',
            'view-activity-log',
            'manage-roles-permissions',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        // 2. Create ERP Roles & Assign Permissions
        $adminRole = Role::findOrCreate('Super Admin', 'web');
        $adminRole->syncPermissions(Permission::all());

        $managerRole = Role::findOrCreate('Warehouse Manager', 'web');
        $managerRole->syncPermissions(['view-dashboard', 'manage-inventory', 'manage-orders']);

        $salesRole = Role::findOrCreate('Sales Executive', 'web');
        $salesRole->syncPermissions(['view-dashboard', 'manage-orders', 'manage-customers']);

        // 3. Create Admin User & Assign Role
        $admin = User::updateOrCreate(
            ['email' => 'admin@apexerp.com'],
            [
                'name' => 'Sarah Jenkins',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        $admin->syncRoles([$adminRole]);

        // 4. Record Initial ERP Activity Log
        activity()
            ->causedBy($admin)
            ->performedOn($admin)
            ->withProperties(['ip' => '127.0.0.1', 'action' => 'System Bootstrap'])
            ->log('Admin user initialized with Super Admin role and Spatie permission tables.');
    }
}
