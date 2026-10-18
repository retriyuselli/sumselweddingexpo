@extends('layouts.app')
@section('title', 'Sumsel Wedding Expo — Inspirasi untuk Hari Bahagiamu')
@push('head')
    <meta name="description" content="{{ $home->meta_description ?: 'Jelajahi Sumsel Wedding Expo: temukan vendor pernikahan, jadwal acara, denah booth, dan informasi pendaftaran exhibitor.' }}">
    @include('home-styles')
@endpush

@php
    $publicImage = function ($path) {
        return $path && \Illuminate\Support\Facades\Storage::disk('public')->exists($path)
            ? asset('storage/' . $path) : null;
    };
    $heroDesktop = asset('images/swe-home-hero.jpg');
    $heroMobile = $heroDesktop;
    $heroSide = $publicImage($home->hero_side_image) ?? $publicImage($penyelenggara?->banner_2);
    $eventEndIso = $eventEnd?->copy()->timezone('Asia/Jakarta')->endOfDay()->toIso8601String();
    $eventHasEnded = $eventEnd && now('Asia/Jakarta')->greaterThan($eventEnd->copy()->endOfDay());
    $eventIsLive = $eventStart && $eventEnd && now('Asia/Jakarta')->between($eventStart->copy()->startOfDay(), $eventEnd->copy()->endOfDay());
    $dateLabel = $eventStart
        ? $eventStart->translatedFormat('d F Y') . ($eventEnd && !$eventStart->isSameDay($eventEnd) ? ' – ' . $eventEnd->translatedFormat('d F Y') : '')
        : 'Tanggal segera diumumkan';
    $mapQuery = trim(implode(' ', array_filter([$eventLocation, $expo?->alamat])));
    $visibleSponsors = $sponsors->filter(fn ($sponsor) => $publicImage($sponsor->logo));
    $videos = collect($home->highlight_videos ?? [])->filter(fn ($video) => preg_match('/^[a-zA-Z0-9_-]{11}$/', $video['video_id'] ?? ''))->take(3);
    $arrow = 'M7 17 17 7M7 7h10v10';
@endphp

@section('content')
<div class="swe-home">
    <section class="swe-hero" aria-labelledby="home-heading">
        <picture class="swe-hero-photo">
            <source media="(max-width: 767px)" srcset="{{ $heroMobile }}">
            <img src="{{ $heroDesktop }}" alt="Pasangan pengantin bernuansa adat Sumatera Selatan dengan busana burgundy dan emas" fetchpriority="high">
        </picture>
        <div class="swe-wrap swe-hero-inner">
            <div class="swe-hero-copy">
                <span class="swe-eyebrow swe-light">THE WEDDING EXPERIENCE · SUMATERA SELATAN</span>
                <p class="swe-event-name">{{ $eventName }}</p>
                <h1 id="home-heading">Awal indah untuk<br>hari <em>bahagiamu.</em></h1>
                <p class="swe-hero-description">{{ $home->hero_subtitle ?: 'Temukan inspirasi, kenali vendor, dan rencanakan hari istimewa dalam satu pengalaman wedding expo.' }}</p>
                <div class="swe-actions">
                    <a class="swe-btn swe-btn-cream" href="{{ route('peserta.index') }}">Jelajahi Peserta <span aria-hidden="true">↗</span></a>
                    <a class="swe-btn swe-btn-glass" href="{{ route('exhibitor') }}">Daftar Exhibitor</a>
                </div>
                <div class="swe-countdown" data-event-start="{{ $eventDate }}" data-event-end="{{ $eventEndIso }}">
                    <p data-countdown-label>{{ $eventHasEnded ? 'Terima kasih telah menjadi bagian dari expo' : ($eventIsLive ? 'Expo sedang berlangsung' : ($eventStart ? 'Menuju hari istimewa' : 'Nantikan edisi berikutnya')) }}</p>
                    <div class="swe-clock" data-countdown-digits @if (!$eventStart || $eventHasEnded || $eventIsLive) hidden @endif>
                        @foreach (['days' => 'Hari', 'hours' => 'Jam', 'minutes' => 'Menit', 'seconds' => 'Detik'] as $unit => $label)
                            <div><strong data-unit="{{ $unit }}">00</strong><span>{{ $label }}</span></div>
                        @endforeach
                    </div>
                </div>
            </div>
            @if ($heroSide)
                <div class="swe-event-poster"><img src="{{ $heroSide }}" alt="Poster {{ $eventName }}"><span>YOUR WEDDING JOURNEY STARTS HERE</span></div>
            @endif
        </div>
        <div class="swe-wrap">
            <div class="swe-event-bar">
                <div><span class="swe-bar-label">TANGGAL EXPO</span><strong>{{ $dateLabel }}</strong></div>
                <div><span class="swe-bar-label">TEMUI KAMI DI</span><strong>{{ $eventLocation ?: 'Lokasi segera diumumkan' }}</strong></div>
                <a href="#jadwal">Jadwal & lokasi <svg viewBox="0 0 24 24" aria-hidden="true"><path d="{{ $arrow }}"/></svg></a>
            </div>
        </div>
    </section>

    <nav class="swe-quick swe-wrap" aria-label="Akses cepat expo">
        @foreach ([['01', 'Denah Booth', route('peserta.index')], ['02', 'Jadwal Acara', route('jadwal')], ['03', 'Peserta Expo', route('peserta.index')], ['04', 'Daftar Exhibitor', route('exhibitor')]] as [$number, $label, $url])
            <a href="{{ $url }}"><span>{{ $number }}</span><strong>{{ $label }}</strong><svg viewBox="0 0 24 24" aria-hidden="true"><path d="{{ $arrow }}"/></svg></a>
        @endforeach
    </nav>

    <section class="swe-section swe-intro" aria-labelledby="experience-heading">
        <div class="swe-wrap swe-intro-grid">
            <div><span class="swe-eyebrow">LEBIH DARI SEBUAH PAMERAN</span><h2 id="experience-heading">Satu tempat.<br>Segala inspirasi<br><em>pernikahan.</em></h2><a class="swe-text-link" href="{{ route('penyelenggara') }}">Kenali Sumsel Wedding Expo <span aria-hidden="true">↗</span></a></div>
            <div class="swe-intro-copy">
                @if (filled(strip_tags($home->tentang_kami ?? '')))
                    <p>{{ \Illuminate\Support\Str::limit(html_entity_decode(strip_tags($home->tentang_kami)), 380) }}</p>
                @else
                    <p>Setiap pernikahan punya ceritanya sendiri. Mulai cerita Anda dengan bertemu penyedia jasa pernikahan dan menemukan inspirasi dalam satu tempat.</p>
                @endif
                <div class="swe-benefits">
                    @foreach ([['01', 'Bertemu langsung', 'Kenali vendor dan diskusikan kebutuhan pernikahan Anda.'], ['02', 'Temukan inspirasi', 'Jelajahi ide dekorasi, busana, dan momen pernikahan.'], ['03', 'Rencanakan kunjungan', 'Lihat peserta, denah, dan agenda sebelum datang.']] as [$number, $title, $description])
                        <div><span>{{ $number }}</span><div><h3>{{ $title }}</h3><p>{{ $description }}</p></div></div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="swe-section swe-vendors" id="peserta" aria-labelledby="vendor-heading">
        <div class="swe-wrap">
            <div class="swe-section-top"><div><span class="swe-eyebrow">KENALI PILIHAN ANDA</span><h2 id="vendor-heading">Bertemu vendor,<br>menemukan <em>yang tepat.</em></h2></div><div><p>Jelajahi peserta {{ $eventName }} dan mulai percakapan untuk hari bahagia Anda.</p><a class="swe-text-link" href="{{ route('peserta.index') }}">Lihat semua peserta @if ($participantCount) ({{ $participantCount }}) @endif <span aria-hidden="true">↗</span></a></div></div>
            <div class="swe-vendor-grid">
                @forelse ($featuredVendors as $vendor)
                    <a class="swe-vendor" href="{{ route('peserta.show', $vendor->slug) }}">
                        <div class="swe-vendor-image">
                            @if ($publicImage($vendor->logo))<img src="{{ $publicImage($vendor->logo) }}" alt="Logo {{ $vendor->nama_vendor }}" loading="lazy" width="320" height="220">
                            @else<span class="swe-monogram" aria-hidden="true">{{ mb_substr($vendor->nama_vendor, 0, 1) }}</span>@endif
                            <span class="swe-vendor-category">{{ $vendor->jenisUsaha?->nama_jenis_usaha ?: 'Vendor Pernikahan' }}</span>
                        </div>
                        <div class="swe-vendor-info"><div><h3>{{ $vendor->nama_vendor }}</h3><p>{{ $vendor->kota ?: 'Peserta expo' }}</p></div><span aria-hidden="true">↗</span></div>
                    </a>
                @empty
                    <div class="swe-empty"><span class="swe-eyebrow">SEGERA HADIR</span><h3>Kenalan dengan vendor impian Anda.</h3><p>Daftar peserta akan ditampilkan setelah keikutsertaan vendor tersedia untuk edisi ini.</p><a class="swe-text-link" href="{{ route('exhibitor') }}">Ingin menjadi peserta? <span aria-hidden="true">↗</span></a></div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="swe-section" id="jadwal" aria-labelledby="schedule-heading">
        <div class="swe-wrap">
            <div class="swe-section-top"><div><span class="swe-eyebrow">RENCANAKAN KUNJUNGAN ANDA</span><h2 id="schedule-heading">Catat harinya.<br><em>Rasakan momennya.</em></h2></div><a class="swe-text-link" href="{{ route('jadwal') }}">Lihat rundown lengkap <span aria-hidden="true">↗</span></a></div>
            <div class="swe-schedule-grid">
                <div class="swe-agenda">
                    @forelse ($scheduleDays as $day)
                        <div class="swe-agenda-row"><div class="swe-date"><strong>{{ $day['date']->format('d') }}</strong><span>{{ $day['date']->translatedFormat('M Y') }}</span></div><div><span class="swe-eyebrow">{{ $day['day_name'] }} · {{ $day['day_label'] }}</span><h3>{{ $day['activity'] }}</h3><p>{{ $day['waktu'] }}</p></div></div>
                    @empty
                        <div class="swe-empty"><h3>Jadwal segera diumumkan.</h3><p>Ikuti informasi terbaru untuk mengetahui tanggal dan rangkaian kegiatan expo berikutnya.</p></div>
                    @endforelse
                </div>
                <aside class="swe-location"><span class="swe-eyebrow">LOKASI EXPO</span><h3>{{ $eventLocation ?: 'Sampai bertemu di expo berikutnya.' }}</h3><p>{{ $expo?->alamat ?: 'Informasi lokasi dan akses akan diperbarui sesuai edisi expo.' }}</p>
                    @if ($mapQuery)
                        <a class="swe-btn swe-btn-burgundy" href="https://www.google.com/maps/search/?api=1&query={{ rawurlencode($mapQuery) }}" target="_blank" rel="noopener noreferrer">Buka petunjuk arah <span aria-hidden="true">↗</span></a>
                        <iframe title="Peta lokasi {{ $eventLocation }}" src="https://maps.google.com/maps?q={{ rawurlencode($mapQuery) }}&output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    @else
                        <a class="swe-text-link" href="https://www.instagram.com/sumselweddingexpo/" target="_blank" rel="noopener noreferrer">Ikuti kabar terbaru <span aria-hidden="true">↗</span></a>
                    @endif
                </aside>
            </div>
        </div>
    </section>

    <section class="swe-section swe-memories" aria-labelledby="memories-heading">
        <div class="swe-wrap">
            <div class="swe-section-top"><div><span class="swe-eyebrow">A LITTLE LOOK BACK</span><h2 id="memories-heading">Momen yang menjadi<br><em>awal cerita.</em></h2></div><a class="swe-text-link" href="{{ route('gallery') }}">Jelajahi galeri <span aria-hidden="true">↗</span></a></div>
            @if ($videos->isNotEmpty())
                <div class="swe-video-grid" id="home-video-list">@foreach ($videos as $video)<article><iframe src="https://www.youtube-nocookie.com/embed/{{ $video['video_id'] }}" title="{{ $video['title'] ?? 'Highlight Sumsel Wedding Expo' }}" loading="lazy" allow="encrypted-media; picture-in-picture" allowfullscreen></iframe><h3>{{ $video['title'] ?? 'Highlight Sumsel Wedding Expo' }}</h3></article>@endforeach</div>
                <nav class="swe-video-controls" aria-label="Navigasi video highlight" hidden>
                    <button type="button" data-video-prev aria-label="Video sebelumnya" aria-controls="home-video-list">&larr;</button>
                    <span data-video-page role="status" aria-live="polite"></span>
                    <button type="button" data-video-next aria-label="Video selanjutnya" aria-controls="home-video-list">&rarr;</button>
                </nav>
                @include('home-video-pagination')
            @else
                <div class="swe-memory-banner"><div><p>Suasana expo, inspirasi pernikahan, dan pertemuan yang berkesan.</p><a class="swe-btn swe-btn-cream" href="{{ route('gallery') }}">Lihat dokumentasi <span aria-hidden="true">↗</span></a></div></div>
            @endif
        </div>
    </section>

    @if ($visibleSponsors->isNotEmpty())
        <section class="swe-section swe-sponsors" id="sponsor" aria-labelledby="sponsor-heading"><div class="swe-wrap"><span class="swe-eyebrow">BERSAMA MEWUJUDKAN MOMEN ISTIMEWA</span><h2 id="sponsor-heading">Partner & <em>Sponsor</em></h2><div class="swe-sponsor-grid">@foreach ($visibleSponsors as $sponsor)<img src="{{ $publicImage($sponsor->logo) }}" alt="{{ $sponsor->name }}" loading="lazy" width="160" height="96">@endforeach</div></div></section>
    @endif

    <section class="swe-exhibitor" id="exhibitor" aria-labelledby="exhibitor-heading"><div class="swe-wrap swe-exhibitor-inner"><div><span class="swe-eyebrow swe-light">GROW YOUR BRAND WITH US</span><h2 id="exhibitor-heading">Jadilah bagian dari<br>cerita <em>bahagia mereka.</em></h2><p>Perkenalkan layanan Anda, bertemu calon pengantin, dan bangun koneksi di Sumsel Wedding Expo.</p></div><a class="swe-btn swe-btn-cream" href="{{ route('exhibitor') }}">Daftar Exhibitor <span aria-hidden="true">↗</span></a></div></section>
    <div class="swe-social swe-wrap"><div><span class="swe-eyebrow">STAY INSPIRED</span><p>Inspirasi dan kabar terbaru, setiap saat.</p></div><a class="swe-text-link" href="https://www.instagram.com/sumselweddingexpo/" target="_blank" rel="noopener noreferrer">&#64;sumselweddingexpo <span aria-hidden="true">↗</span></a></div>
</div>
@endsection

@push('scripts')
<script>
(() => {
    const clock = document.querySelector('.swe-countdown');
    if (!clock || !clock.dataset.eventStart) return;
    const start = Date.parse(clock.dataset.eventStart);
    const end = Date.parse(clock.dataset.eventEnd);
    const label = clock.querySelector('[data-countdown-label]');
    const digits = clock.querySelector('[data-countdown-digits]');
    if (!Number.isFinite(start)) return;
    let timer;
    function update() {
        const now = Date.now();
        const remaining = start - now;
        if (remaining <= 0) {
            digits.hidden = true;
            label.textContent = Number.isFinite(end) && now > end
                ? 'Terima kasih telah menjadi bagian dari expo'
                : 'Expo sedang berlangsung';
            if (Number.isFinite(end) && now > end && timer) clearInterval(timer);
            return;
        }
        digits.hidden = false;
        label.textContent = 'Menuju hari istimewa';
        const seconds = Math.floor(remaining / 1000);
        const values = { days: Math.floor(seconds / 86400), hours: Math.floor(seconds / 3600) % 24, minutes: Math.floor(seconds / 60) % 60, seconds: seconds % 60 };
        Object.entries(values).forEach(([unit, value]) => {
            clock.querySelector(`[data-unit="${unit}"]`).textContent = String(value).padStart(2, '0');
        });
    }
    update();
    timer = setInterval(update, 1000);
})();
</script>
@endpush
