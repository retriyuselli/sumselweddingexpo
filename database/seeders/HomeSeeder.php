<?php

namespace Database\Seeders;

use App\Models\Home;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
Sumatra Wedding Expo (SWE) 2024-2025 adalah sebuah pameran pernikahan skala regional yang diselenggarakan di Sumatera dengan tujuan mempromosikan dan menghubungkan calon pengantin dengan berbagai vendor pernikahan profesional.

Pameran ini menghadirkan lebih dari 50 vendor terkemuka di industri pernikahan, mulai dari dekorasi, catering, fotografi, videografi, hingga layanan venue dan entertainment. Setiap vendor telah tersertifikasi dan memiliki pengalaman bertahun-tahun dalam memberikan layanan pernikahan berkualitas tinggi.

Dengan mengikuti SWE 2024-2025, calon pengantin dapat:
• Melihat dan berinteraksi langsung dengan berbagai pilihan vendor
• Mendapatkan penawaran spesial dan paket hemat eksklusif
• Mengumpulkan inspirasi dan ide-ide baru untuk pernikahan impian mereka
• Membangun relasi bisnis dengan profesional di industri pernikahan

Visi kami adalah menjadi platform terpercaya yang memudahkan calon pengantin menemukan vendor pernikahan terbaik dengan layanan yang ramah, profesional, dan transparan. Kami berkomitmen untuk memberikan pengalaman pameran yang berkesan dan bermanfaat bagi semua peserta.
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
