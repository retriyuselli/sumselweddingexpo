<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RekeningTujuan;

class RekeningTujuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
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
            RekeningTujuan::create($data);
        }

        $this->command->info('RekeningTujuan seeder completed: 3 bank accounts created');
    }
}
