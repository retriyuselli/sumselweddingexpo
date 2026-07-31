<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            $this->command?->warn('CustomerSeeder skipped outside local/testing.');

            return;
        }

        $password = $_ENV['SEED_PASSWORD']
            ?? $_SERVER['SEED_PASSWORD']
            ?? env('SEED_PASSWORD');

        if (! is_string($password) || $password === '') {
            $password = 'password';
        }

        $guard = config('auth.defaults.guard', 'web');
        $customerRole = Role::firstOrCreate(['name' => 'customer', 'guard_name' => $guard]);

        $customers = [
            [
                'email' => 'andi.pratama@example.com',
                'name' => 'Andi Pratama',
                'bio' => 'Calon pengantin — Palembang',
            ],
            [
                'email' => 'sari.melati@example.com',
                'name' => 'Sari Melati',
                'bio' => 'Calon pengantin — Lubuklinggau',
            ],
            [
                'email' => 'budi.santoso@example.com',
                'name' => 'Budi Santoso',
                'bio' => 'Calon pengantin — Prabumulih',
            ],
            [
                'email' => 'dewi.lestari@example.com',
                'name' => 'Dewi Lestari',
                'bio' => 'Calon pengantin — Kayuagung',
            ],
            [
                'email' => 'rizky.maulana@example.com',
                'name' => 'Rizky Maulana',
                'bio' => 'Calon pengantin — Palembang',
            ],
        ];

        foreach ($customers as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make($password),
                    'email_verified_at' => now(),
                    'bio' => $data['bio'],
                    'author_color' => '#64748b',
                ]
            );

            $user->syncRoles([$customerRole]);
        }

        $this->command?->info('CustomerSeeder: '.count($customers).' customers ensured.');
    }
}
