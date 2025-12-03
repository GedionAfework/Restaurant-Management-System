<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Permissions by Module
        $permissions = [
            // Menu Module
            ['name' => 'View Menu', 'slug' => 'menu-view', 'module' => 'menu'],
            ['name' => 'Create Menu Item', 'slug' => 'menu-create', 'module' => 'menu'],
            ['name' => 'Edit Menu Item', 'slug' => 'menu-edit', 'module' => 'menu'],
            ['name' => 'Delete Menu Item', 'slug' => 'menu-delete', 'module' => 'menu'],
            
            // Orders Module
            ['name' => 'View Orders', 'slug' => 'orders-view', 'module' => 'orders'],
            ['name' => 'Create Orders', 'slug' => 'orders-create', 'module' => 'orders'],
            ['name' => 'Update Order Status', 'slug' => 'orders-update', 'module' => 'orders'],
            ['name' => 'Cancel Orders', 'slug' => 'orders-cancel', 'module' => 'orders'],
            
            // Employees Module
            ['name' => 'View Employees', 'slug' => 'employees-view', 'module' => 'employees'],
            ['name' => 'Create Employees', 'slug' => 'employees-create', 'module' => 'employees'],
            ['name' => 'Edit Employees', 'slug' => 'employees-edit', 'module' => 'employees'],
            ['name' => 'Delete Employees', 'slug' => 'employees-delete', 'module' => 'employees'],
            
            // Tables Module
            ['name' => 'View Tables', 'slug' => 'tables-view', 'module' => 'tables'],
            ['name' => 'Manage Tables', 'slug' => 'tables-manage', 'module' => 'tables'],
            ['name' => 'Assign Tables', 'slug' => 'tables-assign', 'module' => 'tables'],
            
            // Reservations Module
            ['name' => 'View Reservations', 'slug' => 'reservations-view', 'module' => 'reservations'],
            ['name' => 'Create Reservations', 'slug' => 'reservations-create', 'module' => 'reservations'],
            ['name' => 'Manage Reservations', 'slug' => 'reservations-manage', 'module' => 'reservations'],
            
            // Roles & Permissions Module
            ['name' => 'View Roles', 'slug' => 'roles-view', 'module' => 'roles'],
            ['name' => 'Manage Roles', 'slug' => 'roles-manage', 'module' => 'roles'],
            ['name' => 'Manage Permissions', 'slug' => 'permissions-manage', 'module' => 'roles'],
            
            // Dashboard Module
            ['name' => 'View Dashboard', 'slug' => 'dashboard-view', 'module' => 'dashboard'],
            ['name' => 'View Reports', 'slug' => 'reports-view', 'module' => 'dashboard'],
            
            // Inventory Module
            ['name' => 'View Inventory', 'slug' => 'inventory-view', 'module' => 'inventory'],
            ['name' => 'Manage Inventory', 'slug' => 'inventory-manage', 'module' => 'inventory'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }

        // Create Roles
        $roles = [
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Full access to all features',
                'is_system' => true,
                'color' => '#EF4444',
            ],
            [
                'name' => 'Manager',
                'slug' => 'manager',
                'description' => 'Can manage operations and view reports',
                'is_system' => false,
                'color' => '#3B82F6',
            ],
            [
                'name' => 'Waiter',
                'slug' => 'waiter',
                'description' => 'Can take orders and manage tables',
                'is_system' => false,
                'color' => '#10B981',
            ],
            [
                'name' => 'Chef',
                'slug' => 'chef',
                'description' => 'Can view and update order status',
                'is_system' => false,
                'color' => '#F59E0B',
            ],
        ];

        foreach ($roles as $roleData) {
            $role = Role::firstOrCreate(
                ['slug' => $roleData['slug']],
                $roleData
            );

            // Assign permissions based on role
            if ($role->slug === 'admin') {
                // Admin gets all permissions
                $role->assignPermissions(Permission::pluck('id')->toArray());
            } elseif ($role->slug === 'manager') {
                // Manager gets most permissions except roles/permissions management
                $permissionIds = Permission::whereNotIn('module', ['roles'])->pluck('id')->toArray();
                $role->assignPermissions($permissionIds);
            } elseif ($role->slug === 'waiter') {
                // Waiter can view menu, create orders, view reservations, manage tables
                $permissionIds = Permission::whereIn('slug', [
                    'menu-view',
                    'orders-view',
                    'orders-create',
                    'orders-update',
                    'tables-view',
                    'tables-assign',
                    'reservations-view',
                    'reservations-create',
                    'dashboard-view',
                ])->pluck('id')->toArray();
                $role->assignPermissions($permissionIds);
            } elseif ($role->slug === 'chef') {
                // Chef can view orders and update status
                $permissionIds = Permission::whereIn('slug', [
                    'menu-view',
                    'orders-view',
                    'orders-update',
                    'dashboard-view',
                ])->pluck('id')->toArray();
                $role->assignPermissions($permissionIds);
            }
        }

        $this->command->info('Roles and Permissions seeded successfully!');
    }
}
