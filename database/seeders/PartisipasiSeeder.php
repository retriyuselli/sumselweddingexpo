<?php

namespace Database\Seeders;

use App\Models\CategoryTenant;
use App\Models\Expo;
use App\Models\Partisipasi;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class PartisipasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get active expo
        $expo = Expo::where('status', 1)->first();

        if (! $expo) {
            $this->command->warn('No active expo found. Please run ExpoSeeder first.');

            return;
        }

        // Get vendors
        $vendors = Vendor::all();

        if ($vendors->isEmpty()) {
            $this->command->warn('No vendors found. Please run VendorSeeder first.');

            return;
        }

        // Get category tenants for this expo
        $categories = CategoryTenant::where('expo_id', $expo->id)->get();

        if ($categories->isEmpty()) {
            $this->command->warn('No category tenants found for expo: '.$expo->nama_expo);
            $this->command->info('Please create category tenants first in the admin panel.');

            return;
        }

        // Status pembayaran options
        $statusPembayaran = ['Lunas', 'Belum Lunas', 'DP', 'Cicilan'];

        // Create participations
        $partisipasi = [];
        $blokCounter = 1;

        // Use first 15 vendors or all if less than 15
        $selectedVendors = $vendors->take(15);

        foreach ($selectedVendors as $vendor) {
            // Random category tenant
            $category = $categories->random();

            // Random status pembayaran with higher probability for "Lunas"
            $rand = rand(1, 100);
            if ($rand <= 50) {
                $status = 'Lunas';
            } elseif ($rand <= 70) {
                $status = 'DP';
            } elseif ($rand <= 85) {
                $status = 'Cicilan';
            } else {
                $status = 'Belum Lunas';
            }

            // Generate blok tenant (A-01, A-02, ..., B-01, B-02, etc.)
            $row = chr(65 + floor(($blokCounter - 1) / 10)); // A, B, C, ...
            $number = str_pad((($blokCounter - 1) % 10) + 1, 2, '0', STR_PAD_LEFT);
            $blok = $row.'-'.$number;

            // Random booking date (within 30 days before expo start)
            $daysBeforeExpo = rand(1, 30);
            $bookingDate = $expo->tanggal_mulai->copy()->subDays($daysBeforeExpo);

            // Optional companion vendor IDs (JSON array) — 30% chance pick another vendor
            $vendorPendamping = null;
            if (rand(1, 100) <= 30 && $vendors->count() > 1) {
                $other = $vendors->where('id', '!=', $vendor->id)->random();
                $vendorPendamping = [$other->id];
            }

            $partisipasi[] = [
                'expo_id' => $expo->id,
                'vendor_id' => $vendor->id,
                'vendor_pendamping' => $vendorPendamping,
                'tanggal_booking' => $bookingDate,
                'status_pembayaran' => $status,
                'category_tenant_id' => $category->id,
                'blok_tenant' => $blok,
                'harga_jual' => $category->harga_jual,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $blokCounter++;
        }

        // Insert all participations
        foreach ($partisipasi as $data) {
            Partisipasi::create($data);
        }

        $this->command->info('Partisipasi seeder completed: '.count($partisipasi).' participations created for '.$expo->nama_expo);

        // Show statistics
        $lunas = collect($partisipasi)->where('status_pembayaran', 'Lunas')->count();
        $dp = collect($partisipasi)->where('status_pembayaran', 'DP')->count();
        $cicilan = collect($partisipasi)->where('status_pembayaran', 'Cicilan')->count();
        $belumLunas = collect($partisipasi)->where('status_pembayaran', 'Belum Lunas')->count();

        $this->command->info('Status: Lunas: '.$lunas.', DP: '.$dp.', Cicilan: '.$cicilan.', Belum Lunas: '.$belumLunas);
    }
}
