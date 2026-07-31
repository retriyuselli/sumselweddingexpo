@extends('layouts.app')

@section('title', 'Bukti Pembayaran — WeddingExpo')

@section('content')
    <main class="min-h-screen bg-gray-50">
        <section class="pt-24 md:pt-28 pb-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-xl border border-neutral-200 p-4 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            @if (!empty($penyelenggara?->logo) && \Illuminate\Support\Facades\Storage::disk('public')->exists($penyelenggara->logo))
                                <img src="{{ asset('storage/'.$penyelenggara->logo) }}" alt="{{ $penyelenggara->name }}" class="h-8 w-auto">
                            @else
                                <div class="h-8 w-8 rounded bg-neutral-900 text-white flex items-center justify-center text-xs font-bold">{{ strtoupper(substr(($penyelenggara->name ?? 'SWE'), 0, 2)) }}</div>
                            @endif
                            <div>
                                <h1 class="text-base sm:text-lg font-bold">Bukti Pembayaran</h1>
                                <p class="text-[10px] sm:text-xs text-neutral-600">{{ $penyelenggara->name ?? 'WeddingExpo' }}</p>
                            </div>
                        </div>
                    </div>

                    @php
                        $order = $payment?->order;
                        $totalAmount = (float) ($order?->amount_total ?? 0);
                        $paidAmount = (float) ($payment?->amount ?? 0);
                        $remainingAmount = max(0, $totalAmount - $paidAmount);
                        $isDp = $paidAmount > 0 && $paidAmount < $totalAmount;
                    @endphp

                    <div class="mt-4 grid grid-cols-2 gap-4 text-xs sm:text-sm">
                        <div>
                            <p class="text-neutral-600">Order ID</p>
                            <p class="font-semibold">{{ $code ?: ($payment->external_id ?? '—') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-neutral-600">Status</p>
                            <p class="font-semibold">{{ $payment?->status ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-neutral-600">Metode</p>
                            <p class="">{{ $payment?->method ?? '—' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-neutral-600">Waktu</p>
                            <p class="">{{ optional($payment?->paid_at)->format('d M Y H:i') ?? '—' }}</p>
                        </div>
                    </div>

                    <div class="mt-4 rounded-xl border border-neutral-200 p-3">
                        <div class="grid grid-cols-2 gap-2 text-xs sm:text-sm">
                            <p class="text-neutral-600">Nominal Dibayar</p>
                            <p class="text-right font-semibold">Rp {{ number_format($paidAmount, 0, ',', '.') }}</p>
                            <p class="text-neutral-600">Total Pesanan</p>
                            <p class="text-right">Rp {{ number_format($totalAmount, 0, ',', '.') }}</p>
                            <p class="text-neutral-600">Sisa Pembayaran</p>
                            <p class="text-right">Rp {{ number_format($remainingAmount, 0, ',', '.') }}</p>
                        </div>
                        @if ($isDp)
                            <p class="mt-2 text-[10px] sm:text-xs text-neutral-600">Transaksi ini adalah Downpayment (DP). Simpan bukti ini dan selesaikan sisa pembayaran sesuai ketentuan.</p>
                        @endif
                    </div>

                    <div class="mt-4">
                        <h2 class="text-sm sm:text-base font-semibold">Detail Pesanan</h2>
                        <div class="mt-2 text-xs sm:text-sm">
                            @foreach (($order?->items ?? collect()) as $it)
                                <div class="py-1 border-b border-neutral-100">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-medium">{{ $it->name_snapshot }}</p>
                                            <p class="text-neutral-600">Qty {{ (int) ($it->qty ?? 0) }} • Harga Rp {{ number_format((float) ($it->price_snapshot ?? 0), 0, ',', '.') }}</p>
                                        </div>
                                        <p class="font-medium">Rp {{ number_format((float) ($it->subtotal ?? 0), 0, ',', '.') }}</p>
                                    </div>
                                    <div class="mt-1 text-[10px] sm:text-xs text-neutral-600">
                                        <p>Vendor: {{ $it->vendor?->nama_vendor ?? '—' }}</p>
                                        <p>Kontak: {{ $it->vendor?->nama_pic ? ('PIC '.$it->vendor->nama_pic) : ($it->vendor?->email ?? '—') }}</p>
                                        <p>No. WA/Telp: {{ $it->vendor?->no_wa_pic ?? ($it->vendor?->no_telepon ?? '—') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-2">
                        <button onclick="window.print()" class="px-3 py-1.5 rounded-lg border border-neutral-300 text-neutral-700 text-xs sm:text-sm hover:bg-neutral-50">Download</button>
                        <a href="{{ route('payment.success') }}?code={{ urlencode($code) }}" class="px-3 py-1.5 rounded-lg border border-neutral-300 text-neutral-700 text-xs sm:text-sm hover:bg-neutral-50">Kembali</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <style>
        @media print {
            html { font-size: 12px; }
            @page { size: A5 portrait; margin: 10mm; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; background: #fff !important; }
            .bg-gray-50, main { background: #fff !important; }
            .rounded-xl { border-radius: 0; }
            .border { border-color: #ddd !important; }
            .hover\:bg-neutral-50 { background: none !important; }
            button, a { display: none !important; }
            .text-\[10px\] { font-size: 9px !important; }
        }
    </style>
@endsection