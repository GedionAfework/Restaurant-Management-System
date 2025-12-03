<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Make sure admin role exists first
        $adminRole = Role::where('slug', 'admin')->first();

        if (!$adminRole) {
            $this->command->error('Admin role not found! Please run RolePermissionSeeder first.');
            return;
        }

        // Check if admin already exists
        $adminEmail = 'admin@restaurant.com';
        $existingAdmin = User::where('email', $adminEmail)->first();

        if ($existingAdmin) {
            $this->command->info('Admin user already exists. Updating...');
            $existingAdmin->update([
                'role_id' => $adminRole->id,
                'is_admin' => true,
            ]);
            $this->command->info('Admin user updated successfully!');
        } else {
            // Create admin user
            $admin = User::create([
                'name' => 'Admin',
                'email' => $adminEmail,
                'password' => Hash::make('password'), // Change this password!
                'role_id' => $adminRole->id,
                'is_admin' => true,
            ]);

            $this->command->info('Admin user created successfully!');
            $this->command->info('Email: admin@restaurant.com');
            $this->command->warn('Password: password (Please change this!)');
        }
    }
}

