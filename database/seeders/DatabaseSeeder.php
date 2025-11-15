<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            BlogSeeder::class,
            ExpoSeeder::class,
            JenisUsahaSeeder::class,
            VendorSeeder::class,
            ProductVendorSeeder::class,
            GallerySeeder::class,
            PenyelenggaraSeeder::class,
            RekeningTujuanSeeder::class,
            CategoryTenantSeeder::class,
            PartisipasiSeeder::class,
            DataPembayaranSeeder::class,
            SponsorSeeder::class,
            HomeSeeder::class,
            RoleSeeder::class,
        ]);
    }
}
