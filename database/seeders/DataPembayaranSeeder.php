<?php

namespace Database\Seeders;

use App\Models\DataPembayaran;
use App\Models\Partisipasi;
use App\Models\RekeningTujuan;
use Illuminate\Database\Seeder;

class DataPembayaranSeeder extends Seeder
{
    public function run(): void
    {
        $partisipasis = Partisipasi::with('vendor')->get();
        if ($partisipasis->isEmpty()) {
            $this->command?->warn('No participations found. Please run PartisipasiSeeder first.');

            return;
        }

        $rekenings = RekeningTujuan::all();
        if ($rekenings->isEmpty()) {
            $this->command?->warn('No rekening tujuan found. Please run RekeningTujuanSeeder first.');

            return;
        }

        $metodePembayaran = ['Transfer Bank', 'Tunai', 'QRIS'];
        $created = 0;

        foreach ($partisipasis as $partisipasi) {
            if ($partisipasi->dataPembayarans()->exists()) {
                continue;
            }

            $hargaJual = (int) $partisipasi->harga_jual;
            $namaPembayar = $partisipasi->vendor->nama_vendor ?? 'Unknown';
            $rekeningId = $rekenings[$partisipasi->id % $rekenings->count()]->id;
            $metode = $metodePembayaran[$partisipasi->id % count($metodePembayaran)];
            $baseDate = $partisipasi->tanggal_booking?->copy() ?? now();

            $rows = match ($partisipasi->status_pembayaran) {
                'Lunas' => [[
                    'nominal' => $hargaJual,
                    'tanggal_bayar' => $baseDate->copy()->addDays(2),
                    'keterangan' => 'Pembayaran Lunas',
                ]],
                'DP' => [[
                    'nominal' => (int) ($hargaJual * 0.3),
                    'tanggal_bayar' => $baseDate->copy()->addDays(1),
                    'keterangan' => 'Pembayaran DP 30%',
                ]],
                'Cicilan' => [
                    [
                        'nominal' => (int) ($hargaJual * 0.5),
                        'tanggal_bayar' => $baseDate->copy()->addDays(1),
                        'keterangan' => 'Pembayaran Cicilan 1 - 50%',
                    ],
                    [
                        'nominal' => (int) ($hargaJual * 0.3),
                        'tanggal_bayar' => $baseDate->copy()->addDays(15),
                        'keterangan' => 'Pembayaran Cicilan 2 - 30%',
                    ],
                ],
                default => [],
            };

            foreach ($rows as $row) {
                DataPembayaran::create([
                    'partisipasi_id' => $partisipasi->id,
                    'nama_pembayar' => $namaPembayar,
                    'nominal' => $row['nominal'],
                    'tanggal_bayar' => $row['tanggal_bayar'],
                    'metode_pembayaran' => $metode,
                    'bukti_transfer' => null,
                    'rekening_tujuan_id' => $rekeningId,
                    'keterangan' => $row['keterangan'],
                ]);
                $created++;
            }

            $partisipasi->recalculatePaymentStatus();
            $partisipasi->save();
        }

        $this->command?->info("DataPembayaranSeeder: {$created} payments created.");
    }
}
