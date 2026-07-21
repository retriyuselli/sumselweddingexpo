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
        if (! app()->environment(['local', 'testing'])) {
            $this->command?->warn('UserSeeder skipped outside local/testing. Set privileged users manually.');

            return;
        }

        $password = env('SEED_PASSWORD');
        if (! is_string($password) || $password === '') {
            $this->command?->error('Set SEED_PASSWORD in .env before seeding users.');

            return;
        }

        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $sweRole = Role::firstOrCreate(['name' => 'swe']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);

        $accounts = [
            [
                'email' => 'superadmin@weddingexpo.com',
                'name' => 'Super Admin',
                'role' => $superAdminRole,
                'bio' => 'Super Administrator untuk sistem WeddingExpo',
                'author_color' => '#ef4444',
            ],
            [
                'email' => 'admin@weddingexpo.com',
                'name' => 'Admin WeddingExpo',
                'role' => $adminRole,
                'bio' => 'Admin yang mengelola konten dan data WeddingExpo',
                'author_color' => '#3b82f6',
            ],
            [
                'email' => 'swe@weddingexpo.com',
                'name' => 'Sumsel Wedding Expo',
                'role' => $sweRole,
                'bio' => 'Akun penyelenggara Sumsel Wedding Expo.',
                'author_color' => '#f59e0b',
            ],
            [
                'email' => 'customer@weddingexpo.com',
                'name' => 'Customer Demo',
                'role' => $customerRole,
                'bio' => null,
                'author_color' => null,
            ],
        ];

        foreach ($accounts as $account) {
            $user = User::updateOrCreate(
                ['email' => $account['email']],
                array_filter([
                    'name' => $account['name'],
                    'password' => Hash::make($password),
                    'email_verified_at' => now(),
                    'bio' => $account['bio'],
                    'author_color' => $account['author_color'],
                ], fn ($v) => $v !== null)
            );
            $user->assignRole($account['role']);
        }

        $this->command?->info('Users created/updated with Spatie roles (password from SEED_PASSWORD).');
    }
}
