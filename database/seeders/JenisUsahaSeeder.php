<?php

namespace Database\Seeders;

use App\Models\JenisUsaha;
use Illuminate\Database\Seeder;

class JenisUsahaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisUsaha = [
            // Venue & Decoration
            'Gedung Pernikahan',
            'Hotel & Resort',
            'Outdoor Venue',
            'Dekorasi Pernikahan',
            'Wedding Organizer',
            'Event Planner',

            // Fashion & Beauty
            'Gaun Pengantin',
            'Kebaya Pengantin',
            'Jas & Tuxedo',
            'Make Up Artist (MUA)',
            'Hair Stylist',
            'Salon Kecantikan',

            // Photography & Videography
            'Fotografer',
            'Videografer',
            'Pre-Wedding Photography',
            'Wedding Cinematic',
            'Photo Booth',
            'Drone Photography',

            // Catering & Cake
            'Catering Pernikahan',
            'Wedding Cake',
            'Dessert Table',
            'Traditional Cake',
            'Cupcake & Cookies',

            // Entertainment
            'Wedding Band',
            'DJ',
            'MC (Master of Ceremony)',
            'Musik Tradisional',
            'Penari',
            'Singer/Penyanyi',

            // Invitation & Souvenir
            'Undangan Pernikahan',
            'Undangan Digital',
            'Souvenir Pernikahan',
            'Hampers',
            'Gift Registry',

            // Jewelry & Accessories
            'Perhiasan Emas',
            'Cincin Kawin',
            'Aksesoris Pengantin',
            'Sepatu Pengantin',

            // Transportation
            'Rental Mobil Pengantin',
            'Sewa Bus Tamu',
            'Wedding Car Decoration',

            // Honeymoon & Travel
            'Paket Honeymoon',
            'Travel Agent',
            'Hotel Honeymoon',

            // Miscellaneous
            'Wedding Favors',
            'Lighting & Sound System',
            'Tenda & Perlengkapan',
            'Florist',
            'Henna/Pacar',
            'Wedding Website',
            'Live Streaming',
        ];

        foreach ($jenisUsaha as $jenis) {
            JenisUsaha::create([
                'nama_jenis_usaha' => $jenis,
            ]);
        }
    }
}
