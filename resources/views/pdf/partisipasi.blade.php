<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Daftar Partisipasi — {{ $expo->nama_expo }}</title>
    <style>
        @page { margin: 130px 16px 28px 16px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 8px; color: #222; margin: 0; }

        .header-fixed {
            position: fixed;
            top: -115px;
            left: 0;
            right: 0;
            height: 100px;
        }
        .header { border-bottom: 2px solid #333; padding-bottom: 6px; margin-bottom: 8px; }
        .header h1 { margin: 0; font-size: 13px; text-transform: uppercase; }
        .header p { margin: 2px 0 0; color: #555; font-size: 9px; }
        .meta { margin-bottom: 0; width: 100%; }
        .meta td { border: none; padding: 1px 6px 1px 0; vertical-align: top; font-size: 8px; }

        .content {
            margin-top: 12px;
        }

        table.data { width: 100%; border-collapse: collapse; table-layout: fixed; }
        table.data th, table.data td { border: 1px solid #ccc; padding: 3px 4px; word-wrap: break-word; }
        table.data th { background: #f3f4f6; font-size: 7px; text-transform: uppercase; }
        table.data td { font-size: 7.5px; }
        table.data thead { display: table-header-group; }
        table.data tfoot { display: table-footer-group; }
        table.data tfoot td { background: #f9fafb; font-weight: bold; font-size: 7.5px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .badge-yes { color: #15803d; font-weight: bold; }
        .badge-no { color: #b91c1c; font-weight: bold; }
        .badge-lunas { color: #15803d; font-weight: bold; }
        .badge-belum { color: #b45309; font-weight: bold; }
        .footer { margin-top: 10px; font-size: 8px; color: #666; }
        .empty { padding: 20px; text-align: center; color: #666; border: 1px dashed #ccc; }
        .sub { font-size: 6.5px; color: #6b7280; }

        .summary {
            margin-top: 14px;
            page-break-inside: avoid;
            border: 1.5px solid #003D79;
            border-radius: 4px;
            overflow: hidden;
        }
        .summary-title {
            background: #003D79;
            color: #fff;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            padding: 6px 10px;
        }
        table.summary-grid {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        table.summary-grid td {
            width: 25%;
            vertical-align: top;
            padding: 8px 10px;
            border-right: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;
            background: #f8fafc;
        }
        table.summary-grid td:nth-child(4n) { border-right: none; }
        table.summary-grid .label {
            font-size: 7px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 3px;
        }
        table.summary-grid .value {
            font-size: 11px;
            font-weight: bold;
            color: #111827;
        }
        table.summary-grid .value.green { color: #15803d; }
        table.summary-grid .value.amber { color: #b45309; }
        table.summary-grid .value.blue { color: #003D79; }
        table.summary-grid .value.red { color: #b91c1c; }
        .summary-note {
            padding: 6px 10px;
            font-size: 7px;
            color: #6b7280;
            background: #fff;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    @php
        $totalHargaJual = 0;
        $totalDiskon = 0;
        $totalBarter = 0;
        $totalHargaBersih = 0;
        $totalDibayar = 0;
        $totalSisa = 0;
        $jumlahLunas = 0;
        $jumlahBelum = 0;
        $jumlahBarter = 0;
        $jumlahAktif = 0;
    @endphp

    <div class="header-fixed">
        <div class="header">
            <h1>Daftar Partisipasi Expo</h1>
            <p>{{ $penyelenggara->name ?? 'Sumsel Wedding Expo' }}</p>
        </div>

        <table class="meta">
            <tr>
                <td><strong>Expo</strong></td>
                <td>: {{ $expo->nama_expo }}</td>
                <td><strong>Periode</strong></td>
                <td>: {{ $expo->periode ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Tanggal</strong></td>
                <td>:
                    {{ $expo->tanggal_mulai?->format('d M Y') ?? '-' }}
                    –
                    {{ $expo->tanggal_selesai?->format('d M Y') ?? '-' }}
                </td>
                <td><strong>Filter</strong></td>
                <td>: {{ !empty($onlyActive) ? 'Hanya aktif' : 'Semua partisipasi' }}</td>
            </tr>
            <tr>
                <td><strong>Lokasi</strong></td>
                <td colspan="3">: {{ $expo->lokasi ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="content">
    @if ($partisipasis->isEmpty())
        <div class="empty">Belum ada data partisipasi untuk expo ini.</div>
    @else
        <table class="data">
            <thead>
                <tr>
                    <th class="text-center" style="width:3%;">No</th>
                    <th style="width:16%;">Vendor</th>
                    <th style="width:12%;">Vendor Pendamping</th>
                    <th class="text-center" style="width:6%;">Paket</th>
                    <th class="text-center" style="width:5%;">Blok</th>
                    <th class="text-right" style="width:9%;">Harga Jual</th>
                    <th class="text-right" style="width:7%;">Diskon</th>
                    <th class="text-right" style="width:7%;">Barter</th>
                    <th class="text-right" style="width:9%;">Harga Bersih</th>
                    <th class="text-right" style="width:9%;">Dibayar</th>
                    <th class="text-right" style="width:7%;">Sisa</th>
                    <th class="text-center" style="width:7%;">Status</th>
                    <th class="text-center" style="width:4%;">Aktif</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($partisipasis as $i => $p)
                    @php
                        $pendampingIds = is_array($p->vendor_pendamping) ? $p->vendor_pendamping : [];
                        $pendampingList = collect($pendampingIds)
                            ->map(fn ($id) => $pendampingNames[(int) $id] ?? null)
                            ->filter()
                            ->values();

                        $hargaJual = (int) ($p->harga_jual ?? 0);
                        $diskon = (int) ($p->diskon ?? 0);
                        $barter = $p->is_barter ? (int) ($p->barter_nominal ?? 0) : 0;
                        $hargaBersih = method_exists($p, 'hitungHargaBersih')
                            ? $p->hitungHargaBersih()
                            : max(0, $hargaJual - $diskon - $barter);
                        $dibayar = (int) ($p->total_pembayaran ?? 0);
                        $sisa = max(0, $hargaBersih - $dibayar);

                        $totalHargaJual += $hargaJual;
                        $totalDiskon += $diskon;
                        $totalBarter += $barter;
                        $totalHargaBersih += $hargaBersih;
                        $totalDibayar += $dibayar;
                        $totalSisa += $sisa;

                        // Status mengikuti perhitungan barter, bukan hanya kolom DB (bisa stale di production)
                        $status = method_exists($p, 'statusPembayaranEfektif')
                            ? $p->statusPembayaranEfektif($dibayar)
                            : (($hargaBersih === 0 || $dibayar >= $hargaBersih) ? 'Lunas' : 'Belum Lunas');
                        $isLunas = $status === 'Lunas';

                        if ($isLunas) {
                            $jumlahLunas++;
                        } else {
                            $jumlahBelum++;
                        }
                        if ($barter > 0) {
                            $jumlahBarter++;
                        }
                        if ($p->is_active) {
                            $jumlahAktif++;
                        }
                    @endphp
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td>
                            {{ $p->vendor?->nama_vendor ?? '-' }}
                            @if ($p->vendor?->nama_pic)
                                <div class="sub">PIC: {{ $p->vendor->nama_pic }}</div>
                            @endif
                        </td>
                        <td>
                            @if ($pendampingList->isEmpty())
                                -
                            @else
                                <ol style="margin:0; padding-left:12px;">
                                    @foreach ($pendampingList as $nama)
                                        <li>{{ $nama }}</li>
                                    @endforeach
                                </ol>
                            @endif
                        </td>
                        <td class="text-center">
                            @php $cat = $p->categoryTenant?->category; @endphp
                            {{ $cat?->label() ?? '-' }}
                        </td>
                        <td class="text-center">{{ $p->tenantSpot?->kode_booth ?? '-' }}</td>
                        <td class="text-right">{{ number_format($hargaJual, 0, ',', '.') }}</td>
                        <td class="text-right">{{ $diskon > 0 ? number_format($diskon, 0, ',', '.') : '-' }}</td>
                        <td class="text-right">
                            @if ($barter > 0)
                                {{ number_format($barter, 0, ',', '.') }}
                                @if ($p->barter_description)
                                    <div class="sub">{{ \Illuminate\Support\Str::limit($p->barter_description, 24) }}</div>
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-right">{{ number_format($hargaBersih, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($dibayar, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($sisa, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <span class="{{ $isLunas ? 'badge-lunas' : 'badge-belum' }}">{{ $status }}</span>
                        </td>
                        <td class="text-center">
                            @if ($p->is_active)
                                <span class="badge-yes">Ya</span>
                            @else
                                <span class="badge-no">Tidak</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-right">TOTAL</td>
                    <td class="text-right">{{ number_format($totalHargaJual, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($totalDiskon, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($totalBarter, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($totalHargaBersih, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($totalDibayar, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($totalSisa, 0, ',', '.') }}</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>

        @php
            $persenLunas = $partisipasis->count() > 0
                ? round(($jumlahLunas / $partisipasis->count()) * 100, 1)
                : 0;
            $persenTerbayar = $totalHargaBersih > 0
                ? round(($totalDibayar / $totalHargaBersih) * 100, 1)
                : ($totalHargaBersih === 0 && $partisipasis->isNotEmpty() ? 100 : 0);
        @endphp

        <div class="summary">
            <div class="summary-title">Ringkasan Pembayaran</div>
            <table class="summary-grid">
                <tr>
                    <td>
                        <div class="label">Total Harga Jual</div>
                        <div class="value">Rp {{ number_format($totalHargaJual, 0, ',', '.') }}</div>
                    </td>
                    <td>
                        <div class="label">Total Diskon</div>
                        <div class="value amber">Rp {{ number_format($totalDiskon, 0, ',', '.') }}</div>
                    </td>
                    <td>
                        <div class="label">Total Barter</div>
                        <div class="value blue">Rp {{ number_format($totalBarter, 0, ',', '.') }}</div>
                    </td>
                    <td>
                        <div class="label">Total Harga Bersih / Tagihan</div>
                        <div class="value">Rp {{ number_format($totalHargaBersih, 0, ',', '.') }}</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="label">Total Pembayaran (Sudah Dibayar)</div>
                        <div class="value green">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</div>
                    </td>
                    <td>
                        <div class="label">Total Sisa Pembayaran</div>
                        <div class="value {{ $totalSisa > 0 ? 'red' : 'green' }}">Rp {{ number_format($totalSisa, 0, ',', '.') }}</div>
                    </td>
                    <td>
                        <div class="label">Total Yang Belum Dibayar</div>
                        <div class="value {{ $totalSisa > 0 ? 'amber' : 'green' }}">Rp {{ number_format($totalSisa, 0, ',', '.') }}</div>
                    </td>
                    <td>
                        <div class="label">Progress Pelunasan</div>
                        <div class="value blue">{{ number_format($persenTerbayar, 1, ',', '.') }}%</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="label">Jumlah Partisipasi</div>
                        <div class="value">{{ $partisipasis->count() }}</div>
                    </td>
                    <td>
                        <div class="label">Sudah Lunas</div>
                        <div class="value green">{{ $jumlahLunas }} ({{ number_format($persenLunas, 1, ',', '.') }}%)</div>
                    </td>
                    <td>
                        <div class="label">Belum Lunas</div>
                        <div class="value amber">{{ $jumlahBelum }}</div>
                    </td>
                    <td>
                        <div class="label">Partisipasi Ada Barter</div>
                        <div class="value blue">{{ $jumlahBarter }}</div>
                    </td>
                </tr>
            </table>
            <div class="summary-note">
                Catatan: Harga Bersih = Harga Jual − Diskon − Barter.
                Total Sisa / Belum Dibayar = Harga Bersih − Total Pembayaran.
                Partisipasi aktif: {{ $jumlahAktif }} dari {{ $partisipasis->count() }}.
            </div>
        </div>
    @endif
    </div>

    <div class="footer">
        Digenerate: {{ $generatedAt->timezone('Asia/Jakarta')->format('d M Y H:i') }} WIB
    </div>
</body>
</html>
