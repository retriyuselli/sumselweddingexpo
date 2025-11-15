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
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl border border-neutral-200 p-4 sm:p-6">
                        <div id="empty-state" class="hidden text-sm text-neutral-600">Keranjang kosong.</div>
                        <table id="cart-table" class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-neutral-200">
                                    <th class="py-2 text-left">Product</th>
                                    <th class="py-2 text-right">Price</th>
                                    <th class="py-2 text-center">Quantity</th>
                                    <th class="py-2 text-right">Subtotal</th>
                                    <th class="py-2 text-center">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody id="cart-items"></tbody>
                        </table>
                        <div class="mt-4 flex items-center gap-2">
                            <input id="coupon" type="text" class="flex-1 rounded-lg border border-neutral-300 px-3 py-2 text-sm" placeholder="Coupon code">
                            <button id="apply-coupon" disabled class="rounded-lg border border-neutral-300 px-3 py-2 text-sm opacity-50 cursor-not-allowed">Apply coupon</button>
                            <button id="update-cart" class="rounded-lg border border-neutral-300 px-3 py-2 text-sm">Update cart</button>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="bg-white rounded-xl border border-neutral-200 p-4 sm:p-6">
                        <h2 class="text-lg font-semibold">Cart totals</h2>
                        <table class="mt-3 w-full text-sm">
                            <tbody>
                                <tr class="border-t border-neutral-200">
                                    <td class="py-2">Subtotal</td>
                                    <td id="subtotal-text" class="py-2 text-right">Rp 0</td>
                                </tr>
                                
                                <tr class="border-t border-neutral-200">
                                    <td class="py-2 font-semibold">Total</td>
                                    <td id="total-text" class="py-2 text-right font-semibold">Rp 0</td>
                                </tr>
                            </tbody>
                        </table>
                        <a id="to-checkout" href="{{ route('checkout') }}" class="mt-4 w-full inline-flex items-center justify-center rounded-lg bg-green-600 text-white text-sm px-4 py-2 hover:bg-green-700">Proceed to checkout</a>
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
            const empty = document.getElementById('empty-state');
            const tbody = document.getElementById('cart-items');
            if (items.length === 0) {
                if (tbody) tbody.innerHTML = '';
                empty.classList.remove('hidden');
                return;
            }
            empty.classList.add('hidden');
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

        const renderTable = () => {
            const items = readCart();
            const empty = document.getElementById('empty-state');
            const tbody = document.getElementById('cart-items');
            if (items.length === 0) {
                if (tbody) tbody.innerHTML = '';
                empty.classList.remove('hidden');
                return;
            }
            empty.classList.add('hidden');
            const rows = items.map(it => {
                const img = it.img ? `<img src="${it.img}" alt="${it.nama_produk || ''}" class="w-14 h-14 rounded object-cover">` : `<div class=\"w-14 h-14 rounded bg-linear-to-br from-blue-400 to-indigo-600 flex items-center justify-center text-white text-xs font-semibold\">${(it.nama_produk||'P').substring(0,1).toUpperCase()}<\/div>`;
                const price = parseInt(it.harga || 0, 10);
                const qty = Math.max(1, parseInt(it.qty || 1, 10));
                const sub = price * qty;
                return `
                    <tr class=\"border-b border-neutral-200\">
                        <td class=\"py-3\"><div class=\"flex items-center gap-3\">${img}<div><p class=\"font-medium\">${it.nama_produk || 'Produk'}<\/p><p class=\"text-xs text-neutral-600\">${it.vendor_nama || '—'}<\/p><\/div><\/div><\/td>
                        <td class=\"py-3 text-right\">Rp ${formatRupiah(price)}<\/td>
                        <td class=\"py-3 text-center\"><input type=\"number\" min=\"1\" value=\"${qty}\" class=\"w-16 rounded-lg border border-neutral-300 px-2 py-1 text-xs qty-input\" data-product-id=\"${it.product_vendor_id}\"><\/td>
                        <td class=\"py-3 text-right\">Rp ${formatRupiah(sub)}<\/td>
                        <td class=\"py-3 text-center\"><button class=\"text-rose-600 remove-btn\" data-product-id=\"${it.product_vendor_id}\">×<\/button><\/td>
                    <\/tr>
                `;
            }).join('');
            if (tbody) tbody.innerHTML = rows;

            document.querySelectorAll('.qty-input').forEach(input => {
                input.addEventListener('change', e => {
                    const id = parseInt(e.target.dataset.productId, 10);
                    const qty = Math.max(1, parseInt(e.target.value || '1', 10));
                    const items = readCart();
                    const idx = items.findIndex(i => i.product_vendor_id === id);
                    if (idx >= 0) {
                        items[idx].qty = qty;
                        writeCart(items);
                        updateTotalsTable();
                    }
                });
            });
            document.querySelectorAll('.remove-btn').forEach(btn => {
                btn.addEventListener('click', e => {
                    const id = parseInt(e.target.dataset.productId, 10);
                    const items = readCart().filter(i => i.product_vendor_id !== id);
                    writeCart(items);
                    renderTable();
                    updateTotalsTable();
                });
            });

            updateTotalsTable();
            updateCheckoutLink();
        };

        const updateTotalsTable = () => {
            const items = readCart();
            const subtotal = items.reduce((sum, it) => sum + ((it.harga || 0) * (it.qty || 1)), 0);
            const st = document.getElementById('subtotal-text');
            const tt = document.getElementById('total-text');
            if (st) st.textContent = 'Rp ' + formatRupiah(subtotal);
            if (tt) tt.textContent = 'Rp ' + formatRupiah(subtotal);
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
                    try {
                        localStorage.removeItem('cartItems');
                        window.dispatchEvent(new Event('storage'));
                    } catch (e) {}
                    const code = (result && result.order_id) ? result.order_id : '';
                    window.location.href = '{{ route('payment.success') }}' + (code ? ('?code=' + encodeURIComponent(code)) : '');
                },
                onPending: function(result) { if (btn) btn.disabled = false; },
                onError: function(result) { if (btn) btn.disabled = false; alert('Terjadi kesalahan pembayaran'); },
                onClose: function() { if (btn) btn.disabled = false; }
            });
        };

        document.getElementById('update-cart').addEventListener('click', () => {
            const items = readCart();
            document.querySelectorAll('.qty-input').forEach(input => {
                const id = parseInt(input.dataset.productId, 10);
                const qty = Math.max(1, parseInt(input.value || '1', 10));
                const idx = items.findIndex(i => i.product_vendor_id === id);
                if (idx >= 0) items[idx].qty = qty;
            });
            writeCart(items);
            renderTable();
        });

        renderTable();
    </script>
@endsection
