<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Form Leads Pengunjung</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 12px 24px 24px 24px;
            background-color: #ffffff;
        }

        h1 {
            margin: 16px 0 16px 0;
            font-size: 18px;
            color: #111827;
        }

        .card {
            background: #ffffff;
            border-radius: 8px;
            padding: 0px;
            border: 0px solid #dde3e8;
        }

        .row {
            display: flex;
            width: 100%;
            margin-bottom: 12px;
        }

        /* Tabel layout untuk PDF support yang lebih baik daripada flexbox kompleks */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        td {
            vertical-align: top;
            padding-bottom: 12px;
        }

        td.left {
            padding-right: 10px;
            width: 50%;
        }

        td.right {
            padding-left: 10px;
            width: 50%;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 4px;
            color: #374151;
        }

        .required::after {
            content: " *";
            color: #e02424;
        }

        .input-box {
            border: 1px solid #d1d5db;
            border-radius: 4px;
            height: 32px;
            width: 100%;
            background-color: #fff;
            padding: 0 8px;
            margin-top: 4px;
        }

        .select-box {
            border: 1px solid #d1d5db;
            border-radius: 4px;
            height: 32px;
            width: 100%;
            background-color: #fff;
            position: relative;
            padding: 0 8px;
        }

        /* Arrow down simulasi untuk select */
        .select-box::after {
            content: "▼";
            font-size: 8px;
            color: #9ca3af;
            position: absolute;
            right: 8px;
            top: 10px;
        }

        .hint {
            font-size: 9px;
            color: #6b7280;
            margin-top: 3px;
        }

        .checkbox-row {
            margin-top: 8px;
            page-break-inside: avoid;
        }

        .checkbox-box {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 1px solid #6b7280;
            border-radius: 2px;
            margin-right: 6px;
            vertical-align: text-top;
            margin-top: 1px;
        }

        .checkbox-text {
            display: inline;
            font-size: 10px;
            color: #374151;
            line-height: 1.4;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #6b7280;
            padding-top: 20px;
        }

        .signature-line {
            display: inline-block;
            width: 200px;
            border-bottom: 1px solid #000;
            margin-left: 5px;
        }

        .header-kop {
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #d1d5db;
        }

        .header-kop table {
            width: 100%;
            margin-bottom: 20px;
        }

        .header-kop td {
            vertical-align: top;
            padding: 0;
        }

        .header-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .header-info {
            font-size: 11px;
            line-height: 1.4;
        }
    </style>
</head>

<body>
    <div class="header-kop">
        <table>
            <tr>
                <td style="width: 60%; text-align: left;">
                    <div class="header-title">{{ $penyelenggara->name ?? 'PT. Makna Kreatif Indonesia' }}</div>
                    <div class="header-info">
                        Alamat : {{ $penyelenggara->alamat ?? '-' }}<br>
                        No. Tlp : {{ $penyelenggara->no_tlp ?? '-' }}<br>
                        Email : {{ $penyelenggara->email ?? '-' }}
                    </div>
                </td>
                <td style="width: 40%; text-align: right;">
                    @if (isset($penyelenggara->logo) && file_exists(public_path('storage/' . $penyelenggara->logo)))
                        <img src="{{ public_path('storage/' . $penyelenggara->logo) }}"
                            style="max-height: 40px; width: auto;" alt="Logo">
                    @elseif(isset($penyelenggara->name))
                        <h2 style="color: #333; margin: 0;">{{ $penyelenggara->name }}</h2>
                    @endif
                </td>
            </tr>
        </table>
    </div>
    <br>
    <br>
    <h1>Form Leads Pengunjung</h1>
    <div class="card">
        <table>
            <tr>
                <td class="left">
                    <label class="required">Nama Lengkap</label>
                    <div class="input-box">
                        @isset($doorprize)
                            {{ $doorprize->name }}
                        @endisset
                    </div>
                </td>
                <td class="right">
                    <label class="required">Alamat Email</label>
                    <div class="input-box">
                        @isset($doorprize)
                            {{ $doorprize->email }}
                        @endisset
                    </div>
                </td>
            </tr>
            <tr>
                <td class="left">
                    <label class="required">Nomor Handphone</label>
                    <div class="input-box">
                        @isset($doorprize)
                            0{{ $doorprize->no_wa }}
                        @endisset
                    </div>
                </td>
                <td class="right">
                    <label class="required">Minat Produk</label>
                    <div class="select-box">
                        @isset($doorprize)
                            {{ $doorprize->partisipasi?->vendor?->jenisUsaha?->nama_jenis_usaha ?? $doorprize->partisipasi?->vendor?->nama_vendor }}
                        @endisset
                    </div>
                </td>
            </tr>
            <tr>
                <td class="left">
                    <label class="required">Provinsi</label>
                    <div class="select-box">
                        @isset($doorprize)
                            {{ $doorprize->provinsi }}
                        @endisset
                    </div>
                </td>
                <td class="right">
                    <label class="required">Kota / Kabupaten</label>
                    <div class="select-box">
                        @isset($doorprize)
                            {{ $doorprize->partisipasi?->vendor?->kota }}
                        @endisset
                    </div>
                </td>
            </tr>
            <tr>
                <td class="left">
                    <label class="required">Kegiatan yang diikuti?</label>
                    <div class="select-box">
                        @isset($doorprize)
                            {{ $doorprize->partisipasi?->expo?->nama_expo }}
                        @endisset
                    </div>
                </td>
                <td class="right">
                    <label class="required">Masukkan kode Unit Pegadaian</label>
                    <div class="select-box">
                        @isset($doorprize)
                            00709
                        @endisset
                    </div>
                    <div class="hint">Masukkan 00709</div>
                </td>
            </tr>
        </table>

        <div class="checkbox-row">
            <div class="checkbox-box"></div>
            <div class="checkbox-text">
                Dengan mengisi informasi di atas, Saya bersedia dan mengizinkan data pribadi digunakan dalam penawaran,
                promosi seluruh produk serta fasilitas layanan PT Pegadaian, induk dan anak Perusahaan serta Pihak
                ketiga yang bekerjasama dengan PT Pegadaian.
            </div>
        </div>

        <div class="checkbox-row">
            <div class="checkbox-box"></div>
            <div class="checkbox-text">
                Bersedia dan mengizinkan data pribadi digunakan dalam pertukaran data dan informasi oleh PT Pegadaian,
                induk dan anak Perusahaan serta Pihak ketiga yang bekerjasama dengan PT Pegadaian sesuai ketentuan
                perundang-undangan yang berlaku.
            </div>
        </div>

        <div class="checkbox-row">
            <div class="checkbox-box"></div>
            <div class="checkbox-text">
                Bersedia untuk melakukan download TRING! pada aplikasi Androind atau Ios.
            </div>
        </div>

        <div class="footer">
            <div style="margin-bottom: 30px;">Palembang, ...........................................</div>
            <br><br><br>
            Tanda tangan peserta: <span class="signature-line"></span>
        </div>
    </div>
</body>

</html>
</div>
</div>
</body>

</html>
