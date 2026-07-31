<?php

namespace Database\Seeders;

use App\Models\Penyelenggara;
use App\Models\PenyelenggaraGallery;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PenyelenggaraSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Seed satu Penyelenggara saja
        $main = [
            'name' => 'PT. Makna Kreatif Indonesia',
            'alamat' => 'Jl. Sintraman Jaya I No.2148, 20 Ilir D II, Kec. Kemuning, Kota Palembang, Sumatera Selatan 30137',
            'tentang' => 'PT. Makna Kreatif Indonesia adalah penyelenggara kegiatan Sumsel Wedding Expo yang diadakan di Palembang Icon Mall dan Palembang Indah Mall. Serta menyelenggarakan acara B2B, B2C dan B2G yang inovative dan terkemuka.',
            'jam_operasional' => 'Senin - Sabtu 09:00 - 17:00',
            'email' => 'office@sumselweddingexpo.com',
            'no_tlp' => '+62 813-6018-513',
            'instagram' => 'sumselweddingexpo',
            'tiktok' => 'sumselweddingexpo',
        ];

        // Cari termasuk yang terhapus (soft-deleted), lalu restore/update jika ada
        $existing = Penyelenggara::withTrashed()->where('email', $main['email'])->first();
        if ($existing) {
            $existing->fill($main);
            if ($existing->trashed()) {
                $existing->restore();
            }
            $existing->save();
            $org = $existing;
        } else {
            $org = Penyelenggara::create($main);
        }

        // Hapus (force delete) penyelenggara lain agar hanya satu yang tersisa
        Penyelenggara::where('id', '!=', $org->id)->get()->each(function ($row) {
            $row->forceDelete();
        });

        // Seed Gallery untuk setiap Penyelenggara (idempotent)
        // Seed Gallery hanya untuk penyelenggara utama (idempotent)
        $items = [
            [
                'title' => 'Sesi Foto Pra-Wedding',
                'description' => 'Koleksi foto pra-wedding dengan tema klasik.',
                'image_path' => [
                    'penyelenggara_galleries/demo1.jpg',
                    'penyelenggara_galleries/demo2.jpg',
                ],
                'photographer_name' => 'Studio Palembang',
                'photo_date' => now()->subDays(10)->toDateString(),
                'display_order' => 1,
                'is_featured' => true,
                'is_published' => true,
                'tags' => ['pra-wedding', 'classic'],
            ],
            [
                'title' => 'Dokumentasi Hari H',
                'description' => 'Momen-momen pernikahan yang tak terlupakan.',
                'image_path' => [
                    'penyelenggara_galleries/demo3.jpg',
                    'penyelenggara_galleries/demo4.jpg',
                ],
                'photographer_name' => 'Tim Dokumentasi',
                'photo_date' => now()->subDays(7)->toDateString(),
                'display_order' => 2,
                'is_featured' => false,
                'is_published' => true,
                'tags' => ['wedding-day', 'moment'],
            ],
            [
                'title' => 'Resepsi dan After Party',
                'description' => 'Suasana resepsi dan pesta setelah acara.',
                'image_path' => [
                    'penyelenggara_galleries/demo5.jpg',
                ],
                'photographer_name' => 'Kameramen Lapangan',
                'photo_date' => now()->subDays(5)->toDateString(),
                'display_order' => 3,
                'is_featured' => false,
                'is_published' => true,
                'tags' => ['reception', 'party'],
            ],
        ];

        foreach ($items as $item) {
            PenyelenggaraGallery::updateOrCreate(
                [
                    'penyelenggara_id' => $org->id,
                    'title' => $item['title'],
                ],
                array_merge($item, [
                    'penyelenggara_id' => $org->id,
                ])
            );
        }
    }
}
