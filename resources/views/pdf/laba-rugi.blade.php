<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Detail Laba Rugi</title>
    <style>
        @page {
            margin: 110px 35px 35px 60px;
        }

        body {
            font-family: sans-serif;
            font-size: 11px;
            margin: 0;
        }

        header {
            position: fixed;
            top: -70px;
            left: 0px;
            right: 0px;
            height: 120px;
        }

        .header-container {
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header-table {
            width: 100%;
            border: none;
            margin-bottom: 0;
        }

        .header-table td {
            border: none;
            vertical-align: middle;
            padding: 0;
        }

        .logo-cell {
            width: 100px;
            text-align: left;
        }

        .logo-img {
            max-width: 150px;
            max-height: 80px;
        }

        .company-info {
            text-align: left;
            padding-left: 15px !important;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }

        .company-address {
            margin: 5px 0 0;
            font-size: 11px;
            color: #555;
        }

        .report-title-center {
            text-align: center;
            margin-bottom: 20px;
        }

        .report-title-center h1 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
        }

        .report-title-center h2 {
            margin: 5px 0 0;
            font-size: 14px;
            color: #333;
        }

        .report-title-center p {
            margin: 5px 0 0;
            font-size: 10px;
            color: #666;
        }

        /* Old header styles replaced by above */
        .section-title {
            font-weight: bold;
            font-size: 12px;
            margin-top: 15px;
            margin-bottom: 5px;
            text-transform: uppercase;
            color: #333;
            border-bottom: 1px solid #ccc;
            padding-bottom: 2px;
        }

        .subsection-title {
            font-weight: bold;
            font-size: 11px;
            margin-top: 10px;
            margin-bottom: 5px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
            font-weight: bold;
            font-size: 10px;
        }

        td {
            font-size: 10px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }

        .success {
            color: #16a34a;
        }

        .danger {
            color: #dc2626;
        }

        .warning {
            color: #d97706;
        }

        .info {
            color: #0284c7;
        }

        .total-row td {
            background-color: #fafafa;
            font-weight: bold;
        }

        .summary-box {
            border: 1px solid #ccc;
            padding: 10px;
            margin-top: 10px;
            background-color: #f9f9f9;
            width: 50%;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .page-break {
            page-break-after: always;
        }

        .no-border {
            border: none;
        }
    </style>
</head>

<body>
    <header>
        <div class="header-container">
            <table class="header-table">
                <tr>
                    @if (isset($penyelenggara) && $penyelenggara->logo)
                        <td class="logo-cell">
                            <img src="{{ public_path('storage/' . $penyelenggara->logo) }}" alt="Logo"
                                class="logo-img">
                        </td>
                    @endif
                    <td class="company-info">
                        @if (isset($penyelenggara))
                            <h1 class="company-name">{{ $penyelenggara->name }}</h1>
                            <p class="company-address">
                                {{ $penyelenggara->alamat }}<br>
                                Telp: {{ $penyelenggara->no_tlp }}
                            </p>
                        @else
                            <h1 class="company-name">SWE 2 JAN</h1>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </header>

    @foreach ($expos as $index => $expo)
        <div class="{{ $index > 0 ? 'page-break' : '' }}">
            <div class="report-title-center">
                <h1>Laporan Laba Rugi Detail</h1>
                <h2>{{ $expo->nama_expo }}</h2>
                <p>Dicetak pada: {{ now()->format('d F Y H:i') }}</p>
            </div>

            @php
                // Cash basis — samakan dengan LabaRugiAggregator (SUM data pembayaran)
                $totalPartisipasi = $expo->partisipasis->sum(
                    fn ($p) => (float) $p->dataPembayarans->sum('nominal')
                );
                $totalSponsor = $expo->sponsors->sum('nominal');
                $totalPemasukan = $totalPartisipasi + $totalSponsor;

                $totalPengeluaran = $expo->pengeluarans->sum('nominal');
                $labaRugi = $totalPemasukan - $totalPengeluaran;

                $piutangList = $expo->partisipasis->where('sisa_pembayaran', '>', 0);
                $totalPiutang = $piutangList->sum('sisa_pembayaran');

                $barterList = $expo->partisipasis->where('is_barter', true);
                $totalBarter = $barterList->sum(fn ($p) => (float) ($p->barter_nominal ?? 0));
            @endphp

            <!-- I. PEMASUKAN -->
            <div class="section-title">I. Pemasukan</div>

            <div class="subsection-title">A. Partisipasi Tenant</div>
            <table>
                <thead>
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="25%">Vendor</th>
                        <th width="8%">Blok</th>
                        <th width="8%">Status</th>
                        <th width="12%">Tanggal Bayar</th>
                        <th width="15%" class="text-right">Nominal Bayar</th>
                        <th width="15%" class="text-right">Total Tagihan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expo->partisipasis as $i => $partisipasi)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td>{{ $partisipasi->vendor->nama_vendor ?? '-' }}</td>
                            <td>{{ $partisipasi->tenantSpot?->kode_booth ?? '-' }}</td>
                            <td>{{ $partisipasi->status_pembayaran }}</td>
                            <td>
                                @if ($partisipasi->dataPembayarans->isNotEmpty())
                                    <dl style="padding-left: 0px; margin: 0;">
                                        @foreach ($partisipasi->dataPembayarans as $pembayaran)
                                            <dt>{{ $pembayaran->tanggal_bayar ? $pembayaran->tanggal_bayar->format('d M Y') : '-' }}
                                            </dt>
                                        @endforeach
                                    </dl>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-right">{{ number_format($partisipasi->dataPembayarans->sum('nominal'), 0, ',', '.') }}
                            </td>
                            <td class="text-right text-gray-500">
                                {{ number_format($partisipasi->harga_bersih, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data partisipasi</td>
                        </tr>
                    @endforelse
                    <tr class="total-row">
                        <td colspan="5" class="text-right">Subtotal Partisipasi</td>
                        <td class="text-right">{{ number_format($totalPartisipasi, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <div class="subsection-title">B. Sponsor</div>
            <table>
                <thead>
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="40%">Nama Sponsor</th>
                        <th width="35%">Keterangan</th>
                        <th width="20%" class="text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expo->sponsors as $i => $sponsor)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td>{{ $sponsor->name }}</td>
                            <td>
                                @if (isset($sponsor->kewajiban) && is_array($sponsor->kewajiban))
                                    <ul style="padding-left: 15px; margin: 0;">
                                        @foreach ($sponsor->kewajiban as $kewajiban)
                                            <li>{{ $kewajiban }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    {{ $sponsor->kewajiban ?? '-' }}
                                @endif
                            </td>
                            <td class="text-right">{{ number_format($sponsor->nominal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data sponsor</td>
                        </tr>
                    @endforelse
                    <tr class="total-row">
                        <td colspan="3" class="text-right">Subtotal Sponsor</td>
                        <td class="text-right">{{ number_format($totalSponsor, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>

            <table style="margin-top: 10px; border: none;">
                <tr class="total-row" style="background-color: #e6fffa;">
                    <td class="text-right no-border" width="80%">TOTAL PEMASUKAN (A + B)</td>
                    <td class="text-right success font-bold" width="20%">Rp
                        {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
                </tr>
            </table>

            <!-- II. PENGELUARAN -->
            <div class="section-title">II. Pengeluaran</div>
            <table>
                <thead>
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="30%">Judul Pengeluaran</th>
                        <th width="15%">Tanggal</th>
                        <th width="30%">Keterangan</th>
                        <th width="20%" class="text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expo->pengeluarans as $i => $pengeluaran)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td>{{ $pengeluaran->nama_pengeluaran }}</td>
                            <td>{{ $pengeluaran->tanggal ? $pengeluaran->tanggal->format('d M Y') : '-' }}</td>
                            <td>{{ $pengeluaran->keterangan ?? '-' }}</td>
                            <td class="text-right">{{ number_format($pengeluaran->nominal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data pengeluaran</td>
                        </tr>
                    @endforelse
                    <tr class="total-row">
                        <td colspan="4" class="text-right">Total Pengeluaran</td>
                        <td class="text-right danger">{{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- III. RINGKASAN -->
            <div class="section-title">III. Ringkasan Laba / Rugi</div>
            <table style="width: 50%;">
                <tr>
                    <td class="font-bold">Total Pemasukan</td>
                    <td class="text-right success">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="font-bold">Total Pengeluaran</td>
                    <td class="text-right danger">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
                </tr>
                <tr class="total-row" style="background-color: {{ $labaRugi >= 0 ? '#f0fdf4' : '#fef2f2' }};">
                    <td class="font-bold text-lg">LABA / RUGI BERSIH</td>
                    <td class="text-right font-bold text-lg {{ $labaRugi >= 0 ? 'success' : 'danger' }}">
                        Rp {{ number_format($labaRugi, 0, ',', '.') }}
                    </td>
                </tr>
            </table>

            <!-- IV. PIUTANG -->
            @if ($totalPiutang > 0)
                <div class="section-title" style="color: #d97706;">IV. Rincian Piutang (Belum Lunas)</div>
                <table>
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="35%">Vendor</th>
                            <th width="20%">Blok</th>
                            <th width="20%" class="text-right">Total Tagihan</th>
                            <th width="20%" class="text-right">Sisa Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($piutangList as $i => $piutang)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $piutang->vendor->nama_vendor ?? '-' }}</td>
                                <td>{{ $piutang->tenantSpot?->kode_booth ?? '-' }}</td>
                                <td class="text-right">{{ number_format($piutang->harga_bersih, 0, ',', '.') }}</td>
                                <td class="text-right warning font-bold">
                                    {{ number_format($piutang->sisa_pembayaran, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        <tr class="total-row">
                            <td colspan="4" class="text-right">Total Piutang</td>
                            <td class="text-right warning">{{ number_format($totalPiutang, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            @endif

            <!-- V. BARTER -->
            @if ($totalBarter > 0)
                <div class="section-title" style="color: #0284c7;">V. Rincian Barter</div>
                <table>
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="30%">Vendor</th>
                            <th width="45%">Keterangan Barter</th>
                            <th width="20%" class="text-right">Nilai Barter</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($barterList as $i => $barter)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $barter->vendor->nama_vendor ?? '-' }}</td>
                                <td>{{ $barter->barter_description }}</td>
                                <td class="text-right info font-bold">
                                    {{ number_format($barter->barter_nominal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        <tr class="total-row">
                            <td colspan="3" class="text-right">Total Nilai Barter</td>
                            <td class="text-right info">{{ number_format($totalBarter, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            @endif

            @if (isset($is_preview) && $is_preview)
                <div style="text-align: center; margin-top: 30px; page-break-inside: avoid;">
                    <a href="{{ route('laporan.laba-rugi.download', $expo->id) }}"
                        style="background-color: #000; color: #fff; padding: 10px 20px; text-decoration: none; font-weight: bold; border-radius: 5px; display: inline-block;">
                        DOWNLOAD PDF
                    </a>
                </div>
            @endif

        </div>
    @endforeach
</body>

</html>
