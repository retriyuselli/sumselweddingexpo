<?php

namespace Database\Seeders;

use App\Models\Expo;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ExpoSeeder extends Seeder
{
    public function run(): void
    {
        $expos = [
            [
                'nama_expo' => 'Sumsel Wedding Expo Tahun 2026',
                'periode' => 'Season I · 15-17 Agu 2026',
                'tanggal_mulai' => Carbon::parse('2026-08-15'),
                'tanggal_selesai' => Carbon::parse('2026-08-17'),
                'lokasi' => 'Palembang Icon Mall, Jl. Basuki Rahmat No.817, Talang Semut, Kec. Bukit Kecil, Kota Palembang, Sumatera Selatan 30111',
                'alamat' => 'Palembang Icon Mall',
                'status' => true,
            ],
            [
                'nama_expo' => 'Sumsel Wedding Expo Tahun 2025',
                'periode' => 'Season II · 15-17 Nov 2025',
                'tanggal_mulai' => Carbon::parse('2025-11-15'),
                'tanggal_selesai' => Carbon::parse('2025-11-17'),
                'lokasi' => 'Palembang Indah Mall, Kota Palembang, Sumatera Selatan',
                'alamat' => 'Palembang Indah Mall',
                'status' => false,
            ],
        ];

        foreach ($expos as $data) {
            Expo::updateOrCreate(
                [
                    'nama_expo' => $data['nama_expo'],
                    'periode' => $data['periode'],
                ],
                $data
            );
        }

        $this->command?->info('ExpoSeeder: '.count($expos).' expos ensured.');
    }
}
