<?php

namespace Database\Seeders;

use App\Models\Expo;
use App\Models\Pengeluaran;
use App\Models\RekeningTujuan;
use App\Models\User;
use App\Services\ExpoResolver;
use Illuminate\Database\Seeder;

class PengeluaranSeeder extends Seeder
{
    public function run(): void
    {
        $expo = app(ExpoResolver::class)->nearestActive()
            ?? Expo::where('status', true)->orderByDesc('tanggal_mulai')->first()
            ?? Expo::orderByDesc('tanggal_mulai')->first();

        $rekening = RekeningTujuan::first();
        $admin = User::query()
            ->whereHas('roles', fn ($q) => $q->whereIn('name', ['admin', 'super_admin']))
            ->first()
            ?? User::query()->first();

        if (! $expo) {
            $this->command?->warn('No expo found. Please run ExpoSeeder first.');

            return;
        }

        if (! $rekening) {
            $this->command?->warn('No rekening tujuan found. Please run RekeningTujuanSeeder first.');

            return;
        }

        $items = [
            [
                'nama_pengeluaran' => 'Sewa Venue Mall',
                'keterangan' => 'Biaya sewa area expo selama 3 hari',
                'nominal' => 45_000_000,
                'rek_transfer' => '1234567890',
                'nama_rekening_penerima' => 'Manajemen Mall',
            ],
            [
                'nama_pengeluaran' => 'Dekorasi Panggung & Backdrop',
                'keterangan' => 'Vendor dekorasi area utama',
                'nominal' => 12_500_000,
                'rek_transfer' => '9876543210',
                'nama_rekening_penerima' => 'CV Dekorasi Jaya',
            ],
            [
                'nama_pengeluaran' => 'Cetak Banner & Media Promosi',
                'keterangan' => 'Banner, flyer, dan standing banner',
                'nominal' => 3_750_000,
                'rek_transfer' => '1122334455',
                'nama_rekening_penerima' => 'Percetakan Nusantara',
            ],
            [
                'nama_pengeluaran' => 'Sound System & Lighting',
                'keterangan' => 'Sewa audio lighting untuk acara',
                'nominal' => 8_000_000,
                'rek_transfer' => '5566778899',
                'nama_rekening_penerima' => 'PT Audio Visual Indo',
            ],
            [
                'nama_pengeluaran' => 'Konsumsi Panitia',
                'keterangan' => 'Makan panitia H-1 sampai H+1',
                'nominal' => 2_250_000,
                'rek_transfer' => '6677889900',
                'nama_rekening_penerima' => 'Catering Sehat',
            ],
        ];

        $created = 0;

        foreach ($items as $index => $item) {
            $exists = Pengeluaran::where('expo_id', $expo->id)
                ->where('nama_pengeluaran', $item['nama_pengeluaran'])
                ->exists();

            if ($exists) {
                continue;
            }

            Pengeluaran::create([
                'expo_id' => $expo->id,
                'nama_pengeluaran' => $item['nama_pengeluaran'],
                'keterangan' => $item['keterangan'],
                'nominal' => $item['nominal'],
                'tanggal' => $expo->tanggal_mulai->copy()->subDays(14 - $index),
                'rekening_tujuan_id' => $rekening->id,
                'rek_transfer' => $item['rek_transfer'],
                'nama_rekening_penerima' => $item['nama_rekening_penerima'],
                'bukti_transfer' => null,
                'user_id' => $admin?->id,
            ]);

            $created++;
        }

        $this->command?->info("PengeluaranSeeder: {$created} expenses created for {$expo->nama_expo}.");
    }
}
