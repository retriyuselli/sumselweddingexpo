<?php

namespace Database\Seeders;

use App\Models\JenisUsaha;
use App\Models\Vendor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan JenisUsaha sudah ada
        $jenisUsahas = JenisUsaha::pluck('id', 'nama_jenis_usaha');

        $vendors = [
            // Venue & Decoration
            [
                'nama_vendor' => 'Grand Palace Wedding Hall',
                'jenis_usaha' => 'Gedung Pernikahan',
                'alamat' => 'Jl. Jenderal Sudirman No. 123, RT 05/RW 02, Bukit Besar',
                'kota' => 'Palembang',
                'no_telepon' => '0711-234567',
                'email' => 'info@grandpalace.com',
                'nama_pic' => 'Budi Santoso',
                'no_wa_pic' => '081234567890',
            ],
            [
                'nama_vendor' => 'Novotel Palembang Hotel',
                'jenis_usaha' => 'Hotel & Resort',
                'alamat' => 'Jl. R. Sukamto No. 8, Sako',
                'kota' => 'Palembang',
                'no_telepon' => '0711-456789',
                'email' => 'wedding@novotelpalembang.com',
                'nama_pic' => 'Siti Rahmawati',
                'no_wa_pic' => '081234567891',
            ],
            [
                'nama_vendor' => 'Dekorasi Cantik Indonesia',
                'jenis_usaha' => 'Dekorasi Pernikahan',
                'alamat' => 'Jl. Kapten A. Rivai No. 45, Ilir Timur I',
                'kota' => 'Palembang',
                'no_telepon' => '0711-567890',
                'email' => 'dekorasicantik@gmail.com',
                'nama_pic' => 'Rini Astuti',
                'no_wa_pic' => '081234567892',
            ],
            [
                'nama_vendor' => 'Perfect Wedding Organizer',
                'jenis_usaha' => 'Wedding Organizer',
                'alamat' => 'Jl. Veteran No. 78, Seberang Ulu I',
                'kota' => 'Palembang',
                'no_telepon' => '0711-678901',
                'email' => 'perfectwo@yahoo.com',
                'nama_pic' => 'Andi Wijaya',
                'no_wa_pic' => '081234567893',
            ],

            // Fashion & Beauty
            [
                'nama_vendor' => 'Elegan Bridal Boutique',
                'jenis_usaha' => 'Gaun Pengantin',
                'alamat' => 'Jl. Merdeka No. 56, Ilir Barat I',
                'kota' => 'Palembang',
                'no_telepon' => '0711-789012',
                'email' => 'elegan.bridal@gmail.com',
                'nama_pic' => 'Dewi Kusuma',
                'no_wa_pic' => '081234567894',
            ],
            [
                'nama_vendor' => 'Kebaya Nusantara',
                'jenis_usaha' => 'Kebaya Pengantin',
                'alamat' => 'Jl. Sudirman No. 234, Ilir Timur II',
                'kota' => 'Palembang',
                'no_telepon' => '0711-890123',
                'email' => 'kebaya.nusantara@gmail.com',
                'nama_pic' => 'Lestari Handayani',
                'no_wa_pic' => '081234567895',
            ],
            [
                'nama_vendor' => 'Makeup by Cantika',
                'jenis_usaha' => 'Make Up Artist (MUA)',
                'alamat' => 'Jl. Ahmad Yani No. 89, Talang Semut',
                'kota' => 'Palembang',
                'no_telepon' => '0711-901234',
                'email' => 'cantika.mua@gmail.com',
                'nama_pic' => 'Cantika Sari',
                'no_wa_pic' => '081234567896',
            ],

            // Photography & Videography
            [
                'nama_vendor' => 'Capture Moment Photography',
                'jenis_usaha' => 'Fotografer',
                'alamat' => 'Jl. Demang Lebar Daun No. 67, Demang Lebar Daun',
                'kota' => 'Palembang',
                'no_telepon' => '0711-012345',
                'email' => 'capture.moment@gmail.com',
                'nama_pic' => 'Rizki Pratama',
                'no_wa_pic' => '081234567897',
            ],
            [
                'nama_vendor' => 'Wedding Cinematic Studio',
                'jenis_usaha' => 'Videografer',
                'alamat' => 'Jl. Angkatan 45 No. 123, Kenten',
                'kota' => 'Palembang',
                'no_telepon' => '0711-123456',
                'email' => 'wedding.cinematic@yahoo.com',
                'nama_pic' => 'Dimas Ardiansyah',
                'no_wa_pic' => '081234567898',
            ],

            // Catering & Cake
            [
                'nama_vendor' => 'Berkah Jaya Catering',
                'jenis_usaha' => 'Catering Pernikahan',
                'alamat' => 'Jl. Residen H. Abdul Rozak No. 45, Talang Betutu',
                'kota' => 'Palembang',
                'no_telepon' => '0711-234567',
                'email' => 'berkahjaya.catering@gmail.com',
                'nama_pic' => 'Hendra Gunawan',
                'no_wa_pic' => '081234567899',
            ],
            [
                'nama_vendor' => 'Sweet Cake by Yanti',
                'jenis_usaha' => 'Wedding Cake',
                'alamat' => 'Jl. Rajawali No. 78, 16 Ilir',
                'kota' => 'Palembang',
                'no_telepon' => '0711-345678',
                'email' => 'sweetcakeyanti@gmail.com',
                'nama_pic' => 'Yanti Susanti',
                'no_wa_pic' => '081234567800',
            ],

            // Entertainment
            [
                'nama_vendor' => 'Harmoni Wedding Band',
                'jenis_usaha' => 'Wedding Band',
                'alamat' => 'Jl. Bambang Utoyo No. 12, Plaju',
                'kota' => 'Palembang',
                'no_telepon' => '0711-456789',
                'email' => 'harmoni.band@gmail.com',
                'nama_pic' => 'Yoga Permana',
                'no_wa_pic' => '081234567801',
            ],
            [
                'nama_vendor' => 'MC Professional',
                'jenis_usaha' => 'MC (Master of Ceremony)',
                'alamat' => 'Jl. KH Ahmad Dahlan No. 34, Lorok Pakjo',
                'kota' => 'Palembang',
                'no_telepon' => '0711-567890',
                'email' => 'mc.professional@yahoo.com',
                'nama_pic' => 'Arief Budiman',
                'no_wa_pic' => '081234567802',
            ],

            // Invitation & Souvenir
            [
                'nama_vendor' => 'Kartu Undangan Elegan',
                'jenis_usaha' => 'Undangan Pernikahan',
                'alamat' => 'Jl. Sayangan No. 56, 3-4 Ulu',
                'kota' => 'Palembang',
                'no_telepon' => '0711-678901',
                'email' => 'undangan.elegan@gmail.com',
                'nama_pic' => 'Wulan Sari',
                'no_wa_pic' => '081234567803',
            ],
            [
                'nama_vendor' => 'Souvenir Berkah',
                'jenis_usaha' => 'Souvenir Pernikahan',
                'alamat' => 'Jl. Letkol Iskandar No. 90, Bukit Kecil',
                'kota' => 'Palembang',
                'no_telepon' => '0711-789012',
                'email' => 'souvenir.berkah@gmail.com',
                'nama_pic' => 'Fitri Handayani',
                'no_wa_pic' => '081234567804',
            ],

            // Jewelry & Accessories
            [
                'nama_vendor' => 'Toko Emas Sejahtera',
                'jenis_usaha' => 'Perhiasan Emas',
                'alamat' => 'Jl. Pasar 16 Ilir No. 123, 16 Ilir',
                'kota' => 'Palembang',
                'no_telepon' => '0711-890123',
                'email' => 'emas.sejahtera@yahoo.com',
                'nama_pic' => 'Rudi Hartono',
                'no_wa_pic' => '081234567805',
            ],
            [
                'nama_vendor' => 'Wedding Ring Specialist',
                'jenis_usaha' => 'Cincin Kawin',
                'alamat' => 'Jl. Tasik No. 45, 5 Ilir',
                'kota' => 'Palembang',
                'no_telepon' => '0711-901234',
                'email' => 'wedding.ring@gmail.com',
                'nama_pic' => 'Diana Permatasari',
                'no_wa_pic' => '081234567806',
            ],

            // Transportation
            [
                'nama_vendor' => 'Rental Mobil Pengantin Mewah',
                'jenis_usaha' => 'Rental Mobil Pengantin',
                'alamat' => 'Jl. MP Mangkunegara No. 67, Talang Kelapa',
                'kota' => 'Palembang',
                'no_telepon' => '0711-012345',
                'email' => 'rental.mewah@gmail.com',
                'nama_pic' => 'Agus Setiawan',
                'no_wa_pic' => '081234567807',
            ],

            // Miscellaneous
            [
                'nama_vendor' => 'Florist Paradise',
                'jenis_usaha' => 'Florist',
                'alamat' => 'Jl. Radial No. 34, Sako Baru',
                'kota' => 'Palembang',
                'no_telepon' => '0711-123456',
                'email' => 'florist.paradise@gmail.com',
                'nama_pic' => 'Maya Safitri',
                'no_wa_pic' => '081234567808',
            ],
            [
                'nama_vendor' => 'Sound & Lighting Pro',
                'jenis_usaha' => 'Lighting & Sound System',
                'alamat' => 'Jl. Dempo No. 89, Tangga Buntung',
                'kota' => 'Palembang',
                'no_telepon' => '0711-234567',
                'email' => 'soundlight.pro@yahoo.com',
                'nama_pic' => 'Wahyu Nugroho',
                'no_wa_pic' => '081234567809',
            ],
        ];

        foreach ($vendors as $vendor) {
            $jenisUsahaId = $jenisUsahas[$vendor['jenis_usaha']] ?? null;

            if ($jenisUsahaId) {
                Vendor::firstOrCreate(
                    ['email' => $vendor['email']],
                    [
                        'nama_vendor' => $vendor['nama_vendor'],
                        'slug' => Str::slug($vendor['nama_vendor']),
                        'jenis_usaha_id' => $jenisUsahaId,
                        'alamat' => $vendor['alamat'],
                        'kota' => $vendor['kota'],
                        'no_telepon' => $vendor['no_telepon'],
                        'nama_pic' => $vendor['nama_pic'],
                        'no_wa_pic' => $vendor['no_wa_pic'],
                        'nama_pendaftar' => $vendor['nama_pic'] ?? null,
                        'user_id' => null,
                    ]
                );
            }
        }
    }
}
