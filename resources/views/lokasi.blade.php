@extends('layouts.app')

@section('title', 'Lokasi Pameran — WeddingExpo')
@push('head')
    <meta name="description" content="Informasi lokasi pameran WeddingExpo, peta, akses, dan fasilitas.">
@endpush

@section('content')

    <!-- Hero Lokasi -->
    <section class="pt-24 md:pt-28 pb-10 bg-rose-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold">Lokasi Pameran</h1>
            <p class="mt-2 sm:mt-3 text-xs sm:text-sm md:text-base text-neutral-600">Temukan venue pameran dan cara terbaik
                untuk menuju ke sana.</p>
        </div>
    </section>

    <!-- Detail Venue & Peta -->
    <section class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-8 items-start">
            <div class="p-4 sm:p-6 rounded-xl border border-neutral-200 bg-white">
                <h2 class="text-base sm:text-lg md:text-xl font-bold">Venue</h2>
                <p class="mt-2 sm:mt-3 text-xs sm:text-sm md:text-base text-neutral-700">Palembang Icon Mall, Palembang -
                    Sumatera Selatan </p>
                <p class="mt-1 text-neutral-500 text-xs sm:text-sm">Jalan POM IX, Lorok Pakjo, Kecamatan Ilir Barat I, Kota
                    Palembang, Sumatera Selatan 30137, Indonesia</p>
                <div class="mt-4 sm:mt-6 grid sm:grid-cols-2 gap-4">
                    <div>
                        <div class="text-xs sm:text-sm text-neutral-500">Jam Operasional</div>
                        <div class="mt-1 text-xs sm:text-sm text-neutral-800">10.00–21.00 (Jumat–Sabtu) <br>10.00–22.00
                            (Minggu)</div>
                    </div>
                    <div>
                        <div class="text-xs sm:text-sm text-neutral-500">Parkir</div>
                        <div class="mt-1 text-xs sm:text-sm text-neutral-800">Area parkir luas tersedia di venue</div>
                    </div>
                </div>
                <a href="#tiket"
                    class="mt-4 sm:mt-6 inline-flex items-center px-3 py-2 sm:px-4 rounded-full bg-rose-600 text-white hover:bg-rose-700 text-xs sm:text-sm">Rencanakan
                    Kunjungan</a>
            </div>

            <div class="rounded-xl overflow-hidden ring-1 ring-neutral-200 bg-white">
                <!-- Embed peta Google - Palembang Icon Mall -->
                <iframe class="w-full h-[360px] sm:h-[460px]"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6806.726978181259!2d104.74252707684133!3d-2.979279996996814!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e3b75e6d62d790f%3A0x4f51deef4db78c49!2sPalembang%20Icon%20Mall!5e1!3m2!1sid!2sid!4v1762711387398!5m2!1sid!2sid"
                    style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>

    <!-- Info Akses -->
    <section class="pb-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-base sm:text-lg md:text-xl font-bold mb-6">Akses Transportasi</h2>

            <!-- Akses dengan Angkutan Umum -->
            <div class="mb-8">
                <h3 class="text-sm sm:text-base font-semibold text-neutral-800 mb-3 flex items-center gap-2">
                    <span class="text-lg">🚌</span> Akses dengan Angkutan Umum
                </h3>
                <div class="text-xs sm:text-sm text-neutral-700 space-y-3">
                    <p>Terdapat halte bus terdekat seperti <strong>Halte PIM</strong> yang hanya sekitar 4-5 menit jalan
                        kaki dari mall.</p>

                    <p class="font-medium">Beberapa rute bus yang melewati dekat mall:</p>
                    <ul class="list-disc ml-6 space-y-1">
                        <li>AMPERA – LEMABANG</li>
                        <li>KARYA JAYA – PUSRI</li>
                        <li>PLAJU – ALANG-ALANG LEBAR</li>
                    </ul>

                    <p>Jika naik bus, usahakan turun di halte yang disebut di atas, lalu jalan kaki pendek ke mall.</p>
                </div>
            </div>

            <!-- Akses dengan LRT -->
            <div class="mb-8">
                <h3 class="text-sm sm:text-base font-semibold text-neutral-800 mb-3 flex items-center gap-2">
                    <span class="text-lg">🚆</span> Akses dengan Kereta Ringan / LRT
                </h3>
                <div class="text-xs sm:text-sm text-neutral-700 space-y-3">
                    <p>Sistem LRT Palembang beroperasi di Palembang.</p>

                    <p>Stasiun LRT yang relatif dekat: <strong>Stasiun Terpadu Jembatan Ampera</strong> — sekitar 25 menit
                        jalan kaki dari mall.</p>

                    <p>Setelah turun LRT, mungkin perlu lanjut dengan ojol/taksi atau jalan kaki tergantung jarak/stasiun
                        Anda.</p>
                </div>
            </div>

            <!-- Akses dengan Kendaraan Pribadi -->
            <div class="mb-8">
                <h3 class="text-sm sm:text-base font-semibold text-neutral-800 mb-3 flex items-center gap-2">
                    <span class="text-lg">🚗</span> Akses dengan Kendaraan Pribadi / Taksi / Rideshare
                </h3>
                <div class="text-xs sm:text-sm text-neutral-700 space-y-3">
                    <p>Mall mudah diakses dengan kendaraan karena berada di pusat kota Palembang.</p>

                    <p>Jika pakai aplikasi peta atau Waze, cari rute ke <strong>"Palembang Icon Mall, Jalan POM IX,
                            Palembang"</strong>.</p>

                    <p>Perhatikan jam sibuk: di kota besar biasanya pagi dan sore banyak lalu-lintas. Sebaiknya berangkat
                        lebih awal jika waktu penting.</p>
                </div>
            </div>

            <!-- Tips Tambahan -->
            <div class="bg-rose-50 border border-rose-200 rounded-xl p-4 sm:p-6">
                <h3 class="text-sm sm:text-base font-semibold text-neutral-800 mb-3 flex items-center gap-2">
                    <span class="text-lg">✅</span> Tips Tambahan
                </h3>
                <ul class="text-xs sm:text-sm text-neutral-700 space-y-2 list-disc ml-6">
                    <li>Jika Anda datang dari luar kota atau dari bandara, pertama menggunakan LRT atau bus ke pusat kota,
                        lalu lanjut ke mall.</li>
                    <li>Pastikan naik bus dengan rute yang melewati "Jalan POM IX" atau pusat kota Palembang.</li>
                    <li>Periksa jadwal dan ketersediaan transportasi umum di hari Anda datang karena bisa berubah.</li>
                    <li>Jika membawa banyak barang belanjaan atau bersama keluarga, kendaraan pribadi atau taksi/ojol bisa
                        lebih nyaman.</li>
                </ul>
            </div>
        </div>
    </section>

@endsection
