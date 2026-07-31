<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create blog categories first (use firstOrCreate to avoid duplicates)
        $budgetCategory = BlogCategory::firstOrCreate(
            ['slug' => 'budget-planning'],
            [
                'name' => 'Budget & Planning',
                'description' => 'Tips mengatur budget dan merencanakan pernikahan',
            ]
        );

        $tipsCategory = BlogCategory::firstOrCreate(
            ['slug' => 'tips-tricks'],
            [
                'name' => 'Tips & Tricks',
                'description' => 'Tips dan trik seputar persiapan pernikahan',
            ]
        );

        $vendorCategory = BlogCategory::firstOrCreate(
            ['slug' => 'vendor-guide'],
            [
                'name' => 'Vendor Guide',
                'description' => 'Panduan memilih vendor pernikahan terbaik',
            ]
        );

        $venueCategory = BlogCategory::firstOrCreate(
            ['slug' => 'venue-decoration'],
            [
                'name' => 'Venue & Decoration',
                'description' => 'Inspirasi venue dan dekorasi pernikahan',
            ]
        );

        $trendCategory = BlogCategory::firstOrCreate(
            ['slug' => 'wedding-trends'],
            [
                'name' => 'Wedding Trends',
                'description' => 'Tren pernikahan terkini dan ide kreatif',
            ]
        );

        $adminAuthor = User::whereHas('roles', fn ($q) => $q->where('name', 'admin'))->first();
        $superAdminAuthor = User::whereHas('roles', fn ($q) => $q->where('name', 'super_admin'))->first();

        $defaultAuthor = $adminAuthor ?? $superAdminAuthor ?? User::first();

        if (! $defaultAuthor) {
            $this->command?->warn('BlogSeeder skipped: no users found. Run UserSeeder first.');

            return;
        }

        $sarah = $adminAuthor ?? $defaultAuthor;
        $budi = $adminAuthor ?? $defaultAuthor;
        $linda = $superAdminAuthor ?? $defaultAuthor;

        // Blog 1
        Blog::firstOrCreate(
            ['slug' => 'cara-mengatur-budget-pernikahan'],
            [
                'title' => 'Cara Mengatur Budget Pernikahan Agar Tetap Hemat',
                'excerpt' => 'Tips praktis mengatur budget pernikahan agar tetap hemat tanpa mengorbankan kualitas dan kemewahan acara Anda.',
                'blog_category_id' => $budgetCategory->id,
                'category_color' => '#f59e0b',
                'user_id' => $sarah->id,
                'date' => '2025-11-01',
                'read_time' => 7,
                'image' => 'https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?w=1200&h=600&fit=crop&auto=format&q=80',
                'is_published' => true,
                'content' => '<p>Pernikahan impian tidak selalu harus menguras kantong. Dengan planning yang smart dan prioritas yang jelas, Anda bisa mengadakan pernikahan memorable tanpa harus berutang.</p><h2>Tips Mengatur Budget</h2><ul><li>Tentukan total budget yang realistis</li><li>Buat breakdown per kategori</li><li>Sisihkan dana cadangan 10%</li><li>Prioritaskan hal yang penting</li></ul>',
            ]
        );

        // Blog 2
        Blog::firstOrCreate(
            ['slug' => 'memilih-fotografer-pernikahan-profesional'],
            [
                'title' => 'Panduan Memilih Fotografer Pernikahan Profesional',
                'excerpt' => 'Dokumentasi pernikahan adalah investasi selamanya. Pelajari cara memilih fotografer yang tepat untuk hari spesial Anda.',
                'blog_category_id' => $vendorCategory->id,
                'category_color' => '#8b5cf6',
                'user_id' => $budi->id,
                'date' => '2025-11-02',
                'read_time' => 6,
                'image' => 'https://images.unsplash.com/photo-1606800052052-a08af7148866?w=1200&h=600&fit=crop&auto=format&q=80',
                'is_published' => true,
                'content' => '<p>Fotografer adalah salah satu vendor paling penting dalam pernikahan Anda. Mereka akan mengabadikan momen-momen berharga yang tidak bisa diulang.</p><h2>Yang Perlu Diperhatikan</h2><ul><li>Review portfolio dan style fotografi</li><li>Tanya paket dan harga detail</li><li>Cek testimoni klien sebelumnya</li><li>Meeting langsung untuk chemistry</li></ul>',
            ]
        );

        // Blog 3
        Blog::firstOrCreate(
            ['slug' => 'tren-dekorasi-pernikahan-2026'],
            [
                'title' => 'Tren Dekorasi Pernikahan 2026 yang Wajib Dicoba',
                'excerpt' => 'Simak tren dekorasi pernikahan terkini yang akan membuat acara Anda tampil lebih elegan dan Instagram-worthy.',
                'blog_category_id' => $trendCategory->id,
                'category_color' => '#ec4899',
                'user_id' => $linda->id,
                'date' => '2025-11-03',
                'read_time' => 5,
                'image' => 'https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=1200&h=600&fit=crop&auto=format&q=80',
                'is_published' => true,
                'content' => '<p>Tahun 2026 membawa tren dekorasi pernikahan yang memadukan elegansi klasik dengan sentuhan modern dan sustainable.</p><h2>Tren Populer 2026</h2><ul><li>Dried flowers dan pampas grass</li><li>Warna earth tone dan sage green</li><li>Minimalist dengan greenery</li><li>Sustainable decoration</li></ul>',
            ]
        );

        // Blog 4
        Blog::firstOrCreate(
            ['slug' => 'checklist-persiapan-pernikahan-6-bulan'],
            [
                'title' => 'Checklist Persiapan Pernikahan 6 Bulan Sebelum H-Day',
                'excerpt' => 'Panduan lengkap checklist persiapan pernikahan mulai dari 6 bulan sebelum hari H agar tidak ada yang terlewat.',
                'blog_category_id' => $tipsCategory->id,
                'category_color' => '#10b981',
                'user_id' => $sarah->id,
                'date' => '2025-11-04',
                'read_time' => 8,
                'image' => 'https://images.unsplash.com/photo-1522673607200-164d1b6ce486?w=1200&h=600&fit=crop&auto=format&q=80',
                'is_published' => true,
                'content' => '<p>Persiapan pernikahan membutuhkan planning yang matang. Dengan checklist ini, Anda bisa mengatur timeline dengan lebih terstruktur.</p><h2>Timeline 6 Bulan</h2><ul><li>Bulan 1-2: Booking venue dan vendor utama</li><li>Bulan 3-4: Fitting gaun dan setelan</li><li>Bulan 5: Finalisasi detail acara</li><li>Bulan 6: Rehearsal dan last minute check</li></ul>',
            ]
        );

        // Blog 5
        Blog::firstOrCreate(
            ['slug' => 'memilih-venue-pernikahan-indoor-vs-outdoor'],
            [
                'title' => 'Memilih Venue Pernikahan: Indoor vs Outdoor',
                'excerpt' => 'Bingung pilih venue indoor atau outdoor? Simak kelebihan dan kekurangan masing-masing untuk menentukan pilihan terbaik.',
                'blog_category_id' => $venueCategory->id,
                'category_color' => '#3b82f6',
                'user_id' => $budi->id,
                'date' => '2025-11-05',
                'read_time' => 6,
                'image' => 'https://images.unsplash.com/photo-1510076857177-7470076d4098?w=1200&h=600&fit=crop&auto=format&q=80',
                'is_published' => true,
                'content' => '<p>Pemilihan venue adalah keputusan penting yang akan mempengaruhi keseluruhan konsep dan budget pernikahan Anda.</p><h2>Indoor vs Outdoor</h2><h3>Kelebihan Indoor</h3><ul><li>Tidak tergantung cuaca</li><li>AC dan fasilitas lengkap</li><li>Lebih predictable</li></ul><h3>Kelebihan Outdoor</h3><ul><li>Natural lighting bagus untuk foto</li><li>Suasana lebih romantis</li><li>Lebih unik dan memorable</li></ul>',
            ]
        );

        // Blog 6
        Blog::firstOrCreate(
            ['slug' => 'tips-memilih-gaun-pengantin-sesuai-bentuk-tubuh'],
            [
                'title' => 'Tips Memilih Gaun Pengantin Sesuai Bentuk Tubuh',
                'excerpt' => 'Setiap bentuk tubuh memiliki model gaun yang paling flattering. Temukan gaun pengantin yang sempurna untuk Anda.',
                'blog_category_id' => $tipsCategory->id,
                'category_color' => '#f59e0b',
                'user_id' => $linda->id,
                'date' => '2025-11-06',
                'read_time' => 7,
                'image' => 'https://images.unsplash.com/photo-1594552072238-52759f1f9c71?w=1200&h=600&fit=crop&auto=format&q=80',
                'is_published' => true,
                'content' => '<p>Gaun pengantin yang tepat akan membuat Anda tampil percaya diri dan cantik maksimal di hari pernikahan.</p><h2>Panduan Bentuk Tubuh</h2><ul><li>Pear Shape: A-line atau ball gown</li><li>Apple Shape: Empire waist</li><li>Hourglass: Mermaid atau fit-and-flare</li><li>Rectangle: Ball gown dengan detail pinggang</li></ul>',
            ]
        );

        // Blog 7
        Blog::firstOrCreate(
            ['slug' => 'wedding-catering-menu-yang-disukai-tamu'],
            [
                'title' => 'Menu Catering Pernikahan yang Paling Disukai Tamu',
                'excerpt' => 'Food adalah salah satu yang paling diingat tamu. Simak rekomendasi menu catering yang selalu jadi favorit.',
                'blog_category_id' => $vendorCategory->id,
                'category_color' => '#ef4444',
                'user_id' => $sarah->id,
                'date' => '2025-11-07',
                'read_time' => 5,
                'image' => 'https://images.unsplash.com/photo-1555244162-803834f70033?w=1200&h=600&fit=crop&auto=format&q=80',
                'is_published' => true,
                'content' => '<p>Menu catering yang lezat dan variatif akan membuat tamu Anda puas dan mengingat pernikahan Anda dengan baik.</p><h2>Menu Favorit</h2><ul><li>Main Course: Nasi Briyani, Beef Wellington</li><li>Appetizer: Spring rolls, Bruschetta</li><li>Dessert: Mini cakes, Gelato station</li><li>Beverage: Signature mocktail, Coffee bar</li></ul>',
            ]
        );

        // Blog 8
        Blog::firstOrCreate(
            ['slug' => 'makeup-natural-vs-glam-untuk-pengantin'],
            [
                'title' => 'Makeup Natural vs Glam: Mana yang Cocok untuk Anda?',
                'excerpt' => 'Pilih style makeup yang sesuai dengan kepribadian dan tema pernikahan Anda agar tampil maksimal.',
                'blog_category_id' => $tipsCategory->id,
                'category_color' => '#ec4899',
                'user_id' => $budi->id,
                'date' => '2025-11-08',
                'read_time' => 6,
                'image' => 'https://images.unsplash.com/photo-1487412947147-5cebf100ffc2?w=1200&h=600&fit=crop&auto=format&q=80',
                'is_published' => true,
                'content' => '<p>Makeup pengantin harus tahan lama, photogenic, dan tentunya membuat Anda merasa cantik dan percaya diri.</p><h2>Natural vs Glam</h2><h3>Natural Makeup</h3><ul><li>Cocok untuk outdoor/garden wedding</li><li>Terlihat fresh dan youthful</li><li>Flawless skin dengan soft colors</li></ul><h3>Glam Makeup</h3><ul><li>Cocok untuk ballroom/evening wedding</li><li>Bold dan dramatic</li><li>Strong eyes dan defined features</li></ul>',
            ]
        );

        // Blog 9
        Blog::firstOrCreate(
            ['slug' => 'entertainment-pernikahan-yang-memorable'],
            [
                'title' => 'Ide Entertainment Pernikahan yang Memorable',
                'excerpt' => 'Buat pernikahan Anda lebih berkesan dengan pilihan entertainment yang unik dan menghibur tamu.',
                'blog_category_id' => $trendCategory->id,
                'category_color' => '#8b5cf6',
                'user_id' => $linda->id,
                'date' => '2025-11-09',
                'read_time' => 5,
                'image' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?w=1200&h=600&fit=crop&auto=format&q=80',
                'is_published' => true,
                'content' => '<p>Entertainment yang tepat akan membuat suasana pernikahan lebih hidup dan tamu Anda terhibur sepanjang acara.</p><h2>Ide Entertainment</h2><ul><li>Live band atau akustik</li><li>Photo booth dengan props lucu</li><li>Magic show atau live painting</li><li>Traditional dance performance</li><li>Interactive games untuk tamu</li></ul>',
            ]
        );

        // Blog 10
        Blog::firstOrCreate(
            ['slug' => 'honeymoon-destinasi-romantis-di-indonesia'],
            [
                'title' => 'Destinasi Honeymoon Romantis di Indonesia',
                'excerpt' => 'Tidak perlu jauh-jauh ke luar negeri, Indonesia punya banyak destinasi honeymoon yang tak kalah romantis dan memukau.',
                'blog_category_id' => $tipsCategory->id,
                'category_color' => '#10b981',
                'user_id' => $sarah->id,
                'date' => '2025-11-10',
                'read_time' => 8,
                'image' => 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=1200&h=600&fit=crop&auto=format&q=80',
                'is_published' => true,
                'content' => '<p>Indonesia memiliki surga-surga tersembunyi yang sempurna untuk bulan madu romantis bersama pasangan.</p><h2>Top Destinasi</h2><ul><li>Bali - Ubud dan Nusa Penida</li><li>Labuan Bajo - Komodo Island</li><li>Raja Ampat - Paradise underwater</li><li>Yogyakarta - Culture dan nature</li><li>Lombok - Pantai dan Gili Islands</li></ul><p>Setiap destinasi menawarkan pengalaman unik yang akan membuat honeymoon Anda tak terlupakan.</p>',
            ]
        );

        $this->command->info('BlogSeeder completed: 10 blog posts created across 5 categories');
    }
}
