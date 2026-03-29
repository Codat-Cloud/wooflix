<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RolesAndUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $vendorRole = Role::firstOrCreate(['name' => 'vendor']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);

        // Create admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
        ]);

        $admin->assignRole($adminRole);

        // Create vendor user (not active yet)
        $vendor = User::create([
            'name' => 'Vendor',
            'email' => 'vendor@test.com',
            'password' => Hash::make('password'),
        ]);

        $vendor->assignRole($vendorRole);

        // Create customer
        $customer = User::create([
            'name' => 'Customer',
            'email' => 'customer@test.com',
            'password' => Hash::make('password'),
        ]);

        $customer->assignRole($customerRole);
    }
    
}
