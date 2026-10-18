@extends('layouts.app')
@section('title', 'Lokasi Pameran — Sumsel Wedding Expo')
@push('head')
    <meta name="description" content="Temukan lokasi Sumsel Wedding Expo, lihat peta venue, dan rencanakan perjalanan Anda menuju pameran.">
    @include('home-styles')
    @include('jadwal-styles')
    @include('lokasi-styles')
@endpush
@php
    $event = $expo ?? null;
    $eventName = $event?->nama_expo ?: 'Sumsel Wedding Expo';
    $venue = trim((string) $event?->lokasi);
    $address = trim((string) $event?->alamat);
    $mapQuery = trim(implode(' ', array_unique(array_filter([$venue, $address]))));
    $directionsUrl = 'https://www.google.com/maps/dir/?api=1&destination=' . rawurlencode($mapQuery);
    $start = $event?->tanggal_mulai?->copy()->locale('id');
    $end = $event?->tanggal_selesai?->copy()->locale('id');
    $today = now('Asia/Jakarta')->toDateString();
    $ended = $end && $end->toDateString() < $today;
    $live = $start && $end && $start->toDateString() <= $today && $end->toDateString() >= $today;
    $status = $ended ? 'Event telah selesai' : ($live ? 'Sedang berlangsung' : ($start ? 'Event mendatang' : 'Nantikan edisi berikutnya'));
    $dateLabel = $start ? $start->translatedFormat('d F Y') . ($end && !$start->isSameDay($end) ? ' – ' . $end->translatedFormat('d F Y') : '') : 'Tanggal segera diumumkan';
@endphp
@section('content')
<div class="swe-home swe-location-page">
    <section class="swe-schedule-hero" aria-labelledby="location-heading">
        <div class="swe-wrap">
            <nav class="swe-breadcrumb" aria-label="Jejak navigasi"><a href="{{ route('home') }}">Beranda</a><span aria-hidden="true">/</span><span aria-current="page">Lokasi Pameran</span></nav>
            <div class="swe-schedule-hero-grid">
                <div><span class="swe-eyebrow swe-light">YOUR JOURNEY STARTS HERE</span><h1 id="location-heading">Satu tujuan.<br><em>Beribu inspirasi.</em></h1><p>Temukan tempat bertemunya inspirasi, vendor, dan cerita bahagia. Rencanakan perjalanan Anda menuju {{ $eventName }}.</p></div>
                <div class="swe-edition"><span class="swe-status @if ($live) swe-status-live @endif"><span aria-hidden="true"></span>{{ $status }}</span><p>{{ $eventName }}</p><strong>{{ $dateLabel }}</strong>@if ($ended)<span class="swe-archive-note">Lokasi di bawah merupakan venue untuk edisi yang telah selesai.</span>@endif</div>
            </div>
        </div>
    </section>

    <nav class="swe-wrap swe-location-shortcuts" aria-label="Panduan lokasi">
        <a href="#venue"><span>01</span>Venue pameran <span aria-hidden="true">↘</span></a>
        <a href="#peta"><span>02</span>Peta & arah <span aria-hidden="true">↘</span></a>
        <a href="#perjalanan"><span>03</span>Rencanakan perjalanan <span aria-hidden="true">↘</span></a>
    </nav>

    <section class="swe-section" id="venue" aria-labelledby="venue-heading">
        <div class="swe-wrap">
            <div class="swe-section-top"><div><span class="swe-eyebrow">THE PLACE TO BE</span><h2 id="venue-heading">Sampai bertemu<br>di <em>venue expo.</em></h2></div><p>{{ $ended ? 'Informasi lokasi untuk dokumentasi dan referensi edisi ini.' : 'Lihat alamat venue dan pilih rute perjalanan yang sesuai dengan titik keberangkatan Anda.' }}</p></div>
            <div class="swe-venue-grid">
                <div class="swe-venue-details">
                    <div class="swe-venue-number" aria-hidden="true">SWE<span>WEDDING EXPERIENCE</span></div>
                    <span class="swe-eyebrow">LOKASI PAMERAN</span>
                    <h3>{{ $venue ?: 'Lokasi segera diumumkan.' }}</h3>
                    <p class="swe-address">{{ $address ?: ($venue ? 'Gunakan peta untuk melihat detail alamat dan rute menuju venue.' : 'Informasi venue akan ditampilkan setelah lokasi edisi berikutnya tersedia.') }}</p>
                    <dl class="swe-venue-meta"><div><dt>Tanggal expo</dt><dd>{{ $dateLabel }}</dd></div><div><dt>Jam kegiatan</dt><dd>Mengikuti rundown acara</dd></div></dl>
                    <div class="swe-actions">@if ($mapQuery)<a class="swe-btn swe-btn-burgundy" href="{{ $directionsUrl }}" target="_blank" rel="noopener noreferrer">Petunjuk Arah <span aria-hidden="true">↗</span></a>@endif<a class="swe-text-link" href="{{ route('jadwal') }}">{{ $ended ? 'Lihat arsip jadwal' : 'Lihat jadwal acara' }} <span aria-hidden="true">↗</span></a></div>
                </div>
                <div class="swe-venue-map" id="peta">
                    @if ($mapQuery)
                        <iframe title="Peta lokasi {{ $venue ?: $address }}" src="https://maps.google.com/maps?q={{ rawurlencode($mapQuery) }}&output=embed" loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade"></iframe>
                        <div class="swe-map-caption"><span>Pilih rute dari lokasi keberangkatan Anda.</span><a href="{{ $directionsUrl }}" target="_blank" rel="noopener noreferrer">Buka Google Maps <span aria-hidden="true">↗</span></a></div>
                    @else
                        <div class="swe-map-empty"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 10c0 6-8 11-8 11S4 16 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg><h3>Peta segera tersedia.</h3><p>Lokasi akan diperbarui bersama informasi edisi expo berikutnya.</p><a class="swe-text-link" href="https://www.instagram.com/sumselweddingexpo/" target="_blank" rel="noopener noreferrer">Ikuti kabar terbaru <span aria-hidden="true">↗</span></a></div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section class="swe-section swe-travel" id="perjalanan" aria-labelledby="travel-heading">
        <div class="swe-wrap">
            <div class="swe-section-top"><div><span class="swe-eyebrow">ENJOY THE JOURNEY</span><h2 id="travel-heading">Perjalanan nyaman.<br>Kunjungan <em>berkesan.</em></h2></div><p>Sesuaikan pilihan transportasi dengan kebutuhan Anda dan cek kondisi perjalanan sebelum berangkat.</p></div>
            <div class="swe-travel-grid">
                @foreach ([['01', 'Kendaraan pribadi', 'Gunakan petunjuk arah menuju venue. Periksa pintu masuk dan ketentuan parkir langsung melalui informasi pengelola lokasi.', 'M4 15V9l2-4h12l2 4v6M4 9h16M6 15v3m12-3v3M7 12h.01M17 12h.01'], ['02', 'Taksi & transportasi online', 'Masukkan nama dan alamat venue sebagai tujuan. Sesuaikan titik turun dan penjemputan dengan area yang diizinkan pengelola.', 'M5 17H3V9l3-5h12l3 5v8h-2M5 17h14M6 9h12M7 13h.01M17 13h.01M7 17v3m10-3v3'], ['03', 'Transportasi umum', 'Cek rute, titik pemberhentian, dan jadwal terbaru melalui layanan transportasi yang Anda gunakan. Rencanakan perjalanan lanjutan menuju venue.', 'M5 16V5c0-3 14-3 14 0v11M5 10h14M8 19l-2 3m10-3 2 3M8 15h.01M16 15h.01M5 19h14']] as [$number, $title, $description, $path])
                    <article class="swe-travel-card"><div class="swe-travel-card-top"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="{{ $path }}"/></svg><span>{{ $number }}</span></div><h3>{{ $title }}</h3><p>{{ $description }}</p></article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="swe-section" aria-labelledby="visit-heading"><div class="swe-wrap swe-visit-preparation"><div><span class="swe-eyebrow">A LITTLE PLANNING GOES A LONG WAY</span><h2 id="visit-heading">Sebelum<br><em>Anda berangkat.</em></h2><p>Persiapan sederhana untuk menikmati kunjungan dengan lebih leluasa.</p></div><ol class="swe-visit-checklist"><li><span>01</span><div><h3>Pilih agenda yang ingin diikuti</h3><p>Lihat waktu dan lokasi setiap kegiatan di halaman jadwal.</p><a class="swe-text-link" href="{{ route('jadwal') }}">Jelajahi jadwal <span aria-hidden="true">↗</span></a></div></li><li><span>02</span><div><h3>Kenali peserta dan denah booth</h3><p>Temukan vendor yang ingin ditemui agar kunjungan lebih terarah.</p><a class="swe-text-link" href="{{ route('peserta.index') }}">Peserta & denah booth <span aria-hidden="true">↗</span></a></div></li><li><span>03</span><div><h3>Pastikan informasi terbaru</h3><p>Untuk kebutuhan akses, fasilitas, dan perubahan lokasi, cek pengumuman resmi penyelenggara sebelum datang.</p><a class="swe-text-link" href="https://www.instagram.com/sumselweddingexpo/" target="_blank" rel="noopener noreferrer">Kabar terbaru <span aria-hidden="true">↗</span></a></div></li></ol></div></section>

    <section class="swe-exhibitor"><div class="swe-wrap swe-exhibitor-inner"><div><span class="swe-eyebrow swe-light">{{ $ended ? 'THE MEMORIES LIVE ON' : 'SEE YOU AT THE EXPO' }}</span><h2>{{ $ended ? 'Tempat bertemu.' : 'Tujuan yang sama.' }}<br><em>{{ $ended ? 'Kenangan yang tinggal.' : 'Cerita yang istimewa.' }}</em></h2><p>{{ $ended ? 'Lihat kembali suasana dan momen berkesan dari Sumsel Wedding Expo.' : 'Lengkapi rencana kunjungan Anda dengan melihat rangkaian acara Sumsel Wedding Expo.' }}</p></div><a class="swe-btn swe-btn-cream" href="{{ $ended ? route('gallery') : route('jadwal') }}">{{ $ended ? 'Jelajahi Galeri' : 'Lihat Jadwal Acara' }} <span aria-hidden="true">↗</span></a></div></section>
</div>
@endsection
