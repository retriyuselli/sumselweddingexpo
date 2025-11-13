<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles if not exist (updated roles)
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $sweRole = Role::firstOrCreate(['name' => 'swe']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);

        // Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@weddingexpo.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'bio' => 'Super Administrator untuk sistem WeddingExpo',
                'author_color' => '#ef4444',
            ]
        );
        $superAdmin->assignRole($superAdminRole);

        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@weddingexpo.com'],
            [
                'name' => 'Admin WeddingExpo',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'bio' => 'Admin yang mengelola konten dan data WeddingExpo',
                'author_color' => '#3b82f6',
            ]
        );
        $admin->assignRole($adminRole);

        // SWE (Event Organizer)
        $swe = User::firstOrCreate(
            ['email' => 'swe@weddingexpo.com'],
            [
                'name' => 'Sumsel Wedding Expo',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'bio' => 'Akun penyelenggara Sumsel Wedding Expo.',
                'author_color' => '#f59e0b',
            ]
        );
        $swe->assignRole($sweRole);

        // Customer sample
        $customer = User::firstOrCreate(
            ['email' => 'customer@weddingexpo.com'],
            [
                'name' => 'Customer Demo',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        $customer->assignRole($customerRole);

        $this->command->info('Users created with Spatie roles:');
        $this->command->info('- Super Admin: superadmin@weddingexpo.com');
        $this->command->info('- Admin: admin@weddingexpo.com');
        $this->command->info('- SWE: swe@weddingexpo.com');
        $this->command->info('- Customer: customer@weddingexpo.com');
        $this->command->info('Password for all: password123');
    }
}
