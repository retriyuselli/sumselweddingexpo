<?php

namespace Database\Seeders;

use App\Models\DataPembayaran;
use App\Models\Partisipasi;
use App\Models\RekeningTujuan;
use Illuminate\Database\Seeder;

class DataPembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all participations
        $partisipasis = Partisipasi::with('vendor')->get();

        if ($partisipasis->isEmpty()) {
            $this->command->warn('No participations found. Please run PartisipasiSeeder first.');

            return;
        }

        // Get rekening tujuan
        $rekenings = RekeningTujuan::all();

        if ($rekenings->isEmpty()) {
            $this->command->warn('No rekening tujuan found. Please run RekeningTujuanSeeder first.');

            return;
        }

        $metodePembayaran = ['Transfer Bank', 'Tunai', 'QRIS'];
        $pembayaranData = [];

        foreach ($partisipasis as $partisipasi) {
            $hargaJual = (int) $partisipasi->harga_jual;
            $namaPembayar = $partisipasi->vendor->nama_vendor ?? 'Unknown';

            // Generate payment based on status_pembayaran
            switch ($partisipasi->status_pembayaran) {
                case 'Lunas':
                    // Single full payment
                    $pembayaranData[] = $this->createPembayaran(
                        $partisipasi->id,
                        $namaPembayar,
                        $hargaJual,
                        $partisipasi->tanggal_booking->addDays(rand(1, 3)),
                        $metodePembayaran[array_rand($metodePembayaran)],
                        $rekenings->random()->id,
                        'Pembayaran Lunas'
                    );
                    break;

                case 'DP':
                    // 30% DP payment
                    $dpAmount = $hargaJual * 0.3;
                    $pembayaranData[] = $this->createPembayaran(
                        $partisipasi->id,
                        $namaPembayar,
                        $dpAmount,
                        $partisipasi->tanggal_booking->addDays(rand(1, 2)),
                        $metodePembayaran[array_rand($metodePembayaran)],
                        $rekenings->random()->id,
                        'Pembayaran DP 30%'
                    );
                    break;

                case 'Cicilan':
                    // 50% first payment + 30% second payment (80% total)
                    $pembayaranData[] = $this->createPembayaran(
                        $partisipasi->id,
                        $namaPembayar,
                        $hargaJual * 0.5,
                        $partisipasi->tanggal_booking->addDays(rand(1, 2)),
                        $metodePembayaran[array_rand($metodePembayaran)],
                        $rekenings->random()->id,
                        'Pembayaran Cicilan 1 - 50%'
                    );

                    $pembayaranData[] = $this->createPembayaran(
                        $partisipasi->id,
                        $namaPembayar,
                        $hargaJual * 0.3,
                        $partisipasi->tanggal_booking->addDays(rand(10, 20)),
                        $metodePembayaran[array_rand($metodePembayaran)],
                        $rekenings->random()->id,
                        'Pembayaran Cicilan 2 - 30%'
                    );
                    break;

                case 'Belum Lunas':
                    // No payment yet
                    break;
            }
        }

        // Insert all payments
        foreach ($pembayaranData as $data) {
            DataPembayaran::create($data);
        }

        $this->command->info('DataPembayaran seeder completed: '.count($pembayaranData).' payments created');

        // Statistics
        $lunas = $partisipasis->where('status_pembayaran', 'Lunas')->count();
        $dp = $partisipasis->where('status_pembayaran', 'DP')->count();
        $cicilan = $partisipasis->where('status_pembayaran', 'Cicilan')->count();
        $belumLunas = $partisipasis->where('status_pembayaran', 'Belum Lunas')->count();

        $this->command->info('Payment breakdown:');
        $this->command->info("- Lunas: {$lunas} participations = {$lunas} payments");
        $this->command->info("- DP: {$dp} participations = {$dp} payments");
        $this->command->info("- Cicilan: {$cicilan} participations = ".($cicilan * 2).' payments');
        $this->command->info("- Belum Lunas: {$belumLunas} participations = 0 payments");
    }

    private function createPembayaran($partisipasiId, $namaPembayar, $nominal, $tanggalBayar, $metode, $rekeningId, $keterangan)
    {
        return [
            'partisipasi_id' => $partisipasiId,
            'nama_pembayar' => $namaPembayar,
            'nominal' => (int) $nominal,
            'tanggal_bayar' => $tanggalBayar,
            'metode_pembayaran' => $metode,
            'bukti_transfer' => null, // Can be uploaded later via admin
            'rekening_tujuan_id' => $rekeningId,
            'keterangan' => $keterangan,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
