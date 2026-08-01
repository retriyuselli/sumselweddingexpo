<?php

namespace Database\Seeders;

use App\Models\PengeluaranLain;
use App\Models\RekeningTujuan;
use App\Models\User;
use Illuminate\Database\Seeder;

class PengeluaranLainSeeder extends Seeder
{
    public function run(): void
    {
        $rekening = RekeningTujuan::first();
        $admin = User::query()
            ->whereHas('roles', fn ($q) => $q->whereIn('name', ['admin', 'super_admin']))
            ->first()
            ?? User::query()->first();

        if (! $rekening) {
            $this->command?->warn('No rekening tujuan found. Please run RekeningTujuanSeeder first.');

            return;
        }

        $items = [
            [
                'nama_pengeluaran' => 'Biaya Operasional Kantor',
                'keterangan' => 'ATK, internet, dan utilitas bulanan',
                'nominal' => 1_500_000,
            ],
            [
                'nama_pengeluaran' => 'Transportasi Tim Marketing',
                'keterangan' => 'Kunjungan vendor & survey lokasi',
                'nominal' => 850_000,
            ],
            [
                'nama_pengeluaran' => 'Langganan Software',
                'keterangan' => 'Hosting, domain, dan tools desain',
                'nominal' => 1_200_000,
            ],
            [
                'nama_pengeluaran' => 'Souvenir Relasi',
                'keterangan' => 'Hadiah untuk mitra media',
                'nominal' => 750_000,
            ],
        ];

        $created = 0;

        foreach ($items as $index => $item) {
            $exists = PengeluaranLain::where('nama_pengeluaran', $item['nama_pengeluaran'])->exists();

            if ($exists) {
                continue;
            }

            PengeluaranLain::create([
                'nama_pengeluaran' => $item['nama_pengeluaran'],
                'keterangan' => $item['keterangan'],
                'nominal' => $item['nominal'],
                'tanggal' => now()->subDays(20 - ($index * 3)),
                'rekening_tujuan_id' => $rekening->id,
                'user_id' => $admin?->id,
                'bukti_transfer' => 'seeders/bukti-transfer-placeholder.jpg',
                'nota_dinas' => null,
            ]);

            $created++;
        }

        $this->command?->info("PengeluaranLainSeeder: {$created} other expenses created.");
    }
}
