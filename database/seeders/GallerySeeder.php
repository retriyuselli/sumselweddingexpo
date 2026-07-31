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
        $expo = app(\App\Services\ExpoResolver::class)->nearestActive()
            ?? Expo::where('status', 1)->orderByDesc('tanggal_mulai')->first();

        if (! $expo) {
            $this->command->warn('No active expo found. Please run ExpoSeeder first.');

            return;
        }

        $galleries = [
            [
                'title' => 'Grand Opening Ceremony',
                'image_path' => ['https://images.unsplash.com/photo-1519741497674-611481863552?w=800&q=80'],
            ],
            [
                'title' => 'Fashion Show Pengantin',
                'image_path' => ['https://images.unsplash.com/photo-1595476108010-b4d1f102b1b1?w=800&q=80'],
            ],
            [
                'title' => 'Booth Dekorasi Pernikahan',
                'image_path' => ['https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=800&q=80'],
            ],
            [
                'title' => 'Talkshow Persiapan Pernikahan',
                'image_path' => ['https://images.unsplash.com/photo-1511578314322-379afb476865?w=800&q=80'],
            ],
            [
                'title' => 'Booth Catering & Wedding Cake',
                'image_path' => ['https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?w=800&q=80'],
            ],
            [
                'title' => 'Makeup Demo & Tutorial',
                'image_path' => ['https://images.unsplash.com/photo-1487412947147-5cebf100ffc2?w=800&q=80'],
            ],
            [
                'title' => 'Booth Fotografi & Videografi',
                'image_path' => ['https://images.unsplash.com/photo-1606800052052-a08af7148866?w=800&q=80'],
            ],
            [
                'title' => 'Live Music Performance',
                'image_path' => ['https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=800&q=80'],
            ],
            [
                'title' => 'Booth Undangan & Souvenir',
                'image_path' => ['https://images.unsplash.com/photo-1522673607200-164d1b6ce486?w=800&q=80'],
            ],
            [
                'title' => 'Perhiasan & Cincin Kawin',
                'image_path' => ['https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=800&q=80'],
            ],
            [
                'title' => 'Pengunjung Wedding Expo',
                'image_path' => ['https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&q=80'],
            ],
            [
                'title' => 'Grand Doorprize Ceremony',
                'image_path' => ['https://images.unsplash.com/photo-1511285560929-80b456fea0bc?w=800&q=80'],
            ],
        ];

        foreach ($galleries as $gallery) {
            Gallery::firstOrCreate(
                [
                    'expo_id' => $expo->id,
                    'title' => $gallery['title'],
                ],
                [
                    'image_path' => $gallery['image_path'],
                ]
            );
        }

        $this->command->info('Gallery seeder completed for '.$expo->nama_expo);
    }
}
