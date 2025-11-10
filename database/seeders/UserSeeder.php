<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // Create roles if not exist
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $authorRole = Role::firstOrCreate(['name' => 'author']);
        $editorRole = Role::firstOrCreate(['name' => 'editor']);
        $vendorRole = Role::firstOrCreate(['name' => 'vendor']);

        // Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@weddingexpo.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'bio' => 'Super Administrator untuk sistem WeddingExpo',
            'author_color' => '#ef4444',
        ]);
        $superAdmin->assignRole($superAdminRole);

        // Admin
        $admin = User::create([
            'name' => 'Admin WeddingExpo',
            'email' => 'admin@weddingexpo.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'bio' => 'Admin yang mengelola konten dan data WeddingExpo',
            'author_color' => '#3b82f6',
        ]);
        $admin->assignRole($adminRole);

        // Author 1
        $author1 = User::create([
            'name' => 'Sarah Wijaya',
            'email' => 'sarah@weddingexpo.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'bio' => 'Wedding planner berpengalaman lebih dari 10 tahun di industri pernikahan. Passionate tentang membuat setiap pernikahan menjadi momen yang tak terlupakan.',
            'author_color' => '#ec4899',
        ]);
        $author1->assignRole($authorRole);

        // Author 2
        $author2 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@weddingexpo.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'bio' => 'Fotografer pernikahan profesional dan content creator. Senang berbagi tips tentang fotografi dan tren pernikahan terkini.',
            'author_color' => '#8b5cf6',
        ]);
        $author2->assignRole($authorRole);

        // Editor
        $editor = User::create([
            'name' => 'Linda Kusuma',
            'email' => 'linda@weddingexpo.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'bio' => 'Editor konten yang memastikan setiap artikel berkualitas tinggi dan informatif untuk para calon pengantin.',
            'author_color' => '#10b981',
        ]);
        $editor->assignRole($editorRole);

        // User Biasa
        $vendor = User::create([
            'name' => 'Vendor',
            'email' => 'vendor@weddingexpo.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
        $vendor->assignRole($vendorRole);

        $this->command->info('Users created with Spatie roles:');
        $this->command->info('- Super Admin: superadmin@weddingexpo.com');
        $this->command->info('- Admin: admin@weddingexpo.com');
        $this->command->info('- Author 1: sarah@weddingexpo.com (Sarah Wijaya)');
        $this->command->info('- Author 2: budi@weddingexpo.com (Budi Santoso)');
        $this->command->info('- Editor: linda@weddingexpo.com (Linda Kusuma)');
        $this->command->info('- Vendor: vendor@weddingexpo.com');
        $this->command->info('Password for all: password123');
    }
}
