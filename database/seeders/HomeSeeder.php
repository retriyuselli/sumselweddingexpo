<?php

namespace Database\Seeders;

use App\Models\Home;
use Illuminate\Database\Seeder;

class HomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Home::create([
            'tentang_kami' => <<<'EOT'
<p><strong>Sumsel Wedding Expo (SWE)</strong> pada tahun <strong>2024 dan 2025</strong> telah sukses diselenggarakan dengan mengusung tema <em>"Recovery Together."</em></p>
<p>Ajang ini berhasil menghadirkan lebih dari <strong>80 vendor pernikahan terkemuka di Sumatera Selatan</strong>, yang terdiri dari berbagai kategori seperti <strong>venue gedung, hotel, catering, wedding organizer, make up artist, fotografer, dekorasi, hingga developer perumahan</strong>.</p>
<p></p>
<p>Kehadiran <strong>Sumsel Wedding Expo</strong> diharapkan dapat memberikan kemudahan bagi para calon pengantin dalam menemukan dan berinteraksi langsung dengan vendor-vendor impian mereka, sehingga segala kebutuhan dan harapan dalam mewujudkan pernikahan ideal dapat terencana dengan baik.</p>
<p></p>
<p>Setelah kesuksesan <strong>SWE 2024 Season I &amp; II</strong> serta <strong>SWE 2025 Season I &amp; II</strong>, kami kembali hadir menyelenggarakan <strong>Sumsel Wedding Expo 2026 Season I</strong>.</p>
<p>Pada edisi kali ini, lokasi penyelenggaraan berpindah dari <strong>Palembang Indah Mall</strong> ke <strong>Palembang Icon</strong>, dengan harapan dapat menghadirkan suasana baru serta memberikan pengalaman yang lebih berkesan bagi para pengunjung.</p>
<p></p>
<p>Melalui acara ini, kami ingin terus menjadi wadah inspiratif bagi para calon pengantin untuk menemukan vendor terbaik, berkomunikasi langsung dengan para peserta pameran, serta mempersiapkan momen pernikahan impian mereka dengan lebih mudah dan menyenangkan.</p>
EOT,
            'highlight_videos' => [
                [
                    'title' => 'Highlight Video 1',
                    'video_id' => 'SZtypoLHDu4',
                ],
                [
                    'title' => 'Highlight Video 2',
                    'video_id' => 'MId8wPEI1Uk',
                ],
                [
                    'title' => 'Highlight Video 3',
                    'video_id' => 'dQw4w9WgXcQ',
                ],
                [
                    'title' => 'Highlight Video 4',
                    'video_id' => 'jNQXAC9IVRw',
                ],
                [
                    'title' => 'Highlight Video 5',
                    'video_id' => 'zHAIhstD_xc',
                ],
            ],
            'hero_subtitle' => 'Temukan Vendor Pernikahan Impian Anda',
            'meta_description' => 'Sumatra Wedding Expo - Platform terpercaya untuk menemukan vendor pernikahan profesional dengan penawaran eksklusif.',
            'is_active' => true,
        ]);
    }
}
