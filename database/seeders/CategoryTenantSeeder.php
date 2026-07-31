<?php

namespace Database\Seeders;

use App\Enums\CategoryTier;
use App\Models\CategoryTenant;
use App\Models\Expo;
use App\Services\ExpoResolver;
use Illuminate\Database\Seeder;

class CategoryTenantSeeder extends Seeder
{
    public function run(): void
    {
        $expo = app(ExpoResolver::class)->nearestActive()
            ?? Expo::where('status', true)->orderByDesc('tanggal_mulai')->first();

        if (! $expo) {
            $this->command?->warn('No active expo found. Please run ExpoSeeder first.');

            return;
        }

        $categories = [
            [
                'category' => CategoryTier::Platinum,
                'harga_jual' => 15000000,
                'harga_modal' => 10000000,
                'jumlah_unit' => 10,
                'ukuran' => '4x4 m',
                'deskripsi' => 'Booth premium dengan lokasi strategis, ukuran besar, dan fasilitas lengkap. Termasuk backdrop, meja, kursi, lighting, dan spot promosi prioritas.',
                'status' => 'Aktif',
            ],
            [
                'category' => CategoryTier::Gold,
                'harga_jual' => 11000000,
                'harga_modal' => 8000000,
                'jumlah_unit' => 20,
                'ukuran' => '3x3 m',
                'deskripsi' => 'Booth standar dengan lokasi baik dan fasilitas memadai. Termasuk backdrop, meja, kursi, dan basic lighting.',
                'status' => 'Aktif',
            ],
            [
                'category' => CategoryTier::Silver,
                'harga_jual' => 8500000,
                'harga_modal' => 6000000,
                'jumlah_unit' => 25,
                'ukuran' => '2x3 m',
                'deskripsi' => 'Booth ekonomis dengan fasilitas dasar. Termasuk meja, kursi, dan listrik.',
                'status' => 'Aktif',
            ],
        ];

        foreach ($categories as $category) {
            CategoryTenant::updateOrCreate(
                [
                    'expo_id' => $expo->id,
                    'category' => $category['category'],
                ],
                array_merge($category, ['expo_id' => $expo->id])
            );
        }

        ExpoResolver::forgetNearest();

        $this->command?->info('CategoryTenantSeeder completed for '.$expo->nama_expo.' ('.count($categories).' paket).');
    }
}
