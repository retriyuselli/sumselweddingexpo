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
                <h2 id="attendance-heading">{{ $open ? 'Catat kehadiran Anda.' : 'Sampai jumpa di expo.' }}</h2>
                @if ($open)
                    <p class="intro">Lengkapi informasi berikut untuk mencatat kunjungan Anda pada {{ $today->locale('id')->translatedFormat('d F Y') }}.</p>
                    <div class="hours"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg><span>Absensi dibuka pukul <strong>09.00–22.00 WIB</strong></span></div>
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
                        <button type="submit">Catat kehadiran <span aria-hidden="true">&rarr;</span></button>
                    </form>
                @else
                    <p class="intro">Informasi waktu pencatatan kehadiran untuk edisi expo ini.</p>
                    <div class="notice" role="status"><span class="notice-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></span><div><h3>Jadwal absensi</h3><p>{{ $notice }}</p></div></div>
                    <p class="note">Silakan buka kembali halaman ini pada waktu absensi yang tersedia.</p>
                @endif
                <div class="content-footer"><span>SUMSEL WEDDING EXPO</span><a href="{{ url('/') }}">Jelajahi expo <span aria-hidden="true">&nearr;</span></a></div>
            </section>
        </div>
        <footer>Setiap pertemuan, awal sebuah cerita.<a href="{{ route('privacy') }}">Kebijakan Privasi</a></footer>
    </main>
</body>
</html>
