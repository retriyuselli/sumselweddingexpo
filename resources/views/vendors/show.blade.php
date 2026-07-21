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
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-base sm:text-lg font-bold">Produk Vendor</h2>
                            @if (auth()->check() && (int) auth()->id() === (int) ($vendor->user_id ?? 0))
                                <button id="btn-open-add-product" type="button"
                                    class="px-3 py-1.5 text-xs rounded-lg border border-neutral-300 text-neutral-700 hover:bg-neutral-50">Tambah
                                    Produk</button>
                            @endif
                        </div>
                        @if (auth()->check() && (int) auth()->id() === (int) ($vendor->user_id ?? 0))
                            <div id="add-product-form" class="hidden mb-4">
                                <form method="POST" action="{{ route('vendors.products.store', $vendor) }}"
                                    enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @csrf
                                    <div>
                                        <label class="text-xs text-neutral-600">Nama Produk</label>
                                        <input name="nama_produk" type="text" required
                                            class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm"
                                            placeholder="Contoh: Paket Wedding Basic" value="{{ old('nama_produk') }}">
                                    </div>
                                    <div>
                                        <label class="text-xs text-neutral-600">Harga</label>
                                        <input name="harga" type="number" min="0" required
                                            class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm"
                                            placeholder="120000000" value="{{ old('harga') }}">
                                    </div>
                                    <div>
                                        <label class="text-xs text-neutral-600">DP (opsional)</label>
                                        <input name="dp_fixed" type="number" min="0"
                                            class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm"
                                            placeholder="10000000" value="{{ old('dp_fixed') }}">
                                    </div>
                                    <div>
                                        <label class="text-xs text-neutral-600">Foto Produk (opsional)</label>
                                        <input name="foto" type="file" accept="image/*"
                                            class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm">
                                        <p class="mt-1 text-[11px] text-neutral-500">Max 1MB</p>
                                        <img id="foto-preview-create" class="mt-2 h-20 w-20 object-cover rounded hidden"
                                            alt="Preview">
                                        <p id="foto-error-create" class="mt-1 text-[11px] text-rose-600 hidden"></p>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="text-xs text-neutral-600">Deskripsi (opsional)</label>
                                        <div class="mt-1 rounded-lg border border-neutral-300">
                                            <div
                                                class="flex items-center gap-1 p-2 border-b border-neutral-200 bg-neutral-50">
                                                <button type="button" data-cmd="bold"
                                                    class="px-2 py-1 text-xs rounded border border-neutral-300">B</button>
                                                <button type="button" data-cmd="italic"
                                                    class="px-2 py-1 text-xs rounded border border-neutral-300"><em>I</em></button>
                                                <button type="button" data-cmd="underline"
                                                    class="px-2 py-1 text-xs rounded border border-neutral-300"><u>U</u></button>
                                                <span class="mx-2 h-4 w-px bg-neutral-300"></span>
                                                <button type="button" data-cmd="insertUnorderedList"
                                                    class="px-2 py-1 text-xs rounded border border-neutral-300">•
                                                    List</button>
                                                <button type="button" data-cmd="insertOrderedList"
                                                    class="px-2 py-1 text-xs rounded border border-neutral-300">1.
                                                    List</button>
                                                <button type="button" id="btn-insert-link"
                                                    class="px-2 py-1 text-xs rounded border border-neutral-300">Link</button>
                                                <span class="mx-2 h-4 w-px bg-neutral-300"></span>
                                                <button type="button" data-cmd="removeFormat"
                                                    class="px-2 py-1 text-xs rounded border border-neutral-300">Clear</button>
                                            </div>
                                            <div id="rich-desc" class="min-h-[120px] p-3 text-sm" contenteditable="true"
                                                placeholder="Detail paket, fasilitas, dll."></div>
                                            <textarea id="rich-desc-textarea" name="deskripsi" class="hidden">{{ old('deskripsi') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 sm:col-span-2">
                                        <label class="inline-flex items-center gap-2 text-xs text-neutral-700"><input
                                                type="checkbox" name="is_active" value="1" class="rounded" checked>
                                            Aktifkan produk</label>
                                        <button type="submit"
                                            class="ml-auto px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-xs hover:bg-indigo-700">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        @endif
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
                                    <div class="rounded-xl border border-neutral-200 overflow-hidden bg-white flex flex-col">
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
                                        <div class="p-4 flex flex-col flex-1">
                                            <p class="text-sm font-semibold text-neutral-900">{{ $p->nama_produk }}</p>
                                            <p class="text-sm text-neutral-700 mt-1">Rp
                                                {{ number_format((float) ($p->harga ?? 0), 0, ',', '.') }}</p>
                                            <div class="mt-auto pt-3 flex items-center gap-2">
                                                <a href="{{ route('products.show', $p->slug) }}"
                                                    class="px-3 py-1.5 text-xs rounded-lg border border-neutral-300 text-neutral-700 hover:bg-neutral-50"
                                                    aria-label="Lihat detail">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    <span class="sr-only">Detail</span>
                                                </a>
                                                <button
                                                    class="px-3 py-1.5 text-xs rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 add-to-cart"
                                                    data-id="{{ $p->id }}" data-vendor="{{ $vendor->id }}"
                                                    data-slug="{{ $p->slug }}" data-nama="{{ $p->nama_produk }}"
                                                    data-harga="{{ (int) ($p->harga ?? 0) }}"
                                                    data-dp-fixed="{{ (int) ($p->dp_fixed ?? 0) }}"
                                                    data-img="{{ $img }}" aria-label="Tambah ke keranjang">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 7M7 13l-2 9h12m-6-9v9" />
                                                        <circle cx="9" cy="21" r="1" />
                                                        <circle cx="15" cy="21" r="1" />
                                                    </svg>
                                                    <span class="sr-only">Tambah</span>
                                                </button>
                                                @if (auth()->check() && (int) auth()->id() === (int) ($vendor->user_id ?? 0))
                                                    <a href="{{ route('vendors.products.edit', [$vendor, $p]) }}"
                                                        class="px-3 py-1.5 text-xs rounded-lg border border-neutral-300 text-neutral-700 hover:bg-neutral-50">Edit</a>
                                                @endif
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
                                <p class="font-semibold text-neutral-900">
                                    {{ $partisipasi?->tenantSpot?->kode_booth ?? $partisipasi?->blok_tenant ?? '—' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-neutral-600">Paket</p>
                                <p class="font-semibold text-neutral-900">
                                    @php
                                        $cat = $partisipasi?->categoryTenant?->category;
                                        $catValue = $cat instanceof \BackedEnum ? $cat->value : $cat;
                                    @endphp
                                    {{ \App\Enums\CategoryTier::tryFrom((string) ($catValue ?? ''))?->label() ?? ($catValue ?? '—') }}
                                </p>
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
                        <img id="cart-modal-img" src="" alt=""
                            class="w-full h-full object-cover hidden" />
                    </div>
                    <div class="flex-1">
                        <p id="cart-modal-name" class="text-sm font-semibold text-neutral-900"></p>
                        <p id="cart-modal-vendor" class="text-xs text-neutral-600"></p>
                    </div>
                </div>
                <div class="border-t border-neutral-100 p-3 flex items-center justify-between">
                    <p class="text-sm text-neutral-700">Ditambahkan ke keranjang</p>
                    <div class="flex items-center gap-2">
                        <button id="cart-modal-close" type="button"
                            class="px-3 py-1.5 rounded-lg border border-neutral-300 text-neutral-700 text-xs hover:bg-neutral-50">Lanjut
                            belanja</button>
                        <a href="{{ route('cart') }}"
                            class="px-3 py-1.5 rounded-lg bg-rose-600 text-white text-xs hover:bg-rose-700">Lihat
                            keranjang</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const btnOpen = document.getElementById('btn-open-add-product');
            const formWrap = document.getElementById('add-product-form');
            if (btnOpen && formWrap) {
                btnOpen.addEventListener('click', () => {
                    formWrap.classList.toggle('hidden');
                });
            }

            const rte = document.getElementById('rich-desc');
            const rteHidden = document.getElementById('rich-desc-textarea');
            const rteBtns = document.querySelectorAll('#add-product-form [data-cmd]');
            const linkBtn = document.getElementById('btn-insert-link');
            const formEl = document.querySelector('#add-product-form form');
            if (rte && rteHidden && formEl) {
                if (rteHidden.value) {
                    rte.innerHTML = rteHidden.value;
                }
                rteBtns.forEach(b => {
                    b.addEventListener('click', () => {
                        document.execCommand(b.dataset.cmd, false, null);
                        rte.focus();
                    });
                });
                if (linkBtn) {
                    linkBtn.addEventListener('click', () => {
                        const url = prompt('Masukkan URL');
                        if (url) {
                            document.execCommand('createLink', false, url);
                        }
                        rte.focus();
                    });
                }
                formEl.addEventListener('submit', () => {
                    rteHidden.value = rte.innerHTML.trim();
                });
            }

            const createFotoInput = document.querySelector('#add-product-form input[name="foto"]');
            const createFotoPreview = document.getElementById('foto-preview-create');
            const createFotoErr = document.getElementById('foto-error-create');
            const MAX_SIZE = 1024 * 1024;

            function previewImage(file, imgEl, errEl) {
                errEl.classList.add('hidden');
                errEl.textContent = '';
                if (!file) {
                    if (imgEl) imgEl.classList.add('hidden');
                    return;
                }
                if (file.size > MAX_SIZE) {
                    errEl.textContent = 'Ukuran file melebihi 1MB';
                    errEl.classList.remove('hidden');
                    if (imgEl) imgEl.classList.add('hidden');
                    return;
                }
                const reader = new FileReader();
                reader.onload = e => {
                    if (imgEl) {
                        imgEl.src = e.target.result;
                        imgEl.classList.remove('hidden');
                    }
                };
                reader.readAsDataURL(file);
            }
            if (createFotoInput) {
                createFotoInput.addEventListener('change', () => {
                    const file = createFotoInput.files && createFotoInput.files[0];
                    previewImage(file, createFotoPreview, createFotoErr);
                });
                const addForm = document.querySelector('#add-product-form form');
                if (addForm) {
                    addForm.addEventListener('submit', (e) => {
                        const file = createFotoInput.files && createFotoInput.files[0];
                        if (file && file.size > MAX_SIZE) {
                            e.preventDefault();
                            previewImage(file, createFotoPreview, createFotoErr);
                        }
                    });
                }
            }

            // Edit dipindahkan ke halaman terpisah

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
