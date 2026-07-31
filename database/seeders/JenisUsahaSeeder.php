<?php

namespace Database\Seeders;

use App\Models\JenisUsaha;
use Illuminate\Database\Seeder;

class JenisUsahaSeeder extends Seeder
{
    public function run(): void
    {
        $jenisUsaha = [
            'Gedung Pernikahan',
            'Hotel & Resort',
            'Outdoor Venue',
            'Dekorasi Pernikahan',
            'Wedding Organizer',
            'Event Planner',
            'Gaun Pengantin',
            'Kebaya Pengantin',
            'Jas & Tuxedo',
            'Make Up Artist (MUA)',
            'Hair Stylist',
            'Salon Kecantikan',
            'Fotografer',
            'Videografer',
            'Pre-Wedding Photography',
            'Wedding Cinematic',
            'Photo Booth',
            'Drone Photography',
            'Catering Pernikahan',
            'Wedding Cake',
            'Dessert Table',
            'Traditional Cake',
            'Cupcake & Cookies',
            'Wedding Band',
            'DJ',
            'MC (Master of Ceremony)',
            'Musik Tradisional',
            'Penari',
            'Singer/Penyanyi',
            'Undangan Pernikahan',
            'Undangan Digital',
            'Souvenir Pernikahan',
            'Hampers',
            'Gift Registry',
            'Perhiasan Emas',
            'Cincin Kawin',
            'Aksesoris Pengantin',
            'Sepatu Pengantin',
            'Rental Mobil Pengantin',
            'Sewa Bus Tamu',
            'Wedding Car Decoration',
            'Paket Honeymoon',
            'Travel Agent',
            'Hotel Honeymoon',
            'Wedding Favors',
            'Lighting & Sound System',
            'Tenda & Perlengkapan',
            'Florist',
            'Henna/Pacar',
            'Wedding Website',
            'Live Streaming',
        ];

        foreach ($jenisUsaha as $jenis) {
            JenisUsaha::firstOrCreate(['nama_jenis_usaha' => $jenis]);
        }

        $this->command?->info('JenisUsahaSeeder: '.count($jenisUsaha).' jenis usaha ensured.');
    }
}
