@extends('layouts.app')

@section('title', 'Vendor — WeddingExpo')

@section('content')
    <main class="min-h-screen bg-gray-50">
        <section class="pt-24 md:pt-28 pb-10 bg-linear-to-r from-blue-50 to-indigo-50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h1 class="text-xl sm:text-2xl md:text-3xl font-bold">{{ $vendor->nama_vendor }}</h1>
                <p class="text-sm text-neutral-600 mt-1">{{ $vendor->jenisUsaha->nama_jenis_usaha ?? '—' }} •
                    {{ $vendor->kota }}</p>
            </div>
        </section>

        <section class="py-8 sm:py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 grid lg:grid-cols-1 gap-6">
                <div class="space-y-6">
                    <div class="bg-white rounded-xl border border-neutral-200 p-4 sm:p-6">
                        <h2 class="text-base sm:text-lg font-bold mb-4">Produk Vendor</h2>
                        @if (($products->count() ?? 0) === 0)
                            <p class="text-sm text-neutral-600">Belum ada produk aktif.</p>
                        @else
                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                                @foreach ($products as $p)
                                    @php
                                        $img = !empty($p->foto_url)
                                            ? (\Illuminate\Support\Str::startsWith($p->foto_url, [
                                                'http://',
                                                'https://',
                                            ])
                                                ? $p->foto_url
                                                : \Illuminate\Support\Facades\Storage::url($p->foto_url))
                                            : null;
                                    @endphp
                                    <div class="rounded-xl border border-neutral-200 overflow-hidden bg-white">
                                        <div class="w-full bg-neutral-100" style="aspect-ratio: 1 / 1;">
                                            @if ($img)
                                                <img src="{{ $img }}" alt="{{ $p->nama_produk }}"
                                                    class="w-full h-full object-cover object-center">
                                            @else
                                                <div
                                                    class="w-full h-full bg-linear-to-br from-blue-400 to-indigo-600 flex items-center justify-center">
                                                    <span
                                                        class="text-2xl font-bold text-white">{{ strtoupper(substr($p->nama_produk, 0, 1)) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="p-4">
                                            <p class="text-sm font-semibold text-neutral-900">{{ $p->nama_produk }}</p>
                                            <p class="text-sm text-neutral-700 mt-1">Rp
                                                {{ number_format((float) ($p->harga ?? 0), 0, ',', '.') }}</p>
                                            <div class="mt-3 flex items-center gap-2">
                                                <a href="{{ route('products.show', $p->slug) }}"
                                                    class="px-3 py-1.5 text-xs rounded-lg border border-neutral-300 text-neutral-700 hover:bg-neutral-50">Detail</a>
                                                <button
                                                    class="px-3 py-1.5 text-xs rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 add-to-cart"
                                                    data-id="{{ $p->id }}" data-vendor="{{ $vendor->id }}"
                                                    data-slug="{{ $p->slug }}" data-nama="{{ $p->nama_produk }}"
                                                    data-harga="{{ (int) ($p->harga ?? 0) }}"
                                                    data-dp-fixed="{{ (int) ($p->dp_fixed ?? 0) }}"
                                                    data-img="{{ $img }}">Tambah</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="bg-white rounded-xl border border-neutral-200 p-4 sm:p-6">
                        <h2 class="text-base sm:text-lg font-bold mb-4">Informasi Vendor</h2>
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 text-sm">
                            <div>
                                <p class="text-neutral-600">Nama Vendor</p>
                                <p class="font-semibold text-neutral-900">{{ $vendor->nama_vendor }}</p>
                            </div>
                            <div>
                                <p class="text-neutral-600">Jenis Usaha</p>
                                <p class="font-semibold text-neutral-900">
                                    {{ $vendor->jenisUsaha->nama_jenis_usaha ?? '—' }}</p>
                            </div>
                            <div>
                                <p class="text-neutral-600">Kota</p>
                                <p class="font-semibold text-neutral-900">{{ $vendor->kota }}</p>
                            </div>
                            <div>
                                <p class="text-neutral-600">Lokasi Booth</p>
                                <p class="font-semibold text-neutral-900">{{ $vendor->lokasi_booth ?? '—' }}</p>
                            </div>
                            <div>
                                <p class="text-neutral-600">Paket</p>
                                <p class="font-semibold text-neutral-900">{{ $vendor->paket ?? '—' }}</p>
                            </div>
                            <div>
                                <p class="text-neutral-600">Kontak PIC</p>
                                <p class="font-semibold text-neutral-900">{{ $vendor->nama_pic }} •
                                    {{ $vendor->no_wa_pic }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-neutral-200 p-4 sm:p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-base sm:text-lg font-bold">Janji Temu Mendatang</h2>
                            <a href="{{ route('appointments.index') }}"
                                class="text-xs sm:text-sm text-blue-600 hover:text-blue-700 font-medium">Lihat Semua</a>
                        </div>
                        @if (($upcomingAppointments->count() ?? 0) === 0)
                            <p class="text-sm text-neutral-600">Belum ada janji temu yang dijadwalkan.</p>
                        @else
                            <div class="space-y-3">
                                @foreach ($upcomingAppointments as $appt)
                                    <div class="p-3 rounded-lg border border-neutral-200">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-xs text-neutral-600">
                                                    {{ $appt->customer->name ?? 'Customer' }}</p>
                                                <p class="text-sm font-semibold text-neutral-900">
                                                    {{ $appt->starts_at?->format('d M Y, H:i') }}</p>
                                                <p class="text-xs text-neutral-700">{{ $appt->subject }}</p>
                                            </div>
                                            <span
                                                class="text-xs px-2 py-1 rounded-full border {{ $appt->status === 'confirmed' ? 'border-green-300 text-green-700 bg-green-50' : ($appt->status === 'rejected' ? 'border-red-300 text-red-700 bg-red-50' : 'border-amber-300 text-amber-700 bg-amber-50') }}">{{ ucfirst($appt->status) }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4">
                                {{ $upcomingAppointments->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </main>

    <div id="cart-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="w-full max-w-sm bg-white rounded-xl shadow-xl border border-neutral-200">
                <div class="p-4 flex items-center gap-3">
                    <div class="w-12 h-12 rounded-lg overflow-hidden bg-neutral-100">
                        <img id="cart-modal-img" src="" alt="" class="w-full h-full object-cover hidden" />
                    </div>
                    <div class="flex-1">
                        <p id="cart-modal-name" class="text-sm font-semibold text-neutral-900"></p>
                        <p id="cart-modal-vendor" class="text-xs text-neutral-600"></p>
                    </div>
                </div>
                <div class="border-t border-neutral-100 p-3 flex items-center justify-between">
                    <p class="text-sm text-neutral-700">Ditambahkan ke keranjang</p>
                    <div class="flex items-center gap-2">
                        <button id="cart-modal-close" type="button" class="px-3 py-1.5 rounded-lg border border-neutral-300 text-neutral-700 text-xs hover:bg-neutral-50">Lanjut belanja</button>
                        <a href="{{ route('cart') }}" class="px-3 py-1.5 rounded-lg bg-rose-600 text-white text-xs hover:bg-rose-700">Lihat keranjang</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            function readCart() {
                try {
                    return JSON.parse(localStorage.getItem('cartItems') || '[]');
                } catch (e) {
                    return [];
                }
            }

            function writeCart(items) {
                localStorage.setItem('cartItems', JSON.stringify(items));
                window.dispatchEvent(new Event('storage'));
            }

            function addItem(data) {
                const items = readCart();
                const idx = items.findIndex(i => i.product_vendor_id === data.product_vendor_id);
                if (idx >= 0) {
                    items[idx].qty = (items[idx].qty || 1) + 1;
                } else {
                    items.push(data);
                }
                writeCart(items);
            }

            const modal = document.getElementById('cart-modal');
            const modalImg = document.getElementById('cart-modal-img');
            const modalName = document.getElementById('cart-modal-name');
            const modalVendor = document.getElementById('cart-modal-vendor');
            const modalClose = document.getElementById('cart-modal-close');

            function openCartModal(data) {
                if (data.img) {
                    modalImg.src = data.img;
                    modalImg.classList.remove('hidden');
                } else {
                    modalImg.src = '';
                    modalImg.classList.add('hidden');
                }
                modalName.textContent = data.nama_produk || '';
                modalVendor.textContent = data.vendor_nama || '';
                modal.classList.remove('hidden');
            }

            function closeCartModal() {
                modal.classList.add('hidden');
            }

            if (modalClose) {
                modalClose.addEventListener('click', closeCartModal);
            }
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        closeCartModal();
                    }
                });
            }

            document.querySelectorAll('.add-to-cart').forEach(btn => {
                btn.addEventListener('click', () => {
                    const data = {
                        product_vendor_id: parseInt(btn.dataset.id, 10),
                        qty: 1,
                        vendor_id: parseInt(btn.dataset.vendor, 10),
                        nama_produk: btn.dataset.nama || '',
                        harga: parseInt(btn.dataset.harga || '0', 10),
                        img: btn.dataset.img || null,
                        slug: btn.dataset.slug || '',
                        vendor_nama: @json($vendor->nama_vendor ?? ''),
                        dp_fixed: parseInt(btn.dataset.dpFixed || '0', 10)
                    };
                    addItem(data);
                    openCartModal(data);
                });
            });
        })();
    </script>
@endsection
