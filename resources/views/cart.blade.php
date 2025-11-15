@extends('layouts.app')

@section('title', 'Keranjang — WeddingExpo')

@section('content')
    @php
        $snapBase = config('services.midtrans.is_production')
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
        $clientKey = config('services.midtrans.client_key');
    @endphp
    <main class="min-h-screen bg-gray-50">
        <section class="pt-24 md:pt-28 pb-10 bg-linear-to-r from-blue-50 to-indigo-50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h1 class="text-xl sm:text-2xl md:text-3xl font-bold">Keranjang</h1>
                <p class="text-xs sm:text-sm text-neutral-600 mt-1">Ringkasan produk yang akan dibeli</p>
            </div>
        </section>

        <section class="py-8 sm:py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div id="empty-state" class="hidden text-center text-sm text-neutral-600">Keranjang kosong.</div>
                <div id="cart-list" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                </div>

                <div id="summary"
                    class="mt-6 rounded-xl border border-neutral-200 bg-white p-4 sm:p-6 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-neutral-600">Total</p>
                        <p id="total-price" class="text-2xl font-bold">Rp 0</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a id="to-checkout" href="{{ route('checkout') }}"
                            class="px-4 py-2 rounded-lg border border-neutral-300 text-neutral-700 text-sm hover:bg-neutral-50">Lanjut
                            ke Checkout</a>
                        <button id="pay-btn"
                            class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm hover:bg-indigo-700">Bayar
                            Sekarang</button>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="{{ $snapBase }}" data-client-key="{{ $clientKey }}"></script>
    <script>
        const csrfToken = '{{ csrf_token() }}';
        const formatRupiah = (n) => new Intl.NumberFormat('id-ID').format(n || 0);

        const readCart = () => JSON.parse(localStorage.getItem('cartItems') || '[]');
        const writeCart = (items) => {
            localStorage.setItem('cartItems', JSON.stringify(items));
            window.dispatchEvent(new Event('storage'));
        };

        const render = () => {
            const items = readCart();
            const list = document.getElementById('cart-list');
            const empty = document.getElementById('empty-state');
            const summary = document.getElementById('summary');
            if (items.length === 0) {
                list.innerHTML = '';
                empty.classList.remove('hidden');
                summary.classList.add('hidden');
                return;
            }
            empty.classList.add('hidden');
            summary.classList.remove('hidden');
            list.innerHTML = items.map(it => {
                const img = it.img ?
                    `<img src="${it.img}" alt="${it.nama_produk || ''}" class="w-full h-full object-cover object-center">` :
                    `<div class=\"w-full h-full bg-linear-to-br from-blue-400 to-indigo-600 flex items-center justify-center\"><span class=\"text-2xl font-bold text-white\">${(it.nama_produk || 'P').substring(0,1).toUpperCase()}</span></div>`;
                return `
                    <div class=\"bg-white rounded-xl border border-neutral-200 p-4 sm:p-6 flex flex-col\">
                        <div class=\"w-full rounded-xl overflow-hidden bg-neutral-100\" style=\"aspect-ratio: 3 / 4;\">${img}</div>
                        <h3 class=\"mt-3 text-sm font-semibold text-neutral-900\">${it.nama_produk || 'Produk'}</h3>
                        <p class=\"text-xs text-neutral-600\">Vendor: ${it.vendor_nama || '—'}</p>
                        <p class=\"mt-1 text-sm font-medium\">Rp ${formatRupiah(it.harga || 0)}</p>
                        <div class=\"mt-3 flex items-center gap-2\">
                            <label class=\"text-xs text-neutral-700\">Qty</label>
                            <input type=\"number\" min=\"1\" value=\"${it.qty || 1}\" class=\"w-16 rounded-lg border border-neutral-300 px-2 py-1 text-xs qty-input\" data-product-id=\"${it.product_vendor_id}\">
                            <button class=\"text-xs text-rose-600 remove-btn\" data-product-id=\"${it.product_vendor_id}\">Hapus</button>
                        </div>
                    </div>
                `;
            }).join('');

            document.querySelectorAll('.qty-input').forEach(input => {
                input.addEventListener('change', e => {
                    const id = parseInt(e.target.dataset.productId, 10);
                    const qty = Math.max(1, parseInt(e.target.value || '1', 10));
                    const items = readCart();
                    const idx = items.findIndex(i => i.product_vendor_id === id);
                    if (idx >= 0) {
                        items[idx].qty = qty;
                        writeCart(items);
                        updateTotal();
                    }
                });
            });
            document.querySelectorAll('.remove-btn').forEach(btn => {
                btn.addEventListener('click', e => {
                    const id = parseInt(e.target.dataset.productId, 10);
                    const items = readCart().filter(i => i.product_vendor_id !== id);
                    writeCart(items);
                    render();
                    updateTotal();
                });
            });

            updateTotal();
            updateCheckoutLink();
        };

        const updateTotal = () => {
            const items = readCart();
            const total = items.reduce((sum, it) => sum + ((it.harga || 0) * (it.qty || 1)), 0);
            document.getElementById('total-price').textContent = `Rp ${formatRupiah(total)}`;
        };

        const updateCheckoutLink = () => {
            const items = readCart();
            const vendors = Array.from(new Set(items.map(i => i.vendor_id).filter(Boolean)));
            const a = document.getElementById('to-checkout');
            if (!a) return;
            if (vendors.length === 1) {
                a.href = `{{ route('checkout') }}?vendor_id=${vendors[0]}`;
            } else {
                a.href = `{{ route('checkout') }}`;
            }
        };

        const pay = async () => {
            const raw = readCart();
            if (raw.length === 0) return alert('Keranjang kosong');
            const errors = [];
            raw.forEach(it => {
                const q = parseInt(it.qty || '1', 10);
                if (!Number.isFinite(q) || q <= 0) errors.push(`Qty tidak valid untuk ${it.nama_produk || 'produk'}`);
                const h = parseInt(it.harga || '0', 10);
                if (!Number.isFinite(h) || h <= 0) errors.push(`Harga belum ditentukan untuk ${it.nama_produk || 'produk'}`);
            });
            const vendors = Array.from(new Set(raw.map(i => i.vendor_id).filter(Boolean)));
            if (vendors.length > 1) errors.push('Semua item harus dari vendor yang sama');
            if (errors.length > 0) return alert(errors.join('\n'));
            const vendorId = vendors.length === 1 ? vendors[0] : null;
            const items = raw.map(it => ({ product_vendor_id: it.product_vendor_id, qty: Math.max(1, parseInt(it.qty || '1', 10)) }));
            const btn = document.getElementById('pay-btn');
            if (btn) btn.disabled = true;
            const res = await fetch('{{ route('payments.snap') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ items, vendor_id: vendorId })
            });
            const data = await res.json();
            if (!data.snap_token) {
                if (btn) btn.disabled = false;
                const err = (data && (data.message || (data.midtrans && (data.midtrans.error_messages || (data.midtrans.body && data.midtrans.body.error_messages))))) || null;
                const msg = Array.isArray(err) ? err.join('\n') : (err || 'Gagal membuat transaksi');
                console.error('Midtrans error:', data);
                return alert(msg);
            }
            window.snap.pay(data.snap_token, {
                onSuccess: function(result) { 
                    const code = (result && result.order_id) ? result.order_id : '';
                    window.location.href = '{{ route('payment.success') }}' + (code ? ('?code=' + encodeURIComponent(code)) : '');
                },
                onPending: function(result) { if (btn) btn.disabled = false; },
                onError: function(result) { if (btn) btn.disabled = false; alert('Terjadi kesalahan pembayaran'); },
                onClose: function() { if (btn) btn.disabled = false; }
            });
        };

        document.getElementById('pay-btn').addEventListener('click', pay);

        render();
    </script>
@endsection
