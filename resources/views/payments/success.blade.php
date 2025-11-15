@extends('layouts.app')

@section('title', 'Pembayaran Berhasil — WeddingExpo')

@section('content')
    <main class="min-h-screen bg-gray-50">
        <section class="pt-24 md:pt-28 pb-10 bg-linear-to-r from-green-50 to-emerald-50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h1 class="text-xl sm:text-2xl md:text-3xl font-bold">Pembayaran Berhasil</h1>
                <p class="text-xs sm:text-sm text-neutral-600 mt-1">Ringkasan transaksi Midtrans</p>
            </div>
        </section>

        <section class="py-8 sm:py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-xl border border-neutral-200 p-4 sm:p-6">
                    <div class="flex items-start justify-between gap-6">
                        <div>
                            <p class="text-sm text-neutral-600">Order ID</p>
                            <p class="text-base font-semibold">{{ $code ?: ($payment->external_id ?? '—') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-neutral-600">Status</p>
                            <p id="status-text" class="text-base font-semibold">{{ $payment?->status ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-neutral-600">Metode</p>
                            <p id="method-text" class="text-base">{{ $payment?->method ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-neutral-600">Nominal</p>
                            <p id="amount-text" class="text-base">Rp {{ number_format((float) ($payment?->amount ?? 0), 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-neutral-600">Waktu</p>
                            <p id="time-text" class="text-base">{{ optional($payment?->paid_at)->format('d M Y H:i') ?? '—' }}</p>
                        </div>
                    </div>
                    @php
                        $order = $payment?->order;
                        $items = $order?->items ?? collect();
                    @endphp
                    @if ($order && $items->count())
                        <div class="mt-6">
                            <h2 class="text-base font-semibold">Detail Pesanan</h2>
                            <table class="mt-3 w-full text-sm">
                                <thead>
                                    <tr class="border-b border-neutral-200">
                                        <th class="py-2 text-left">Produk</th>
                                        <th class="py-2 text-center">Qty</th>
                                        <th class="py-2 text-right">Harga</th>
                                        <th class="py-2 text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $it)
                                        <tr class="border-b border-neutral-100">
                                            <td class="py-2">{{ $it->name_snapshot }}</td>
                                            <td class="py-2 text-center">{{ (int) ($it->qty ?? 0) }}</td>
                                            <td class="py-2 text-right">Rp {{ number_format((float) ($it->price_snapshot ?? 0), 0, ',', '.') }}</td>
                                            <td class="py-2 text-right">Rp {{ number_format((float) ($it->subtotal ?? 0), 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="border-t border-neutral-200">
                                        <td class="py-2 font-semibold" colspan="3">Total</td>
                                        <td class="py-2 text-right font-semibold">Rp {{ number_format((float) ($order->amount_total ?? 0), 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-neutral-600">Nama</p>
                                    <p class="text-sm">{{ trim(($order->billing_first_name ?? '').' '.($order->billing_last_name ?? '')) ?: '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-neutral-600">Kontak</p>
                                    <p class="text-sm">{{ $order->billing_phone ?? '—' }} • {{ $order->billing_email ?? '—' }}</p>
                                </div>
                                <div class="sm:col-span-2">
                                    <p class="text-sm text-neutral-600">Alamat</p>
                                    <p class="text-sm">{{ trim(($order->billing_street ?? '').' '.($order->billing_apt ?? '')) ?: '—' }}, {{ $order->billing_city ?? '' }}{{ $order->billing_city ? ',' : '' }} {{ $order->billing_province ?? '' }} {{ $order->billing_postcode ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="mt-6 flex items-center gap-3">
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-lg bg-neutral-900 text-white text-sm hover:bg-neutral-800">Kembali ke Dashboard</a>
                        <a href="{{ route('cart') }}" class="px-4 py-2 rounded-lg border border-neutral-300 text-neutral-700 text-sm hover:bg-neutral-50">Lihat Keranjang</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <script>
        const code = @json($code);
        const formatRupiah = (n) => new Intl.NumberFormat('id-ID').format(n || 0);
        async function refreshStatus() {
            try {
                // Try refresh from Midtrans if still pending
                const rs = await fetch('{{ route('payments.refresh') }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ code }) });
                const res = await fetch('{{ route('payments.status') }}?code=' + encodeURIComponent(code), { headers: { 'Accept': 'application/json' } });
                if (!res.ok) return;
                const data = await res.json();
                document.getElementById('status-text').textContent = data.status || '—';
                document.getElementById('method-text').textContent = data.method || '—';
                document.getElementById('amount-text').textContent = 'Rp ' + formatRupiah(parseFloat(data.amount || 0));
                const t = data.paid_at ? new Date(data.paid_at) : null;
                document.getElementById('time-text').textContent = t ? t.toLocaleString('id-ID', { hour12: false }) : '—';
                if ((data.status || '').toLowerCase() === 'pending') {
                    setTimeout(refreshStatus, 3000);
                }
            } catch (e) {}
        }
        refreshStatus();
    </script>
@endsection