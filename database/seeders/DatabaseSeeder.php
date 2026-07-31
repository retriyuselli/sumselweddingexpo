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
            RoleSeeder::class,
            UserSeeder::class,
            CustomerSeeder::class,
            BlogSeeder::class,
            ExpoSeeder::class,
            JenisUsahaSeeder::class,
            VendorSeeder::class,
            ProductVendorSeeder::class,
            GallerySeeder::class,
            PenyelenggaraSeeder::class,
            RekeningTujuanSeeder::class,
            CategoryTenantSeeder::class,
            TenantSpotSeeder::class,
            PartisipasiSeeder::class,
            DataPembayaranSeeder::class,
            DoorprizeSeeder::class,
            AppointmentSeeder::class,
            PengeluaranSeeder::class,
            PengeluaranLainSeeder::class,
            OrderSeeder::class,
            SponsorSeeder::class,
            HomeSeeder::class,
            RundownSeeder::class,
        ]);
    }
}
