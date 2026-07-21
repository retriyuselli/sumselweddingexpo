<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Daftar Partisipasi — {{ $expo->nama_expo }}</title>
    <style>
        @page { margin: 24px 28px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #222; margin: 0; }
        .header { border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 14px; }
        .header h1 { margin: 0; font-size: 15px; text-transform: uppercase; }
        .header p { margin: 3px 0 0; color: #555; font-size: 10px; }
        .meta { margin-bottom: 12px; }
        .meta td { border: none; padding: 2px 8px 2px 0; vertical-align: top; }
        table.data { width: 100%; border-collapse: collapse; }
        table.data th, table.data td { border: 1px solid #ccc; padding: 5px 6px; }
        table.data th { background: #f3f4f6; font-size: 9px; text-transform: uppercase; }
        table.data td { font-size: 9px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .badge-yes { color: #15803d; font-weight: bold; }
        .badge-no { color: #b91c1c; font-weight: bold; }
        .footer { margin-top: 14px; font-size: 9px; color: #666; }
        .empty { padding: 20px; text-align: center; color: #666; border: 1px dashed #ccc; }
    </style>
</head>
<body>
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
                    <th class="text-center" style="width:28px;">No</th>
                    <th>Vendor</th>
                    <th>Jenis Usaha</th>
                    <th>Paket</th>
                    <th>Blok/Nomor Tenant</th>
                    <th>Preferensi</th>
                    <th class="text-right">Harga</th>
                    <th class="text-center">Status Bayar</th>
                    <th class="text-center">Aktif</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($partisipasis as $i => $p)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td>{{ $p->vendor?->nama_vendor ?? '-' }}</td>
                        <td>{{ $p->vendor?->jenisUsaha?->nama_jenis_usaha ?? '-' }}</td>
                        <td>
                            @php $cat = $p->categoryTenant?->category; @endphp
                            {{ $cat?->label() ?? '-' }}
                        </td>
                        <td>{{ $p->tenantSpot?->kode_booth ?? '-' }}</td>
                        <td>{{ $p->blok_tenant ?? '-' }}</td>
                        <td class="text-right">Rp {{ number_format((int) $p->harga_jual, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $p->status_pembayaran ?? '-' }}</td>
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
        </table>
    @endif

    <div class="footer">
        Total: {{ $partisipasis->count() }} partisipasi
        · Digenerate: {{ $generatedAt->format('d M Y H:i') }}
    </div>
</body>
</html>
