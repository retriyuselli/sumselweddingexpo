<?php

namespace Database\Seeders;

use App\Models\RekeningTujuan;
use Illuminate\Database\Seeder;

class RekeningTujuanSeeder extends Seeder
{
    public function run(): void
    {
        $rekening = [
            [
                'nama_bank' => 'Bank Mandiri',
                'nomor_rekening' => '1370012345678',
                'nama_pemilik' => 'PT. Makna Kreatif Indonesia',
            ],
            [
                'nama_bank' => 'Bank BCA',
                'nomor_rekening' => '7650987654321',
                'nama_pemilik' => 'PT. Makna Kreatif Indonesia',
            ],
            [
                'nama_bank' => 'Bank BRI',
                'nomor_rekening' => '002401234567890',
                'nama_pemilik' => 'PT. Makna Kreatif Indonesia',
            ],
        ];

        foreach ($rekening as $data) {
            RekeningTujuan::firstOrCreate(
                ['nomor_rekening' => $data['nomor_rekening']],
                $data
            );
        }

        $this->command?->info('RekeningTujuanSeeder: '.count($rekening).' accounts ensured.');
    }
}
