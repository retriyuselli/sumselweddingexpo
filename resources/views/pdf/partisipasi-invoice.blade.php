@php
    $accent = '#003D79'; // Bank Mandiri Blue (RGB 0, 61, 121)
    $hargaJual = (int) ($partisipasi->harga_jual ?? 0);
    $diskon = (int) ($partisipasi->diskon ?? 0);
    $barterNominal = $partisipasi->is_barter ? (int) ($partisipasi->barter_nominal ?? 0) : 0;
    // Harga bersih sudah = harga jual - diskon - barter
    $hargaBersih = $partisipasi->hitungHargaBersih();
    $tagihanAkhir = $hargaBersih;
    $totalBayar = (int) ($partisipasi->total_pembayaran ?? $partisipasi->dataPembayarans->sum('nominal'));
    $sisaBayar = max(0, $tagihanAkhir - $totalBayar);
    $status = $tagihanAkhir === 0 || $totalBayar >= $tagihanAkhir
        ? 'Lunas'
        : ($partisipasi->status_pembayaran ?? 'Belum Lunas');
    $statusBg = match ($status) {
        'Lunas' => '#dcfce7',
        'DP', 'Cicilan' => '#fef9c3',
        default => '#fee2e2',
    };
    $statusColor = match ($status) {
        'Lunas' => '#166534',
        'DP', 'Cicilan' => '#854d0e',
        default => '#991b1b',
    };
    $paket = $partisipasi->categoryTenant?->category?->label() ?? '-';
    $booth = $partisipasi->tenantSpot?->kode_booth
        ?? ($partisipasi->blok_tenant ? 'Preferensi: '.$partisipasi->blok_tenant : '-');
    $ukuran = $partisipasi->categoryTenant?->ukuran;
    $itemLabel = 'Sewa Booth '.$paket;
    if ($ukuran) {
        $itemLabel .= ' ('.$ukuran.')';
    }

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
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:'DejaVu Sans', sans-serif; font-size:10px; color:#1a1a1a; background:#fff; }

.row  { display:table; width:100%; }
.col  { display:table-cell; vertical-align:top; }
.col-r{ display:table-cell; vertical-align:top; text-align:right; }

.deco-bar {
    position:relative;
    height: 68px;
    background:#fff;
    overflow:hidden;
    margin-bottom: 4px;
}
.deco-pill {
    position:absolute;
    top:-20px; right:-30px;
    width:320px; height:100px;
    background: {{ $accent }};
    border-radius:60px;
}
.deco-dot {
    position:absolute;
    top:-28px; left:-24px;
    width:90px; height:90px;
    background: {{ $accent }};
    border-radius:50%;
}

.brand-wrap  { padding: 0 52px 16px; border-bottom: 1px solid #e5e7eb; }
.brand-name  { font-size:13px; font-weight:bold; color:#1a1a1a; letter-spacing:0.5px; }
.brand-tagline { font-size:8px; color:#6b7280; margin-top:2px; }

.invoice-title {
    font-size:44px;
    font-weight:bold;
    color:#1a1a1a;
    letter-spacing:1px;
    padding: 20px 52px 0;
}

.bill-section { padding: 18px 52px 0; }
.label-sm  { font-size:9px; color:#9ca3af; margin-bottom:3px; }
.name-lg   { font-size:13px; font-weight:bold; color:#1a1a1a; margin-bottom:3px; }
.text-muted { font-size:9.5px; color:#6b7280; line-height:1.7; }

.meta-table { border-collapse:collapse; width:100%; }
.meta-table td { padding: 4px 0; font-size:10px; vertical-align:top; }
.meta-table td.ml { color:#6b7280; white-space:nowrap; width:1px; }
.meta-table td.mc { color:#6b7280; padding:4px 8px 0; width:1px; white-space:nowrap; }
.meta-table td.mv { font-weight:bold; color:#1a1a1a; text-align:right; }

.badge {
    display:inline-block;
    padding: 2px 9px;
    border-radius:20px;
    font-size:8px;
    font-weight:bold;
    text-transform:uppercase;
    letter-spacing:0.5px;
}

.items-wrap { padding: 22px 52px 0; }
table.items { width:100%; border-collapse:collapse; }
table.items thead tr { border-bottom: 1.5px solid #1a1a1a; }
table.items thead th {
    padding: 7px 0;
    font-size:9px;
    font-weight:bold;
    text-transform:uppercase;
    letter-spacing:0.6px;
    color:#1a1a1a;
    text-align:left;
}
table.items thead th.r { text-align:right; }
table.items thead th.c { text-align:center; }
table.items tbody tr { border-bottom:1px solid #e5e7eb; }
table.items tbody td { padding:10px 0; font-size:10px; color:#374151; vertical-align:top; }
table.items tbody td.r { text-align:right; }
table.items tbody td.c { text-align:center; }
table.items tbody td .sub { font-size:8.5px; color:#9ca3af; margin-top:2px; }

.bottom-wrap { padding: 20px 52px 0; }
.pay-method-title { font-size:9px; font-weight:bold; text-transform:uppercase; letter-spacing:0.8px; color:#1a1a1a; margin-bottom:6px; }
.pay-method-val   { font-size:10px; color:#374151; line-height:1.8; }

table.totals { border-collapse:collapse; width:280px; }
table.totals td { padding:4px 0; font-size:10px; white-space:nowrap; }
table.totals td:first-child { padding-right:16px; }
table.totals td:last-child { text-align:right; }
table.totals .sub-row td { color:#6b7280; }
table.totals .total-row td { font-size:14px; font-weight:bold; color:#1a1a1a; white-space:nowrap; }
table.totals .total-row td:last-child { color: {{ $accent }}; }

.payments-wrap { padding: 18px 52px 0; }
.payments-title { font-size:9px; font-weight:bold; text-transform:uppercase; letter-spacing:0.8px; margin-bottom:8px; }
table.payments { width:100%; border-collapse:collapse; }
table.payments th, table.payments td { padding:6px 0; font-size:9px; border-bottom:1px solid #e5e7eb; }
table.payments th { text-align:left; color:#6b7280; text-transform:uppercase; letter-spacing:0.4px; }
table.payments td.r { text-align:right; }

.thankyou-wrap { padding: 24px 52px 60px; }
.thankyou-text { font-size:14px; color:#1a1a1a; }
.signed-block { width:160px; margin-left:auto; padding-top:60px; }
.signed-line  { border-top:1.5px solid #1a1a1a; margin-bottom:5px; }
.signed-label { font-size:9px; color:#6b7280; text-align:center; }

.footer-bar {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: {{ $accent }};
    padding: 11px 52px;
    color:#fff;
}
.footer-bar .row-inner { display:table; width:100%; }
.footer-bar .fi {
    display:table-cell;
    vertical-align:top;
    font-size:9px;
    padding-right:28px;
}
.footer-bar .fi-label {
    font-size:7.5px;
    font-weight:bold;
    text-transform:uppercase;
    letter-spacing:0.6px;
    opacity:0.65;
    display:block;
    margin-bottom:1px;
}
.footer-bar .fi-val { font-size:9px; }
</style>
</head>
<body>

<div class="deco-bar">
    <div class="deco-dot"></div>
    <div class="deco-pill"></div>
</div>

<div class="brand-wrap">
    <div class="row">
        <div class="col">
            @if($logoBase64)
                <img src="{{ $logoBase64 }}"
                     alt="{{ $penyelenggara->name ?? 'Sumsel Wedding Expo' }}"
                     style="height:60px; width:auto; display:block;">
            @else
                <div class="brand-name">{{ strtoupper($penyelenggara->name ?? 'SUMSEL WEDDING EXPO') }}</div>
                <div class="brand-tagline">Invoice Partisipasi Tenant</div>
            @endif
        </div>
    </div>
</div>

<div class="invoice-title">INVOICE</div>

<div class="bill-section">
    <div class="row">
        <div class="col" style="width:50%">
            <div class="label-sm">Invoice to:</div>
            <div class="name-lg">{{ $vendor->nama_vendor ?? '-' }}</div>
            <div class="text-muted">
                @if($vendor?->nama_pic)
                    PIC: {{ $vendor->nama_pic }}<br>
                @endif
                @if($vendor?->email)
                    {{ $vendor->email }}<br>
                @endif
                @if($vendor?->no_telepon || $vendor?->no_wa_pic)
                    {{ $vendor->no_telepon ?: $vendor->no_wa_pic }}<br>
                @endif
                @if($vendor?->alamat || $vendor?->kota)
                    {{ collect([$vendor->alamat, $vendor->kota])->filter()->implode(', ') }}
                @endif
            </div>
        </div>
        <div class="col-r" style="width:50%">
            <table class="meta-table">
                <tr>
                    <td class="ml">Invoice#</td>
                    <td class="mc">:</td>
                    <td class="mv">{{ $invoiceNumber }}</td>
                </tr>
                <tr>
                    <td class="ml">Tanggal</td>
                    <td class="mc">:</td>
                    <td class="mv">{{ ($partisipasi->tanggal_booking ?? $generatedAt)->format('d / m / Y') }}</td>
                </tr>
                <tr>
                    <td class="ml">Expo</td>
                    <td class="mc">:</td>
                    <td class="mv">{{ $expo->nama_expo ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="ml">Periode</td>
                    <td class="mc">:</td>
                    <td class="mv">{{ $expo->periode ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="ml">Booth</td>
                    <td class="mc">:</td>
                    <td class="mv">{{ $booth }}</td>
                </tr>
                <tr>
                    <td class="ml">Status</td>
                    <td class="mc">:</td>
                    <td class="mv">
                        <span class="badge" style="background:{{ $statusBg }}; color:{{ $statusColor }}">
                            {{ strtoupper($status) }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

<div class="items-wrap">
    <table class="items">
        <thead>
            <tr>
                <th style="width:48%">Item</th>
                <th style="width:12%" class="c">Qty</th>
                <th style="width:20%" class="r">Harga</th>
                <th style="width:20%" class="r">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    {{ $itemLabel }}
                    <div class="sub">
                        {{ $expo->nama_expo ?? '' }}
                        @if($expo?->tanggal_mulai)
                            · {{ $expo->tanggal_mulai->format('d M Y') }}
                            @if($expo->tanggal_selesai)
                                – {{ $expo->tanggal_selesai->format('d M Y') }}
                            @endif
                        @endif
                        @if($expo?->lokasi)
                            · {{ $expo->lokasi }}
                        @endif
                    </div>
                </td>
                <td class="c">1</td>
                <td class="r">Rp {{ number_format($hargaJual, 0, ',', '.') }}</td>
                <td class="r">Rp {{ number_format($hargaJual, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="bottom-wrap">
    <div class="row">
        <div class="col" style="width:50%; padding-top:6px;">
            <div class="pay-method-title">Pembayaran dapat dilakukan melalui</div>
            <div class="pay-method-val">
                @forelse($rekeningTujuans as $i => $rekening)
                    {{ $i + 1 }}. {{ $rekening->nama_bank }} — {{ $rekening->nomor_rekening }}
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;a.n. {{ $rekening->nama_pemilik }}
                    @if(! $loop->last)<br>@endif
                @empty
                    —
                @endforelse
            </div>

            @if($partisipasi->keterangan || $barterNominal > 0)
                <div class="pay-method-title" style="margin-top:12px;">Keterangan</div>
                <div class="pay-method-val">
                    @if($partisipasi->keterangan)
                        {{ $partisipasi->keterangan }}
                    @endif
                    @if($barterNominal > 0)
                        @if($partisipasi->keterangan)<br>@endif
                        <strong>Barter:</strong> Rp {{ number_format($barterNominal, 0, ',', '.') }}
                        @if($partisipasi->barter_description)
                            <br>{{ $partisipasi->barter_description }}
                        @endif
                        <br><span style="color:#9ca3af; font-size:8.5px;">Nilai barter tidak termasuk pembayaran tunai ke perusahaan.</span>
                    @endif
                </div>
            @endif
        </div>
        <div class="col-r" style="width:50%">
            <table class="totals" style="margin-left:auto;">
                <tr class="sub-row">
                    <td>Harga Jual</td>
                    <td>Rp {{ number_format($hargaJual, 0, ',', '.') }}</td>
                </tr>
                @if($diskon > 0)
                <tr class="sub-row">
                    <td>Diskon</td>
                    <td>- Rp {{ number_format($diskon, 0, ',', '.') }}</td>
                </tr>
                @endif
                @if($barterNominal > 0)
                <tr class="sub-row">
                    <td>Barter</td>
                    <td>- Rp {{ number_format($barterNominal, 0, ',', '.') }}</td>
                </tr>
                @endif
                <tr class="sub-row">
                    <td>Sudah Dibayar</td>
                    <td>Rp {{ number_format($totalBayar, 0, ',', '.') }}</td>
                </tr>
                <tr class="sub-row">
                    <td>Sisa</td>
                    <td>Rp {{ number_format($sisaBayar, 0, ',', '.') }}</td>
                </tr>
                <tr class="line-row"><td colspan="2" style="padding:0; border-top:1.5px solid #e5e7eb;"></td></tr>
                <tr class="total-row">
                    <td>Total Tagihan</td>
                    <td>Rp {{ number_format($tagihanAkhir, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>

@if($partisipasi->dataPembayarans->isNotEmpty())
<div class="payments-wrap">
    <div class="payments-title">Riwayat Pembayaran</div>
    <table class="payments">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Pembayar</th>
                <th>Metode</th>
                <th>Rekening Tujuan</th>
                <th class="r">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($partisipasi->dataPembayarans as $bayar)
            @php
                $metode = $metodeLabels[$bayar->metode_pembayaran] ?? ($bayar->metode_pembayaran ? strtoupper($bayar->metode_pembayaran) : '-');
                $rek = $bayar->rekeningTujuan;
                $rekLabel = $rek
                    ? $rek->nama_bank.' · '.$rek->nomor_rekening
                    : '-';
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

<div class="thankyou-wrap">
    <div class="row">
        <div class="col" style="width:55%; padding-top:8px;">
            <div class="thankyou-text">Terima kasih atas partisipasi Anda!</div>
            <div class="text-muted" style="margin-top:6px;">
                Dokumen digenerate: {{ $generatedAt->format('d M Y H:i') }} WIB
            </div>
        </div>
        <div class="col-r" style="width:45%">
            <div class="signed-block">
                <div class="signed-line"></div>
                <div class="signed-label">{{ $penyelenggara->name ?? 'Sumsel Wedding Expo' }}</div>
            </div>
        </div>
    </div>
</div>

<div class="footer-bar">
    <div class="row-inner">
        @if($penyelenggara?->no_tlp)
        <div class="fi">
            <span class="fi-label">Telepon</span>
            <span class="fi-val">{{ $penyelenggara->no_tlp }}</span>
        </div>
        @endif
        @if($penyelenggara?->email)
        <div class="fi">
            <span class="fi-label">Email</span>
            <span class="fi-val">{{ $penyelenggara->email }}</span>
        </div>
        @endif
        @if($penyelenggara?->instagram)
        <div class="fi">
            <span class="fi-label">Instagram</span>
            <span class="fi-val">{{ $penyelenggara->instagram }}</span>
        </div>
        @endif
        @if($penyelenggara?->alamat)
        <div class="fi">
            <span class="fi-label">Alamat</span>
            <span class="fi-val">{{ $penyelenggara->alamat }}</span>
        </div>
        @endif
    </div>
</div>

</body>
</html>
