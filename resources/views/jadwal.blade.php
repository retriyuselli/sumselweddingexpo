@extends('layouts.app')

@section('title', 'Jadwal Acara — WeddingExpo')
@push('head')
    <meta name="description"
        content="Jadwal lengkap acara Sumsel Wedding Expo 2025, termasuk rundown, workshop, talkshow, dan fashion show.">
@endpush

@section('content')
    <main class="min-h-screen">

        <!-- Hero Jadwal -->
        <section class="pt-24 md:pt-28 pb-10 bg-linear-to-r from-rose-50 to-pink-50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-2 sm:mb-3">Jadwal Acara</h1>
                <p class="text-sm sm:text-base text-neutral-600 max-w-3xl">
                    Jangan lewatkan berbagai acara menarik di Sumsel Wedding Expo 2025. Rencanakan kunjungan Anda
                    dan ikuti workshop, talkshow, fashion show, serta berbagai kegiatan seru lainnya.
                </p>
            </div>
        </section>

        <!-- Event Info -->
        <section class="py-12 bg-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-3 gap-6 mb-12">
                    <div class="bg-linear-to-br from-rose-500 to-pink-600 rounded-lg p-6 text-white">
                        <div class="flex items-center gap-3 mb-3">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <h3 class="text-base sm:text-xl font-semibold">Tanggal</h3>
                        </div>
                        @if (isset($expo) && $expo)
                            <p class="text-xl sm:text-2xl font-bold">
                                {{ $expo->tanggal_mulai->locale('id')->translatedFormat('d') }} -
                                {{ $expo->tanggal_selesai->locale('id')->translatedFormat('d F Y') }}</p>
                            <p class="text-xs sm:text-sm opacity-90 mt-1">
                                {{ $expo->tanggal_mulai->locale('id')->translatedFormat('l') }} -
                                {{ $expo->tanggal_selesai->locale('id')->translatedFormat('l') }}</p>
                        @else
                            <p class="text-xl sm:text-2xl font-bold">16 - 18 Januari 2026</p>
                            <p class="text-xs sm:text-sm opacity-90 mt-1">Jumat - Minggu</p>
                        @endif
                    </div>

                    <div class="bg-linear-to-br from-purple-500 to-indigo-600 rounded-lg p-6 text-white">
                        <div class="flex items-center gap-3 mb-3">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="text-base sm:text-xl font-semibold">Waktu</h3>
                        </div>
                        <p class="text-xl sm:text-2xl font-bold">10:00 - 21:00 WIB</p>
                        <p class="text-xs sm:text-sm opacity-90 mt-1">Setiap hari</p>
                    </div>

                    <div class="bg-linear-to-br from-amber-500 to-orange-600 rounded-lg p-6 text-white">
                        <div class="flex items-center gap-3 mb-3">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <h3 class="text-base sm:text-xl font-semibold">Lokasi</h3>
                        </div>
                        <p class="text-xl sm:text-2xl font-bold">{{ $expo->lokasi ?? 'Palembang Icon' }}</p>
                        <p class="text-xs sm:text-sm opacity-90 mt-1">{{ $expo->alamat ?? 'PI, Palembang' }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Jadwal Per Hari -->
        <section class="py-12 bg-gray-50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-center mb-8 sm:mb-10">Rundown Acara</h2>

                @php
                    $days = [];
                    if (isset($expo)) {
                        $current = $expo->tanggal_mulai->copy();
                        while ($current->lte($expo->tanggal_selesai)) {
                            $days[] = $current->copy();
                            $current->addDay();
                        }
                    }
                @endphp

                @if (count($days) > 0)
                    <!-- Tabs -->
                    <div class="flex justify-center gap-2 sm:gap-4 mb-8 flex-wrap">
                        @foreach ($days as $index => $day)
                            <button onclick="showDay('day{{ $index + 1 }}')" id="btn-day{{ $index + 1 }}"
                                class="day-tab px-4 py-2 sm:px-6 sm:py-3 rounded-lg font-semibold {{ $index === 0 ? 'bg-rose-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} text-sm sm:text-base">
                                Hari {{ $index + 1 }} - {{ $day->translatedFormat('l, d M') }}
                            </button>
                        @endforeach
                    </div>

                    <!-- Day Content -->
                    @foreach ($days as $index => $day)
                        <div id="day{{ $index + 1 }}" class="day-content {{ $index === 0 ? '' : 'hidden' }}">
                            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 md:p-8">
                                <h3 class="text-lg sm:text-xl md:text-2xl font-bold mb-4 sm:mb-6 text-rose-600">
                                    {{ $day->translatedFormat('l, d F Y') }}</h3>

                                <div class="space-y-4 sm:space-y-6">
                                    @php
                                        $dailyRundowns = $expo->rundowns
                                            ->filter(function ($rundown) use ($day) {
                                                return $rundown->tanggal->isSameDay($day);
                                            })
                                            ->sortBy('waktu');
                                    @endphp

                                    @if ($dailyRundowns->count() > 0)
                                        @foreach ($dailyRundowns as $rundown)
                                            <div class="flex gap-3 sm:gap-4 md:gap-6">
                                                <div class="shrink-0 w-16 sm:w-24 md:w-32">
                                                    <span
                                                        class="inline-block px-2 py-1 sm:px-3 bg-rose-100 text-rose-700 rounded-lg font-semibold text-xs sm:text-sm">
                                                        {{ $rundown->waktu }}
                                                    </span>
                                                </div>
                                                <div class="flex-1">
                                                    <h4 class="font-bold text-sm sm:text-base md:text-lg mb-1 sm:mb-2">
                                                        {{ $rundown->acara }}</h4>
                                                    @if ($rundown->deskripsi)
                                                        <p class="text-gray-600 text-xs sm:text-sm mb-1 sm:mb-2">
                                                            {{ $rundown->deskripsi }}</p>
                                                    @endif
                                                    @if ($rundown->lokasi)
                                                        <span
                                                            class="inline-block px-2 py-1 sm:px-3 bg-purple-100 text-purple-700 rounded text-xs font-medium">{{ $rundown->lokasi }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-center text-gray-500">Coming Soon.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500">Jadwal acara belum tersedia.</p>
                    </div>
                @endif
            </div>
        </section>

        <!-- Call to Action -->
        <section class="py-16 bg-linear-to-r from-rose-500 to-pink-600 text-white">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-2xl sm:text-3xl font-bold mb-4">Jangan Lewatkan Acara Seru Ini!</h2>
                <p class="text-lg mb-8 opacity-90">
                    Daftar sekarang dan dapatkan tiket gratis untuk mengikuti semua workshop dan talkshow.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/#tiket"
                        class="inline-flex items-center justify-center px-8 py-3 bg-white text-rose-600 font-semibold rounded-lg hover:bg-gray-100 transition-colors duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                            </path>
                        </svg>
                        Dapatkan Tiket Gratis
                    </a>
                    <a href="/partners"
                        class="inline-flex items-center justify-center px-8 py-3 bg-transparent border-2 border-white text-white font-semibold rounded-lg hover:bg-white hover:text-rose-600 transition-colors duration-300">
                        Lihat Daftar Vendor
                    </a>
                </div>
            </div>
        </section>

        <!-- Important Notes -->
        <section class="py-12 bg-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold mb-6 text-center">Informasi Penting</h2>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="border border-gray-200 rounded-lg p-6">
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-blue-600 shrink-0 mt-1" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h3 class="font-semibold mb-2">Kapasitas Workshop Terbatas</h3>
                                <p class="text-gray-600 text-sm">Beberapa workshop memiliki kuota peserta terbatas. Daftar
                                    lebih awal untuk mendapatkan tempat!</p>
                            </div>
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-6">
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-green-600 shrink-0 mt-1" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h3 class="font-semibold mb-2">Tiket Gratis</h3>
                                <p class="text-gray-600 text-sm">Masuk pameran dan mengikuti semua talkshow GRATIS!
                                    Workshop tertentu mungkin memerlukan biaya tambahan.</p>
                            </div>
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-6">
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-amber-600 shrink-0 mt-1" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h3 class="font-semibold mb-2">Jadwal Dapat Berubah</h3>
                                <p class="text-gray-600 text-sm">Jadwal dapat berubah sewaktu-waktu. Follow Instagram kami
                                    untuk update terkini!</p>
                            </div>
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-6">
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-purple-600 shrink-0 mt-1" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                            <div>
                                <h3 class="font-semibold mb-2">Promo Eksklusif</h3>
                                <p class="text-gray-600 text-sm">Dapatkan diskon hingga 50% dari berbagai vendor hanya
                                    selama acara berlangsung!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <script>
        function showDay(dayId) {
            // Hide all day contents
            document.querySelectorAll('.day-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Reset all buttons
            document.querySelectorAll('.day-tab').forEach(btn => {
                btn.classList.remove('bg-rose-600', 'text-white');
                btn.classList.add('bg-gray-200', 'text-gray-700');
            });

            // Show selected day
            document.getElementById(dayId).classList.remove('hidden');

            // Activate selected button
            const btnId = 'btn-' + dayId;
            const activeBtn = document.getElementById(btnId);
            activeBtn.classList.remove('bg-gray-200', 'text-gray-700');
            activeBtn.classList.add('bg-rose-600', 'text-white');
        }
    </script>
@endsection
