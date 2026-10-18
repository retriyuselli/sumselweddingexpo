@extends('layouts.app')
@section('title', 'Vendor Pernikahan — Sumsel Wedding Expo')
@push('head')
    <meta name="description" content="Kenali vendor peserta Sumsel Wedding Expo. Cari berdasarkan nama, kota, atau kategori, lihat lokasi booth, dan hubungi vendor pilihan Anda.">
    @include('home-styles')
    @include('jadwal-styles')
    @include('partners-styles')
@endpush
@php
    $event = $expo ?? null;
    $allVendors = $vendors ?? collect();
    $categories = ($jenisUsahas ?? collect())->filter(fn ($category) => $category->vendors_count > 0);
    $today = now('Asia/Jakarta')->toDateString();
    $ended = $event?->tanggal_selesai && $event->tanggal_selesai->toDateString() < $today;
    $live = $event?->tanggal_mulai && $event?->tanggal_selesai && $event->tanggal_mulai->toDateString() <= $today && $event->tanggal_selesai->toDateString() >= $today;
    $status = $ended ? 'Peserta edisi sebelumnya' : ($live ? 'Sedang berlangsung' : ($event ? 'Peserta expo' : 'Nantikan edisi berikutnya'));
@endphp
@section('content')
<div class="swe-home swe-partners-page">
    <section class="swe-schedule-hero" aria-labelledby="partners-heading"><div class="swe-wrap">
        <nav class="swe-breadcrumb" aria-label="Jejak navigasi"><a href="{{ route('home') }}">Beranda</a><span aria-hidden="true">/</span><span aria-current="page">Vendor Pernikahan</span></nav>
        <div class="swe-schedule-hero-grid"><div><span class="swe-eyebrow swe-light">MEET YOUR WEDDING PARTNERS</span><h1 id="partners-heading">Pilihan yang tepat.<br><em>Untuk hari istimewa.</em></h1><p>Kenali vendor peserta expo, temukan layanan yang Anda butuhkan, dan mulai percakapan untuk mewujudkan rencana pernikahan.</p><div class="swe-actions"><a class="swe-btn swe-btn-cream" href="#vendor">Temukan Vendor <span aria-hidden="true">↘</span></a><a class="swe-btn swe-btn-glass" href="{{ route('peserta.index') }}">Lihat Denah Booth</a></div></div><div class="swe-edition"><span class="swe-status @if ($live) swe-status-live @endif"><span aria-hidden="true"></span>{{ $status }}</span><p>{{ $event?->nama_expo ?: 'Sumsel Wedding Expo' }}</p>@if ($event?->periode)<strong>{{ $event->periode }}</strong>@endif<span class="swe-partners-total">{{ $allVendors->count() }} vendor · {{ $categories->count() }} kategori</span>@if ($ended)<span class="swe-archive-note">Daftar vendor ini mengikuti keikutsertaan pada edisi yang telah selesai.</span>@endif</div></div>
    </div></section>

    <section class="swe-section" id="vendor" aria-labelledby="vendor-list-heading"><div class="swe-wrap">
        <div class="swe-section-top"><div><span class="swe-eyebrow">FIND YOUR PERFECT MATCH</span><h2 id="vendor-list-heading">Kenali vendornya.<br>Mulai <em>ceritanya.</em></h2></div><p>Cari nama atau kota, lalu pilih kategori untuk menemukan vendor sesuai kebutuhan Anda.</p></div>
        <div class="swe-partners-filters" role="search" aria-label="Cari vendor peserta"><div class="swe-partners-search"><label for="partners-search">Nama vendor atau kota</label><div><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10" cy="10" r="6"/><path d="m15 15 5 5"/></svg><input id="partners-search" type="search" placeholder="Cari vendor atau kota…" autocomplete="off" aria-controls="partners-grid"></div></div><div class="swe-partners-category"><label for="partners-filter">Kategori layanan</label><select id="partners-filter" aria-controls="partners-grid"><option value="">Semua kategori</option>@foreach ($categories as $category)<option value="{{ $category->id }}">{{ $category->nama_jenis_usaha }} ({{ $category->vendors_count }})</option>@endforeach</select></div><button type="button" class="swe-partners-reset" data-reset-filters>Reset filter <span aria-hidden="true">↺</span></button></div>
        <div class="swe-partners-results"><p role="status" aria-live="polite" data-result-count>Menampilkan {{ $allVendors->count() }} dari {{ $allVendors->count() }} vendor</p><a class="swe-text-link" href="{{ route('peserta.index') }}">Peserta & denah booth <span aria-hidden="true">↗</span></a></div>
        <div class="swe-partners-grid" id="partners-grid">
            @forelse ($allVendors as $vendor)
                @php
                    $participation = $vendor->partisipasis->first();
                    $booth = $participation?->tenantSpot?->kode_booth ?? $participation?->blok_tenant;
                    $tier = $participation?->categoryTenant?->category;
                    $tierLabel = $tier instanceof \BackedEnum ? (method_exists($tier, 'label') ? $tier->label() : $tier->value) : $tier;
                    $logo = $vendor->logo && \Illuminate\Support\Facades\Storage::disk('public')->exists($vendor->logo) ? asset('storage/' . $vendor->logo) : null;
                    $phone = preg_replace('/\D/', '', (string) $vendor->no_wa_pic);
                    if (str_starts_with($phone, '0')) { $phone = '62' . substr($phone, 1); }
                    elseif (str_starts_with($phone, '8')) { $phone = '62' . $phone; }
                    $waUrl = strlen($phone) >= 8 && strlen($phone) <= 15 ? 'https://wa.me/' . $phone : null;
                @endphp
                <article class="swe-partner-card" data-vendor-card data-name="{{ $vendor->nama_vendor }}" data-city="{{ $vendor->kota }}" data-category="{{ $vendor->jenis_usaha_id }}">
                    <a class="swe-partner-art" href="{{ route('vendors.show', $vendor->slug) }}" aria-label="Lihat profil {{ $vendor->nama_vendor }}">@if ($logo)<img src="{{ $logo }}" alt="Logo {{ $vendor->nama_vendor }}" loading="lazy">@else<span class="swe-partner-initial" aria-hidden="true">{{ mb_strtoupper(mb_substr($vendor->nama_vendor, 0, 1)) }}</span>@endif<span class="swe-partner-category">{{ $vendor->jenisUsaha?->nama_jenis_usaha ?: 'Vendor Pernikahan' }}</span>@if ($booth)<span class="swe-partner-booth">Booth {{ $booth }}</span>@endif</a>
                    <div class="swe-partner-body"><p class="swe-partner-city">{{ $vendor->kota ?: 'Kota belum dicantumkan' }}</p><h3><a href="{{ route('vendors.show', $vendor->slug) }}">{{ $vendor->nama_vendor }}</a></h3><dl class="swe-partner-meta"><div><dt>Produk aktif</dt><dd>{{ number_format($vendor->products_active_count ?? 0) }}</dd></div><div><dt>Paket booth</dt><dd>{{ $tierLabel ?: 'Belum ditentukan' }}</dd></div></dl><div class="swe-partner-actions"><a class="swe-text-link" href="{{ route('vendors.show', $vendor->slug) }}">Lihat Profil <span aria-hidden="true">↗</span></a>@if ($waUrl)<a class="swe-partner-whatsapp" href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp {{ $vendor->nama_vendor }}">WhatsApp <span aria-hidden="true">↗</span></a>@endif</div></div>
                </article>
            @empty
                <div class="swe-empty"><span class="swe-eyebrow">SEGERA HADIR</span><h3>{{ $event ? 'Daftar vendor sedang disiapkan.' : 'Nantikan peserta expo berikutnya.' }}</h3><p>Vendor akan ditampilkan setelah data keikutsertaan tersedia untuk edisi expo ini.</p><a class="swe-text-link" href="{{ route('exhibitor') }}">Ingin menjadi exhibitor? <span aria-hidden="true">↗</span></a></div>
            @endforelse
        </div>
        <div class="swe-empty swe-partners-no-results" data-no-results hidden><h3>Belum menemukan yang Anda cari?</h3><p>Coba nama atau kota lain, atau pilih semua kategori untuk melihat lebih banyak vendor.</p><button type="button" class="swe-btn swe-btn-burgundy" data-reset-filters>Tampilkan Semua Vendor <span aria-hidden="true">↗</span></button></div>
        <noscript><p class="swe-partners-noscript">Semua vendor ditampilkan. Aktifkan JavaScript untuk menggunakan pencarian dan filter kategori.</p></noscript>
    </div></section>
    <section class="swe-exhibitor"><div class="swe-wrap swe-exhibitor-inner"><div><span class="swe-eyebrow swe-light">GROW YOUR BRAND WITH US</span><h2>Giliran brand Anda<br>menjadi <em>bagian cerita.</em></h2><p>Kenali peluang partisipasi dan perkenalkan layanan pernikahan Anda di Sumsel Wedding Expo.</p></div><a class="swe-btn swe-btn-cream" href="{{ route('exhibitor') }}">Daftar Exhibitor <span aria-hidden="true">↗</span></a></div></section>
</div>
@endsection
@push('scripts')
<script>
(() => {
    const search = document.getElementById('partners-search');
    const category = document.getElementById('partners-filter');
    const cards = [...document.querySelectorAll('[data-vendor-card]')];
    const counter = document.querySelector('[data-result-count]');
    const empty = document.querySelector('[data-no-results]');
    if (!search || !category || !counter || !empty) return;
    const normalize = value => String(value || '').normalize('NFKC').toLocaleLowerCase('id-ID').trim();
    function filter() {
        const query = normalize(search.value);
        let visible = 0;
        cards.forEach(card => {
            const match = (!query || normalize(card.dataset.name).includes(query) || normalize(card.dataset.city).includes(query))
                && (!category.value || card.dataset.category === category.value);
            card.hidden = !match;
            if (match) visible++;
        });
        counter.textContent = `Menampilkan ${visible} dari ${cards.length} vendor`;
        empty.hidden = visible > 0 || cards.length === 0;
    }
    search.addEventListener('input', filter);
    category.addEventListener('change', filter);
    document.querySelectorAll('[data-reset-filters]').forEach(button => button.addEventListener('click', () => {
        search.value = ''; category.value = ''; filter(); search.focus();
    }));
    filter();
})();
</script>
@endpush
