@php
    $hadirHariIni = $expo->absensis()->whereDate('tanggal', now()->toDateString())->count();
@endphp

<div style="display:flex;flex-direction:column;align-items:center;gap:1rem;padding:0 0.25rem 0.5rem;max-width:100%;">
    <div class="qr-absensi" style="width:min(280px, 100%);">
        {!! $expo->absensiQrSvg() !!}
    </div>
    <p style="width:100%;margin:0;text-align:center;font-size:0.875rem;word-break:break-all;">
        {{ $expo->absensiUrl() }}
    </p>
    <p style="margin:0;font-size:0.875rem;color:#6b7280;">
        Hadir hari ini: {{ $hadirHariIni }}
    </p>
    <a href="{{ route('absensi.qr', $expo) }}" style="display:block;width:100%;max-width:280px;box-sizing:border-box;text-align:center;text-decoration:none;background:#f59e0b;color:#111827;font-weight:700;border-radius:0.75rem;padding:0.75rem 1rem;">
        Unduh QR
    </a>
</div>

<style>
    .qr-absensi svg { width: 100%; height: auto; display: block; }
</style>
