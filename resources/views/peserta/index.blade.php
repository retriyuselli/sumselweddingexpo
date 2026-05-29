@extends('layouts.app')

@section('title', 'Peserta Expo — WeddingExpo')

@section('content')
    <main class="min-h-screen bg-gray-50">
        <section class="pt-24 md:pt-28 pb-10 bg-linear-to-r from-blue-50 to-indigo-50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-xl sm:text-2xl md:text-3xl font-bold">Peserta Expo</h1>
                        @if ($expo)
                            <p class="text-sm text-neutral-600 mt-2">
                                Daftar vendor yang berpartisipasi dalam <span
                                    class="font-semibold text-neutral-800">{{ $expo->nama_expo }}</span>
                            </p>
                            <p class="text-xs text-neutral-500 mt-1">
                                {{ $expo->tanggal_mulai->format('d M Y') }} -
                                {{ $expo->tanggal_selesai->format('d M Y') }}
                                @if ($expo->lokasi)
                                    • {{ $expo->lokasi }}
                                @endif
                            </p>
                        @else
                            <p class="text-sm text-neutral-600 mt-2">Belum ada informasi expo yang aktif saat ini.</p>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <section class="py-8 sm:py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                {{-- ===== FLOOR PLAN ===== --}}
                @if($expo)
                @php
                    if ($tenantSpots->isNotEmpty()) {
                        // ── Data-driven layout from TenantSpot model ──
                        $byBlok      = $tenantSpots->groupBy('blok');
                        $blokkASpots = $byBlok->get('A', collect())->sortBy([['baris','asc'],['kolom','asc']]);
                        $blokkABooths = $blokkASpots->pluck('kode_booth')->all();
                        $blokkARows   = max(1, $blokkASpots->max('baris') ?: 5);

                        $bBySection  = $byBlok->get('B', collect())->groupBy('section');
                        $blokkBLeft  = $bBySection->get('kiri',  collect())->sortBy([['baris','asc'],['kolom','asc']])->pluck('kode_booth')->all();
                        $blokkBRight = $bBySection->get('kanan', collect())->sortBy([['baris','asc'],['kolom','asc']])->pluck('kode_booth')->all();
                        $blokkBCols  = max(1, $bBySection->get('kiri', collect())->max('kolom') ?: 5);

                        $blokkCBooths = $byBlok->get('C', collect())->sortBy([['baris','asc'],['kolom','asc']])->pluck('kode_booth')->all();
                    } else {
                        // ── Fallback hardcoded layout (A=10, B=20, C=10) ──
                        $blokkABooths = array_map(fn($n) => 'A-' . str_pad($n, 2, '0', STR_PAD_LEFT), range(1, 10));
                        $blokkBLeft   = array_map(fn($n) => 'B-' . str_pad($n, 2, '0', STR_PAD_LEFT), range(1, 10));
                        $blokkBRight  = array_map(fn($n) => 'B-' . str_pad($n, 2, '0', STR_PAD_LEFT), range(11, 20));
                        $blokkCBooths = array_map(fn($n) => 'C-' . str_pad($n, 2, '0', STR_PAD_LEFT), range(1, 10));
                        $blokkARows   = 5;
                        $blokkBCols   = 5;
                    }

                    // Display label: 'A-01' → 'A1', 'B-11' → 'B11'
                    $label = fn($id) => preg_replace('/^([A-Z])-0?(\d+)$/', '$1$2', $id);

                    // Tailwind colour classes for a booth cell
                    $boothColor = function($ps) {
                        if (!$ps) return 'bg-green-100 border-green-300 text-green-700';
                        return match($ps->categoryTenant?->category?->value ?? '') {
                            'gold'     => 'bg-yellow-300 border-yellow-500 text-yellow-900',
                            'platinum' => 'bg-violet-400 border-violet-600 text-white',
                            'silver'   => 'bg-emerald-300 border-emerald-500 text-emerald-900',
                            default    => 'bg-gray-200 border-gray-400 text-gray-700',
                        };
                    };
                @endphp

                <div class="rounded-2xl overflow-hidden shadow-lg mb-6" style="background:linear-gradient(135deg,#9ee8e8 0%,#6dd3d3 100%)">
                    <div class="p-4 md:p-5">

                        {{-- Header --}}
                        <div class="flex flex-wrap gap-3 justify-between items-start mb-4">
                            <div class="flex items-center gap-3">
                                <div class="bg-white rounded-xl px-3 py-1.5 shadow-sm shrink-0">
                                    <img src="/storage/logo/logoswe.png" alt="SWE" class="h-9 w-auto">
                                </div>
                                <div>
                                    <h2 class="text-xl md:text-2xl font-black text-gray-900 tracking-tight leading-tight">LAYOUT SWE</h2>
                                    <p class="text-[11px] text-gray-700 leading-tight">
                                        {{ $expo->tanggal_mulai->format('d M Y') }} – {{ $expo->tanggal_selesai->format('d M Y') }}
                                        @if($expo->lokasi) &nbsp;|&nbsp; {{ Str::words($expo->lokasi, 4, '…') }} @endif
                                    </p>
                                </div>
                            </div>

                            <div class="bg-white/70 backdrop-blur rounded-xl px-3 py-2 text-right">
                                <p class="text-[10px] font-black text-gray-800 tracking-widest mb-1">HARGA TENANT</p>
                                @foreach($categoryTenants as $cat)
                                    @php
                                        $dotColor = match($cat->category->value) {
                                            'gold'     => 'bg-yellow-400',
                                            'platinum' => 'bg-violet-500',
                                            'silver'   => 'bg-emerald-400',
                                            default    => 'bg-gray-400',
                                        };
                                    @endphp
                                    <div class="flex items-center justify-end gap-1.5 text-[11px] mb-0.5">
                                        <span class="w-5 h-3 rounded {{ $dotColor }} shrink-0"></span>
                                        <span class="font-semibold">{{ $cat->category->label() }}</span>
                                        <span class="text-gray-600">Rp&nbsp;{{ number_format($cat->harga_jual, 0, ',', '.') }},-</span>
                                    </div>
                                @endforeach
                                <div class="flex items-center justify-end gap-1.5 text-[11px]">
                                    <span class="w-5 h-3 rounded bg-green-200 border border-green-400 shrink-0"></span>
                                    <span class="text-gray-500">Tersedia</span>
                                </div>
                            </div>
                        </div>

                        {{-- Floor plan (scrollable) --}}
                        <div class="overflow-x-auto pb-1">
                            <div class="inline-flex gap-3 items-start" style="min-width:max-content">

                                {{-- ── BLOK A ── --}}
                                <div class="flex flex-col items-center">
                                    <div class="grid grid-flow-col gap-1" style="grid-template-rows:repeat({{ $blokkARows }},2.75rem)">
                                        @foreach($blokkABooths as $boothId)
                                            @php $ps = $boothMap[$boothId] ?? null; @endphp
                                            <a href="{{ $ps ? '#vendor-' . $ps->id : 'javascript:void(0)' }}"
                                               class="w-11 h-11 rounded border-2 flex flex-col items-center justify-center text-center p-0.5 transition-all {{ $boothColor($ps) }} {{ $ps ? 'hover:scale-105 hover:shadow-md' : '' }}"
                                               title="{{ $ps ? ($ps->vendor->nama_vendor ?? '') : 'Tersedia' }}">
                                                <span class="text-[9px] font-black leading-none">{{ $label($boothId) }}</span>
                                                @if($ps)
                                                    <span class="text-[7px] leading-tight mt-0.5 w-full px-0.5 truncate block text-center">{{ Str::limit($ps->vendor->nama_vendor ?? '', 8) }}</span>
                                                    <span class="text-[6px] font-bold block">BOOKED</span>
                                                @else
                                                    <span class="text-[7px] opacity-60 block">avail</span>
                                                @endif
                                            </a>
                                        @endforeach
                                    </div>
                                    <p class="text-[9px] font-black tracking-widest text-gray-800 mt-1.5">BLOK A</p>
                                </div>

                                {{-- Pilar A/B --}}
                                <div class="self-center">
                                    <div class="w-8 h-8 rounded-full bg-gray-900 text-white flex items-center justify-center">
                                        <span class="text-[6px] font-black leading-none text-center">PILAR</span>
                                    </div>
                                </div>

                                {{-- ── BLOK B ── --}}
                                <div class="flex flex-col items-center">
                                    <p class="text-[9px] font-black tracking-widest text-gray-800 mb-1.5">BLOK B</p>
                                    <div class="flex gap-2 items-stretch">

                                        {{-- Stage + seating --}}
                                        <div class="flex flex-col items-center gap-1">
                                            <div class="bg-gray-500 text-white rounded-lg flex items-center justify-center font-black text-xs shrink-0"
                                                 style="width:44px;height:92px;writing-mode:vertical-rl;transform:rotate(180deg)">
                                                STAGE
                                            </div>
                                            <div class="grid grid-cols-4 gap-0.5 mt-0.5">
                                                @for($i = 0; $i < 16; $i++)
                                                    <div class="w-2 h-2 rounded-full bg-white/50 border border-white/60"></div>
                                                @endfor
                                            </div>
                                        </div>

                                        {{-- B-01 to B-10 --}}
                                        <div class="grid gap-1" style="grid-template-columns:repeat({{ $blokkBCols }},2.75rem)">
                                            @foreach($blokkBLeft as $boothId)
                                                @php $ps = $boothMap[$boothId] ?? null; @endphp
                                                <a href="{{ $ps ? '#vendor-' . $ps->id : 'javascript:void(0)' }}"
                                                   class="w-11 h-11 rounded border-2 flex flex-col items-center justify-center text-center p-0.5 transition-all {{ $boothColor($ps) }} {{ $ps ? 'hover:scale-105 hover:shadow-md' : '' }}"
                                                   title="{{ $ps ? ($ps->vendor->nama_vendor ?? '') : 'Tersedia' }}">
                                                    <span class="text-[9px] font-black leading-none">{{ $label($boothId) }}</span>
                                                    @if($ps)
                                                        <span class="text-[7px] leading-tight mt-0.5 w-full px-0.5 truncate block text-center">{{ Str::limit($ps->vendor->nama_vendor ?? '', 8) }}</span>
                                                        <span class="text-[6px] font-bold block">BOOKED</span>
                                                    @else
                                                        <span class="text-[7px] opacity-60 block">avail</span>
                                                    @endif
                                                </a>
                                            @endforeach
                                        </div>

                                        {{-- Pilar mid-B --}}
                                        <div class="self-center">
                                            <div class="w-8 h-8 rounded-full bg-gray-900 text-white flex items-center justify-center">
                                                <span class="text-[6px] font-black leading-none text-center">PILAR</span>
                                            </div>
                                        </div>

                                        {{-- B-11 to B-20 --}}
                                        <div class="grid gap-1" style="grid-template-columns:repeat({{ $blokkBCols }},2.75rem)">
                                            @foreach($blokkBRight as $boothId)
                                                @php $ps = $boothMap[$boothId] ?? null; @endphp
                                                <a href="{{ $ps ? '#vendor-' . $ps->id : 'javascript:void(0)' }}"
                                                   class="w-11 h-11 rounded border-2 flex flex-col items-center justify-center text-center p-0.5 transition-all {{ $boothColor($ps) }} {{ $ps ? 'hover:scale-105 hover:shadow-md' : '' }}"
                                                   title="{{ $ps ? ($ps->vendor->nama_vendor ?? '') : 'Tersedia' }}">
                                                    <span class="text-[9px] font-black leading-none">{{ $label($boothId) }}</span>
                                                    @if($ps)
                                                        <span class="text-[7px] leading-tight mt-0.5 w-full px-0.5 truncate block text-center">{{ Str::limit($ps->vendor->nama_vendor ?? '', 8) }}</span>
                                                        <span class="text-[6px] font-bold block">BOOKED</span>
                                                    @else
                                                        <span class="text-[7px] opacity-60 block">avail</span>
                                                    @endif
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                {{-- Pilar B/C --}}
                                <div class="self-center">
                                    <div class="w-8 h-8 rounded-full bg-gray-900 text-white flex items-center justify-center">
                                        <span class="text-[6px] font-black leading-none text-center">PILAR</span>
                                    </div>
                                </div>

                                {{-- ── BLOK C ── --}}
                                <div class="flex flex-col items-center">
                                    <p class="text-[9px] font-black tracking-widest text-gray-800 mb-1.5">BLOK C</p>
                                    <div class="flex gap-1">
                                        @foreach($blokkCBooths as $boothId)
                                            @php $ps = $boothMap[$boothId] ?? null; @endphp
                                            <a href="{{ $ps ? '#vendor-' . $ps->id : 'javascript:void(0)' }}"
                                               class="w-11 h-20 rounded border-2 flex flex-col items-center justify-center text-center p-0.5 transition-all {{ $boothColor($ps) }} {{ $ps ? 'hover:scale-105 hover:shadow-md' : '' }}"
                                               title="{{ $ps ? ($ps->vendor->nama_vendor ?? '') : 'Tersedia' }}">
                                                <span class="text-[9px] font-black leading-none">{{ $label($boothId) }}</span>
                                                @if($ps)
                                                    <span class="text-[7px] leading-tight mt-0.5 w-full px-0.5 truncate block text-center">{{ Str::limit($ps->vendor->nama_vendor ?? '', 8) }}</span>
                                                    <span class="text-[6px] font-bold block">BOOKED</span>
                                                @else
                                                    <span class="text-[7px] opacity-60 block">avail</span>
                                                @endif
                                            </a>
                                        @endforeach
                                    </div>
                                </div>

                            </div>{{-- end inline-flex --}}
                        </div>{{-- end overflow-x-auto --}}

                        <p class="text-[9px] text-gray-600 mt-2 italic">* Layout sewaktu-waktu dapat berubah tanpa pemberitahuan lebih lanjut</p>
                    </div>
                </div>
                @endif
                {{-- ===== END FLOOR PLAN ===== --}}

                <div class="grid sm:grid-cols-3 gap-3 mb-6">
                    <div class="sm:col-span-2 flex gap-3">
                        <form action="{{ route('peserta.index') }}" method="GET" class="w-full">
                            <input type="text" name="search" value="{{ $search ?? '' }}"
                                class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm"
                                placeholder="Cari vendor atau kota">
                        </form>
                    </div>
                    <div class="flex items-center justify-end">
                        <span class="text-xs px-2 py-1 bg-blue-50 text-blue-700 rounded-full">
                            {{ number_format($partisipasis->count()) }} peserta
                        </span>
                    </div>
                </div>
                @if ($partisipasis->isEmpty())
                    <div class="text-center py-12 bg-white rounded-xl border border-neutral-200 shadow-sm">
                        <svg class="w-12 h-12 text-neutral-300 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <h3 class="text-lg font-medium text-neutral-900">Belum Ada Peserta</h3>
                        <p class="text-neutral-500 mt-1">Daftar peserta expo belum tersedia saat ini.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($partisipasis as $partisipasi)
                            <div id="vendor-{{ $partisipasi->id }}"
                                class="group bg-white rounded-xl border border-neutral-200 overflow-hidden hover:shadow-lg hover:border-blue-200 transition-all duration-300">
                                <div class="p-5">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex items-center gap-3">
                                            @if ($partisipasi->vendor->logo)
                                                <img src="{{ asset('storage/' . $partisipasi->vendor->logo) }}"
                                                    alt="{{ $partisipasi->vendor->nama_vendor }}"
                                                    class="w-12 h-12 rounded-lg object-contain p-1 bg-white border border-neutral-200 shadow-md group-hover:scale-110 transition-transform">
                                            @else
                                                <div
                                                    class="w-12 h-12 rounded-lg bg-linear-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-lg font-bold shadow-md group-hover:scale-110 transition-transform">
                                                    {{ strtoupper(substr($partisipasi->vendor->nama_vendor, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <h3
                                                    class="font-bold text-neutral-900 line-clamp-1 group-hover:text-blue-600 transition-colors">
                                                    {{ $partisipasi->vendor->nama_vendor }}
                                                </h3>
                                                <p class="text-xs text-neutral-500 font-medium">
                                                    {{ $partisipasi->vendor->jenisUsaha->nama_jenis_usaha ?? 'Umum' }}
                                                </p>
                                            </div>
                                        </div>
                                        @if ($partisipasi->blok_tenant)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                                Blok {{ $partisipasi->blok_tenant }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="space-y-2 text-sm">
                                        <div class="flex items-center text-neutral-600">
                                            <svg class="w-4 h-4 mr-2 text-neutral-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span class="truncate">{{ $partisipasi->vendor->kota }}</span>
                                        </div>
                                        <div class="flex items-center text-neutral-600">
                                            <svg class="w-4 h-4 mr-2 text-neutral-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            <span class="truncate">{{ $partisipasi->vendor->no_telepon }}</span>
                                        </div>
                                        @if ($partisipasi->categoryTenant)
                                            <div class="flex items-center text-neutral-600">
                                                <svg class="w-4 h-4 mr-2 text-neutral-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                </svg>
                                                <span>{{ $partisipasi->categoryTenant->category }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mt-4 pt-4 border-t border-neutral-100 flex items-center justify-between">
                                        <a href="{{ route('peserta.show', $partisipasi->vendor->slug) }}"
                                            class="text-sm font-medium text-indigo-600 hover:text-indigo-800 flex items-center gap-1 group-hover:gap-2 transition-all">
                                            Lihat Profil
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                            </svg>
                                        </a>
                                        @if ($partisipasi->vendor->no_wa_pic)
                                            <a href="https://wa.me/{{ $partisipasi->vendor->whatsapp_number }}"
                                                target="_blank"
                                                class="text-green-600 hover:text-green-700 bg-green-50 p-2 rounded-full hover:bg-green-100 transition-colors"
                                                title="Chat WhatsApp">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.017-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    </main>
@endsection
