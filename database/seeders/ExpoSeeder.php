<?php

namespace Database\Seeders;

use App\Models\Expo;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ExpoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Expo::create([
            'nama_expo' => 'Wedding Expo Palembang 2026',
            'tanggal_mulai' => Carbon::parse('2026-01-16'),
            'tanggal_selesai' => Carbon::parse('2026-01-18'),
            'lokasi' => 'Palembang Icon Mall, Jl. Basuki Rahmat No.817, Talang Semut, Kec. Bukit Kecil, Kota Palembang, Sumatera Selatan 30111',
            'status' => true, // 1 = aktif
            'periode' => '16-18 Januari 2026',
        ]);

        // Data expo lain (non-aktif) sebagai contoh
        Expo::create([
            'nama_expo' => 'Wedding Expo Palembang 2025',
            'tanggal_mulai' => Carbon::parse('2025-11-15'),
            'tanggal_selesai' => Carbon::parse('2025-11-17'),
            'lokasi' => 'Grand City Surabaya Convention Hall',
            'status' => false, // 0 = tidak aktif
            'periode' => '15-17 November 2025',
        ]);
    }
}
