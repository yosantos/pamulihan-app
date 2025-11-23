<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $superAdmin = Role::create(['name' => 'super_admin']);
        $admin = Role::create(['name' => 'admin']);
        $user = Role::create(['name' => 'user']);

        // Create permissions
        $permissions = [
            // User management
            'view_user',
            'view_any_user',
            'create_user',
            'update_user',
            'delete_user',
            'delete_any_user',

            // Wallet permissions
            'manage_wallet',
            'add_balance',
            'deduct_balance',
            'transfer_balance',

            // WhatsApp permissions
            'send_whatsapp',

            // Role management
            'view_role',
            'view_any_role',
            'create_role',
            'update_role',
            'delete_role',
            'delete_any_role',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign all permissions to super_admin
        $superAdmin->givePermissionTo(Permission::all());

        // Assign limited permissions to admin
        $admin->givePermissionTo([
            'view_user',
            'view_any_user',
            'create_user',
            'update_user',
            'manage_wallet',
            'add_balance',
            'deduct_balance',
            'send_whatsapp',
        ]);

        // Assign basic permissions to user
        $user->givePermissionTo([
            'view_user',
        ]);

        // Create super admin user
        $superAdminUser = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@pamulihan.com',
            'password' => bcrypt('password'),
            'phone' => '6281234567890',
        ]);

        $superAdminUser->assignRole('super_admin');

        // Create a test user
        $testUser = User::create([
            'name' => 'Test User',
            'email' => 'user@test.com',
            'password' => bcrypt('password'),
            'phone' => '6289876543210',
        ]);

        $testUser->assignRole('user');

        // Give both users initial wallet balance
        $superAdminUser->deposit(1000000, ['description' => 'Initial balance']);
        $testUser->deposit(500000, ['description' => 'Initial balance']);

        $this->command->info('Roles, permissions, and users created successfully!');
        $this->command->info('Super Admin - Email: admin@pamulihan.com | Password: password');
        $this->command->info('Test User - Email: user@test.com | Password: password');
    }
}
