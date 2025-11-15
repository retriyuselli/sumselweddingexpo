@extends('layouts.app')

@section('title', 'Produk — WeddingExpo')

@section('content')
    @php
        $snapBase = config('services.midtrans.is_production')
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
        $clientKey = config('services.midtrans.client_key');
        $img = !empty($product->foto_url)
            ? (\Illuminate\Support\Str::startsWith($product->foto_url, ['http://', 'https://'])
                ? $product->foto_url
                : \Illuminate\Support\Facades\Storage::url($product->foto_url))
            : null;
    @endphp
    <main class="min-h-screen bg-gray-50">
        <section class="pt-24 md:pt-28 pb-10 bg-linear-to-r from-blue-50 to-indigo-50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h1 class="text-xl sm:text-2xl md:text-3xl font-bold">{{ $product->nama_produk }}</h1>
                <p class="text-xs sm:text-sm text-neutral-600 mt-1">Vendor: {{ $product->vendor->nama_vendor ?? '—' }}</p>
            </div>
        </section>

        <section class="py-8 sm:py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-6">
                <div>
                    <div class="w-full rounded-xl border border-neutral-200 overflow-hidden" style="aspect-ratio: 1 / 1;">
                        @if ($img)
                            <img src="{{ $img }}" alt="{{ $product->nama_produk }}"
                                class="w-full h-full object-cover object-center">
                        @else
                            <div
                                class="w-full h-full bg-linear-to-br from-blue-400 to-indigo-600 flex items-center justify-center">
                                <span
                                    class="text-4xl font-bold text-white">{{ strtoupper(substr($product->nama_produk, 0, 1)) }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="mt-4 border-t border-neutral-200 pt-4">
                        @if (!empty($product->deskripsi))
                            <div class="text-sm text-neutral-700 leading-relaxed rich-content">{!! $product->deskripsi !!}</div>
                        @else
                            <p class="text-sm text-neutral-700">Tidak ada deskripsi.</p>
                        @endif
                    </div>
                </div>

                <div>
                    <div class="bg-white rounded-xl border border-neutral-200 p-4 sm:p-6">
                        <p class="text-sm text-neutral-600">Harga</p>
                        <p class="text-2xl font-bold">Rp {{ number_format((float) ($product->harga ?? 0), 0, ',', '.') }}
                        </p>
                        <div class="mt-4 flex items-center gap-3">
                            <label class="text-xs text-neutral-700">Qty</label>
                            <input id="qty" type="number" min="1" value="1"
                                class="border border-neutral-300 rounded-lg w-16 px-2 py-1 text-sm">
                        </div>
                        <div class="mt-6 flex items-center gap-3">
                            {{-- <button id="pay-now"
                                class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm hover:bg-indigo-700">Bayar
                                Sekarang</button> --}}
                            <button id="add-to-cart"
                                class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm hover:bg-indigo-700">Tambah ke
                                Keranjang</button>
                            {{-- <a href="{{ route('checkout') }}?vendor_id={{ $product->vendor_id }}"
                                class="px-4 py-2 rounded-lg border border-neutral-300 text-neutral-700 text-sm hover:bg-neutral-50">Checkout
                                Vendor Ini</a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="{{ $snapBase }}" data-client-key="{{ $clientKey }}"></script>
    <script>
        const csrfToken = '{{ csrf_token() }}';
        const payNow = document.getElementById('pay-now');
        if (payNow) payNow.addEventListener('click', async () => {
            const qty = Math.max(1, parseInt(document.getElementById('qty').value, 10) || 1);
            const res = await fetch('{{ route('payments.snap') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    items: [{
                        product_vendor_id: {{ $product->id }},
                        qty
                    }],
                    vendor_id: {{ $product->vendor_id }}
                })
            });
            const data = await res.json();
            if (!data.snap_token) {
                const err = (data && (data.message || (data.midtrans && (data.midtrans.error_messages || (data.midtrans.body && data.midtrans.body.error_messages))))) || null;
                const msg = Array.isArray(err) ? err.join('\n') : (err || 'Gagal membuat transaksi');
                alert(msg);
                return;
            }
            window.snap.pay(data.snap_token, {
                onSuccess: function(result) {
                    const code = (result && result.order_id) ? result.order_id : '';
                    window.location.href = '{{ route('payment.success') }}' + (code ? ('?code=' + encodeURIComponent(code)) : '');
                },
                onPending: function(result) {},
                onError: function(result) { alert('Terjadi kesalahan pembayaran'); },
                onClose: function() {}
            });
        });

        function addToCart(qty) {
            const q = Math.max(1, parseInt(qty, 10) || 1);
            const items = JSON.parse(localStorage.getItem('cartItems') || '[]');
            const idx = items.findIndex(i => i.product_vendor_id === {{ $product->id }});
            if (idx >= 0) {
                items[idx].qty = (items[idx].qty || 1) + q;
            } else {
                items.push({
                    product_vendor_id: {{ $product->id }},
                    qty: q,
                    vendor_id: {{ $product->vendor_id }},
                    nama_produk: @json($product->nama_produk ?? ''),
                    harga: {{ (int) ($product->harga ?? 0) }},
                    img: @json($img ?? null),
                    slug: @json($product->slug ?? ''),
                    vendor_nama: @json($product->vendor->nama_vendor ?? '')
                });
            }
            localStorage.setItem('cartItems', JSON.stringify(items));
            window.dispatchEvent(new Event('storage'));
            setCartButtonState();
        }

        document.getElementById('add-to-cart').addEventListener('click', () => {
            const qty = Math.max(1, parseInt(document.getElementById('qty').value, 10) || 1);
            addToCart(qty);
        });

        (function() {
            const params = new URLSearchParams(window.location.search);
            if (params.get('auto_add') === '1') {
                const q = Math.max(1, parseInt(params.get('qty') || '1', 10));
                addToCart(q);
                if (params.get('redirect') === 'cart') {
                    window.location.href = '{{ route('cart') }}';
                } else if (params.get('redirect') === 'checkout') {
                    const vendorId = {{ $product->vendor_id ?? 'null' }};
                    if (vendorId) {
                        window.location.href = '{{ route('checkout') }}' + '?vendor_id=' + vendorId;
                    } else {
                        window.location.href = '{{ route('checkout') }}';
                    }
                }
            }
            function setCartButtonState() {
                const items = JSON.parse(localStorage.getItem('cartItems') || '[]');
                const exists = items.some(i => i.product_vendor_id === {{ $product->id }});
                const btn = document.getElementById('add-to-cart');
                if (!btn) return;
                if (exists) {
                    btn.textContent = 'Sudah di Keranjang';
                    btn.disabled = true;
                } else {
                    btn.textContent = 'Tambah ke Keranjang';
                    btn.disabled = false;
                }
            }
            document.addEventListener('DOMContentLoaded', setCartButtonState);
            window.addEventListener('storage', setCartButtonState);
        })();
    </script>
    <style>
        .rich-content ul {
            list-style-type: disc;
            padding-left: 1.25rem;
            margin: 0.5rem 0;
        }

        .rich-content ol {
            list-style-type: decimal;
            padding-left: 1.25rem;
            margin: 0.5rem 0;
        }

        .rich-content li {
            margin: 0.25rem 0;
        }
    </style>
@endsection
