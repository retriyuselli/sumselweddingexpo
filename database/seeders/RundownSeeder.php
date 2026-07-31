<?php

namespace Database\Seeders;

use App\Models\Expo;
use App\Models\Rundown;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RundownSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the active expo
        $expo = Expo::where('status', true)->first();

        if (!$expo) {
            $this->command->info('No active expo found. Skipping RundownSeeder.');
            return;
        }

        // Clear existing rundowns for this expo to prevent duplicates if run multiple times
        Rundown::where('expo_id', $expo->id)->delete();

        $startDate = $expo->tanggal_mulai;

        // Day 1
        $day1 = $startDate->copy();
        $rundownsDay1 = [
            [
                'waktu' => '09:00 - 10:00',
                'acara' => 'Registrasi Ulang Peserta & Vendor',
                'deskripsi' => 'Persiapan pembukaan dan registrasi ulang bagi seluruh vendor dan peserta pameran.',
                'lokasi' => 'Lobby Utama',
            ],
            [
                'waktu' => '10:00 - 11:00',
                'acara' => 'Opening Ceremony Wedding Expo 2026',
                'deskripsi' => 'Pembukaan resmi acara oleh Gubernur Sumatera Selatan beserta tamu undangan VIP.',
                'lokasi' => 'Main Stage',
            ],
            [
                'waktu' => '11:00 - 12:00',
                'acara' => 'Talkshow: "Tren Pernikahan 2026"',
                'deskripsi' => 'Diskusi interaktif bersama desainer ternama membahas tren gaun dan dekorasi terkini.',
                'lokasi' => 'Mini Stage',
            ],
            [
                'waktu' => '13:00 - 15:00',
                'acara' => 'Fashion Show: Traditional Modern',
                'deskripsi' => 'Peragaan busana pengantin tradisional dengan sentuhan modern oleh berbagai vendor lokal.',
                'lokasi' => 'Main Stage',
            ],
            [
                'waktu' => '16:00 - 17:00',
                'acara' => 'Demo Make Up Artist',
                'deskripsi' => 'Tips dan trik make up pengantin natural flawless oleh MUA Hits Palembang.',
                'lokasi' => 'Booth Area A',
            ],
             [
                'waktu' => '19:00 - 20:30',
                'acara' => 'Live Music Performance',
                'deskripsi' => 'Hiburan musik akustik romantis untuk menemani pengunjung pameran.',
                'lokasi' => 'Main Stage',
            ],
        ];

        foreach ($rundownsDay1 as $item) {
            Rundown::create(array_merge($item, [
                'expo_id' => $expo->id,
                'tanggal' => $day1->format('Y-m-d'),
            ]));
        }

        // Day 2
        $day2 = $startDate->copy()->addDay();
        $rundownsDay2 = [
             [
                'waktu' => '10:00 - 11:00',
                'acara' => 'Workshop: Financial Planning for Couples',
                'deskripsi' => 'Belajar mengatur keuangan rumah tangga sejak dini bersama pakar keuangan.',
                'lokasi' => 'Mini Stage',
            ],
            [
                'waktu' => '13:00 - 14:30',
                'acara' => 'Fashion Show: International Gowns',
                'deskripsi' => 'Koleksi gaun pengantin internasional yang elegan dan mewah.',
                'lokasi' => 'Main Stage',
            ],
            [
                'waktu' => '15:00 - 16:00',
                'acara' => 'Food Tasting & Catering Parade',
                'deskripsi' => 'Cicipi aneka hidangan lezat dari vendor catering terbaik di Palembang.',
                'lokasi' => 'Catering Area',
            ],
             [
                'waktu' => '18:30 - 20:00',
                'acara' => 'Talkshow: "Mempersiapkan Mental Menikah"',
                'deskripsi' => 'Sesi konsultasi psikologi pernikahan untuk calon pengantin.',
                'lokasi' => 'Mini Stage',
            ],
        ];

        foreach ($rundownsDay2 as $item) {
            Rundown::create(array_merge($item, [
                'expo_id' => $expo->id,
                'tanggal' => $day2->format('Y-m-d'),
            ]));
        }

        // Day 3
        $day3 = $startDate->copy()->addDays(2);
        $rundownsDay3 = [
            [
                'waktu' => '10:00 - 12:00',
                'acara' => 'Lomba Rias Pengantin',
                'deskripsi' => 'Kompetisi MUA berbakat menampilkan karya terbaik mereka.',
                'lokasi' => 'Main Stage',
            ],
            [
                'waktu' => '14:00 - 15:30',
                'acara' => 'Grand Fashion Show & Closing',
                'deskripsi' => 'Puncak acara fashion show gabungan seluruh desainer dan penutupan pameran.',
                'lokasi' => 'Main Stage',
            ],
            [
                'waktu' => '16:00 - 17:00',
                'acara' => 'Pengundian Doorproze Utama',
                'deskripsi' => 'Pengundian hadiah utama paket bulan madu dan grand prize lainnya.',
                'lokasi' => 'Main Stage',
            ],
             [
                'waktu' => '17:00 - 18:00',
                'acara' => 'Penutupan & Sesi Foto Bersama',
                'deskripsi' => 'Penutupan resmi acara dan sesi foto bersama seluruh panitia dan vendor.',
                'lokasi' => 'Main Stage',
            ],
        ];

        foreach ($rundownsDay3 as $item) {
            Rundown::create(array_merge($item, [
                'expo_id' => $expo->id,
                'tanggal' => $day3->format('Y-m-d'),
            ]));
        }

        $this->command->info('RundownSeeder successfully run for Expo: ' . $expo->nama_expo);
    }
}
