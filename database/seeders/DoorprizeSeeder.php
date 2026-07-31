<?php

namespace Database\Seeders;

use App\Models\Doorprize;
use App\Models\Partisipasi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DoorprizeSeeder extends Seeder
{
    public function run(): void
    {
        $partisipasis = Partisipasi::with('vendor')
            ->where('is_active', true)
            ->where('status_pembayaran', 'Lunas')
            ->take(8)
            ->get();

        if ($partisipasis->isEmpty()) {
            $partisipasis = Partisipasi::with('vendor')->take(5)->get();
        }

        if ($partisipasis->isEmpty()) {
            $this->command?->warn('No participations found. Please run PartisipasiSeeder first.');

            return;
        }

        $entries = [
            ['name' => 'Rina Anggraini', 'no_wa' => '081234560001', 'email' => 'rina.anggraini@example.com', 'provinsi' => 'Sumatera Selatan'],
            ['name' => 'Fajar Nugroho', 'no_wa' => '081234560002', 'email' => 'fajar.nugroho@example.com', 'provinsi' => 'Sumatera Selatan'],
            ['name' => 'Maya Putri', 'no_wa' => '081234560003', 'email' => 'maya.putri@example.com', 'provinsi' => 'Jambi'],
            ['name' => 'Hendra Wijaya', 'no_wa' => '081234560004', 'email' => 'hendra.wijaya@example.com', 'provinsi' => 'Sumatera Selatan'],
            ['name' => 'Lestari Ayu', 'no_wa' => '081234560005', 'email' => 'lestari.ayu@example.com', 'provinsi' => 'Bengkulu'],
            ['name' => 'Agus Salim', 'no_wa' => '081234560006', 'email' => 'agus.salim@example.com', 'provinsi' => 'Sumatera Selatan'],
            ['name' => 'Novi Rahayu', 'no_wa' => '081234560007', 'email' => 'novi.rahayu@example.com', 'provinsi' => 'Lampung'],
            ['name' => 'Dimas Prakoso', 'no_wa' => '081234560008', 'email' => 'dimas.prakoso@example.com', 'provinsi' => 'Sumatera Selatan'],
        ];

        $created = 0;

        foreach ($entries as $index => $entry) {
            $partisipasi = $partisipasis[$index % $partisipasis->count()];
            $kode = 'DP-'.strtoupper(Str::random(8));

            $exists = Doorprize::where('no_wa', $entry['no_wa'])
                ->where('partisipasi_id', $partisipasi->id)
                ->exists();

            if ($exists) {
                continue;
            }

            $nominalTrx = 2_500_000 + (($index % 4) * 500_000);
            $nominalRev = (int) ($nominalTrx * 0.85);

            Doorprize::create([
                'name' => $entry['name'],
                'kodevoucher' => $kode,
                'no_wa' => $entry['no_wa'],
                'email' => $entry['email'],
                'nik' => '16'.str_pad((string) (10000000000000 + $index), 14, '0', STR_PAD_LEFT),
                'alamat' => 'Jl. Demo Doorprize No. '.($index + 1).', Palembang',
                'provinsi' => $entry['provinsi'],
                'partisipasi_id' => $partisipasi->id,
                'foto_ktp' => null,
                'surat_pernyataan' => null,
                'sudah_download_tring' => $index % 2 === 0,
                'transactions' => [
                    [
                        'tgl_trx' => now()->subDays(3 + $index)->toDateString(),
                        'nom_trx' => $nominalTrx,
                        'no_rev' => $nominalRev,
                        'ket' => 'Pembelian paket di booth '.$partisipasi->vendor?->nama_vendor,
                    ],
                ],
            ]);

            $created++;
        }

        $this->command?->info("DoorprizeSeeder: {$created} entries created.");
    }
}
