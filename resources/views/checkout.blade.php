@extends('layouts.app')

@section('title', 'Checkout — WeddingExpo')

@section('content')
    @php
        $vendorId = request()->query('vendor_id');
        $vendor = $vendorId ? \App\Models\Vendor::find($vendorId) : null;
        $query = \App\Models\ProductVendor::with('vendor')->where('is_active', true);
        if ($vendorId && $vendor) {
            $query->where('vendor_id', $vendorId);
        }
        $products = $query->latest()->take(24)->get();
        $snapBase = config('services.midtrans.is_production')
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
        $clientKey = config('services.midtrans.client_key');
    @endphp
    <main class="min-h-screen bg-gray-50">
        <section class="pt-24 md:pt-28 pb-10 bg-linear-to-r from-blue-50 to-indigo-50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h1 class="text-xl sm:text-2xl md:text-3xl font-bold">
                    {{ $vendor ? 'Checkout • ' . $vendor->nama_vendor : 'Checkout' }}</h1>
                <p class="text-xs sm:text-sm text-neutral-600 mt-1">Pilih produk lalu lakukan pembayaran dengan Midtrans</p>
            </div>
        </section>

        <section class="py-8 sm:py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6" id="product-grid">
                    @forelse ($products as $p)
                        <div class="bg-white rounded-xl border border-neutral-200 p-4 sm:p-6 flex flex-col">
                            <a href="{{ route('products.show', $p->slug) }}" class="block group">
                                <div class="w-full rounded-xl overflow-hidden bg-neutral-100" style="aspect-ratio: 3 / 4;">
                                    @if (!empty($p->foto_url))
                                        <img src="{{ \Illuminate\Support\Str::startsWith($p->foto_url, ['http://', 'https://']) ? $p->foto_url : \Illuminate\Support\Facades\Storage::url($p->foto_url) }}"
                                            alt="{{ $p->nama_produk }}"
                                            class="w-full h-full object-cover object-center group-hover:scale-[1.02] transition-transform">
                                    @else
                                        <div
                                            class="w-full h-full bg-linear-to-br from-blue-400 to-indigo-600 flex items-center justify-center">
                                            <span
                                                class="text-2xl font-bold text-white">{{ strtoupper(substr($p->nama_produk, 0, 1)) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <h3 class="mt-3 text-sm font-semibold text-neutral-900 group-hover:text-indigo-700">
                                    {{ $p->nama_produk }}</h3>
                            </a>
                            <p class="text-xs text-neutral-600">Vendor:
                                @if ($p->vendor)
                                    <a href="{{ route('vendors.show', $p->vendor->slug) }}"
                                        class="text-blue-600 hover:text-blue-700">{{ $p->vendor->nama_vendor }}</a>
                                @else
                                    —
                                @endif
                            </p>
                            <p class="mt-1 text-sm font-medium">Rp {{ number_format((float) ($p->harga ?? 0), 0, ',', '.') }}
                            </p>
                            <div class="mt-3 flex items-center gap-2">
                                <label class="text-xs text-neutral-700">Qty</label>
                                <input type="number" min="1" value="1"
                                    class="qty-input w-16 rounded-lg border border-neutral-300 px-2 py-1 text-xs"
                                    data-product-id="{{ $p->id }}">
                                <input type="checkbox" class="select-input" data-product-id="{{ $p->id }}">
                                <span class="text-xs text-neutral-600">Pilih</span>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-4 text-center text-sm text-neutral-600">Belum ada produk.</div>
                    @endforelse
                </div>

                <div class="mt-6 flex items-center justify-end">
                    <button id="pay-btn" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Bayar</button>
                </div>
            </div>
        </section>
    </main>

    <script src="{{ $snapBase }}" data-client-key="{{ $clientKey }}"></script>
    <script>
        const csrfToken = '{{ csrf_token() }}';
        const savedCart = JSON.parse(localStorage.getItem('cartItems') || '[]');
        savedCart.forEach(it => {
            const qtyEl = document.querySelector('.qty-input[data-product-id="'+it.product_vendor_id+'"]');
            const chkEl = document.querySelector('.select-input[data-product-id="'+it.product_vendor_id+'"]');
            if (qtyEl) qtyEl.value = it.qty;
            if (chkEl) chkEl.checked = true;
        });
        const payBtn = document.getElementById('pay-btn');
        if (payBtn) payBtn.addEventListener('click', async () => {
            const items = [];
            document.querySelectorAll('.select-input:checked').forEach(chk => {
                const id = chk.dataset.productId;
                const qtyEl = document.querySelector('.qty-input[data-product-id="' + id + '"]');
                const qty = parseInt(qtyEl.value || '1', 10);
                items.push({
                    product_vendor_id: parseInt(id, 10),
                    qty
                });
            });
            if (items.length === 0 && savedCart.length > 0) {
                savedCart.forEach(it => items.push({ product_vendor_id: it.product_vendor_id, qty: it.qty }));
            }
            if (items.length === 0) {
                alert('Pilih minimal satu produk');
                return;
            }
            const res = await fetch('{{ route('payments.snap') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    items,
                    vendor_id: {{ $vendor ? $vendor->id : 'null' }}
                })
            });
            const data = await res.json();
            if (!data.snap_token) {
                const err = (data && (data.message || (data.midtrans && (data.midtrans.error_messages || (data.midtrans.body && data.midtrans.body.error_messages))))) || null;
                const msg = Array.isArray(err) ? err.join('\n') : (err || 'Gagal membuat transaksi');
                console.error('Midtrans error:', data);
                alert(msg);
                return;
            }
            window.snap.pay(data.snap_token, {
                onSuccess: function(result) {
                    const code = (result && result.order_id) ? result.order_id : '';
                    window.location.href = '{{ route('payment.success') }}' + (code ? ('?code=' + encodeURIComponent(code)) : '');
                },
                onPending: function(result) { console.log(result); },
                onError: function(result) { console.error(result); alert('Terjadi kesalahan pembayaran'); },
                onClose: function() { console.log('Popup ditutup'); }
            });
        });
    </script>
@endsection
