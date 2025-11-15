@extends('layouts.app')

@section('title', 'Partners — WeddingExpo')

@section('content')
    @php
        $vendors = \App\Models\Vendor::with('jenisUsaha')->latest()->get();
        $jenisUsahas = \App\Models\JenisUsaha::withCount('vendors')->orderBy('nama_jenis_usaha')->get();
    @endphp
    <main class="min-h-screen bg-gray-50">
        <section class="pt-24 md:pt-28 pb-10 bg-linear-to-r from-blue-50 to-indigo-50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-xl sm:text-2xl md:text-3xl font-bold">Partners</h1>
                        <p class="text-xs sm:text-sm text-neutral-600 mt-1">Temukan vendor pilihan untuk acara Anda</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-8 sm:py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">
                <div class="grid sm:grid-cols-3 gap-3">
                    <div class="sm:col-span-2 flex gap-3">
                        <input id="partners-search" type="text" class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm" placeholder="Cari vendor atau kota">
                        <select id="partners-filter" class="rounded-lg border border-neutral-300 px-3 py-2 text-sm">
                            <option value="">Semua jenis usaha</option>
                            @foreach ($jenisUsahas as $j)
                                <option value="{{ $j->nama_jenis_usaha }}">{{ $j->nama_jenis_usaha }} ({{ $j->vendors_count }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center justify-end">
                        <span class="text-xs px-2 py-1 bg-blue-50 text-blue-700 rounded-full">{{ number_format($vendors->count()) }} vendor</span>
                    </div>
                </div>

                <div id="partners-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    @forelse ($vendors as $v)
                        <div class="group bg-white rounded-xl border border-neutral-200 hover:border-blue-300 hover:bg-blue-50 transition p-4 sm:p-6" data-jenis="{{ $v->jenisUsaha->nama_jenis_usaha ?? '' }}" data-nama="{{ strtolower($v->nama_vendor) }}" data-kota="{{ strtolower($v->kota) }}">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 rounded-lg bg-linear-to-br from-blue-400 to-indigo-600 flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($v->nama_vendor, 0, 1)) }}
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-sm font-semibold text-neutral-900">{{ $v->nama_vendor }}</h3>
                                    <p class="text-xs text-neutral-600">{{ $v->jenisUsaha->nama_jenis_usaha ?? '—' }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3 text-xs">
                                <div>
                                    <p class="text-neutral-600">Kota</p>
                                    <p class="font-medium text-neutral-900">{{ $v->kota }}</p>
                                </div>
                                <div>
                                    <p class="text-neutral-600">Lokasi Booth</p>
                                    <p class="font-medium text-neutral-900">{{ $v->lokasi_booth ?? '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-neutral-600">Paket</p>
                                    <p class="font-medium text-neutral-900">{{ $v->paket ?? '—' }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="mailto:{{ $v->email }}" class="text-blue-600 hover:text-blue-700">Email</a>
                                    <span class="text-neutral-300">•</span>
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/','',$v->no_wa_pic) }}" target="_blank" class="text-green-600 hover:text-green-700">WhatsApp</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 text-center text-sm text-neutral-600">Belum ada vendor terdaftar.</div>
                    @endforelse
                </div>
            </div>
        </section>
    </main>

    <script>
        const s = document.getElementById('partners-search');
        const f = document.getElementById('partners-filter');
        const grid = document.getElementById('partners-grid');
        function applyFilter() {
            const q = (s.value || '').toLowerCase();
            const jenis = f.value || '';
            [...grid.children].forEach(card => {
                const matchNama = card.dataset.nama.includes(q);
                const matchKota = card.dataset.kota.includes(q);
                const matchJenis = !jenis || card.dataset.jenis === jenis;
                card.style.display = (q ? (matchNama || matchKota) : true) && matchJenis ? '' : 'none';
            });
        }
        s.addEventListener('input', applyFilter);
        f.addEventListener('change', applyFilter);
    </script>
@endsection