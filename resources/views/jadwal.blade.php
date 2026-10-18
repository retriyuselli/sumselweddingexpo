@extends('layouts.app')
@section('title', 'Jadwal Acara — Sumsel Wedding Expo')
@push('head')
    <meta name="description" content="Lihat jadwal dan rundown Sumsel Wedding Expo. Rencanakan kunjungan Anda berdasarkan hari, waktu, dan lokasi kegiatan.">
    @include('home-styles')
    @include('jadwal-styles')
@endpush
@php
    $event = $expo ?? null;
    $eventName = $event?->nama_expo ?: 'Sumsel Wedding Expo';
    $start = $event?->tanggal_mulai?->copy()->locale('id');
    $end = $event?->tanggal_selesai?->copy()->locale('id');
    $today = now('Asia/Jakarta')->toDateString();
    $ended = $end && $end->toDateString() < $today;
    $live = $start && $end && $start->toDateString() <= $today && $end->toDateString() >= $today;
    $status = $ended ? 'Event telah selesai' : ($live ? 'Sedang berlangsung' : ($start ? 'Event mendatang' : 'Nantikan edisi berikutnya'));
    $rundowns = $event?->rundowns ?? collect();
    $days = collect();
    if ($start && $end && $start->lte($end)) {
        for ($day = $start->copy(); $day->lte($end); $day->addDay()) {
            $days->push($day->copy());
        }
    } else {
        $days = $rundowns->pluck('tanggal')->filter()->unique(fn ($date) => $date->toDateString())->sort()->values();
    }
    $dateLabel = $start ? $start->translatedFormat('d F Y') . ($end && !$start->isSameDay($end) ? ' – ' . $end->translatedFormat('d F Y') : '') : 'Tanggal segera diumumkan';
    $initialDay = $days->search(fn ($day) => $day->toDateString() === $today);
    $initialDay = $initialDay === false ? 0 : $initialDay;
    $mapQuery = trim(implode(' ', array_filter([$event?->lokasi, $event?->alamat])));
@endphp
@section('content')
<div class="swe-home swe-schedule-page">
    <section class="swe-schedule-hero" aria-labelledby="schedule-page-heading">
        <div class="swe-wrap">
            <nav class="swe-breadcrumb" aria-label="Jejak navigasi"><a href="{{ route('home') }}">Beranda</a><span aria-hidden="true">/</span><span aria-current="page">Jadwal Acara</span></nav>
            <div class="swe-schedule-hero-grid">
                <div><span class="swe-eyebrow swe-light">SETIAP HARI, CERITA BERBEDA</span><h1 id="schedule-page-heading">Catat harinya.<br><em>Temukan momennya.</em></h1><p>Jelajahi rangkaian kegiatan {{ $eventName }}. Pilih hari kunjungan Anda dan temukan agenda yang ingin diikuti.</p></div>
                <div class="swe-edition"><span class="swe-status @if ($live) swe-status-live @endif"><span aria-hidden="true"></span>{{ $status }}</span><p>{{ $eventName }}</p><strong>{{ $dateLabel }}</strong>@if ($ended)<span class="swe-archive-note">Anda sedang melihat arsip jadwal edisi ini.</span>@endif</div>
            </div>
        </div>
    </section>

    <div class="swe-wrap swe-schedule-facts" aria-label="Ringkasan event">
        <div><span class="swe-eyebrow">01 · TANGGAL</span><strong>{{ $dateLabel }}</strong><span>{{ $days->count() ? $days->count() . ' hari rangkaian acara' : 'Informasi akan diperbarui' }}</span></div>
        <div><span class="swe-eyebrow">02 · WAKTU</span><strong>Ikuti waktu setiap agenda</strong><span>Rincian jam tercantum pada rundown</span></div>
        <div><span class="swe-eyebrow">03 · LOKASI</span><strong>{{ $event?->lokasi ?: 'Lokasi segera diumumkan' }}</strong>@if ($event?->alamat)<span>{{ $event->alamat }}</span>@endif</div>
    </div>

    <section class="swe-section" aria-labelledby="rundown-heading">
        <div class="swe-wrap swe-rundown-layout">
            <div class="swe-rundown-main">
                <div class="swe-section-top"><div><span class="swe-eyebrow">YOUR EXPO ITINERARY</span><h2 id="rundown-heading">Susun hari<br><em>istimewa Anda.</em></h2></div><p>{{ $ended ? 'Lihat kembali rangkaian kegiatan pada edisi ini.' : 'Pilih hari untuk melihat waktu, kegiatan, dan lokasi acara.' }}</p></div>
                @if ($days->isNotEmpty())
                    <div class="swe-day-tabs" aria-label="Pilih hari acara" data-schedule-tabs data-initial-day="{{ $initialDay }}">
                        @foreach ($days as $index => $day)
                            <a class="swe-day-tab" id="tab-{{ $day->format('Y-m-d') }}" href="#agenda-{{ $day->format('Y-m-d') }}" data-day-index="{{ $index }}" aria-controls="agenda-{{ $day->format('Y-m-d') }}"><span>HARI {{ $index + 1 }}</span><strong>{{ $day->copy()->locale('id')->translatedFormat('l') }}</strong><span>{{ $day->copy()->locale('id')->translatedFormat('d M Y') }}</span></a>
                        @endforeach
                    </div>
                    @foreach ($days as $index => $day)
                        @php
                            $daily = $rundowns->filter(fn ($item) => $item->tanggal?->isSameDay($day))->sortBy('waktu');
                        @endphp
                        <section class="swe-day-panel" id="agenda-{{ $day->format('Y-m-d') }}" aria-labelledby="day-heading-{{ $index }}" data-schedule-panel>
                            <div class="swe-day-heading"><div><span class="swe-eyebrow">HARI {{ $index + 1 }}</span><h3 id="day-heading-{{ $index }}">{{ $day->copy()->locale('id')->translatedFormat('l, d F Y') }}</h3></div><span>{{ $daily->count() }} agenda</span></div>
                            @if ($daily->isNotEmpty())
                                <ol class="swe-timeline">
                                    @foreach ($daily as $item)
                                        <li><div class="swe-timeline-time"><span class="swe-timeline-dot" aria-hidden="true"></span><strong>{{ $item->waktu ?: 'Waktu menyusul' }}</strong></div><div class="swe-timeline-content"><h4>{{ $item->acara }}</h4>@if ($item->deskripsi)<p>{{ $item->deskripsi }}</p>@endif @if ($item->lokasi)<span class="swe-stage"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 10c0 6-8 11-8 11S4 16 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg>{{ $item->lokasi }}</span>@endif</div></li>
                                    @endforeach
                                </ol>
                            @else
                                <div class="swe-empty"><h3>Agenda sedang disiapkan.</h3><p>Rincian kegiatan untuk hari ini belum tersedia. Ikuti informasi resmi Sumsel Wedding Expo untuk pembaruan jadwal.</p></div>
                            @endif
                        </section>
                    @endforeach
                @else
                    <div class="swe-empty"><span class="swe-eyebrow">SEGERA HADIR</span><h3>Nantikan jadwal expo berikutnya.</h3><p>Tanggal dan rangkaian kegiatan akan ditampilkan setelah diumumkan. Sementara itu, jelajahi dokumentasi dan kenali Sumsel Wedding Expo.</p><a class="swe-text-link" href="{{ route('gallery') }}">Lihat dokumentasi <span aria-hidden="true">↗</span></a></div>
                @endif
            </div>
            <aside class="swe-schedule-aside" aria-label="Panduan kunjungan">
                <div class="swe-visit-card"><span class="swe-eyebrow">SEBELUM BERKUNJUNG</span><h3>Sedikit persiapan,<br><em>banyak inspirasi.</em></h3><ul><li>Cek waktu dan lokasi setiap kegiatan sebelum datang.</li><li>Kenali vendor peserta dan lihat denah booth.</li><li>Jadwal dapat berubah. Pantau informasi resmi penyelenggara.</li></ul><a class="swe-btn swe-btn-burgundy" href="{{ route('peserta.index') }}">Peserta & denah booth <span aria-hidden="true">↗</span></a></div>
                @if ($mapQuery)
                    <div class="swe-direction-card"><span class="swe-eyebrow">TEMUI KAMI DI</span><h3>{{ $event?->lokasi }}</h3>@if ($event?->alamat)<p>{{ $event->alamat }}</p>@endif<a class="swe-text-link" href="https://www.google.com/maps/search/?api=1&query={{ rawurlencode($mapQuery) }}" target="_blank" rel="noopener noreferrer">Buka petunjuk arah <span aria-hidden="true">↗</span></a></div>
                @endif
                <a class="swe-text-link swe-schedule-social" href="https://www.instagram.com/sumselweddingexpo/" target="_blank" rel="noopener noreferrer">Kabar terbaru di Instagram <span aria-hidden="true">↗</span></a>
            </aside>
        </div>
    </section>
    <section class="swe-exhibitor"><div class="swe-wrap swe-exhibitor-inner"><div><span class="swe-eyebrow swe-light">{{ $ended ? 'THE MEMORIES LIVE ON' : 'MAKE THE MOST OF YOUR VISIT' }}</span><h2>{{ $ended ? 'Acara usai.' : 'Inspirasi ditemukan.' }}<br><em>{{ $ended ? 'Ceritanya tetap ada.' : 'Cerita dimulai.' }}</em></h2><p>{{ $ended ? 'Lihat dokumentasi dan momen berkesan dari perjalanan Sumsel Wedding Expo.' : 'Kenali vendor peserta dan temukan layanan yang sesuai dengan rencana pernikahan Anda.' }}</p></div><a class="swe-btn swe-btn-cream" href="{{ $ended ? route('gallery') : route('peserta.index') }}">{{ $ended ? 'Jelajahi Galeri' : 'Jelajahi Peserta' }} <span aria-hidden="true">↗</span></a></div></section>
</div>
@endsection
@push('scripts')
<script>
(() => {
    const list = document.querySelector('[data-schedule-tabs]');
    if (!list) return;
    const tabs = [...list.querySelectorAll('[data-day-index]')];
    const panels = [...document.querySelectorAll('[data-schedule-panel]')];
    if (!tabs.length || tabs.length !== panels.length) return;
    list.setAttribute('role', 'tablist');
    tabs.forEach((tab, index) => {
        tab.setAttribute('role', 'tab');
        panels[index].setAttribute('role', 'tabpanel');
        panels[index].setAttribute('aria-labelledby', tab.id);
        panels[index].tabIndex = 0;
        tab.addEventListener('click', event => { event.preventDefault(); activate(index); });
        tab.addEventListener('keydown', event => {
            let target;
            if (event.key === 'ArrowRight') target = (index + 1) % tabs.length;
            if (event.key === 'ArrowLeft') target = (index - 1 + tabs.length) % tabs.length;
            if (event.key === 'Home') target = 0;
            if (event.key === 'End') target = tabs.length - 1;
            if (target === undefined) return;
            event.preventDefault(); activate(target); tabs[target].focus();
        });
    });
    function activate(index) {
        tabs.forEach((tab, i) => {
            tab.setAttribute('aria-selected', String(i === index));
            tab.tabIndex = i === index ? 0 : -1;
            panels[i].hidden = i !== index;
        });
    }
    const linkedDay = panels.findIndex(panel => '#' + panel.id === window.location.hash);
    activate(linkedDay >= 0 ? linkedDay : Number(list.dataset.initialDay || 0));
})();
</script>
@endpush
