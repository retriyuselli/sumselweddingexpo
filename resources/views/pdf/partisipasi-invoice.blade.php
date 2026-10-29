@php
    $hargaJual = (int) ($partisipasi->harga_jual ?? 0);
    $diskon = (int) ($partisipasi->diskon ?? 0);
    $barterNominal = $partisipasi->is_barter ? (int) ($partisipasi->barter_nominal ?? 0) : 0;
    $hargaBersih = $partisipasi->hitungHargaBersih();
    $tagihanAkhir = $hargaBersih;
    $totalBayar = (int) ($partisipasi->total_pembayaran ?? $partisipasi->dataPembayarans->sum('nominal'));
    $sisaBayar = max(0, $tagihanAkhir - $totalBayar);
    $status = $partisipasi->statusPembayaranEfektif($totalBayar);
    $statusBg = match ($status) {
        'Lunas' => '#e7f6ec',
        'DP', 'Cicilan' => '#f8efd8',
        default => '#f8e8e6',
    };
    $statusColor = match ($status) {
        'Lunas' => '#1f6b3a',
        'DP', 'Cicilan' => '#8a5a12',
        default => '#8d2d32',
    };
    $paket = $partisipasi->categoryTenant?->category?->label() ?? '-';
    $booth = $partisipasi->tenantSpot?->kode_booth
        ?? ($partisipasi->blok_tenant ? 'Preferensi: '.$partisipasi->blok_tenant : '-');
    $ukuran = $partisipasi->categoryTenant?->ukuran;
    $itemLabel = 'Sewa Booth '.$paket;
    if ($ukuran) {
        $itemLabel .= ' ('.$ukuran.')';
    }
    $tanggalInvoice = ($partisipasi->tanggal_booking ?? $generatedAt)->format('d M Y');
    $periodeExpo = $expo?->tanggal_mulai
        ? $expo->tanggal_mulai->format('d M Y').($expo->tanggal_selesai ? ' – '.$expo->tanggal_selesai->format('d M Y') : '')
        : null;

    $metodeLabels = [
        'transfer' => 'Transfer',
        'cash' => 'Cash',
        'qris' => 'QRIS',
        'cek' => 'Cek',
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Invoice {{ $invoiceNumber }}</title>
<style>
@page { margin: 0 0 58px 0; }
* { margin: 0; padding: 0; }
body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #35252a; line-height: 1.5; }
table { border-collapse: collapse; }
.band { width: 100%; background: #641b2a; color: #fff8f3; }
.band td { vertical-align: middle; padding: 22px 36px; }
.kicker { font-size: 8px; letter-spacing: 1.6px; color: #e6c7a2; text-transform: uppercase; }
.organizer { font-size: 13px; font-weight: bold; color: #fff8f3; margin-top: 4px; }
.doc-kicker { font-size: 8px; letter-spacing: 2px; color: #e6c7a2; text-align: right; }
.doc-title { font-size: 28px; font-weight: bold; letter-spacing: 3px; color: #fff8f3; text-align: right; line-height: 1.1; }
.doc-no { font-size: 9px; color: #f3ddc4; text-align: right; margin-top: 6px; }
.gold { height: 4px; background: #b78a54; }
.pad { padding: 22px 36px 0; }
.card { width: 100%; background: #faf7f2; border: 1px solid #e6d8c8; }
.card td { vertical-align: top; padding: 16px 18px; }
.label { font-size: 8px; letter-spacing: 1.1px; text-transform: uppercase; color: #8a7164; margin-bottom: 6px; }
.name { font-size: 15px; font-weight: bold; color: #641b2a; margin-bottom: 6px; }
.muted { font-size: 9px; color: #6d584f; line-height: 1.7; }
.meta { width: 100%; }
.meta td { padding: 3px 0; font-size: 9px; vertical-align: top; }
.meta .k { color: #8a7164; width: 38%; }
.meta .v { font-weight: bold; color: #35252a; text-align: right; }
.badge { font-size: 8px; font-weight: bold; letter-spacing: .6px; padding: 3px 8px; }
.items { width: 100%; }
.items th { background: #641b2a; color: #fff8f3; font-size: 8px; letter-spacing: .7px; text-transform: uppercase; padding: 9px 8px; text-align: left; }
.items td { padding: 12px 8px; font-size: 9px; border-bottom: 1px solid #eadfd3; vertical-align: top; }
.items td.desc { font-weight: bold; color: #641b2a; }
.sub { font-size: 8px; font-weight: normal; color: #8a7164; margin-top: 4px; line-height: 1.6; }
.r { text-align: right; }
.c { text-align: center; }
.section-title { font-size: 8px; font-weight: bold; letter-spacing: 1.1px; text-transform: uppercase; color: #641b2a; margin-bottom: 8px; }
.note { font-size: 9px; color: #5c463e; line-height: 1.7; }
.totals { width: 100%; }
.totals td { padding: 5px 8px; font-size: 9px; color: #5c463e; }
.totals td.r { text-align: right; white-space: nowrap; }
.totals .sum td { background: #f4ebe1; color: #641b2a; font-weight: bold; font-size: 11px; padding: 9px 8px; }
.totals .due td { background: #641b2a; color: #fff8f3; font-weight: bold; font-size: 11px; padding: 11px 8px; }
.payments { width: 100%; }
.payments th { background: #f4ebe1; color: #641b2a; font-size: 7px; letter-spacing: .5px; text-transform: uppercase; text-align: left; padding: 7px 6px; }
.payments td { font-size: 8px; padding: 8px 6px; border-bottom: 1px solid #eadfd3; vertical-align: top; }
.thanks { font-size: 13px; font-weight: bold; color: #641b2a; }
.sign { width: 170px; margin-left: auto; margin-top: 28px; border-top: 1px solid #b78a54; padding-top: 6px; text-align: center; font-size: 8px; color: #6d584f; }
.footer { position: fixed; left: 36px; right: 36px; bottom: 16px; border-top: 2px solid #b78a54; padding-top: 7px; color: #6d584f; }
.footer td { font-size: 7px; vertical-align: top; padding-right: 10px; }
.footer .fk { display: block; font-size: 6px; letter-spacing: .7px; text-transform: uppercase; color: #a08878; margin-bottom: 2px; }
tr { page-break-inside: avoid; }
thead { display: table-header-group; }
</style>
</head>
<body>

<div class="footer">
    <table width="100%">
        <tr>
            @if($penyelenggara?->no_tlp)
            <td><span class="fk">Telepon</span>{{ $penyelenggara->no_tlp }}</td>
            @endif
            @if($penyelenggara?->email)
            <td><span class="fk">Email</span>{{ $penyelenggara->email }}</td>
            @endif
            @if($penyelenggara?->instagram)
            <td><span class="fk">Instagram</span>{{ $penyelenggara->instagram }}</td>
            @endif
            @if($penyelenggara?->alamat)
            <td><span class="fk">Alamat</span>{{ $penyelenggara->alamat }}</td>
            @endif
        </tr>
    </table>
</div>

<table class="band">
    <tr>
        <td style="width:58%;">
            @if($logoBase64)
                <img src="{{ $logoBase64 }}" alt="{{ $penyelenggara->name ?? 'Sumsel Wedding Expo' }}" style="height:42px; width:auto;">
            @else
                <div class="kicker">Sumsel Wedding Expo</div>
            @endif
            <div class="organizer">{{ $penyelenggara->name ?? 'Sumsel Wedding Expo' }}</div>
        </td>
        <td style="width:42%;">
            <div class="doc-kicker">Partisipasi pameran</div>
            <div class="doc-title">INVOICE</div>
            <div class="doc-no">{{ $invoiceNumber }}<br>{{ $tanggalInvoice }}</div>
        </td>
    </tr>
</table>
<div class="gold"></div>

<div class="pad">
    <table class="card">
        <tr>
            <td style="width:52%;">
                <div class="label">Ditagihkan kepada</div>
                <div class="name">{{ $vendor->nama_vendor ?? '-' }}</div>
                <div class="muted">
                    @if($vendor?->nama_pic)PIC: {{ $vendor->nama_pic }}<br>@endif
                    @if($vendor?->email){{ $vendor->email }}<br>@endif
                    @if($vendor?->no_telepon || $vendor?->no_wa_pic){{ $vendor->no_telepon ?: $vendor->no_wa_pic }}<br>@endif
                    @if($vendor?->alamat || $vendor?->kota){{ collect([$vendor->alamat, $vendor->kota])->filter()->implode(', ') }}@endif
                </div>
            </td>
            <td style="width:48%; background:#fffdf9;">
                <table class="meta">
                    <tr><td class="k">Expo</td><td class="v">{{ $expo->nama_expo ?? '-' }}</td></tr>
                    <tr><td class="k">Periode</td><td class="v">{{ $expo->periode ?? '-' }}</td></tr>
                    <tr><td class="k">Booth</td><td class="v">{{ $booth }}</td></tr>
                    <tr><td class="k">Paket</td><td class="v">{{ $paket }}</td></tr>
                    <tr>
                        <td class="k">Status</td>
                        <td class="v"><span class="badge" style="background:{{ $statusBg }}; color:{{ $statusColor }};">{{ strtoupper($status) }}</span></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>

<div class="pad">
    <table class="items">
        <thead>
            <tr>
                <th style="width:46%;">Deskripsi</th>
                <th style="width:12%;" class="c">Jml</th>
                <th style="width:21%;" class="r">Harga</th>
                <th style="width:21%;" class="r">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="desc">
                    {{ $itemLabel }}
                    <div class="sub">
                        {{ $expo->nama_expo ?? '' }}
                        @if($periodeExpo) · {{ $periodeExpo }} @endif
                        @if($expo?->lokasi) · {{ $expo->lokasi }} @endif
                    </div>
                </td>
                <td class="c">1</td>
                <td class="r">Rp {{ number_format($hargaJual, 0, ',', '.') }}</td>
                <td class="r">Rp {{ number_format($hargaJual, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="pad">
    <table width="100%">
        <tr>
            <td style="width:52%; vertical-align:top; padding-right:18px;">
                <div class="section-title">Pembayaran dapat dilakukan melalui</div>
                <div class="note">
                    @forelse($rekeningTujuans as $i => $rekening)
                        {{ $i + 1 }}. {{ $rekening->nama_bank }} — {{ $rekening->nomor_rekening }}<br>
                        &nbsp;&nbsp;&nbsp;a.n. {{ $rekening->nama_pemilik }}
                        @if(! $loop->last)<br>@endif
                    @empty
                        —
                    @endforelse
                </div>
                @if($partisipasi->keterangan || $barterNominal > 0)
                    <div class="section-title" style="margin-top:14px;">Keterangan</div>
                    <div class="note">
                        @if($partisipasi->keterangan){{ $partisipasi->keterangan }}@endif
                        @if($barterNominal > 0)
                            @if($partisipasi->keterangan)<br>@endif
                            <strong>Barter:</strong> Rp {{ number_format($barterNominal, 0, ',', '.') }}
                            @if($partisipasi->barter_description)<br>{{ $partisipasi->barter_description }}@endif
                            <br><span style="color:#a08878;">Nilai barter tidak termasuk pembayaran tunai ke perusahaan.</span>
                        @endif
                    </div>
                @endif
            </td>
            <td style="width:48%; vertical-align:top;">
                <table class="totals">
                    <tr>
                        <td>Harga jual</td>
                        <td class="r">Rp {{ number_format($hargaJual, 0, ',', '.') }}</td>
                    </tr>
                    @if($diskon > 0)
                    <tr>
                        <td>Diskon</td>
                        <td class="r">- Rp {{ number_format($diskon, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                    @if($barterNominal > 0)
                    <tr>
                        <td>Barter</td>
                        <td class="r">- Rp {{ number_format($barterNominal, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                    <tr class="sum">
                        <td>Total tagihan</td>
                        <td class="r">Rp {{ number_format($tagihanAkhir, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Sudah dibayar</td>
                        <td class="r">Rp {{ number_format($totalBayar, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="due">
                        <td>Sisa tagihan</td>
                        <td class="r">Rp {{ number_format($sisaBayar, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>

@if($partisipasi->dataPembayarans->isNotEmpty())
<div class="pad">
    <div class="section-title">Riwayat pembayaran</div>
    <table class="payments">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Pembayar</th>
                <th>Metode</th>
                <th>Rekening tujuan</th>
                <th class="r">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($partisipasi->dataPembayarans as $bayar)
            @php
                $metode = $metodeLabels[$bayar->metode_pembayaran] ?? ($bayar->metode_pembayaran ? strtoupper($bayar->metode_pembayaran) : '-');
                $rek = $bayar->rekeningTujuan;
                $rekLabel = $rek ? $rek->nama_bank.' · '.$rek->nomor_rekening : '-';
            @endphp
            <tr>
                <td>{{ $bayar->tanggal_bayar?->format('d M Y') ?? '-' }}</td>
                <td>{{ $bayar->nama_pembayar ?? '-' }}</td>
                <td>{{ $metode }}{{ $bayar->termin_pembayaran ? ' · '.$bayar->termin_pembayaran : '' }}</td>
                <td>{{ $rekLabel }}</td>
                <td class="r">Rp {{ number_format((int) $bayar->nominal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<div class="pad" style="padding-bottom:18px;">
    <table width="100%">
        <tr>
            <td style="width:58%; vertical-align:top; padding-top:10px;">
                <div class="thanks">Terima kasih atas partisipasi Anda.</div>
                <div class="muted" style="margin-top:5px;">Diterbitkan {{ $generatedAt->format('d M Y H:i') }} WIB</div>
            </td>
            <td style="width:42%; vertical-align:top;">
                <div class="sign">{{ $penyelenggara->name ?? 'Sumsel Wedding Expo' }}</div>
            </td>
        </tr>
    </table>
</div>

</body>
</html>
