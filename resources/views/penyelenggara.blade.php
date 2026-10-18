@extends('layouts.app')
@section('title', 'Penyelenggara — Sumsel Wedding Expo')
@push('head')
    <meta name="description" content="Kenali penyelenggara Sumsel Wedding Expo. Temukan profil, dokumentasi, tim, dan kontak untuk informasi acara serta kerja sama.">
    @include('home-styles')
    @include('jadwal-styles')
    @include('penyelenggara-styles')
@endpush
@php
    $org = $penyelenggara ?? null;
    $orgName = $org?->name ?: 'Penyelenggara Sumsel Wedding Expo';
    $email = filter_var($org?->email, FILTER_VALIDATE_EMAIL) ? $org->email : null;
    $phone = trim((string) $org?->no_tlp);
    $waNumber = preg_replace('/\D/', '', $phone);
    if (str_starts_with($waNumber, '0')) { $waNumber = '62' . substr($waNumber, 1); }
    elseif (str_starts_with($waNumber, '8')) { $waNumber = '62' . $waNumber; }
    $waUrl = strlen($waNumber) >= 8 && strlen($waNumber) <= 15 ? 'https://wa.me/' . $waNumber : null;
    $socialUrl = function ($value, $platform) {
        $value = trim((string) $value);
        if ($value === '') { return null; }
        if (preg_match('~^https?://~i', $value)) { return filter_var($value, FILTER_VALIDATE_URL) ? $value : null; }
        if (preg_match('~^(www\.)?' . preg_quote($platform, '~') . '\.com/~i', $value)) { return 'https://' . $value; }
        $handle = ltrim($value, '@');
        return preg_match('/^[A-Za-z0-9._]+$/', $handle) ? 'https://www.' . $platform . '.com/' . ($platform === 'tiktok' ? '@' : '') . $handle . '/' : null;
    };
    $socials = array_filter(['Instagram' => $socialUrl($org?->instagram, 'instagram'), 'TikTok' => $socialUrl($org?->tiktok, 'tiktok')]);
    $imageUrl = function ($path) {
        if (!$path) { return null; }
        if (preg_match('~^https?://~i', $path)) { return filter_var($path, FILTER_VALIDATE_URL) ? $path : null; }
        return \Illuminate\Support\Facades\Storage::disk('public')->exists($path) ? asset('storage/' . $path) : null;
    };
    $logo = $imageUrl($org?->logo);
    $galleries = $featuredGalleries ?? collect();
    $members = $teamMembers ?? collect();
    $profile = trim(html_entity_decode(strip_tags($org?->tentang ?? '')));
    $officeMap = filled($org?->alamat) ? 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($org->alamat) : null;
@endphp
@section('content')
<div class="swe-home swe-organizer-page">
    <section class="swe-schedule-hero" aria-labelledby="organizer-heading"><div class="swe-wrap">
        <nav class="swe-breadcrumb" aria-label="Jejak navigasi"><a href="{{ route('home') }}">Beranda</a><span aria-hidden="true">/</span><span aria-current="page">Penyelenggara</span></nav>
        <div class="swe-schedule-hero-grid"><div><span class="swe-eyebrow swe-light">THE PEOPLE BEHIND THE EXPERIENCE</span><h1 id="organizer-heading">Di balik momen.<br><em>Ada sebuah dedikasi.</em></h1><p>Kenali penyelenggara di balik Sumsel Wedding Expo dan temukan ruang untuk terhubung, berbagi inspirasi, serta berkolaborasi.</p><div class="swe-actions"><a class="swe-btn swe-btn-cream" href="#kontak">Hubungi Penyelenggara <span aria-hidden="true">↘</span></a><a class="swe-btn swe-btn-glass" href="#profil">Kenali Kami</a></div></div><div class="swe-organizer-signature"><span class="swe-eyebrow swe-light">ORGANIZED BY</span>@if ($logo)<div class="swe-org-logo"><img src="{{ $logo }}" alt="Logo {{ $orgName }}"></div>@endif<p>{{ $orgName }}</p><span>Sumsel Wedding Expo</span></div></div>
    </div></section>

    <nav class="swe-wrap swe-org-shortcuts" aria-label="Tentang penyelenggara">@foreach ([['01', 'Profil penyelenggara', '#profil'], ['02', 'Dokumentasi', '#dokumentasi'], ['03', 'Tim kami', '#tim'], ['04', 'Kontak & kolaborasi', '#kontak']] as [$number, $label, $url])<a href="{{ $url }}"><span>{{ $number }}</span>{{ $label }} <span aria-hidden="true">↘</span></a>@endforeach</nav>

    <section class="swe-section" id="profil" aria-labelledby="profile-heading"><div class="swe-wrap swe-org-profile"><div><span class="swe-eyebrow">GET TO KNOW US</span><h2 id="profile-heading">Mempertemukan ide.<br>Mewujudkan<br><em>pengalaman.</em></h2><p class="swe-org-name">{{ $orgName }}</p></div><div class="swe-org-story">@if ($profile)<p>{{ $profile }}</p>@else<p>Informasi profil penyelenggara sedang disiapkan. Hubungi kami untuk informasi mengenai Sumsel Wedding Expo dan peluang kerja sama.</p>@endif<div class="swe-org-principles">@foreach ([['01', 'Ruang pertemuan', 'Menghubungkan calon pengantin dengan vendor dan inspirasi pernikahan.'], ['02', 'Pengalaman bersama', 'Menghadirkan ruang untuk menemukan ide dan menjalin percakapan.'], ['03', 'Peluang kolaborasi', 'Membuka kesempatan untuk berpartisipasi dalam perjalanan Sumsel Wedding Expo.']] as [$number, $title, $description])<div><span>{{ $number }}</span><div><h3>{{ $title }}</h3><p>{{ $description }}</p></div></div>@endforeach</div></div></div></section>

    <section class="swe-section swe-org-documentation" id="dokumentasi" aria-labelledby="documentation-heading"><div class="swe-wrap"><div class="swe-section-top"><div><span class="swe-eyebrow">BEHIND THE MOMENTS</span><h2 id="documentation-heading">Dedikasi yang terlihat<br>dalam setiap <em>momen.</em></h2></div><a class="swe-text-link" href="{{ route('gallery') }}">Jelajahi galeri expo <span aria-hidden="true">↗</span></a></div><div class="swe-org-gallery">@forelse ($galleries as $gallery)
@php
    $photo = collect($gallery->image_path ?? [])->map($imageUrl)->filter()->first();
@endphp
<figure class="swe-org-photo">@if ($photo)<img src="{{ $photo }}" alt="{{ $gallery->title ?: 'Dokumentasi penyelenggara' }}" loading="lazy" decoding="async">@else<div class="swe-org-photo-empty"><span aria-hidden="true">SWE</span><p>Dokumentasi penyelenggara</p></div>@endif<figcaption><h3>{{ $gallery->title ?: 'Momen Sumsel Wedding Expo' }}</h3>@if ($gallery->description)<p>{{ $gallery->description }}</p>@endif @if ($gallery->photo_date)<span>{{ $gallery->photo_date->copy()->locale('id')->translatedFormat('d F Y') }}</span>@endif</figcaption></figure>@empty<div class="swe-empty"><h3>Cerita di balik layar, segera hadir.</h3><p>Dokumentasi penyelenggara akan ditampilkan di sini. Anda tetap dapat menjelajahi galeri expo untuk melihat momen acara.</p><a class="swe-text-link" href="{{ route('gallery') }}">Lihat galeri expo <span aria-hidden="true">↗</span></a></div>@endforelse</div></div></section>

    <section class="swe-section" id="tim" aria-labelledby="team-heading"><div class="swe-wrap"><div class="swe-section-top"><div><span class="swe-eyebrow">MEET THE PEOPLE</span><h2 id="team-heading">Orang-orang di balik<br>cerita <em>istimewa.</em></h2></div><p>Kenali tim di balik persiapan dan penyelenggaraan Sumsel Wedding Expo.</p></div><div class="swe-org-team">@forelse ($members as $member)
@php

        $avatar = $member->avatar;
        $avatar = is_string($avatar) && (str_starts_with($avatar, '/') || preg_match('~^https?://~i', $avatar)) ? $avatar : null;
        $memberSocials = array_filter(['Instagram' => $socialUrl(data_get($member->media_sosial, 'instagram'), 'instagram'), 'TikTok' => $socialUrl(data_get($member->media_sosial, 'tiktok'), 'tiktok')]);
    @endphp<article class="swe-team-member"><div class="swe-team-portrait">@if ($avatar)<img src="{{ $avatar }}" alt="{{ $member->name }}" loading="lazy">@else<span aria-hidden="true">{{ mb_substr($member->name, 0, 1) }}</span>@endif</div><div class="swe-team-info"><h3>{{ $member->name }}</h3>@if ($member->jabatan)<p>{{ $member->jabatan }}</p>@endif<div class="swe-team-socials">@foreach ($memberSocials as $label => $url)<a href="{{ $url }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $label }} {{ $member->name }}">{{ $label }} <span aria-hidden="true">↗</span></a>@endforeach</div></div></article>@empty<div class="swe-team-empty"><span class="swe-eyebrow">LET'S CONNECT</span><h3>Kenalan dimulai dari sebuah percakapan.</h3><p>Informasi anggota tim sedang disiapkan. Untuk kebutuhan acara atau kerja sama, hubungi penyelenggara melalui kontak yang tersedia.</p><a class="swe-text-link" href="#kontak">Hubungi kami <span aria-hidden="true">↘</span></a></div>@endforelse</div></div></section>

    <section class="swe-section swe-org-contact" id="kontak" aria-labelledby="contact-heading"><div class="swe-wrap swe-org-contact-grid"><div><span class="swe-eyebrow">LET'S CREATE SOMETHING MEANINGFUL</span><h2 id="contact-heading">Mulai percakapan.<br>Buka <em>peluang baru.</em></h2><p>Untuk informasi acara, partisipasi exhibitor, sponsorship, atau kolaborasi, hubungi penyelenggara melalui saluran berikut.</p><div class="swe-org-socials">@foreach ($socials as $label => $url)<a class="swe-text-link" href="{{ $url }}" target="_blank" rel="noopener noreferrer">{{ $label }} <span aria-hidden="true">↗</span></a>@endforeach</div></div><div class="swe-contact-details">@if ($email)<div><span class="swe-eyebrow">EMAIL</span><a class="swe-contact-value" href="mailto:{{ $email }}">{{ $email }} <span aria-hidden="true">↗</span></a></div>@endif @if ($waUrl)<div><span class="swe-eyebrow">WHATSAPP</span><a class="swe-contact-value" href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer">{{ $phone }} <span aria-hidden="true">↗</span></a></div>@endif @if ($org?->alamat)<div><span class="swe-eyebrow">ALAMAT KANTOR</span><p>{{ $org->alamat }}</p><a class="swe-text-link" href="{{ $officeMap }}" target="_blank" rel="noopener noreferrer">Petunjuk arah kantor <span aria-hidden="true">↗</span></a></div>@endif @if ($org?->jam_operasional)<div><span class="swe-eyebrow">JAM OPERASIONAL KANTOR</span><p>{{ $org->jam_operasional }}</p></div>@endif @if (!$email && !$waUrl)<div><p>Informasi kontak penyelenggara segera tersedia. Untuk informasi partisipasi, silakan kunjungi halaman exhibitor.</p><a class="swe-text-link" href="{{ route('exhibitor') }}">Informasi exhibitor <span aria-hidden="true">↗</span></a></div>@endif</div></div></section>

    <section class="swe-exhibitor"><div class="swe-wrap swe-exhibitor-inner"><div><span class="swe-eyebrow swe-light">BE PART OF THE EXPERIENCE</span><h2>Brand Anda.<br><em>Bagian dari cerita mereka.</em></h2><p>Kenali peluang menjadi exhibitor dan perkenalkan layanan Anda di Sumsel Wedding Expo.</p></div><a class="swe-btn swe-btn-cream" href="{{ route('exhibitor') }}">Daftar Exhibitor <span aria-hidden="true">↗</span></a></div></section>
</div>
@endsection
