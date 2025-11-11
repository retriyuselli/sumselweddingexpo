@extends('layouts.app')

@section('title', 'Lokasi Pameran — WeddingExpo')
@push('head')
    <meta name="description" content="Informasi lokasi pameran WeddingExpo, peta, akses, dan fasilitas.">
@endpush

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')

    <!-- Hero: full-bleed image with overlay -->
    <section id="home" class="relative pt-24 md:pt-28">
        <div class="absolute inset-0 -z-10">
            <img src="https://images.unsplash.com/photo-1520813792240-56fc4a3765a7?q=80&w=1964&auto=format&fit=crop"
                alt="Hero" class="w-full h-full object-cover" />
            <div class="absolute inset-0 bg-linear-to-b from-black/60 via-black/40 to-black/20"></div>
        </div>
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="min-h-[70vh] sm:min-h-[80vh] grid md:grid-cols-2 gap-8 items-center">
                <div class="text-white">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 ring-1 ring-white/20">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span class="text-xs sm:text-sm">Pameran Pernikahan</span>
                    </div>
                    <h1
                        class="mt-4 text-xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight">
                        {{ $eventName ?? 'WeddingExpo 2026' }}</h1>
                    <p class="mt-4 max-w-2xl text-xs sm:text-sm text-rose-100">Vendor terbaik, promo eksklusif, talkshow
                        inspiratif, dan
                        aktivitas seru untuk mewujudkan hari istimewa Anda.</p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="#tiket"
                            class="px-4 py-2 sm:px-5 sm:py-3 rounded-full bg-rose-600 text-white hover:bg-rose-700 text-sm sm:text-base">Peserta</a>
                        <a href="#sponsor"
                            class="px-4 py-2 sm:px-5 sm:py-3 rounded-full border border-white/40 text-white hover:bg-white/10 text-sm sm:text-base">Lihat
                            Partner</a>
                    </div>
                    <div class="mt-8">
                        <div class="text-xs sm:text-sm uppercase tracking-wider text-rose-100">Hitung Mundur Acara</div>
                        <div id="countdown" class="mt-3 flex gap-3 sm:gap-4">
                            <div class="text-center">
                                <div class="text-xl sm:text-3xl md:text-4xl font-bold" id="cd-days">0</div>
                                <div class="text-xs text-rose-100">Hari</div>
                            </div>
                            <div class="text-center">
                                <div class="text-xl sm:text-3xl md:text-4xl font-bold" id="cd-hours">0</div>
                                <div class="text-xs text-rose-100">Jam</div>
                            </div>
                            <div class="text-center">
                                <div class="text-xl sm:text-3xl md:text-4xl font-bold" id="cd-mins">0</div>
                                <div class="text-xs text-rose-100">Menit</div>
                            </div>
                            <div class="text-center">
                                <div class="text-xl sm:text-3xl md:text-4xl font-bold" id="cd-secs">0</div>
                                <div class="text-xs text-rose-100">Detik</div>
                            </div>
                        </div>
                        @if ($eventStart && $eventEnd)
                            <div class="mt-3 text-xs sm:text-sm text-rose-100">Tanggal acara: <span
                                    class="font-semibold">{{ $eventStart->format('d') }}–{{ $eventEnd->format('d F Y') }}</span>
                            </div>
                        @else
                            <div class="mt-3 text-xs sm:text-sm text-rose-100">Tanggal acara: <span
                                    class="font-semibold">16–18 Januari 2026</span></div>
                        @endif
                    </div>
                </div>
                <div class="relative hidden md:block">
                    <div class="aspect-4/3 w-full overflow-hidden rounded-2xl shadow-lg ring-1 ring-white/20 bg-white/10">
                        <div class="h-full w-full bg-cover bg-center opacity-95"
                            style="background-image:url('https://images.unsplash.com/photo-1511285560929-80b456fea0bc?q=80&w=1600&auto=format&fit=crop');">
                        </div>
                    </div>
                    <div
                        class="absolute -bottom-4 -left-4 bg-white/10 backdrop-blur rounded-xl shadow-md ring-1 ring-white/20 px-4 py-3 text-white">
                        <div class="text-xs text-rose-100">Pengunjung tahun lalu</div>
                        <div class="text-base font-bold">12.500+</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tentang -->
    <section class="py-16 sm:py-20 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-1 gap-12 items-center">
                <div>
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-rose-100 text-rose-700 text-sm font-medium mb-6">
                        Tentang Kami
                    </div>
                    <h2 class="text-xl sm:text-3xl font-bold text-neutral-900 mb-6">Wedding Expo Terbesar di Sumatera
                        Selatan</h2>
                    <div class="space-y-4 text-neutral-700 prose prose-sm max-w-none">
                        {!! $home->tentang_kami ?? '' !!}
                    </div>
                    <div class="grid grid-cols-3 gap-6 mt-8">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-rose-600">80+</div>
                            <div class="text-sm text-neutral-600 mt-1">Vendor</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-rose-600">12K+</div>
                            <div class="text-sm text-neutral-600 mt-1">Pengunjung</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-rose-600">5th</div>
                            <div class="text-sm text-neutral-600 mt-1">Season</div>
                        </div>
                    </div>
                </div>
                {{-- <div class="relative">
                    <div class="aspect-square rounded-2xl overflow-hidden shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=1200&auto=format&fit=crop"
                            alt="Wedding Event" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute -bottom-6 -left-6 bg-white rounded-xl shadow-lg p-6 max-w-xs">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-rose-100 rounded-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-rose-600">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <div class="font-bold text-neutral-900">Trusted Event</div>
                                <div class="text-sm text-neutral-600">Since 2022</div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </section>

    <!-- Highlight - Mengapa Harus Datang -->
    <section class="py-16 sm:py-20 bg-linear-to-br from-rose-600 via-pink-600 to-pink-700 text-white relative overflow-hidden">
        <!-- Decorative Elements -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        </div>

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-12">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/20 backdrop-blur-lg border border-white/30 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                    </svg>
                    <span class="text-sm font-medium">Keuntungan</span>
                </div>
                <h2 class="text-2xl sm:text-4xl font-bold mb-4">Mengapa Harus Datang?</h2>
                <p class="text-lg text-white/90 max-w-2xl mx-auto">Manfaat yang akan Anda dapatkan saat mengunjungi WeddingExpo</p>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 text-center hover:bg-white/20 transition-all duration-300 hover:scale-105 border border-white/20">
                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-8 h-8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">80+ Vendor</h3>
                    <p class="text-white/90 text-sm">Berbagai pilihan vendor terlengkap dalam satu tempat</p>
                </div>
                <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 text-center hover:bg-white/20 transition-all duration-300 hover:scale-105 border border-white/20">
                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-8 h-8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Promo Eksklusif</h3>
                    <p class="text-white/90 text-sm">Dapatkan penawaran khusus hanya untuk pengunjung expo</p>
                </div>
                <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 text-center hover:bg-white/20 transition-all duration-300 hover:scale-105 border border-white/20">
                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-8 h-8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Konsultasi Gratis</h3>
                    <p class="text-white/90 text-sm">Tanya jawab langsung dengan para ahli pernikahan</p>
                </div>
                <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 text-center hover:bg-white/20 transition-all duration-300 hover:scale-105 border border-white/20">
                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-8 h-8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 109.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1114.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Doorprize Menarik</h3>
                    <p class="text-white/90 text-sm">Berkesempatan memenangkan hadiah senilai jutaan rupiah</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Video Highlight -->
    <section class="py-16 sm:py-20 bg-neutral-900 text-white">
        <style>
            /* Reduce YouTube play button size */
            iframe[src*="youtube.com"] {
                --yt-spec-brand-background-primary: rgba(0, 0, 0, 0.5);
            }
            .video-container iframe {
                filter: brightness(0.95);
            }
            .video-container .scale-90 {
                transform: scale(0.8);
            }
        </style>
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-xl sm:text-3xl font-bold">Video Highlight</h2>
                <p class="mt-3 text-neutral-300">Lihat kembali moment-moment terbaik dari acara sebelumnya</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6 video-container">
                @forelse($home->highlight_videos ?? [] as $video)
                    <div class="group">
                        <div class="relative aspect-video rounded-xl overflow-hidden bg-neutral-800 scale-100">
                            <iframe class="w-full h-full" 
                                src="https://www.youtube.com/embed/{{ $video['video_id'] }}?modestbranding=1"
                                title="{{ $video['title'] }}" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen>
                            </iframe>
                        </div>
                        <h3 class="mt-4 font-semibold text-sm sm:text-base">{{ $video['title'] }}</h3>
                        <p class="text-xs sm:text-sm text-neutral-400 mt-1"></p>
                    </div>
                @empty
                    <p class="text-neutral-400">Belum ada video highlight</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Sponsor & Exhibitor -->
    <section id="sponsor" class="py-16 sm:py-20 bg-neutral-50">
        <style>
            @keyframes scroll-left {
                0% {
                    transform: translateX(0);
                }
                100% {
                    transform: translateX(calc(-100% - 0px));
                }
            }

            .sponsors-scroll {
                display: flex;
                animation: scroll-left 30s linear infinite;
                width: fit-content;
            }

            .sponsors-scroll:hover {
                animation-play-state: paused;
            }

            .sponsor-item {
                flex: 0 0 calc(100% / 6);
                min-width: 180px;
            }

            @media (max-width: 768px) {
                .sponsor-item {
                    flex: 0 0 calc(100% / 3);
                    min-width: 120px;
                }
            }
        </style>

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-rose-100 text-rose-700 text-sm font-medium mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                    </svg>
                    <span class="font-medium">Partner Kami</span>
                </div>
                <h2 class="text-2xl sm:text-4xl font-bold text-neutral-900">Sponsor & Exhibitor</h2>
                <p class="mt-3 text-neutral-600 max-w-2xl mx-auto">Terima kasih kepada partner resmi dan exhibitor yang mendukung acara ini</p>
            </div>

            <div class="relative w-full overflow-hidden bg-white rounded-lg">
                <div class="sponsors-scroll">
                    @forelse($sponsors as $sponsor)
                        @if($sponsor->logo && Storage::disk('public')->exists($sponsor->logo))
                            <div class="sponsor-item group relative">
                                <div class="relative w-full h-32 transition-all duration-300 hover:scale-110 p-2 flex items-center justify-center overflow-hidden">
                                    <img src="{{ asset('storage/' . $sponsor->logo) }}" alt="{{ $sponsor->name }}" class="w-full h-full object-contain group-hover:brightness-110 transition-all">
                                </div>
                            </div>
                        @endif
                    @endforelse
                    <!-- Duplicate for seamless loop -->
                    @forelse($sponsors as $sponsor)
                        @if($sponsor->logo && Storage::disk('public')->exists($sponsor->logo))
                            <div class="sponsor-item group relative">
                                <div class="relative w-full h-32 transition-all duration-300 hover:scale-110 p-2 flex items-center justify-center overflow-hidden">
                                    <img src="{{ asset('storage/' . $sponsor->logo) }}" alt="{{ $sponsor->name }}" class="w-full h-full object-contain group-hover:brightness-110 transition-all">
                                </div>
                            </div>
                        @endif
                    @endforelse
                </div>
            </div>

            <div class="mt-12 text-center">
                <a href="/exhibitor" class="inline-flex items-center gap-2 px-8 py-4 rounded-full bg-rose-600 text-white hover:bg-rose-700 transition-all hover:scale-105 shadow-lg hover:shadow-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Daftar Sebagai Exhibitor
                </a>
            </div>
        </div>
    </section>

    <!-- Instagram Widget -->
    <section class="py-16 sm:py-20 relative overflow-hidden">
        <!-- Decorative Elements -->
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <div class="absolute top-10 left-10 w-96 h-96"></div>
            <div class="absolute bottom-10 right-10 w-xl h-144"></div>
        </div>

        <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-linear-to-r from-purple-100 via-pink-100 to-rose-100 mb-4">
                    <svg class="w-5 h-5 text-pink-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                    </svg>
                    <span class="text-sm font-medium bg-linear-to-r from-purple-600 to-pink-600 text-transparent bg-clip-text">Social Media</span>
                </div>
                <h2 class="text-2xl sm:text-4xl font-bold text-neutral-900 mb-4">Follow Us on Instagram</h2>
                <p class="text-neutral-600 max-w-2xl mx-auto">Ikuti kami untuk mendapatkan update terbaru, behind the scenes, dan inspirasi pernikahan</p>

                <!-- Instagram Handle Button -->
                <a href="https://www.instagram.com/sumselweddingexpo" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 mt-6 px-6 py-3 rounded-full bg-linear-to-r from-purple-600 via-pink-600 to-rose-600 text-white hover:shadow-lg hover:scale-105 transition-all duration-300 font-medium">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                    </svg>
                    @sumselweddingexpo
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
            </div>

            <!-- Instagram Feed -->
            <div class="flex justify-center">
                <blockquote class="instagram-media"
                    data-instgrm-permalink="https://www.instagram.com/sumselweddingexpo/"
                    data-instgrm-version="14"
                    style="background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; max-width:100%; min-width:326px; padding:0; width:99.375%; width:-webkit-calc(100% - 2px); width:calc(100% - 2px);">
                </blockquote>
            </div>

            <script async src="//www.instagram.com/embed.js"></script>

            <style>
                /* Custom CSS untuk Instagram embed agar menampilkan 4 kolom */
                .instagram-media {
                    max-width: 100% !important;
                }

                /* Styling untuk grid Instagram */
                iframe.instagram-media-rendered {
                    max-width: 100% !important;
                }
            </style>
        </div>
    </section>

    <!-- Gallery -->
    {{-- <section class="py-16 sm:py-20 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-xl sm:text-3xl font-bold text-neutral-900">Gallery</h2>
                <p class="mt-3 text-neutral-600">Dokumentasi acara WeddingExpo sebelumnya</p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <div class="group cursor-pointer overflow-hidden rounded-xl aspect-square">
                    <img src="https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=600&auto=format&fit=crop"
                        alt="Gallery 1" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                </div>
                <div class="group cursor-pointer overflow-hidden rounded-xl aspect-square">
                    <img src="https://images.unsplash.com/photo-1511285560929-80b456fea0bc?q=80&w=600&auto=format&fit=crop"
                        alt="Gallery 2" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                </div>
                <div class="group cursor-pointer overflow-hidden rounded-xl aspect-square">
                    <img src="https://images.unsplash.com/photo-1465495976277-4387d4b0b4c6?q=80&w=600&auto=format&fit=crop"
                        alt="Gallery 3" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                </div>
                <div class="group cursor-pointer overflow-hidden rounded-xl aspect-square">
                    <img src="https://images.unsplash.com/photo-1460364157752-926555421a7e?q=80&w=600&auto=format&fit=crop"
                        alt="Gallery 4" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                </div>
                <div class="group cursor-pointer overflow-hidden rounded-xl aspect-square">
                    <img src="https://images.unsplash.com/photo-1532712938310-34cb3982ef74?q=80&w=600&auto=format&fit=crop"
                        alt="Gallery 5" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                </div>
                <div class="group cursor-pointer overflow-hidden rounded-xl aspect-square">
                    <img src="https://images.unsplash.com/photo-1522413452208-996ff3f3e740?q=80&w=600&auto=format&fit=crop"
                        alt="Gallery 6" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                </div>
                <div class="group cursor-pointer overflow-hidden rounded-xl aspect-square">
                    <img src="https://images.unsplash.com/photo-1519225421980-715cb0215aed?q=80&w=600&auto=format&fit=crop"
                        alt="Gallery 7" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                </div>
                <div class="group cursor-pointer overflow-hidden rounded-xl aspect-square">
                    <img src="https://images.unsplash.com/photo-1520813792240-56fc4a3765a7?q=80&w=600&auto=format&fit=crop"
                        alt="Gallery 8" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                </div>
                <div class="group cursor-pointer overflow-hidden rounded-xl aspect-square">
                    <img src="https://images.unsplash.com/photo-1606800052052-a08af7148866?q=80&w=600&auto=format&fit=crop"
                        alt="Gallery 9" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                </div>
                <div class="group cursor-pointer overflow-hidden rounded-xl aspect-square">
                    <img src="https://images.unsplash.com/photo-1583939003579-730e3918a45a?q=80&w=600&auto=format&fit=crop"
                        alt="Gallery 10" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                </div>
                <div class="group cursor-pointer overflow-hidden rounded-xl aspect-square">
                    <img src="https://images.unsplash.com/photo-1591604466107-ec97de577aff?q=80&w=600&auto=format&fit=crop"
                        alt="Gallery 11" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                </div>
                <div class="group cursor-pointer overflow-hidden rounded-xl aspect-square">
                    <img src="https://images.unsplash.com/photo-1505236858219-8359eb29e329?q=80&w=600&auto=format&fit=crop"
                        alt="Gallery 12" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                </div>
            </div>
            <div class="mt-12 text-center">
                <a href="/gallery"
                    class="inline-flex items-center gap-2 px-6 py-3 rounded-full border-2 border-rose-600 text-rose-600 hover:bg-rose-600 hover:text-white transition">
                    Lihat Semua Gallery
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>
    </section> --}}

    <!-- Jadwal & Lokasi -->
    <section id="jadwal" class="py-16 sm:py-20 bg-neutral-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-xl sm:text-3xl font-bold text-neutral-900">Jadwal & Lokasi</h2>
                <p class="mt-3 text-neutral-600">Catat tanggalnya dan jangan sampai terlewat!</p>
            </div>
            <div class="grid lg:grid-cols-2 gap-8">
                <!-- Jadwal -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-rose-100 rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-rose-600">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-neutral-900">Tanggal & Waktu</h3>
                    </div>
                    <div class="space-y-4">
                        @if ($eventStart && $eventEnd)
                            @php
                                $duration = $eventStart->diffInDays($eventEnd) + 1;
                                $days = ['Jumat', 'Sabtu', 'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis'];
                            @endphp
                            @for ($i = 0; $i < $duration; $i++)
                                @php
                                    $currentDate = $eventStart->copy()->addDays($i);
                                    $dayName = $days[$currentDate->dayOfWeek];
                                    $dayLabel =
                                        $i === 0
                                            ? 'Hari Pertama'
                                            : ($i === $duration - 1
                                                ? 'Hari Terakhir'
                                                : 'Hari Ke-' . ($i + 1));
                                    $activity =
                                        $i === 0
                                            ? 'Grand Opening & Talkshow'
                                            : ($i === $duration - 1
                                                ? 'Grand Doorprize'
                                                : 'Fashion Show & Live Music');
                                @endphp
                                <div class="flex items-start gap-4 p-4 bg-rose-50 rounded-xl">
                                    <div class="text-center min-w-[60px]">
                                        <div class="text-lg font-bold text-rose-600">{{ $currentDate->format('d') }}</div>
                                        <div class="text-sm text-neutral-600">{{ strtoupper($currentDate->format('M')) }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-neutral-900">{{ $dayName }} -
                                            {{ $dayLabel }}</div>
                                        <div class="text-sm text-neutral-600 mt-1">10:00 - 21:00 WIB</div>
                                        <div class="text-xs text-neutral-500 mt-1">{{ $activity }}</div>
                                    </div>
                                </div>
                            @endfor
                        @else
                            <div class="flex items-start gap-4 p-4 bg-rose-50 rounded-xl">
                                <div class="text-center min-w-[60px]">
                                    <div class="text-lg font-bold text-rose-600">16</div>
                                    <div class="text-sm text-neutral-600">JAN</div>
                                </div>
                                <div>
                                    <div class="font-semibold text-neutral-900">Jumat - Hari Pertama</div>
                                    <div class="text-sm text-neutral-600 mt-1">10:00 - 21:00 WIB</div>
                                    <div class="text-xs text-neutral-500 mt-1">Grand Opening & Talkshow</div>
                                </div>
                            </div>
                            <div class="flex items-start gap-4 p-4 bg-rose-50 rounded-xl">
                                <div class="text-center min-w-[60px]">
                                    <div class="text-lg font-bold text-rose-600">17</div>
                                    <div class="text-sm text-neutral-600">JAN</div>
                                </div>
                                <div>
                                    <div class="font-semibold text-neutral-900">Sabtu - Hari Kedua</div>
                                    <div class="text-sm text-neutral-600 mt-1">10:00 - 21:00 WIB</div>
                                    <div class="text-xs text-neutral-500 mt-1">Fashion Show & Live Music</div>
                                </div>
                            </div>
                            <div class="flex items-start gap-4 p-4 bg-rose-50 rounded-xl">
                                <div class="text-center min-w-[60px]">
                                    <div class="text-lg font-bold text-rose-600">18</div>
                                    <div class="text-sm text-neutral-600">JAN</div>
                                </div>
                                <div>
                                    <div class="font-semibold text-neutral-900">Minggu - Hari Terakhir</div>
                                    <div class="text-sm text-neutral-600 mt-1">10:00 - 21:00 WIB</div>
                                    <div class="text-xs text-neutral-500 mt-1">Grand Doorprize</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Lokasi -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-rose-100 rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-rose-600">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-neutral-900">Lokasi Acara</h3>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <div class="text-sm font-bold text-rose-600 mb-2">
                                {{ $eventLocation ?? 'Palembang Icon Mall' }}</div>
                            <div class="text-sm text-neutral-600">
                        </div>
                        <div class="aspect-video rounded-xl overflow-hidden bg-neutral-200">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3984.1234567890123!2d104.75!3d-2.98!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMsKwNTgnNDguMCJTIDEwNMKwNDUnMDAuMCJF!5e0!3m2!1sid!2sid!4v1234567890123!5m2!1sid!2sid"
                                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-4 bg-neutral-50 rounded-xl">
                                <div class="text-sm text-neutral-600">Parkir</div>
                                <div class="font-bold text-neutral-900">Tersedia</div>
                            </div>
                            <div class="text-center p-4 bg-neutral-50 rounded-xl">
                                <div class="text-sm text-neutral-600">Akses</div>
                                <div class="font-bold text-neutral-900">Mudah</div>
                            </div>
                        </div>
                        <a href="https://maps.google.com" target="_blank"
                            class="block w-full text-center px-6 py-3 rounded-full bg-rose-600 text-white hover:bg-rose-700 transition">
                            Buka di Google Maps
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Tiket -->
    <section id="tiket" class="py-20 sm:py-24 bg-linear-to-br from-neutral-900 via-neutral-800 to-neutral-900 text-white relative overflow-hidden">
        <!-- Decorative Elements -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-0 right-0 w-96 h-96 bg-rose-500 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-pink-500 rounded-full blur-3xl"></div>
        </div>

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-lg border border-white/20 mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-rose-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" />
                </svg>
                <span class="text-sm font-medium">Daftar Sekarang</span>
            </div>
            <h2 class="text-2xl sm:text-4xl font-bold mb-4">Siap Merencanakan Pernikahan Impian?</h2>
            <p class="mt-3 text-lg text-neutral-300 max-w-2xl mx-auto">Lihat daftar peserta dan nikmati pengalaman WeddingExpo 2026.</p>
            <div class="mt-8 flex justify-center">
                <a href="#" class="inline-flex items-center gap-2 px-8 py-4 rounded-full bg-rose-600 text-white hover:bg-rose-700 transition-all hover:scale-105 shadow-2xl hover:shadow-rose-500/50 font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                    Lihat Daftar Peserta
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Countdown Script -->
    <script>
        const target = new Date(@json($eventDate));
        const elDays = document.getElementById('cd-days');
        const elHours = document.getElementById('cd-hours');
        const elMins = document.getElementById('cd-mins');
        const elSecs = document.getElementById('cd-secs');

        function updateCountdown() {
            const now = new Date();
            const diff = target - now;
            if (diff <= 0) {
                elDays.textContent = '0';
                elHours.textContent = '0';
                elMins.textContent = '0';
                elSecs.textContent = '0';
                return;
            }
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
            const mins = Math.floor((diff / (1000 * 60)) % 60);
            const secs = Math.floor((diff / 1000) % 60);
            elDays.textContent = days;
            elHours.textContent = hours;
            elMins.textContent = mins;
            elSecs.textContent = secs;
        }
        updateCountdown();
        setInterval(updateCountdown, 1000);
    </script>

@endsection
