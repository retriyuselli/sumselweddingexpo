<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CategoryTenant;
use App\Models\Expo;
use App\Enums\CategoryTier;

class CategoryTenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get active expo
        $expo = Expo::where('status', 1)->first();
        
        if (!$expo) {
            $this->command->warn('No active expo found. Please run ExpoSeeder first.');
            return;
        }

        $categories = [
            [
                'expo_id' => $expo->id,
                'category' => CategoryTier::Platinum,
                'harga_jual' => 15000000, // 15 juta
                'harga_modal' => 10000000, // 10 juta
                'jumlah_unit' => 10,
                'ukuran' => '4x4 m',
                'deskripsi' => 'Booth premium dengan lokasi strategis, ukuran besar, dan fasilitas lengkap. Termasuk backdrop, meja, kursi, lighting, dan spot promosi prioritas.',
                'status' => true,
            ],
            [
                'expo_id' => $expo->id,
                'category' => CategoryTier::Gold,
                'harga_jual' => 11000000, // 11 juta
                'harga_modal' => 8000000, // 8 juta
                'jumlah_unit' => 20,
                'ukuran' => '3x3 m',
                'deskripsi' => 'Booth standar dengan lokasi baik dan fasilitas memadai. Termasuk backdrop, meja, kursi, dan basic lighting.',
                'status' => true,
            ],
        ];

        foreach ($categories as $category) {
            CategoryTenant::create($category);
        }

        $this->command->info('CategoryTenant seeder completed for ' . $expo->nama_expo);
        $this->command->info('- Platinum: 10 units @ Rp 15.000.000');
        $this->command->info('- Gold: 20 units @ Rp 11.000.000');
        $this->command->info('Total available booths: 30 units');
    }
}
