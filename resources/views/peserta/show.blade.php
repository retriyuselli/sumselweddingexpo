@extends('layouts.app')

@section('title', $vendor->nama_vendor . ' — Peserta Expo')

@section('content')
    <main class="min-h-screen bg-gray-50">
        {{-- Header Section --}}
        <section class="pt-24 md:pt-28 pb-10 bg-linear-to-r from-blue-50 to-indigo-50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
                    <div class="flex items-start gap-4">
                        @if ($vendor->logo)
                            <img src="{{ asset('storage/' . $vendor->logo) }}" alt="{{ $vendor->nama_vendor }}"
                                class="w-20 h-20 md:w-24 md:h-24 rounded-xl object-contain p-2 shadow-lg bg-white border border-gray-100">
                        @else
                            <div
                                class="w-20 h-20 md:w-24 md:h-24 rounded-xl bg-linear-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-3xl md:text-4xl font-bold shadow-lg border border-white/20">
                                {{ strtoupper(substr($vendor->nama_vendor, 0, 1)) }}
                            </div>
                        @endif
                        <div class="pt-1">
                            <h1 class="text-2xl sm:text-3xl font-bold text-neutral-900">{{ $vendor->nama_vendor }}</h1>
                            <div class="flex flex-wrap items-center gap-2 mt-2 text-sm text-neutral-600">
                                <span
                                    class="px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-700 font-medium text-xs">{{ $vendor->jenisUsaha->nama_jenis_usaha ?? 'Umum' }}</span>
                                <span>•</span>
                                <span>{{ $vendor->kota }}</span>
                            </div>
                        </div>
                    </div>

                    @if ($partisipasi)
                        <div class="bg-white/80 backdrop-blur-sm px-4 py-3 rounded-xl border border-blue-100 shadow-sm">
                            <p class="text-xs text-neutral-500 uppercase tracking-wide font-semibold mb-1">Status
                                Partisipasi</p>
                            <p class="text-sm font-medium text-neutral-900">
                                <span class="text-indigo-600">{{ $expo->nama_expo }}</span>
                            </p>
                            <p class="text-sm text-neutral-600 mt-0.5">
                                Paket:
                                <span class="font-bold text-neutral-900">
                                    {{ $partisipasi->categoryTenant?->category?->label() ?? '—' }}
                                </span>
                                @if ($partisipasi->tenantSpot?->kode_booth || $partisipasi->blok_tenant)
                                    • Lokasi:
                                    <span class="font-bold text-neutral-900">
                                        {{ $partisipasi->tenantSpot?->kode_booth ?? $partisipasi->blok_tenant }}
                                    </span>
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        {{-- Content Section --}}
        <section class="py-8 sm:py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {{-- Left Column: Info --}}
                    <div class="space-y-6">
                        {{-- About Vendor --}}
                        <div class="bg-white rounded-xl border border-neutral-200 p-6 shadow-xs">
                            <h3 class="text-lg font-bold text-neutral-900 mb-4">Tentang Vendor</h3>

                            <div class="space-y-4">
                                <div>
                                    <p class="text-xs text-neutral-500 mb-1">Alamat</p>
                                    <p class="text-sm text-neutral-800">{{ $vendor->alamat }}</p>
                                </div>

                                <div>
                                    <p class="text-xs text-neutral-500 mb-1">Kontak</p>
                                    <div class="flex flex-col gap-2">
                                        @if ($vendor->no_telepon)
                                            <div class="flex items-center gap-2 text-sm text-neutral-700">
                                                <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                    </path>
                                                </svg>
                                                {{ $vendor->no_telepon }}
                                            </div>
                                        @endif

                                        @if ($vendor->email)
                                            <div class="flex items-center gap-2 text-sm text-neutral-700">
                                                <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                                {{ $vendor->email }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 pt-6 border-t border-neutral-100">
                                <a href="https://wa.me/{{ $vendor->whatsapp_number }}" target="_blank"
                                    class="flex items-center justify-center gap-2 w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 px-4 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.017-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                                    </svg>
                                    Hubungi via WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Products --}}
                    <div class="lg:col-span-2 space-y-6">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold text-neutral-900">Produk & Layanan</h2>
                            <span class="text-sm text-neutral-500">{{ $vendor->products->count() }} Produk</span>
                        </div>

                        @if ($vendor->products->isEmpty())
                            <div class="bg-white rounded-xl border border-neutral-200 p-8 text-center">
                                <p class="text-neutral-500">Vendor ini belum menampilkan produk.</p>
                            </div>
                        @else
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach ($vendor->products as $product)
                                    <div
                                        class="bg-white rounded-xl border border-neutral-200 overflow-hidden hover:shadow-md transition-shadow group">
                                        <div class="aspect-video bg-neutral-100 relative overflow-hidden">
                                            @if ($product->foto_url)
                                                <img src="{{ Storage::url($product->foto_url) }}"
                                                    alt="{{ $product->nama_produk }}"
                                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                            @else
                                                <div
                                                    class="flex items-center justify-center w-full h-full text-neutral-300">
                                                    <svg class="w-12 h-12" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.5"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="p-4">
                                            <h4 class="font-bold text-neutral-900 mb-1 line-clamp-1">
                                                {{ $product->nama_produk }}</h4>
                                            <p class="text-indigo-600 font-medium text-sm">
                                                Rp {{ number_format($product->harga, 0, ',', '.') }}
                                            </p>
                                            @if ($product->deskripsi)
                                                <p class="text-xs text-neutral-500 mt-2 line-clamp-2">
                                                    {{ strip_tags($product->deskripsi) }}</p>
                                            @endif

                                            <div class="mt-4 pt-4 border-t border-neutral-100">
                                                <a href="{{ route('products.show', $product->slug) }}"
                                                    class="text-sm font-medium text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                                                    Lihat Detail
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
