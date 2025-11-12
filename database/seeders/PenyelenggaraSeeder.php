<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Penyelenggara;
use App\Models\PenyelenggaraGallery;

class PenyelenggaraSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Seed satu Penyelenggara saja
        $main = [
            'name' => 'PT. Makna Kreatif Indonesia',
            'alamat' => 'Jl. Jenderal Sudirman No. 123, Palembang',
            'tentang' => 'PT. Makna Kreatif Indonesia adalah penyelenggara kegiatan Sumsel Wedding Expo yang diadakan di Palembang Icon Mall dan Palembang Indah Mall. Serta menyelenggarakan acara B2B, B2C dan B2G yang inovative dan terkemuka.',
            'jam_operasional' => '09:00 - 17:00',
            'email' => 'info@sumselwo.test',
            'no_tlp' => '081234567890',
            'instagram' => 'sumselwo',
            'tiktok' => 'sumselwo',
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