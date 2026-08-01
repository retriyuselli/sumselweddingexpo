<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Laba Rugi — {{ $expos[0]->nama_expo ?? 'Expo' }}</title>
    <style>
        @font-face {
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 400;
            src: url('{{ base_path('resources/fonts/poppins/Poppins-Regular.ttf') }}') format('truetype');
        }
        @font-face {
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 500;
            src: url('{{ base_path('resources/fonts/poppins/Poppins-Medium.ttf') }}') format('truetype');
        }
        @font-face {
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 600;
            src: url('{{ base_path('resources/fonts/poppins/Poppins-SemiBold.ttf') }}') format('truetype');
        }
        @font-face {
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 700;
            src: url('{{ base_path('resources/fonts/poppins/Poppins-Bold.ttf') }}') format('truetype');
        }

        /* Header fixed harus muat di margin-top (hindari overlap halaman 2+) */
        @page {
            margin: 120px 28px 40px 28px;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Poppins', DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #142033;
            margin: 0;
            line-height: 1.4;
        }

        header {
            position: fixed;
            top: -100px;
            left: 0;
            right: 0;
            height: 88px;
        }

        .brand {
            width: 100%;
            border-bottom: 2.5px solid #0f3d5e;
            padding-bottom: 8px;
        }

        .brand-table {
            width: 100%;
            border-collapse: collapse;
        }

        .brand-table td {
            border: none !important;
            vertical-align: middle;
            padding: 0;
            background: transparent !important;
        }

        .brand-logo {
            width: 64px;
        }

        .brand-logo img {
            width: 56px;
            height: 56px;
            display: block;
        }

        .brand-meta {
            padding-left: 12px !important;
        }

        .brand-name {
            margin: 0;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            color: #0f3d5e;
            letter-spacing: 0.3px;
        }

        .brand-address {
            margin: 3px 0 0;
            font-size: 8px;
            color: #64748b;
            line-height: 1.35;
        }

        .hero {
            text-align: center;
            margin: 0 0 14px;
            padding: 10px 12px 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }

        .hero-eyebrow {
            margin: 0 0 4px;
            font-size: 8px;
            font-weight: 600;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: #64748b;
        }

        .hero h1 {
            margin: 0;
            font-size: 15px;
            font-weight: 700;
            text-transform: uppercase;
            color: #142033;
            letter-spacing: 0.4px;
        }

        .hero h2 {
            margin: 4px 0 0;
            font-size: 11px;
            font-weight: 600;
            color: #0f3d5e;
        }

        .hero p {
            margin: 4px 0 0;
            font-size: 8px;
            color: #64748b;
        }

        .kpi {
            width: 100%;
            border-collapse: separate;
            border-spacing: 6px 0;
            margin: 0 0 16px;
        }

        .kpi td {
            width: 25%;
            border: 1px solid #e2e8f0 !important;
            background: #fff !important;
            padding: 8px 9px !important;
            vertical-align: top;
        }

        .kpi .label {
            display: block;
            font-size: 7.5px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #64748b;
            margin-bottom: 3px;
        }

        .kpi .value {
            display: block;
            font-size: 10px;
            font-weight: 700;
            color: #142033;
        }

        .kpi .value.green { color: #15803d; }
        .kpi .value.red { color: #b91c1c; }
        .kpi .value.amber { color: #b45309; }
        .kpi .value.blue { color: #0369a1; }

        .section {
            margin-top: 16px;
            page-break-inside: avoid;
        }

        .section-head {
            background: #0f3d5e;
            color: #fff;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 6px 10px;
            margin: 0 0 8px;
        }

        .section-head.amber { background: #9a3412; }
        .section-head.blue { background: #0c4a6e; }

        .subhead {
            margin: 10px 0 5px;
            font-size: 9px;
            font-weight: 700;
            color: #142033;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin: 0 0 8px;
        }

        table.data thead {
            display: table-header-group;
        }

        table.data th {
            background: #eef2f7;
            color: #142033;
            border: 1px solid #e2e8f0;
            padding: 5px 6px;
            font-size: 7.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.2px;
        }

        table.data td {
            border: 1px solid #e2e8f0;
            padding: 4px 6px;
            font-size: 8px;
            vertical-align: top;
            word-wrap: break-word;
        }

        table.data tr:nth-child(even) td {
            background: #fcfdfe;
        }

        table.data .foot td {
            background: #f1f5f9 !important;
            font-weight: 700;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .muted { color: #64748b; }
        .green { color: #15803d; }
        .red { color: #b91c1c; }
        .amber { color: #b45309; }
        .blue { color: #0369a1; }
        .bold { font-weight: 700; }

        .badge {
            display: inline-block;
            padding: 1px 5px;
            border-radius: 2px;
            font-size: 7px;
            font-weight: 600;
            background: #fee2e2;
            color: #991b1b;
        }

        .badge.ok {
            background: #dcfce7;
            color: #166534;
        }

        .summary {
            width: 55%;
            margin-top: 8px;
            border-collapse: collapse;
        }

        .summary td {
            border: 1px solid #e2e8f0;
            padding: 7px 10px;
            font-size: 9px;
        }

        .summary .result td {
            font-size: 11px;
            font-weight: 700;
        }

        .note {
            margin-top: 14px;
            font-size: 7.5px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
            padding-top: 6px;
        }

        .page-break {
            page-break-after: always;
        }

        .download-wrap {
            text-align: center;
            margin-top: 22px;
            page-break-inside: avoid;
        }

        .download-btn {
            display: inline-block;
            background: #0f3d5e;
            color: #fff;
            padding: 9px 18px;
            text-decoration: none;
            font-weight: 700;
            font-size: 9px;
            letter-spacing: 0.4px;
        }
    </style>
</head>
<body>
    <header>
        <div class="brand">
            <table class="brand-table">
                <tr>
                    @if (! empty($penyelenggara?->logo))
                        <td class="brand-logo">
                            <img src="{{ public_path('storage/'.$penyelenggara->logo) }}" alt="Logo">
                        </td>
                    @endif
                    <td class="brand-meta">
                        <p class="brand-name">{{ $penyelenggara->name ?? 'Sumsel Wedding Expo' }}</p>
                        <p class="brand-address">
                            {{ $penyelenggara->alamat ?? 'Palembang, Sumatera Selatan' }}
                            @if (! empty($penyelenggara?->no_tlp))
                                <br>Telp: {{ $penyelenggara->no_tlp }}
                            @endif
                        </p>
                    </td>
                </tr>
            </table>
        </div>
    </header>

    @foreach ($expos as $index => $expo)
        <div class="{{ $index > 0 ? 'page-break' : '' }}">
            @php
                $totalPartisipasi = $expo->partisipasis->sum(
                    fn ($p) => (float) $p->dataPembayarans->sum('nominal')
                );
                $totalSponsor = (float) $expo->sponsors->sum('nominal');
                $totalPemasukan = $totalPartisipasi + $totalSponsor;
                $totalPengeluaran = (float) $expo->pengeluarans->sum('nominal');
                $labaRugi = $totalPemasukan - $totalPengeluaran;

                $piutangList = $expo->partisipasis->where('sisa_pembayaran', '>', 0)->values();
                $totalPiutang = (float) $piutangList->sum('sisa_pembayaran');

                $barterList = $expo->partisipasis->where('is_barter', true)->values();
                $totalBarter = (float) $barterList->sum(fn ($p) => (float) ($p->barter_nominal ?? 0));

                $periode = $expo->labelDetails();
            @endphp

            <div class="hero">
                <p class="hero-eyebrow">Laporan Keuangan · Cash Basis</p>
                <h1>Laporan Laba Rugi Detail</h1>
                <h2>{{ $expo->nama_expo }}</h2>
                <p>
                    @if ($periode){{ $periode }} · @endif
                    Dicetak {{ now('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                </p>
            </div>

            <table class="kpi">
                <tr>
                    <td>
                        <span class="label">Pemasukan</span>
                        <span class="value green">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</span>
                    </td>
                    <td>
                        <span class="label">Pengeluaran</span>
                        <span class="value red">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</span>
                    </td>
                    <td>
                        <span class="label">Piutang</span>
                        <span class="value amber">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</span>
                    </td>
                    <td>
                        <span class="label">Laba / Rugi</span>
                        <span class="value {{ $labaRugi >= 0 ? 'green' : 'red' }}">
                            Rp {{ number_format($labaRugi, 0, ',', '.') }}
                        </span>
                    </td>
                </tr>
            </table>

            {{-- I. PEMASUKAN --}}
            <div class="section-head">I. Pemasukan</div>

            <div class="subhead">A. Partisipasi Tenant</div>
            <table class="data">
                <thead>
                    <tr>
                        <th style="width:4%" class="text-center">No</th>
                        <th style="width:24%">Vendor</th>
                        <th style="width:8%" class="text-center">Blok</th>
                        <th style="width:10%" class="text-center">Status</th>
                        <th style="width:18%">Tanggal Bayar</th>
                        <th style="width:18%" class="text-right">Nominal Bayar</th>
                        <th style="width:18%" class="text-right">Total Tagihan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($expo->partisipasis as $i => $partisipasi)
                        @php
                            $bayar = (float) $partisipasi->dataPembayarans->sum('nominal');
                            $tanggal = $partisipasi->dataPembayarans
                                ->pluck('tanggal_bayar')
                                ->filter()
                                ->map(fn ($d) => $d->format('d/m/y'))
                                ->implode(', ');
                        @endphp
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td>{{ $partisipasi->vendor->nama_vendor ?? '—' }}</td>
                            <td class="text-center">{{ $partisipasi->tenantSpot?->kode_booth ?? '—' }}</td>
                            <td class="text-center">
                                <span class="badge {{ $partisipasi->status_pembayaran === 'Lunas' ? 'ok' : '' }}">
                                    {{ $partisipasi->status_pembayaran ?: '—' }}
                                </span>
                            </td>
                            <td>{{ $tanggal !== '' ? $tanggal : '—' }}</td>
                            <td class="text-right">{{ number_format($bayar, 0, ',', '.') }}</td>
                            <td class="text-right muted">{{ number_format((float) $partisipasi->harga_bersih, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center muted">Tidak ada data partisipasi</td>
                        </tr>
                    @endforelse
                    <tr class="foot">
                        <td colspan="5" class="text-right">Subtotal Partisipasi</td>
                        <td class="text-right">{{ number_format($totalPartisipasi, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <div class="subhead">B. Sponsor</div>
            <table class="data">
                <thead>
                    <tr>
                        <th style="width:5%" class="text-center">No</th>
                        <th style="width:30%">Nama Sponsor</th>
                        <th style="width:45%">Keterangan</th>
                        <th style="width:20%" class="text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($expo->sponsors as $i => $sponsor)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td>{{ $sponsor->name }}</td>
                            <td>
                                @if (is_array($sponsor->kewajiban ?? null))
                                    {{ implode(', ', $sponsor->kewajiban) }}
                                @else
                                    {{ $sponsor->kewajiban ?: '—' }}
                                @endif
                            </td>
                            <td class="text-right">{{ number_format((float) $sponsor->nominal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center muted">Tidak ada data sponsor</td>
                        </tr>
                    @endforelse
                    <tr class="foot">
                        <td colspan="3" class="text-right">Subtotal Sponsor</td>
                        <td class="text-right">{{ number_format($totalSponsor, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>

            <table class="summary" style="width:100%; margin-top:4px;">
                <tr>
                    <td class="text-right bold" style="width:80%; background:#ecfdf5;">Total Pemasukan (A + B)</td>
                    <td class="text-right bold green" style="width:20%; background:#ecfdf5;">
                        Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                    </td>
                </tr>
            </table>

            {{-- II. PENGELUARAN --}}
            <div class="section-head" style="margin-top:16px;">II. Pengeluaran</div>
            <table class="data">
                <thead>
                    <tr>
                        <th style="width:5%" class="text-center">No</th>
                        <th style="width:28%">Judul</th>
                        <th style="width:14%">Tanggal</th>
                        <th style="width:33%">Keterangan</th>
                        <th style="width:20%" class="text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($expo->pengeluarans as $i => $pengeluaran)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td>{{ $pengeluaran->nama_pengeluaran }}</td>
                            <td>{{ $pengeluaran->tanggal ? $pengeluaran->tanggal->format('d M Y') : '—' }}</td>
                            <td>{{ $pengeluaran->keterangan ?: '—' }}</td>
                            <td class="text-right">{{ number_format((float) $pengeluaran->nominal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center muted">Tidak ada data pengeluaran</td>
                        </tr>
                    @endforelse
                    <tr class="foot">
                        <td colspan="4" class="text-right">Total Pengeluaran</td>
                        <td class="text-right red">{{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>

            {{-- III. RINGKASAN --}}
            <div class="section-head" style="margin-top:16px;">III. Ringkasan Laba / Rugi</div>
            <table class="summary">
                <tr>
                    <td class="bold">Total Pemasukan</td>
                    <td class="text-right green bold">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="bold">Total Pengeluaran</td>
                    <td class="text-right red bold">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
                </tr>
                <tr class="result" style="background: {{ $labaRugi >= 0 ? '#f0fdf4' : '#fef2f2' }};">
                    <td>Laba / Rugi Bersih</td>
                    <td class="text-right {{ $labaRugi >= 0 ? 'green' : 'red' }}">
                        Rp {{ number_format($labaRugi, 0, ',', '.') }}
                    </td>
                </tr>
            </table>

            {{-- IV. PIUTANG --}}
            @if ($totalPiutang > 0)
                <div class="section-head amber" style="margin-top:16px; color:#fff;">IV. Rincian Piutang</div>
                <table class="data">
                    <thead>
                        <tr>
                            <th style="width:5%" class="text-center">No</th>
                            <th style="width:35%">Vendor</th>
                            <th style="width:15%" class="text-center">Blok</th>
                            <th style="width:22%" class="text-right">Total Tagihan</th>
                            <th style="width:23%" class="text-right">Sisa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($piutangList as $i => $piutang)
                            <tr>
                                <td class="text-center">{{ $i + 1 }}</td>
                                <td>{{ $piutang->vendor->nama_vendor ?? '—' }}</td>
                                <td class="text-center">{{ $piutang->tenantSpot?->kode_booth ?? '—' }}</td>
                                <td class="text-right">{{ number_format((float) $piutang->harga_bersih, 0, ',', '.') }}</td>
                                <td class="text-right amber bold">{{ number_format((float) $piutang->sisa_pembayaran, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        <tr class="foot">
                            <td colspan="4" class="text-right">Total Piutang</td>
                            <td class="text-right amber">{{ number_format($totalPiutang, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            @endif

            {{-- V. BARTER --}}
            @if ($totalBarter > 0)
                <div class="section-head blue" style="margin-top:16px; color:#fff;">V. Rincian Barter</div>
                <table class="data">
                    <thead>
                        <tr>
                            <th style="width:5%" class="text-center">No</th>
                            <th style="width:28%">Vendor</th>
                            <th style="width:47%">Keterangan</th>
                            <th style="width:20%" class="text-right">Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($barterList as $i => $barter)
                            <tr>
                                <td class="text-center">{{ $i + 1 }}</td>
                                <td>{{ $barter->vendor->nama_vendor ?? '—' }}</td>
                                <td>{{ $barter->barter_description ?: '—' }}</td>
                                <td class="text-right blue bold">{{ number_format((float) $barter->barter_nominal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        <tr class="foot">
                            <td colspan="3" class="text-right">Total Nilai Barter</td>
                            <td class="text-right blue">{{ number_format($totalBarter, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            @endif

            <p class="note">
                Catatan: laporan ini menggunakan metode cash basis (uang masuk aktual dari data pembayaran).
                Piutang dan barter ditampilkan terpisah dan tidak mengurangi/menambah laba bersih di atas.
            </p>

            @if (! empty($is_preview))
                <div class="download-wrap">
                    <a class="download-btn" href="{{ route('laporan.laba-rugi.download', $expo->id) }}">
                        DOWNLOAD PDF
                    </a>
                </div>
            @endif
        </div>
    @endforeach
</body>
</html>
