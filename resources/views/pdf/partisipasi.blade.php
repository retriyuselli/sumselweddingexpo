<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Daftar Partisipasi — {{ $expo->nama_expo }}</title>
    <style>
        @page { margin: 18px 16px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 8px; color: #222; margin: 0; }
        .header { border-bottom: 2px solid #333; padding-bottom: 8px; margin-bottom: 10px; }
        .header h1 { margin: 0; font-size: 13px; text-transform: uppercase; }
        .header p { margin: 2px 0 0; color: #555; font-size: 9px; }
        .meta { margin-bottom: 10px; }
        .meta td { border: none; padding: 1px 6px 1px 0; vertical-align: top; font-size: 8px; }
        table.data { width: 100%; border-collapse: collapse; table-layout: fixed; }
        table.data th, table.data td { border: 1px solid #ccc; padding: 3px 4px; word-wrap: break-word; }
        table.data th { background: #f3f4f6; font-size: 7px; text-transform: uppercase; }
        table.data td { font-size: 7.5px; }
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
    @endphp

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

                        $status = $p->status_pembayaran ?? '-';
                        $isLunas = strcasecmp((string) $status, 'Lunas') === 0;
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
    @endif

    <div class="footer">
        Total: {{ $partisipasis->count() }} partisipasi
        · Lunas: {{ $partisipasis->where('status_pembayaran', 'Lunas')->count() }}
        · Belum Lunas: {{ $partisipasis->where('status_pembayaran', '!=', 'Lunas')->count() }}
        · Digenerate: {{ $generatedAt->timezone('Asia/Jakarta')->format('d M Y H:i') }} WIB
    </div>
</body>
</html>
