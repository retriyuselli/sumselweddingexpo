<?php

namespace Database\Seeders;

use App\Models\Sponsor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SponsorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sponsors = [
            [
                'name' => 'Vendor 1',
                'website' => 'https://vendor1.com',
                'description' => 'Layanan dekorasi pernikahan profesional',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Vendor 2',
                'website' => 'https://vendor2.com',
                'description' => 'Catering untuk acara pernikahan',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Vendor 3',
                'website' => 'https://vendor3.com',
                'description' => 'Fotografi profesional untuk pernikahan',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Vendor 4',
                'website' => 'https://vendor4.com',
                'description' => 'Videografi dan dokumentasi acara',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Vendor 5',
                'website' => 'https://vendor5.com',
                'description' => 'Penyewaan venue dan ballroom',
                'order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Vendor 6',
                'website' => 'https://vendor6.com',
                'description' => 'Koleksi gaun pengantin dan jas',
                'order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Vendor 7',
                'website' => 'https://vendor7.com',
                'description' => 'Penataan rambut dan makeup profesional',
                'order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'Vendor 8',
                'website' => 'https://vendor8.com',
                'description' => 'Undangan dan desain grafis pernikahan',
                'order' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Vendor 9',
                'website' => 'https://vendor9.com',
                'description' => 'Entertainment dan DJ untuk pernikahan',
                'order' => 9,
                'is_active' => true,
            ],
            [
                'name' => 'Vendor 10',
                'website' => 'https://vendor10.com',
                'description' => 'Perlengkapan dan pernik pernikahan',
                'order' => 10,
                'is_active' => true,
            ],
            [
                'name' => 'Vendor 11',
                'website' => 'https://vendor11.com',
                'description' => 'Kue pengantin dan pastry premium',
                'order' => 11,
                'is_active' => true,
            ],
            [
                'name' => 'Vendor 12',
                'website' => 'https://vendor12.com',
                'description' => 'Perencanaan dan koordinasi acara pernikahan',
                'order' => 12,
                'is_active' => true,
            ],
        ];

        foreach ($sponsors as $sponsor) {
            Sponsor::create($sponsor);
        }
    }
}
