<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles and permissions first
        $this->call(RoleAndPermissionSeeder::class);

        // Create users without factories (works in production without Faker)

        // Create a default admin user with owner role
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@growpath.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_approved' => true,
            'approved_at' => now(),
        ]);

        $admin->assignRole('owner');

        // Create a manager user
        $manager = User::create([
            'name' => 'Manager User',
            'email' => 'manager@growpath.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'user',
            'is_approved' => true,
            'approved_at' => now(),
        ]);

        $manager->assignRole('manager');

        // Create an approved test user with member role
        $testUser = User::create([
            'name' => 'Test User',
            'email' => 'test@growpath.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'user',
            'is_approved' => true,
            'approved_at' => now(),
        ]);

        $testUser->assignRole('member');

        // Only seed test data in non-production environments
        if (! app()->environment('production')) {
            // Create a pending user (not approved) for testing approval flow
            User::create([
                'name' => 'Pending User',
                'email' => 'pending@growpath.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_approved' => false,
            ]);

            // Seed companies and attach users to them
            $this->call(CompanySeeder::class);

            // Seed advanced data for testing (prospects, clients, follow-ups, blog posts, etc.)
            $this->call(AdvancedDataSeeder::class);
        }
    }
}
