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
        $lastOrder = auth()->check()
            ? \App\Models\Order::where('customer_id', auth()->id())
                ->orderByDesc('id')
                ->first()
            : null;
        $lastCountry = $lastOrder ? ($lastOrder->billing_country ?: 'Indonesia') : 'Indonesia';
        $lastProvince = $lastOrder ? ($lastOrder->billing_province ?: 'Sumatera Selatan') : 'Sumatera Selatan';
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
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl border border-neutral-200 p-4 sm:p-6">
                        <h2 class="text-lg font-semibold">Billing details</h2>
                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm">First name *</label>
                                <input id="bill-first" type="text"
                                    class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm"
                                    placeholder="Nama depan" value="{{ $lastOrder->billing_first_name ?? '' }}">
                            </div>
                            <div>
                                <label class="text-sm">Last name *</label>
                                <input id="bill-last" type="text"
                                    class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm"
                                    placeholder="Nama belakang" value="{{ $lastOrder->billing_last_name ?? '' }}">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="text-sm">Company name (optional)</label>
                                <input id="bill-company" type="text"
                                    class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm"
                                    placeholder="Perusahaan" value="{{ $lastOrder->billing_company ?? '' }}">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="text-sm">Country / Region *</label>
                                <select id="bill-country"
                                    class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm">
                                    <option value="{{ $lastCountry }}" selected>
                                        {{ strtoupper($lastCountry) === 'IDN' || strtoupper($lastCountry) === 'ID' ? 'Indonesia' : $lastCountry }}
                                    </option>
                                </select>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="text-sm">Street address *</label>
                                <input id="bill-street" type="text"
                                    class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm"
                                    placeholder="Nama jalan dan nomor rumah" value="{{ $lastOrder->billing_street ?? '' }}">
                                <input id="bill-apt" type="text"
                                    class="mt-2 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm"
                                    placeholder="Apartemen, suite, unit (opsional)"
                                    value="{{ $lastOrder->billing_apt ?? '' }}">
                            </div>
                            <div>
                                <label class="text-sm">Town / City *</label>
                                <input id="bill-city" type="text"
                                    class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm"
                                    value="{{ $lastOrder->billing_city ?? '' }}">
                            </div>
                            <div>
                                <label class="text-sm">Province *</label>
                                <select id="bill-province"
                                    class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm">
                                    <option value="{{ $lastProvince }}" selected>{{ $lastProvince }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-sm">Postcode / ZIP *</label>
                                <input id="bill-postcode" type="text"
                                    class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm"
                                    value="{{ $lastOrder->billing_postcode ?? '' }}">
                            </div>
                            <div>
                                <label class="text-sm">Phone *</label>
                                <input id="bill-phone" type="text"
                                    class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm"
                                    value="{{ $lastOrder->billing_phone ?? '' }}">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="text-sm">Email address *</label>
                                <input id="bill-email" type="email"
                                    class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm"
                                    value="{{ $lastOrder->billing_email ?? '' }}">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="inline-flex items-center gap-2 text-sm opacity-60 cursor-not-allowed">
                                    <input id="ship-diff" type="checkbox" class="rounded" disabled>
                                    Ship to a different address?
                                </label>
                                <p class="mt-1 text-[12px] text-neutral-600">Produk berupa jasa, pengiriman fisik tidak
                                    diperlukan.</p>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="text-sm">Order notes (optional)</label>
                                <textarea id="order-notes" class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm" rows="4"
                                    placeholder="Catatan untuk pesanan">{{ $lastOrder->notes ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="bg-white rounded-xl border border-neutral-200 p-4 sm:p-6">
                        <h2 class="text-lg font-semibold">Your order</h2>
                        <div id="order-empty" class="mt-3 text-sm text-neutral-600 hidden">Keranjang kosong.</div>
                        <table id="order-table" class="mt-3 w-full text-sm">
                            <thead>
                                <tr class="border-b border-neutral-200">
                                    <th class="py-2 text-left">Product</th>
                                    <th class="py-2 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="order-items"></tbody>
                            <tfoot>
                                <tr class="border-t border-neutral-200">
                                    <td class="py-2 text-xs sm:text-sm">Subtotal</td>
                                    <td id="subtotal-text" class="py-2 text-right text-xs sm:text-sm">Rp 0</td>
                                </tr>

                                <tr class="border-t border-neutral-200">
                                    <td class="py-2 text-xs sm:text-sm">Downpayment dibayar</td>
                                    <td id="dp-text" class="py-2 text-right text-xs sm:text-sm">Rp 0</td>
                                </tr>

                                <tr class="border-t border-neutral-200">
                                    <td class="py-2 text-xs sm:text-sm">Sisa pembayaran</td>
                                    <td id="remaining-text" class="py-2 text-right text-xs sm:text-sm">Rp 0</td>
                                </tr>

                                <tr class="border-t border-neutral-200">
                                    <td class="py-2 font-semibold">Total</td>
                                    <td id="total-text" class="py-2 text-right font-semibold">Rp 0</td>
                                </tr>
                            </tfoot>
                        </table>

                        <div class="mt-4 flex items-center gap-2 flex-wrap">
                            <input id="coupon" type="text"
                                class="w-full md:w-2/3 min-w-0 rounded-lg border border-neutral-300 px-3 py-2 text-sm"
                                placeholder="Coupon code">
                            <button id="apply-coupon"
                                class="shrink-0 rounded-lg border border-neutral-300 px-3 py-2 text-sm">Apply
                                coupon</button>
                        </div>

                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <p class="text-xs text-neutral-600">Metode pembayaran</p>
                                <div class="mt-2 flex items-center gap-4">
                                    <label class="flex items-center gap-2 text-xs sm:text-sm">
                                        <input type="radio" name="payment-mode" id="mode-dp" value="dp" class="rounded" />
                                        Bayar DP
                                    </label>
                                    <label class="flex items-center gap-2 text-xs sm:text-sm">
                                        <input type="radio" name="payment-mode" id="mode-full" value="full" class="rounded" />
                                        Bayar Lunas
                                    </label>
                                </div>
                            </div>
                            <div id="payment-info" class="rounded border border-blue-100 bg-blue-50 p-3 text-xs text-neutral-700">
                                Sistem menggunakan Downpayment (DP). Nominal yang dibayarkan sekarang adalah DP, sedangkan sisa pembayaran dapat diselesaikan sesuai ketentuan.
                            </div>
                        </div>
                        <div class="mt-4 rounded border border-blue-100 bg-blue-50 p-3 text-xs text-neutral-700">
                            Sistem menggunakan Downpayment (DP). Nominal yang dibayarkan sekarang adalah DP, sedangkan sisa
                            pembayaran dapat diselesaikan sesuai ketentuan.
                        </div>
                        <button id="pay-btn"
                            class="mt-4 w-full rounded-lg bg-green-600 text-white text-sm px-4 py-2 hover:bg-green-700">Place
                            order</button>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="{{ $snapBase }}" data-client-key="{{ $clientKey }}"></script>
    <script>
        const csrfToken = '{{ csrf_token() }}';
        const dpFixedGlobal = {{ (float) (config('services.midtrans.dp_fixed') ?? 0) }};
        const readCart = () => JSON.parse(localStorage.getItem('cartItems') || '[]');
        const formatRupiah = (n) => new Intl.NumberFormat('id-ID').format(n || 0);
        const dpApplicable = (dpFixedGlobal > 0) || readCart().some(it => (parseInt(it.dp_fixed || '0', 10) > 0));
        let paymentMode = dpApplicable ? 'dp' : 'full';

        function renderSummary() {
            const items = readCart();
            const tbody = document.getElementById('order-items');
            const empty = document.getElementById('order-empty');
            const table = document.getElementById('order-table');
            if (!tbody) return;
            if (items.length === 0) {
                empty.classList.remove('hidden');
                table.classList.add('hidden');
                document.getElementById('subtotal-text').textContent = 'Rp 0';
                document.getElementById('total-text').textContent = 'Rp 0';
                return;
            }
            empty.classList.add('hidden');
            table.classList.remove('hidden');
            tbody.innerHTML = items.map(it => {
                const name = (it.nama_produk || 'Produk') + ' × ' + (it.qty || 1);
                const sub = (it.harga || 0) * (it.qty || 1);
                return `<tr><td class="py-2 text-xs sm:text-sm">${name}</td><td class="py-2 text-right text-xs sm:text-sm">Rp ${formatRupiah(sub)}</td></tr>`;
            }).join('');
            const subtotal = items.reduce((s, it) => s + ((it.harga || 0) * (it.qty || 1)), 0);
            const dpAmountPerItem = items.reduce((sum, it) => {
                const priceLine = (it.harga || 0) * (it.qty || 1);
                const dpFix = parseInt(it.dp_fixed || '0', 10);
                if (dpFix > 0) return sum + Math.min(priceLine, dpFix * (it.qty || 1));
                return sum;
            }, 0);
            const dpAmountGlobal = dpFixedGlobal > 0 ? Math.min(subtotal, Math.round(dpFixedGlobal)) : subtotal;
            const dpAmountCalc = (dpAmountPerItem > 0) ? dpAmountPerItem : dpAmountGlobal;
            const dpAmount = paymentMode === 'dp' ? dpAmountCalc : subtotal;
            const remaining = paymentMode === 'dp' ? Math.max(0, subtotal - dpAmount) : 0;
            document.getElementById('subtotal-text').textContent = 'Rp ' + formatRupiah(subtotal);
            document.getElementById('dp-text').textContent = 'Rp ' + formatRupiah(dpAmount);
            document.getElementById('remaining-text').textContent = 'Rp ' + formatRupiah(remaining);
            document.getElementById('total-text').textContent = 'Rp ' + formatRupiah(dpAmount);
        }
        renderSummary();
        const payBtn = document.getElementById('pay-btn');

        const modeDp = document.getElementById('mode-dp');
        const modeFull = document.getElementById('mode-full');
        const infoBox = document.getElementById('payment-info');
        if (modeDp && modeFull) {
            if (dpApplicable) {
                modeDp.checked = true;
                infoBox.textContent = 'Anda memilih bayar DP. Nominal yang ditagihkan sekarang adalah DP; sisa pembayaran dapat diselesaikan kemudian.';
            } else {
                modeFull.checked = true;
                infoBox.textContent = 'DP tidak tersedia. Pembayaran akan ditagihkan penuh.';
            }
            modeDp.addEventListener('change', () => {
                paymentMode = 'dp';
                infoBox.textContent = 'Anda memilih bayar DP. Nominal yang ditagihkan sekarang adalah DP; sisa pembayaran dapat diselesaikan kemudian.';
                renderSummary();
            });
            modeFull.addEventListener('change', () => {
                paymentMode = 'full';
                infoBox.textContent = 'Anda memilih bayar lunas. Nominal yang ditagihkan adalah total pesanan.';
                renderSummary();
            });
        }

        function validateBilling() {
            const required = ['bill-first', 'bill-last', 'bill-country', 'bill-street', 'bill-city', 'bill-province',
                'bill-postcode', 'bill-phone', 'bill-email'
            ];
            const missing = required.filter(id => !document.getElementById(id) || !document.getElementById(id).value
                .trim());
            if (missing.length) {
                alert('Lengkapi data Billing details');
                return false;
            }
            return true;
        }

        function collectBilling() {
            return {
                first_name: document.getElementById('bill-first').value.trim(),
                last_name: document.getElementById('bill-last').value.trim(),
                company: document.getElementById('bill-company').value.trim(),
                country: document.getElementById('bill-country').value.trim() || 'Indonesia',
                street: document.getElementById('bill-street').value.trim(),
                apt: document.getElementById('bill-apt').value.trim(),
                city: document.getElementById('bill-city').value.trim(),
                province: document.getElementById('bill-province').value.trim(),
                postcode: document.getElementById('bill-postcode').value.trim(),
                phone: document.getElementById('bill-phone').value.trim(),
                email: document.getElementById('bill-email').value.trim(),
                notes: document.getElementById('order-notes').value.trim(),
            };
        }
        if (payBtn) payBtn.addEventListener('click', async () => {
            if (!validateBilling()) return;
            const items = [];
            const savedCart = readCart();
            savedCart.forEach(it => items.push({
                product_vendor_id: it.product_vendor_id,
                qty: it.qty || 1
            }));
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
                    vendor_id: {{ $vendor ? $vendor->id : 'null' }},
                    billing: collectBilling(),
                    payment_mode: paymentMode,
                })
            });
            const data = await res.json();
            if (!data.snap_token) {
                const err = (data && (data.message || (data.midtrans && (data.midtrans.error_messages || (data
                    .midtrans.body && data.midtrans.body.error_messages))))) || null;
                const msg = Array.isArray(err) ? err.join('\n') : (err || 'Gagal membuat transaksi');
                console.error('Midtrans error:', data);
                alert(msg);
                return;
            }
            window.snap.pay(data.snap_token, {
                onSuccess: function(result) {
                    try {
                        localStorage.removeItem('cartItems');
                        window.dispatchEvent(new Event('storage'));
                    } catch (e) {}
                    const code = (result && result.order_id) ? result.order_id : '';
                    window.location.href = '{{ route('payment.success') }}' + (code ? ('?code=' +
                        encodeURIComponent(code)) : '');
                },
                onPending: function(result) {
                    console.log(result);
                },
                onError: function(result) {
                    console.error(result);
                    alert('Terjadi kesalahan pembayaran');
                },
                onClose: function() {
                    console.log('Popup ditutup');
                }
            });
        });
    </script>
@endsection
