<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Absensi — {{ $expo->nama_expo }}</title>
    @include('layouts.theme-init')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@400;500;600&display=swap" rel="stylesheet">
    @include('absensi.styles')
</head>
<body>
    <main>
        <a class="brand" href="{{ url('/') }}" aria-label="Beranda Sumsel Wedding Expo"><span>SUMSEL</span><small>WEDDING EXPO</small></a>
        <div class="attendance-shell">
            <header class="event-header">
                <span class="eyebrow">YOUR EXPO EXPERIENCE</span>
                <h1>{{ $expo->nama_expo }}</h1>
                <p class="event-date">{{ $expo->tanggal_mulai?->locale('id')->translatedFormat('d F Y') }}@if ($expo->tanggal_selesai && ! $expo->tanggal_mulai?->equalTo($expo->tanggal_selesai)) – {{ $expo->tanggal_selesai->locale('id')->translatedFormat('d F Y') }}@endif</p>
                @if ($expo->lokasi)<p class="event-location">{{ $expo->lokasi }}</p>@endif
                <span class="event-tag"><span aria-hidden="true"></span>ABSENSI PENGUNJUNG</span>
            </header>
            <section class="attendance-content" aria-labelledby="attendance-heading">
                @if (session('status'))
                    <div class="status" role="status">{{ session('status') }}</div>
                @endif
                <div class="section-kicker"><span class="line" aria-hidden="true"></span><span>SELAMAT DATANG</span></div>
                <h2 id="attendance-heading">Terima kasih sudah mengunjungi Sumsel Wedding Expo.</h2>
                <p class="intro visitor-welcome">Senang menjadi bagian dari perjalanan menuju hari bahagia Anda. Temukan inspirasi pernikahan, kenali vendor pilihan, dan jelajahi potongan harga serta promo menarik selama kegiatan berlangsung.</p>
                @include('absensi.visitor-prizes')
                @if ($open)
                    <p class="intro">Lengkapi data kunjungan Anda dan jadilah bagian dari kemeriahan expo pada {{ $today->locale('id')->translatedFormat('d F Y') }}.</p>
                    <div class="hours"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg><span>Absensi dibuka <strong>{{ $expo->absensiHoursLabel() }}</strong></span></div>
                    <form method="POST" action="{{ route('absensi.store', $expo) }}">
                        @csrf
                        <div class="field">
                            <label for="email">Alamat email</label>
                            <input id="email" name="email" type="email" inputmode="email" autocomplete="email" required maxlength="255" placeholder="nama@email.com" value="{{ old('email') }}" @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                            @error('email')<p class="error" id="email-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="field">
                            <label for="no_tlp">Nomor telepon</label>
                            <input id="no_tlp" name="no_tlp" type="tel" inputmode="tel" autocomplete="tel" required maxlength="30" placeholder="081234567890" value="{{ old('no_tlp') }}" @error('no_tlp') aria-invalid="true" aria-describedby="phone-error" @enderror>
                            @error('no_tlp')<p class="error" id="phone-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="field">
                            <label for="tujuan">Tujuan kunjungan</label>
                            <select id="tujuan" name="tujuan" required @error('tujuan') aria-invalid="true" aria-describedby="tujuan-error" @enderror>
                                <option value="" disabled @selected(old('tujuan') === null)>Pilih tujuan</option>
                                @foreach (\App\Enums\TujuanKunjungan::options() as $value => $label)
                                    <option value="{{ $value }}" @selected(old('tujuan') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('tujuan')<p class="error" id="tujuan-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="field" id="tujuan-lainnya-field" @if (old('tujuan') !== \App\Enums\TujuanKunjungan::Lainnya->value) hidden @endif>
                            <label for="tujuan_lainnya">Tuliskan tujuan Anda</label>
                            <input id="tujuan_lainnya" name="tujuan_lainnya" type="text" maxlength="255" placeholder="Contoh: Mengantar keluarga" value="{{ old('tujuan_lainnya') }}" @error('tujuan_lainnya') aria-invalid="true" aria-describedby="tujuan-lainnya-error" @enderror>
                            @error('tujuan_lainnya')<p class="error" id="tujuan-lainnya-error">{{ $message }}</p>@enderror
                        </div>
                        <button type="submit">Catat kehadiran <span aria-hidden="true">&rarr;</span></button>
                    </form>
                @else
                    <p class="intro">Kunjungan Anda menjadi bagian istimewa dari cerita Sumsel Wedding Expo. Berikut informasi pencatatan kehadiran untuk edisi ini.</p>
                    <div class="notice" role="status"><span class="notice-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></span><div><h3>Jadwal absensi</h3><p>{{ $notice }}</p></div></div>
                    <p class="note">Catat kehadiran Anda pada jadwal yang tersedia, lalu nikmati pengalaman expo dan berbagai penawaran dari vendor peserta.</p>
                @endif
                <div class="content-footer"><span>SUMSEL WEDDING EXPO</span><a href="{{ url('/') }}">Jelajahi expo <span aria-hidden="true">&nearr;</span></a></div>
            </section>
        </div>
        <footer>Terima kasih telah menjadi bagian dari cerita kami.<a href="{{ route('privacy') }}">Kebijakan Privasi</a></footer>
    </main>
    <script>
        const tujuan = document.getElementById('tujuan');
        const tujuanLainnyaField = document.getElementById('tujuan-lainnya-field');
        const tujuanLainnya = document.getElementById('tujuan_lainnya');

        function syncTujuan(clear) {
            if (!tujuan || !tujuanLainnyaField || !tujuanLainnya) return;
            const show = tujuan.value === @json(\App\Enums\TujuanKunjungan::Lainnya->value);
            tujuanLainnyaField.hidden = !show;
            tujuanLainnya.required = show;
            if (!show && clear) tujuanLainnya.value = '';
        }

        tujuan?.addEventListener('change', () => syncTujuan(true));
        syncTujuan(false);
    </script>
</body>
</html>
