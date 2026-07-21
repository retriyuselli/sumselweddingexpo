<?php

namespace Database\Seeders;

use App\Models\CategoryTenant;
use App\Models\Expo;
use App\Models\Partisipasi;
use App\Models\Vendor;
use App\Services\ExpoResolver;
use Illuminate\Database\Seeder;

class PartisipasiSeeder extends Seeder
{
    public function run(): void
    {
        $expo = app(ExpoResolver::class)->nearestActive()
            ?? Expo::where('status', true)->orderByDesc('tanggal_mulai')->first();

        if (! $expo) {
            $this->command?->warn('No active expo found. Please run ExpoSeeder first.');

            return;
        }

        $vendors = Vendor::all();
        if ($vendors->isEmpty()) {
            $this->command?->warn('No vendors found. Please run VendorSeeder first.');

            return;
        }

        $categories = CategoryTenant::where('expo_id', $expo->id)->where('status', 'Aktif')->get();
        if ($categories->isEmpty()) {
            $this->command?->warn('No category tenants found for expo: '.$expo->nama_expo);

            return;
        }

        $created = 0;
        $blokCounter = 1;
        $selectedVendors = $vendors->take(15);

        foreach ($selectedVendors as $index => $vendor) {
            $exists = Partisipasi::where('vendor_id', $vendor->id)
                ->where('expo_id', $expo->id)
                ->exists();
            if ($exists) {
                continue;
            }

            $category = $categories[$index % $categories->count()];

            $statusRoll = ($index % 10) + 1;
            $status = match (true) {
                $statusRoll <= 5 => 'Lunas',
                $statusRoll <= 7 => 'DP',
                $statusRoll <= 8 => 'Cicilan',
                default => 'Belum Lunas',
            };

            $row = chr(65 + (int) floor(($blokCounter - 1) / 10));
            $number = str_pad(((($blokCounter - 1) % 10) + 1), 2, '0', STR_PAD_LEFT);
            $blok = $row.'-'.$number;

            $bookingDate = $expo->tanggal_mulai->copy()->subDays(5 + ($index % 20));

            $vendorPendamping = null;
            if ($index % 3 === 0 && $vendors->count() > 1) {
                $other = $vendors->firstWhere('id', '!=', $vendor->id) ?? $vendors->last();
                if ($other && $other->id !== $vendor->id) {
                    $vendorPendamping = [$other->id];
                }
            }

            Partisipasi::create([
                'expo_id' => $expo->id,
                'vendor_id' => $vendor->id,
                'vendor_pendamping' => $vendorPendamping,
                'tanggal_booking' => $bookingDate,
                'status_pembayaran' => $status,
                'category_tenant_id' => $category->id,
                'blok_tenant' => $blok,
                'harga_jual' => $category->harga_jual,
                'diskon' => 0,
            ]);

            $created++;
            $blokCounter++;
        }

        $this->command?->info("PartisipasiSeeder: {$created} participations created for {$expo->nama_expo}.");
    }
}
