<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $guard = config('auth.defaults.guard', 'web');

        foreach (['super_admin', 'admin'] as $name) {
            Role::firstOrCreate([
                'name' => $name,
                'guard_name' => $guard,
            ]);
        }

        $this->command?->info('Roles ensured: super_admin, admin');
    }
}
