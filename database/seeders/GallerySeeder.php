<?php

namespace Database\Seeders;

use App\Models\Expo;
use App\Models\Gallery;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get active expo
        $expo = Expo::where('status', 1)->first();

        if (! $expo) {
            $this->command->warn('No active expo found. Please run ExpoSeeder first.');

            return;
        }

        $galleries = [
            [
                'title' => 'Grand Opening Ceremony',
                'description' => 'Momen pembukaan resmi Wedding Expo dengan pemotongan pita oleh tamu kehormatan.',
                'photographer_name' => 'Rizki Photography',
                'photo_date' => $expo->tanggal_mulai,
                'display_order' => 1,
                'is_featured' => true,
                'tags' => ['opening', 'ceremony', 'grand opening'],
                'image_path' => ['https://images.unsplash.com/photo-1519741497674-611481863552?w=800&q=80'],
            ],
            [
                'title' => 'Fashion Show Pengantin',
                'description' => 'Peragaan busana pengantin terbaru dari desainer ternama dengan koleksi eksklusif.',
                'photographer_name' => 'Capture Moment Studio',
                'photo_date' => $expo->tanggal_mulai,
                'display_order' => 2,
                'is_featured' => true,
                'tags' => ['fashion show', 'bridal', 'wedding dress'],
                'image_path' => ['https://images.unsplash.com/photo-1595476108010-b4d1f102b1b1?w=800&q=80'],
            ],
            [
                'title' => 'Booth Dekorasi Pernikahan',
                'description' => 'Berbagai booth vendor dekorasi yang menampilkan konsep dekorasi pernikahan modern dan tradisional.',
                'photographer_name' => 'Wedding Cinematic',
                'photo_date' => $expo->tanggal_mulai,
                'display_order' => 3,
                'is_featured' => true,
                'tags' => ['decoration', 'booth', 'vendor'],
                'image_path' => ['https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=800&q=80'],
            ],
            [
                'title' => 'Talkshow Persiapan Pernikahan',
                'description' => 'Talkshow inspiratif bersama wedding planner profesional membahas tips persiapan pernikahan.',
                'photographer_name' => 'Event Photography',
                'photo_date' => $expo->tanggal_mulai,
                'display_order' => 4,
                'is_featured' => false,
                'tags' => ['talkshow', 'wedding planning', 'seminar'],
                'image_path' => ['https://images.unsplash.com/photo-1511578314322-379afb476865?w=800&q=80'],
            ],
            [
                'title' => 'Booth Catering & Wedding Cake',
                'description' => 'Area pameran catering dan kue pengantin dengan berbagai pilihan menu dan desain kue.',
                'photographer_name' => 'Foto Wedding Pro',
                'photo_date' => $expo->tanggal_mulai->addDay(),
                'display_order' => 5,
                'is_featured' => false,
                'tags' => ['catering', 'wedding cake', 'food'],
                'image_path' => ['https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?w=800&q=80'],
            ],
            [
                'title' => 'Makeup Demo & Tutorial',
                'description' => 'Demonstrasi makeup pengantin oleh MUA profesional dengan teknik terbaru.',
                'photographer_name' => 'Beauty Shot Photography',
                'photo_date' => $expo->tanggal_mulai->addDay(),
                'display_order' => 6,
                'is_featured' => false,
                'tags' => ['makeup', 'beauty', 'tutorial', 'mua'],
                'image_path' => ['https://images.unsplash.com/photo-1487412947147-5cebf100ffc2?w=800&q=80'],
            ],
            [
                'title' => 'Booth Fotografi & Videografi',
                'description' => 'Pameran portofolio karya fotografer dan videografer wedding terbaik.',
                'photographer_name' => 'Professional Photo Studio',
                'photo_date' => $expo->tanggal_mulai->addDay(),
                'display_order' => 7,
                'is_featured' => false,
                'tags' => ['photography', 'videography', 'portfolio'],
                'image_path' => ['https://images.unsplash.com/photo-1606800052052-a08af7148866?w=800&q=80'],
            ],
            [
                'title' => 'Live Music Performance',
                'description' => 'Penampilan live music dari band wedding populer yang memeriahkan acara.',
                'photographer_name' => 'Concert Photography',
                'photo_date' => $expo->tanggal_mulai->addDay(),
                'display_order' => 8,
                'is_featured' => false,
                'tags' => ['music', 'entertainment', 'band', 'performance'],
                'image_path' => ['https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=800&q=80'],
            ],
            [
                'title' => 'Booth Undangan & Souvenir',
                'description' => 'Berbagai pilihan desain undangan pernikahan dan souvenir eksklusif untuk tamu.',
                'photographer_name' => 'Detail Photography',
                'photo_date' => $expo->tanggal_selesai,
                'display_order' => 9,
                'is_featured' => false,
                'tags' => ['invitation', 'souvenir', 'gift'],
                'image_path' => ['https://images.unsplash.com/photo-1522673607200-164d1b6ce486?w=800&q=80'],
            ],
            [
                'title' => 'Perhiasan & Cincin Kawin',
                'description' => 'Koleksi perhiasan dan cincin kawin dari toko emas ternama.',
                'photographer_name' => 'Jewelry Photography',
                'photo_date' => $expo->tanggal_selesai,
                'display_order' => 10,
                'is_featured' => false,
                'tags' => ['jewelry', 'wedding ring', 'gold'],
                'image_path' => ['https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=800&q=80'],
            ],
            [
                'title' => 'Pengunjung Wedding Expo',
                'description' => 'Antusiasme pengunjung yang memadati venue Wedding Expo untuk mencari vendor impian.',
                'photographer_name' => 'Crowd Photography',
                'photo_date' => $expo->tanggal_selesai,
                'display_order' => 11,
                'is_featured' => false,
                'tags' => ['visitors', 'crowd', 'event'],
                'image_path' => ['https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&q=80'],
            ],
            [
                'title' => 'Grand Doorprize Ceremony',
                'description' => 'Momen pembagian doorprize berhadiah menarik pada penutupan Wedding Expo.',
                'photographer_name' => 'Event Coverage',
                'photo_date' => $expo->tanggal_selesai,
                'display_order' => 12,
                'is_featured' => true,
                'tags' => ['doorprize', 'closing', 'winners'],
                'image_path' => ['https://images.unsplash.com/photo-1511285560929-80b456fea0bc?w=800&q=80'],
            ],
        ];

        foreach ($galleries as $gallery) {
            Gallery::create([
                'expo_id' => $expo->id,
                'title' => $gallery['title'],
                'description' => $gallery['description'],
                'photographer_name' => $gallery['photographer_name'],
                'photo_date' => $gallery['photo_date'],
                'display_order' => $gallery['display_order'],
                'is_featured' => $gallery['is_featured'],
                'is_published' => true,
                'tags' => $gallery['tags'],
                'image_path' => $gallery['image_path'],
            ]);
        }

        $this->command->info('Gallery seeder completed: 12 photos created for '.$expo->nama_expo);
    }
}
