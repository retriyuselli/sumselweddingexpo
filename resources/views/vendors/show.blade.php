@extends('layouts.app')
@section('title', $vendor->nama_vendor . ' — Sumsel Wedding Expo')
@push('head')
    <meta name="description" content="Kenali {{ $vendor->nama_vendor }}, lihat produk dan paket pernikahan, serta hubungi vendor melalui Sumsel Wedding Expo.">
    @include('home-styles')
    @include('jadwal-styles')
    @include('vendors/detail-styles')
@endpush
@php
    $isOwner = auth()->check() && (int) auth()->id() === (int) $vendor->user_id;
    $event = $expo ?? null;
    $participation = $partisipasi ?? null;
    $ended = $event?->tanggal_selesai && $event->tanggal_selesai->toDateString() < now('Asia/Jakarta')->toDateString();
    $booth = $participation?->tenantSpot?->kode_booth ?? $participation?->blok_tenant;
    $tier = $participation?->categoryTenant?->category;
    $tierLabel = $tier instanceof \BackedEnum ? (method_exists($tier, 'label') ? $tier->label() : $tier->value) : $tier;
    $phone = preg_replace('/\D/', '', (string) $vendor->no_wa_pic);
    if (str_starts_with($phone, '0')) { $phone = '62' . substr($phone, 1); }
    elseif (str_starts_with($phone, '8')) { $phone = '62' . $phone; }
    $waUrl = strlen($phone) >= 8 && strlen($phone) <= 15 ? 'https://wa.me/' . $phone : null;
    $email = filter_var($vendor->email, FILTER_VALIDATE_EMAIL) ? $vendor->email : null;
    $publicImage = function ($path) {
        if (!$path) { return null; }
        if (preg_match('~^https?://~i', $path)) { return filter_var($path, FILTER_VALIDATE_URL) ? $path : null; }
        return \Illuminate\Support\Facades\Storage::disk('public')->exists($path) ? asset('storage/' . $path) : null;
    };
    $logo = $publicImage($vendor->logo);
@endphp
@section('content')
<div class="swe-home swe-vendor-detail">
    <section class="swe-schedule-hero" aria-labelledby="vendor-heading"><div class="swe-wrap"><nav class="swe-breadcrumb" aria-label="Jejak navigasi"><a href="{{ route('home') }}">Beranda</a><span aria-hidden="true">/</span><a href="{{ route('partners') }}">Vendor</a><span aria-hidden="true">/</span><span aria-current="page">Profil Vendor</span></nav><div class="swe-vendor-hero-grid"><div><span class="swe-eyebrow swe-light">{{ $vendor->jenisUsaha?->nama_jenis_usaha ?: 'VENDOR PERNIKAHAN' }} · {{ $vendor->kota ?: 'SUMSEL WEDDING EXPO' }}</span><h1 id="vendor-heading">{{ $vendor->nama_vendor }}</h1><p>Kenali pilihan produk dan paket dari {{ $vendor->nama_vendor }}. Diskusikan kebutuhan, detail layanan, dan rencana hari istimewa Anda bersama vendor.</p><div class="swe-actions"><a class="swe-btn swe-btn-cream" href="#produk">Lihat Produk & Paket <span aria-hidden="true">↘</span></a>@if ($waUrl)<a class="swe-btn swe-btn-glass" href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer">Hubungi via WhatsApp <span aria-hidden="true">↗</span></a>@endif</div></div><div class="swe-vendor-identity">@if ($logo)<img src="{{ $logo }}" alt="Logo {{ $vendor->nama_vendor }}">@else<span aria-hidden="true">{{ mb_strtoupper(mb_substr($vendor->nama_vendor, 0, 1)) }}</span>@endif<p>{{ $vendor->jenisUsaha?->nama_jenis_usaha ?: 'Vendor Pernikahan' }}</p>@if ($booth)<strong>Booth {{ $booth }}</strong>@endif</div></div></div></section>
    <nav class="swe-wrap swe-detail-nav" aria-label="Navigasi profil vendor"><a href="#produk">01 <span>Produk & paket</span> ↘</a><a href="#informasi">02 <span>Informasi vendor</span> ↘</a><a href="#kontak">03 <span>Kontak vendor</span> ↘</a></nav>
    <section class="swe-section"><div class="swe-wrap swe-detail-layout"><div class="swe-detail-main">
        <section id="produk" aria-labelledby="products-heading"><div class="swe-section-top"><div><span class="swe-eyebrow">EXPLORE THE POSSIBILITIES</span><h2 id="products-heading">Pilihan untuk<br><em>hari bahagia.</em></h2></div>@if ($isOwner)<button id="btn-open-add-product" type="button" class="swe-btn swe-btn-burgundy">Tambah Produk <span aria-hidden="true">+</span></button>@else<p>{{ $products->count() }} produk ditampilkan. Lihat rincian paket sebelum menentukan pilihan Anda.</p>@endif</div>
            <div class="swe-product-create">@include('vendors/product-create-form')</div>
            @if ($errors->any())<div class="swe-detail-error" role="alert"><h3>Periksa kembali data produk.</h3><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            <div class="swe-product-grid">
                @forelse ($products as $product)
                    @php
                        $img = $publicImage($product->foto_url);
                    @endphp
                    <article class="swe-product-card"><a class="swe-product-photo" href="{{ route('products.show', $product->slug) }}" aria-label="Lihat detail {{ $product->nama_produk }}">@if ($img)<img src="{{ $img }}" alt="{{ $product->nama_produk }}" loading="lazy">@else<span aria-hidden="true">{{ mb_strtoupper(mb_substr($product->nama_produk, 0, 1)) }}</span>@endif</a><div class="swe-product-body"><h3><a href="{{ route('products.show', $product->slug) }}">{{ $product->nama_produk }}</a></h3><p class="swe-product-price">Rp {{ number_format((float) $product->harga, 0, ',', '.') }}</p>@if ($product->dp_fixed > 0)<p class="swe-product-dp">DP Rp {{ number_format($product->dp_fixed, 0, ',', '.') }}</p>@endif<div class="swe-product-actions"><a class="swe-text-link" href="{{ route('products.show', $product->slug) }}">Detail <span aria-hidden="true">↗</span></a><button type="button" class="swe-cart-button add-to-cart" data-id="{{ $product->id }}" data-vendor="{{ $vendor->id }}" data-slug="{{ $product->slug }}" data-nama="{{ $product->nama_produk }}" data-harga="{{ (int) $product->harga }}" data-dp-fixed="{{ (int) $product->dp_fixed }}" data-img="{{ $img }}" aria-label="Tambah {{ $product->nama_produk }} ke keranjang">+ Keranjang</button>@if ($isOwner)<a class="swe-product-edit" href="{{ route('vendors.products.edit', [$vendor, $product]) }}">Edit</a>@endif</div></div></article>
                @empty
                    <div class="swe-empty"><h3>Pilihan produk segera tersedia.</h3><p>Hubungi vendor untuk menanyakan layanan dan paket yang sesuai dengan kebutuhan Anda.</p>@if ($waUrl)<a class="swe-text-link" href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer">Diskusikan kebutuhan Anda <span aria-hidden="true">↗</span></a>@endif</div>
                @endforelse
            </div>
        </section>
        <section class="swe-vendor-information" id="informasi" aria-labelledby="info-heading"><span class="swe-eyebrow">GET TO KNOW YOUR VENDOR</span><h2 id="info-heading">Sebelum memilih,<br><em>kenali lebih dekat.</em></h2><dl class="swe-vendor-facts">@foreach (['Nama vendor' => $vendor->nama_vendor, 'Kategori layanan' => $vendor->jenisUsaha?->nama_jenis_usaha ?: 'Belum dicantumkan', 'Kota' => $vendor->kota ?: 'Belum dicantumkan', 'Alamat vendor' => $vendor->alamat ?: 'Hubungi vendor untuk informasi alamat', 'Lokasi booth' => $booth ?: 'Belum ditentukan', 'Paket booth' => $tierLabel ?: 'Belum ditentukan'] as $label => $value)<div><dt>{{ $label }}</dt><dd>{{ $value }}</dd></div>@endforeach</dl><p class="swe-expo-note">@if ($participation && $event)Informasi booth dan paket mengikuti {{ $event->nama_expo }}{{ $event->periode ? ' · ' . $event->periode : '' }}. @if ($ended)Edisi ini telah selesai.@endif @else Vendor belum memiliki informasi partisipasi pada edisi expo terpilih.@endif</p></section>
        @if ($isOwner)
            <section class="swe-owner-appointments" aria-labelledby="appointments-heading"><span class="swe-eyebrow">AREA PEMILIK VENDOR</span><h2 id="appointments-heading">Janji temu<br><em>mendatang.</em></h2>@forelse ($upcomingAppointments as $appointment)<article><div><h3>{{ $appointment->customer?->name ?: 'Pelanggan' }}</h3><p>{{ $appointment->subject }}</p><span>{{ $appointment->starts_at?->copy()->locale('id')->translatedFormat('d F Y, H:i') }}</span></div><span class="swe-appointment-status">{{ ['requested'=>'Menunggu konfirmasi','confirmed'=>'Dikonfirmasi','rejected'=>'Ditolak'][$appointment->status] ?? $appointment->status }}</span></article>@empty<p>Belum ada janji temu yang dijadwalkan.</p>@endforelse @if ($upcomingAppointments?->hasPages())<div class="swe-owner-pagination">{{ $upcomingAppointments->links() }}</div>@endif<a class="swe-text-link" href="{{ route('dashboard') }}">Kelola melalui dashboard <span aria-hidden="true">↗</span></a></section>
        @endif
    </div><aside class="swe-detail-contact" id="kontak"><div class="swe-vendor-contact-card"><span class="swe-eyebrow">LET'S TALK ABOUT YOUR DAY</span><h3>Mulai dari<br><em>sebuah percakapan.</em></h3><p>Tanyakan detail paket, ketersediaan layanan, dan kebutuhan khusus langsung kepada vendor.</p><dl>@if ($vendor->nama_pic)<div><dt>Kontak PIC</dt><dd>{{ $vendor->nama_pic }}</dd></div>@endif @if ($waUrl)<div><dt>WhatsApp</dt><dd>{{ $vendor->no_wa_pic }}</dd></div>@endif @if ($email)<div><dt>Email</dt><dd><a href="mailto:{{ $email }}">{{ $email }}</a></dd></div>@endif</dl><div class="swe-vendor-contact-actions">@if ($waUrl)<a class="swe-btn swe-btn-burgundy" href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer">Hubungi WhatsApp <span aria-hidden="true">↗</span></a>@elseif ($email)<a class="swe-btn swe-btn-burgundy" href="mailto:{{ $email }}">Hubungi via Email <span aria-hidden="true">↗</span></a>@else<p>Informasi kontak belum tersedia.</p>@endif @if (!$isOwner && $participation && !$ended)<a class="swe-text-link" href="{{ route('appointments.create') }}">Buat Janji Temu <span aria-hidden="true">↗</span></a>@endif</div></div><div class="swe-vendor-guide"><span class="swe-eyebrow">SEBELUM MEMESAN</span><p>Pastikan detail layanan, tanggal pelaksanaan, ketentuan DP, dan pelunasan telah disepakati bersama vendor.</p><a class="swe-text-link" href="{{ route('partners') }}">Jelajahi vendor lainnya <span aria-hidden="true">↗</span></a></div></aside></div></section>
    <section class="swe-exhibitor"><div class="swe-wrap swe-exhibitor-inner"><div><span class="swe-eyebrow swe-light">CONTINUE YOUR WEDDING JOURNEY</span><h2>Satu langkah lagi,<br>menuju <em>hari istimewa.</em></h2><p>Jelajahi pilihan vendor lain untuk melengkapi kebutuhan pernikahan Anda.</p></div><a class="swe-btn swe-btn-cream" href="{{ route('partners') }}">Lihat Semua Vendor <span aria-hidden="true">↗</span></a></div></section>
</div>
@include('vendors/cart-feedback')
@endsection
@push('scripts')
@include('vendors/detail-scripts')
@endpush
