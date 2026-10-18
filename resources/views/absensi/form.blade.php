<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Absensi — {{ $expo->nama_expo }}</title>
    <style>
        :root { color-scheme: light; }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "DM Sans", ui-sans-serif, system-ui, sans-serif;
            background: #faf7f2;
            color: #1f2937;
            padding: max(1.25rem, env(safe-area-inset-top)) 1rem max(1.5rem, env(safe-area-inset-bottom));
        }
        main {
            display: block;
            width: min(28rem, 100%);
            margin-left: auto;
            margin-right: auto;
        }
        h1 {
            margin: 0 0 0.35rem;
            font-size: 1.5rem;
            line-height: 1.25;
            word-break: break-word;
        }
        p { margin: 0; line-height: 1.5; }
        .muted { color: #6b7280; font-size: 0.95rem; }
        .card {
            margin-top: 1.25rem;
            background: #fff;
            border: 1px solid #eadfce;
            border-radius: 1rem;
            padding: 1.25rem;
        }
        label { display: block; font-weight: 600; margin-bottom: 0.35rem; }
        input {
            width: 100%;
            max-width: 100%;
            min-width: 0;
            border: 1px solid #d6d3d1;
            border-radius: 0.75rem;
            padding: 0.8rem 0.9rem;
            font: inherit;
        }
        .field { margin-bottom: 1rem; }
        .error { color: #b91c1c; font-size: 0.875rem; margin-top: 0.35rem; }
        button {
            width: 100%;
            border: 0;
            border-radius: 0.75rem;
            background: #f59e0b;
            color: #111827;
            font: inherit;
            font-weight: 700;
            padding: 0.9rem 1rem;
            cursor: pointer;
        }
        .status {
            margin-top: 1rem;
            background: #ecfdf5;
            color: #065f46;
            border-radius: 0.75rem;
            padding: 0.85rem 1rem;
        }
    </style>
</head>
<body>
    <main>
        <p class="muted">Sumsel Wedding Expo</p>
        <h1>{{ $expo->nama_expo }}</h1>
        <p class="muted">
            {{ $expo->tanggal_mulai?->locale('id')->translatedFormat('d F Y') }}
            @if ($expo->tanggal_selesai && ! $expo->tanggal_mulai?->equalTo($expo->tanggal_selesai))
                – {{ $expo->tanggal_selesai->locale('id')->translatedFormat('d F Y') }}
            @endif
        </p>

        @if (session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif

        <section class="card">
            @if ($open)
                <p style="margin-bottom:1rem;">Isi email dan nomor telepon untuk mencatat kehadiran hari ini, {{ $today->locale('id')->translatedFormat('d F Y') }}. Absensi pukul 09.00–22.00 WIB.</p>
                <form method="POST" action="{{ route('absensi.store', $expo) }}">
                    @csrf
                    <div class="field">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" inputmode="email" autocomplete="email" required maxlength="255" value="{{ old('email') }}">
                        @error('email')<p class="error">{{ $message }}</p>@enderror
                    </div>
                    <div class="field">
                        <label for="no_tlp">Nomor telepon</label>
                        <input id="no_tlp" name="no_tlp" type="tel" inputmode="tel" autocomplete="tel" required maxlength="30" placeholder="081234567890" value="{{ old('no_tlp') }}">
                        @error('no_tlp')<p class="error">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit">Catat kehadiran</button>
                </form>
            @else
                <p>{{ $notice }}</p>
            @endif
        </section>
    </main>
</body>
</html>
