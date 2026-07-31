<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Seed 4 privileged users: 2× super_admin, 2× admin.
     */
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            $this->command?->warn('UserSeeder skipped outside local/testing. Set privileged users manually.');

            return;
        }

        $password = $_ENV['SEED_PASSWORD']
            ?? $_SERVER['SEED_PASSWORD']
            ?? env('SEED_PASSWORD');

        if (! is_string($password) || $password === '') {
            $this->command?->error('Set SEED_PASSWORD in .env before seeding users.');

            return;
        }

        $guard = config('auth.defaults.guard', 'web');
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => $guard]);
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => $guard]);

        $accounts = [
            [
                'email' => 'superadmin@weddingexpo.com',
                'name' => 'Super Admin',
                'role' => $superAdminRole,
                'bio' => 'Super Administrator WeddingExpo',
                'author_color' => '#ef4444',
            ],
            [
                'email' => 'superadmin2@weddingexpo.com',
                'name' => 'Super Admin 2',
                'role' => $superAdminRole,
                'bio' => 'Super Administrator cadangan WeddingExpo',
                'author_color' => '#f97316',
            ],
            [
                'email' => 'admin@weddingexpo.com',
                'name' => 'Admin WeddingExpo',
                'role' => $adminRole,
                'bio' => 'Admin yang mengelola konten dan data WeddingExpo',
                'author_color' => '#3b82f6',
            ],
            [
                'email' => 'admin2@weddingexpo.com',
                'name' => 'Admin WeddingExpo 2',
                'role' => $adminRole,
                'bio' => 'Admin cadangan WeddingExpo',
                'author_color' => '#0ea5e9',
            ],
        ];

        foreach ($accounts as $account) {
            $user = User::updateOrCreate(
                ['email' => $account['email']],
                [
                    'name' => $account['name'],
                    'password' => Hash::make($password),
                    'email_verified_at' => now(),
                    'bio' => $account['bio'],
                    'author_color' => $account['author_color'],
                ]
            );

            $user->syncRoles([$account['role']]);
        }

        $this->command?->info('4 users seeded (2 super_admin, 2 admin). Password from SEED_PASSWORD.');
    }
}
