<?php

namespace Database\Seeders;

use App\Models\Expo;
use App\Models\TenantSpot;
use App\Services\ExpoResolver;
use Illuminate\Database\Seeder;

class TenantSpotSeeder extends Seeder
{
    public function run(): void
    {
        $expo = app(ExpoResolver::class)->nearestActive()
            ?? Expo::where('status', true)->orderByDesc('tanggal_mulai')->first()
            ?? Expo::orderByDesc('tanggal_mulai')->first();

        if (! $expo) {
            $this->command?->warn('No expo found. Please run ExpoSeeder first.');

            return;
        }

        if (TenantSpot::where('expo_id', $expo->id)->exists()) {
            $this->command?->info("TenantSpotSeeder: spots already exist for {$expo->nama_expo}, skipped.");

            return;
        }

        // Layout default sama dengan action Generate di Filament
        TenantSpot::generateBatch($expo->id, 'A', 1, 10, cols: 2, fillByCol: true);
        TenantSpot::generateBatch($expo->id, 'B', 1, 10, cols: 5, section: 'kiri');
        TenantSpot::generateBatch($expo->id, 'B', 11, 20, cols: 5, section: 'kanan');
        TenantSpot::generateBatch($expo->id, 'C', 1, 10, cols: 10);

        $count = TenantSpot::where('expo_id', $expo->id)->count();
        $this->command?->info("TenantSpotSeeder: {$count} spots created for {$expo->nama_expo}.");
    }
}
