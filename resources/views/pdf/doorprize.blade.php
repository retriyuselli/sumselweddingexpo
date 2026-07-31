<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Doorprize — {{ $expo->nama_expo }}</title>
    <style>
        @page { margin: 120px 16px 28px 16px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 8px; color: #222; margin: 0; }

        .header-fixed {
            position: fixed;
            top: -105px;
            left: 0;
            right: 0;
            height: 90px;
        }
        .header { border-bottom: 2px solid #333; padding-bottom: 6px; margin-bottom: 6px; }
        .header h1 { margin: 0; font-size: 13px; text-transform: uppercase; }
        .header p { margin: 2px 0 0; color: #555; font-size: 9px; }
        .meta { margin-bottom: 0; width: 100%; }
        .meta td { border: none; padding: 1px 6px 1px 0; vertical-align: top; font-size: 8px; }

        .content { margin-top: 10px; }

        table.data { width: 100%; border-collapse: collapse; table-layout: fixed; }
        table.data th, table.data td { border: 1px solid #ccc; padding: 3px 4px; word-wrap: break-word; }
        table.data th { background: #f3f4f6; font-size: 7px; text-transform: uppercase; }
        table.data td { font-size: 7.5px; }
        table.data thead { display: table-header-group; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .badge-yes { color: #15803d; font-weight: bold; }
        .badge-no { color: #b45309; font-weight: bold; }
        .empty { padding: 20px; text-align: center; color: #666; border: 1px dashed #ccc; }
        .sub { font-size: 6.5px; color: #6b7280; }
        .footer { margin-top: 10px; font-size: 8px; color: #666; }

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
            width: 16.66%;
            vertical-align: top;
            padding: 8px 10px;
            border-right: 1px solid #e5e7eb;
            background: #f8fafc;
        }
        table.summary-grid td:last-child { border-right: none; }
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
    </style>
</head>
<body>
    @php
        use App\Models\Doorprize;

        $totalPemenang = $doorprizes->count();
        $sudahTring = $doorprizes->where('sudah_download_tring', true)->count();
        $belumTring = max(0, $totalPemenang - $sudahTring);
        $belumKtp = $doorprizes->filter(fn ($d) => ! $d->hasFotoKtp())->count();
        $totalTransaksi = $doorprizes->sum(fn ($d) => $d->total_nominal_transaksi);
        $totalRevenue = $doorprizes->sum(fn ($d) => $d->total_nominal_revenue);
    @endphp

    <div class="header-fixed">
        <div class="header">
            <h1>Laporan Doorprize</h1>
            <p>{{ $penyelenggara->name ?? 'Sumsel Wedding Expo' }}</p>
        </div>

        <table class="meta">
            <tr>
                <td><strong>Expo:</strong> {{ $expo->nama_expo }}</td>
                <td><strong>Periode:</strong> {{ $expo->periode ?: '—' }}</td>
                <td><strong>Tanggal:</strong>
                    @if ($expo->tanggal_mulai)
                        {{ $expo->tanggal_mulai->format('d M Y') }}
                        @if ($expo->tanggal_selesai && ! $expo->tanggal_mulai->equalTo($expo->tanggal_selesai))
                            – {{ $expo->tanggal_selesai->format('d M Y') }}
                        @endif
                    @else
                        —
                    @endif
                </td>
                <td><strong>Dicetak:</strong> {{ $generatedAt->format('d M Y H:i') }} WIB</td>
            </tr>
        </table>
    </div>

    <div class="content">
        @if ($doorprizes->isEmpty())
            <div class="empty">Belum ada data doorprize untuk expo ini.</div>
        @else
            <table class="data">
                <thead>
                    <tr>
                        <th style="width: 3%;" class="text-center">No</th>
                        <th style="width: 14%;">Nama Pemenang</th>
                        <th style="width: 10%;">Kode Voucher</th>
                        <th style="width: 12%;">Tenant / Vendor</th>
                        <th style="width: 9%;">WhatsApp</th>
                        <th style="width: 12%;">Email</th>
                        <th style="width: 11%;">Alamat</th>
                        <th style="width: 9%;" class="text-right">Total Trx</th>
                        <th style="width: 9%;" class="text-right">Total Rev</th>
                        <th style="width: 5%;" class="text-center">TRING</th>
                        <th style="width: 6%;" class="text-center">KTP</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($doorprizes as $index => $doorprize)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $doorprize->name }}</strong>
                                @if ($doorprize->nik)
                                    <div class="sub">NIK: {{ $doorprize->nik }}</div>
                                @endif
                            </td>
                            <td>{{ $doorprize->kodevoucher }}</td>
                            <td>{{ $doorprize->partisipasi?->vendor?->nama_vendor ?? '—' }}</td>
                            <td>{{ $doorprize->no_wa ?: '—' }}</td>
                            <td>{{ $doorprize->email ?: '—' }}</td>
                            <td>
                                {{ $doorprize->alamat ?: '—' }}
                                @if ($doorprize->provinsi)
                                    <div class="sub">{{ $doorprize->provinsi }}</div>
                                @endif
                            </td>
                            <td class="text-right">{{ Doorprize::formatRupiah($doorprize->total_nominal_transaksi) }}</td>
                            <td class="text-right">{{ Doorprize::formatRupiah($doorprize->total_nominal_revenue) }}</td>
                            <td class="text-center">
                                @if ($doorprize->sudah_download_tring)
                                    <span class="badge-yes">Sudah</span>
                                @else
                                    <span class="badge-no">Belum</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($doorprize->hasFotoKtp())
                                    <span class="badge-yes">Ada</span>
                                @else
                                    <span class="badge-no">Belum</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="summary">
                <div class="summary-title">Ringkasan</div>
                <table class="summary-grid">
                    <tr>
                        <td>
                            <div class="label">Total Pemenang</div>
                            <div class="value blue">{{ $totalPemenang }}</div>
                        </td>
                        <td>
                            <div class="label">Sudah TRING</div>
                            <div class="value green">{{ $sudahTring }}</div>
                        </td>
                        <td>
                            <div class="label">Belum TRING</div>
                            <div class="value amber">{{ $belumTring }}</div>
                        </td>
                        <td>
                            <div class="label">Belum Foto KTP</div>
                            <div class="value red">{{ $belumKtp }}</div>
                        </td>
                        <td>
                            <div class="label">Total Transaksi</div>
                            <div class="value amber">{{ Doorprize::formatRupiah($totalTransaksi) }}</div>
                        </td>
                        <td>
                            <div class="label">Total Revenue</div>
                            <div class="value green">{{ Doorprize::formatRupiah($totalRevenue) }}</div>
                        </td>
                    </tr>
                </table>
            </div>
        @endif

        <div class="footer">
            Dokumen ini digenerate otomatis dari panel admin Sumsel Wedding Expo.
        </div>
    </div>
</body>
</html>
