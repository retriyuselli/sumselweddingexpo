@extends('layouts.app')

@section('title', 'Penyelenggara — WeddingExpo')
@push('head')
    <meta name="description" content="Informasi penyelenggara WeddingExpo: profil, kontak, dan media sosial.">
@endpush

@section('content')
@php
    $org = optional($penyelenggara);
@endphp

    <!-- Hero Penyelenggara -->
    <section class="pt-24 md:pt-28 pb-10 bg-rose-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold">Penyelenggara</h1>
            <p class="mt-2 sm:mt-3 text-xs sm:text-sm text-neutral-600">Kenali tim di balik Sumsel Wedding Expo dan hubungi
                kami kapan saja.</p>
        </div>
    </section>

    <!-- Profil & Kontak -->
    <section class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 grid lg:grid-cols-3 gap-8 items-start">
            <!-- Profil -->
            <div class="lg:col-span-2 p-6 rounded-xl border border-neutral-200 bg-white">
                <h2 class="text-lg sm:text-xl font-bold">Tentang Penyelenggara</h2>

                <div class="mt-3 sm:mt-4 space-y-3 text-xs sm:text-sm text-neutral-700">
                    <p>{{ $org->tentang ?? 'PT. Makna Kreatif Indonesia adalah penyelenggara kegiatan Sumsel Wedding Expo yang diadakan di Palembang Icon Mall dan Palembang Indah Mall. Serta menyelenggarakan acara B2B, B2C dan B2G yang inovative dan terkemuka.' }}
                    </p>

                    <p>Kami mengutamakan diri dalam menyediakan konektivitas tanpa batas serta wawasan bisnis yang penting
                        dalam hubungan antara pembeli dan penjual dari berbagai sektor pasar khususnya yang berada di
                        Sumatra Selatan.</p>

                    <p>PT. MKI merupakan bisnis lokal dengan pengalaman lebih dari 8th dimana saat ini memiliki anak
                        perusahaan antara lain Makna Wedding & Event Planner, Makna Academy, Website Pernikahan, Makna Store
                        dan memiliki +/- 100+ tenaga freelancer serta 8 karyawan tetap.</p>
                </div>

                <div class="mt-4 sm:mt-6 grid sm:grid-cols-2 gap-4">
                    <div>
                        <div class="text-xs sm:text-sm text-neutral-500">Alamat Kantor</div>
                        <div class="mt-1 text-xs sm:text-sm text-neutral-800">
                            {{ $org->alamat ?? 'Jl. Sintraman Jaya I No. 2148, 20 Ilir D II, Kec. Kemuning, Kota Palembang, Sumatera Selatan.' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-xs sm:text-sm text-neutral-500">Jam Operasional</div>
                        <div class="mt-1 text-xs sm:text-sm text-neutral-800">
                            {{ $org->jam_operasional ?? 'Senin–Sabtu, 09.00–17.00' }}</div>
                    </div>
                </div>
            </div>

            <!-- Kontak -->
            <div class="p-6 rounded-xl border border-neutral-200 bg-white">
                <h3 class="text-base sm:text-lg font-semibold">Kontak</h3>
                @php
                    $email   = $org->email   ?? 'info@sumselweddingexpo.id';
                    $noTlp   = $org->no_tlp  ?? '+62 813-7318-3794';
                    $waNumber = preg_replace('/\D/', '', $noTlp);
                @endphp
                <div class="mt-2 sm:mt-3 space-y-2 text-sm text-neutral-700">
                    <div>Email: <a href="mailto:{{ $email }}"
                            class="text-rose-600 hover:text-rose-700">{{ $email }}</a>
                    </div>

                    <div>WhatsApp: <a href="https://wa.me/{{ $waNumber }}"
                            target="_blank"
                            class="text-rose-600 hover:text-rose-700">{{ $noTlp }}</a>
                    </div>
                </div>
                @php
                    $igUrl = $org->instagram ?? 'https://www.instagram.com/makna.wedding/';
                    $ttUrl = $org->tiktok    ?? 'https://www.tiktok.com/@makna.wedding';
                @endphp
                <div class="mt-4 sm:mt-6">
                    <h4 class="text-xs sm:text-sm font-semibold text-neutral-600">Media Sosial</h4>
                    <div class="mt-2 flex items-center gap-3">
                        <!-- Instagram -->
                        <a href="{{ $igUrl }}" target="_blank" rel="noopener noreferrer"
                            class="inline-flex items-center justify-center w-10 h-10 rounded-full border border-neutral-200 hover:bg-rose-50 hover:border-rose-300 transition"
                            aria-label="Instagram">
                            <svg class="w-5 h-5 text-neutral-700" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                            </svg>
                        </a>
                        <!-- TikTok -->
                        <a href="{{ $ttUrl }}" target="_blank" rel="noopener noreferrer"
                            class="inline-flex items-center justify-center w-10 h-10 rounded-full border border-neutral-200 hover:bg-rose-50 hover:border-rose-300 transition"
                            aria-label="TikTok">
                            <svg class="w-5 h-5 text-neutral-700" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z" />
                            </svg>
                        </a>
                        <!-- YouTube -->
                        <a href="https://www.youtube.com/@maknawedding" target="_blank" rel="noopener noreferrer"
                            class="inline-flex items-center justify-center w-10 h-10 rounded-full border border-neutral-200 hover:bg-rose-50 hover:border-rose-300 transition"
                            aria-label="YouTube">
                            <svg class="w-5 h-5 text-neutral-700" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <!-- Foto Slider -->
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-8">
            <h2 class="text-lg sm:text-xl font-bold mb-4">Galeri</h2>
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 bg-white">
                <!-- Slider Container -->
                <div class="slider-container relative">
                    <div class="slider-track flex transition-transform duration-500 ease-in-out" id="sliderTrack">
                        @php($featuredCount = isset($featuredGalleries) ? $featuredGalleries->count() : 0)
                        @if ($featuredCount > 0)
                            @foreach ($featuredGalleries as $gallery)
                                <div class="slider-item min-w-full">
                                    <img src="{{ $gallery->primary_image_url }}" alt="{{ $gallery->title }}" loading="lazy"
                                        decoding="async" class="w-full h-[300px] sm:h-[400px] md:h-[500px] object-cover">
                                </div>
                            @endforeach
                        @else
                            <div class="slider-item min-w-full">
                                <img src="{{ asset('images/placeholder-gallery.jpg') }}" alt="Placeholder Gallery"
                                    class="w-full h-[300px] sm:h-[400px] md:h-[500px] object-cover">
                            </div>
                        @endif
                    </div>

                    <!-- Navigation Buttons -->
                    <button id="prevBtn"
                        class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-neutral-800 rounded-full p-2 sm:p-3 shadow-lg transition">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                    </button>
                    <button id="nextBtn"
                        class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-neutral-800 rounded-full p-2 sm:p-3 shadow-lg transition">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>

                <!-- Dots Indicator -->
                <div class="flex justify-center gap-2 py-4 bg-white">
                    @php($dotCount = $featuredCount > 0 ? $featuredCount : 1)
                    @for ($i = 0; $i < $dotCount; $i++)
                        <button onclick="sliderGoTo({{ $i }})"
                            class="slider-dot w-2 h-2 sm:w-3 sm:h-3 rounded-full {{ $i === 0 ? 'bg-rose-600' : 'bg-neutral-300' }} transition"></button>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Meet The Team -->
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-8">
            <h2 class="text-lg sm:text-xl font-bold mb-4">Meet The Team</h2>
            <p class="text-xs sm:text-sm text-neutral-600 mb-6">Tim profesional kami yang berdedikasi untuk kesuksesan
                acara Anda.</p>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse ($teamMembers as $member)
                    <div class="bg-white rounded-xl border border-neutral-200 overflow-hidden hover:shadow-lg transition">
                        <div
                            class="aspect-square bg-linear-to-br from-rose-100 to-rose-200 flex items-center justify-center overflow-hidden">
                            @php($avatarUrl = $member->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($member->name) . '&color=7F9CF5&background=EBF4FF&size=600')
                            <img src="{{ $avatarUrl }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                        </div>
                        <div class="p-4">
                            <h3 class="text-sm sm:text-base font-semibold text-neutral-800">{{ $member->name }}</h3>
                            <p class="text-xs text-rose-600 mt-1">{{ $member->jabatan }}</p>
                            @php($ig = data_get($member->media_sosial, 'instagram'))
                            @php($tt = data_get($member->media_sosial, 'tiktok'))
                            <div class="mt-2 flex items-center gap-3">
                                @if ($ig)
                                    <a href="{{ $ig }}" target="_blank" rel="noopener noreferrer"
                                        class="group inline-flex items-center justify-center w-8 h-8 rounded-full border border-neutral-200 hover:bg-rose-50 hover:border-rose-300 transition"
                                        aria-label="Instagram">
                                        <svg class="w-5 h-5 text-[#E4405F] group-hover:text-[#C13584]" fill="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162z" />
                                        </svg>
                                    </a>
                                @endif
                                @if ($tt)
                                    <a href="{{ $tt }}" target="_blank" rel="noopener noreferrer"
                                        class="group inline-flex items-center justify-center w-8 h-8 rounded-full border border-neutral-200 hover:bg-rose-50 hover:border-rose-300 transition"
                                        aria-label="TikTok">
                                        <svg class="w-5 h-5 text-black group-hover:text-[#EE1D52]" fill="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z" />
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-xl border border-neutral-200 p-6 sm:col-span-2 lg:col-span-4">
                        <p class="text-sm text-neutral-600">Informasi tim sedang dalam persiapan.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </section>

@endsection

@push('scripts')
    <script>
        var sliderCurrentSlide = 0;
        var sliderTotalSlides =
            {{ isset($featuredGalleries) && $featuredGalleries->count() > 0 ? $featuredGalleries->count() : 1 }};

        function sliderUpdateView() {
            var track = document.getElementById('sliderTrack');
            if (!track) return;

            track.style.transform = 'translateX(-' + (sliderCurrentSlide * 100) + '%)';

            var dots = document.querySelectorAll('.slider-dot');
            for (var i = 0; i < dots.length; i++) {
                if (i === sliderCurrentSlide) {
                    dots[i].classList.remove('bg-neutral-300');
                    dots[i].classList.add('bg-rose-600');
                } else {
                    dots[i].classList.remove('bg-rose-600');
                    dots[i].classList.add('bg-neutral-300');
                }
            }
        }

        function sliderNext() {
            sliderCurrentSlide = (sliderCurrentSlide + 1) % sliderTotalSlides;
            sliderUpdateView();
        }

        function sliderPrev() {
            sliderCurrentSlide = (sliderCurrentSlide - 1 + sliderTotalSlides) % sliderTotalSlides;
            sliderUpdateView();
        }

        function sliderGoTo(index) {
            sliderCurrentSlide = index;
            sliderUpdateView();
        }

        document.addEventListener('DOMContentLoaded', function() {
            var nextBtn = document.getElementById('nextBtn');
            var prevBtn = document.getElementById('prevBtn');

            if (nextBtn && prevBtn) {
                nextBtn.onclick = sliderNext;
                prevBtn.onclick = sliderPrev;
            }

            setInterval(sliderNext, 5000);
        });
    </script>
@endpush
