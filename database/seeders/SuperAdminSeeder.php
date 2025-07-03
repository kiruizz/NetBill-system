<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create permissions
        $permissions = [
            'manage-users',
            'manage-clients',
            'manage-devices',
            'manage-service-plans',
            'manage-subscriptions',
            'manage-invoices',
            'manage-payments',
            'manage-tickets',
            'view-reports',
            'view-dashboard',
            'manage-network-usage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $operatorRole = Role::firstOrCreate(['name' => 'operator']);
        $supportRole = Role::firstOrCreate(['name' => 'support']);

        // Assign all permissions to super admin
        $superAdminRole->givePermissionTo(Permission::all());

        // Assign specific permissions to other roles
        $adminRole->givePermissionTo([
            'manage-clients',
            'manage-devices',
            'manage-service-plans',
            'manage-subscriptions',
            'manage-invoices',
            'manage-payments',
            'manage-tickets',
            'view-reports',
            'view-dashboard',
            'manage-network-usage',
        ]);

        $operatorRole->givePermissionTo([
            'manage-clients',
            'manage-devices',
            'manage-payments',
            'manage-tickets',
            'view-dashboard',
        ]);

        $supportRole->givePermissionTo([
            'manage-tickets',
            'view-dashboard',
        ]);

        // Create super admin user
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@chogoria-network.com'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password123'),
                'phone' => '+254700000000',
                'employee_id' => 'EMP001',
                'department' => 'IT Management',
                'status' => 'active',
            ]
        );

        $superAdmin->assignRole('super-admin');

        // Create sample admin user
        $admin = User::firstOrCreate(
            ['email' => 'manager@chogoria-network.com'],
            [
                'name' => 'Network Manager',
                'password' => Hash::make('password123'),
                'phone' => '+254700000001',
                'employee_id' => 'EMP002',
                'department' => 'Operations',
                'status' => 'active',
            ]
        );

        $admin->assignRole('admin');

        $this->command->info('Super Admin and roles created successfully!');
        $this->command->info('Super Admin Email: admin@chogoria-network.com');
        $this->command->info('Password: password123');
    }
}
