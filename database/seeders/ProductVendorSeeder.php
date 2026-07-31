<?php

namespace Database\Seeders;

use App\Models\ProductVendor;
use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductVendorSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $vendors = Vendor::query()->get();
        foreach ($vendors as $vendor) {
            foreach (['Basic', 'Silver', 'Gold', 'Platinum', 'Diamond'] as $tier) {
                $name = 'Paket '.$vendor->nama_vendor.' '.$tier;
                ProductVendor::updateOrCreate(
                    ['vendor_id' => $vendor->id, 'slug' => Str::slug($name)],
                    [
                        'nama_produk' => $name,
                        'harga' => fake()->numberBetween(1500000, 50000000),
                        'deskripsi' => fake()->sentence(12),
                        'foto_url' => null,
                        'stok' => fake()->numberBetween(0, 100),
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}