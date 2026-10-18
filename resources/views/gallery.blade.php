@extends('layouts.app')
@section('title', 'Galeri — Sumsel Wedding Expo')
@push('head')
    <meta name="description" content="Jelajahi dokumentasi Sumsel Wedding Expo: suasana pameran, dekorasi, fashion show, dan momen pernikahan dari setiap edisi.">
    @include('home-styles')
    @include('jadwal-styles')
    @include('gallery-styles')
@endpush
@php
    $expoOptions = $expos ?? collect();
    $selected = $selectedExpo ?? null;
    $currentEdition = $expoOptions->firstWhere('id', $selected);
    $imageUrl = function ($path) {
        if (!is_string($path) || $path === '') { return null; }
        if (preg_match('~^https?://~i', $path)) { return filter_var($path, FILTER_VALIDATE_URL) ? $path : null; }
        return \Illuminate\Support\Facades\Storage::disk('public')->exists($path) ? asset('storage/' . $path) : null;
    };
    $editionName = fn ($edition) => $edition->nama_expo . (filled($edition->periode) ? ' · ' . $edition->periode : '');
    $albumTotal = $galleries->total();
@endphp
@section('content')
<div class="swe-home swe-gallery-page">
    <section class="swe-schedule-hero" aria-labelledby="gallery-heading"><div class="swe-wrap">
        <nav class="swe-breadcrumb" aria-label="Jejak navigasi"><a href="{{ route('home') }}">Beranda</a><span aria-hidden="true">/</span><span aria-current="page">Galeri</span></nav>
        <div class="swe-schedule-hero-grid"><div><span class="swe-eyebrow swe-light">MOMENTS WORTH REMEMBERING</span><h1 id="gallery-heading">Acara berlalu.<br><em>Momennya abadi.</em></h1><p>Jelajahi suasana pameran, inspirasi pernikahan, dan pertemuan yang menjadi bagian dari cerita Sumsel Wedding Expo.</p><a class="swe-btn swe-btn-cream" href="#koleksi">Jelajahi Galeri <span aria-hidden="true">↘</span></a></div><div class="swe-gallery-hero-note"><span class="swe-eyebrow swe-light">THE EXPO, THROUGH OUR LENS</span><p>Setiap foto,<br>sebuah <em>cerita.</em></p><span>Dokumentasi & inspirasi Sumsel Wedding Expo</span></div></div>
    </div></section>

    <section class="swe-section" id="koleksi" aria-labelledby="collection-heading"><div class="swe-wrap">
        <div class="swe-section-top"><div><span class="swe-eyebrow">A LOOK BACK, AN IDEA FORWARD</span><h2 id="collection-heading">Temukan momen.<br>Simpan <em>inspirasinya.</em></h2></div><p>Pilih edisi untuk melihat dokumentasinya. Tekan foto untuk membuka tampilan layar penuh.</p></div>
        <div class="swe-gallery-toolbar"><div><span class="swe-eyebrow">{{ $currentEdition ? 'EDISI TERPILIH' : 'SEMUA EDISI' }}</span><p>{{ $currentEdition?->nama_expo ?: 'Koleksi Sumsel Wedding Expo' }} <span>{{ $albumTotal }} album</span></p></div><form action="{{ route('gallery') }}" method="GET" class="swe-gallery-filter"><label for="gallery-edition">Edisi expo</label><div><select name="expo" id="gallery-edition"><option value="">Semua edisi</option>@foreach ($expoOptions as $edition)<option value="{{ $edition->id }}" @selected((int) $selected === (int) $edition->id)>{{ $editionName($edition) }}</option>@endforeach</select><button class="swe-btn swe-btn-burgundy" type="submit">Tampilkan <span aria-hidden="true">↗</span></button></div></form></div>
        <div class="swe-gallery-grid">
            @forelse ($galleries as $gallery)
                @php
                    $photos = collect($gallery->image_path ?? [])->map($imageUrl)->filter()->values();
                    $editionLabel = $gallery->expo ? $editionName($gallery->expo) : 'Dokumentasi Sumsel Wedding Expo';
                @endphp
                @forelse ($photos as $photoIndex => $url)
                    <figure class="swe-gallery-item"><a class="swe-gallery-photo" href="{{ $url }}" target="_blank" rel="noopener noreferrer" data-gallery-photo data-caption="{{ $gallery->title ?: 'Momen Sumsel Wedding Expo' }}" data-edition="{{ $editionLabel }}" aria-label="Lihat foto {{ $gallery->title ?: 'Momen Sumsel Wedding Expo' }}{{ $photos->count() > 1 ? ', foto ' . ($photoIndex + 1) : '' }}"><img src="{{ $url }}" alt="{{ $gallery->title ?: 'Dokumentasi Sumsel Wedding Expo' }}{{ $photos->count() > 1 ? ' — foto ' . ($photoIndex + 1) : '' }}" loading="lazy" decoding="async"><span class="swe-photo-expand" aria-hidden="true">↗</span></a><figcaption><span>{{ $editionLabel }}</span><h3>{{ $gallery->title ?: 'Momen Sumsel Wedding Expo' }}</h3>@if ($photos->count() > 1)<p>Foto {{ $photoIndex + 1 }} dari {{ $photos->count() }}</p>@endif</figcaption></figure>
                @empty
                    <figure class="swe-gallery-item"><div class="swe-gallery-unavailable"><span aria-hidden="true">SWE</span><p>Foto belum tersedia</p></div><figcaption><span>{{ $editionLabel }}</span><h3>{{ $gallery->title ?: 'Momen Sumsel Wedding Expo' }}</h3></figcaption></figure>
                @endforelse
            @empty
                <div class="swe-empty"><span class="swe-eyebrow">THE STORY IS STILL UNFOLDING</span><h3>{{ $selected ? 'Belum ada dokumentasi untuk edisi ini.' : 'Momen baru, segera hadir.' }}</h3><p>Dokumentasi akan ditampilkan setelah foto tersedia. Nantikan pembaruan dari Sumsel Wedding Expo.</p>@if ($selected)<a class="swe-text-link" href="{{ route('gallery') }}">Lihat semua edisi <span aria-hidden="true">↗</span></a>@else<a class="swe-text-link" href="https://www.instagram.com/sumselweddingexpo/" target="_blank" rel="noopener noreferrer">Ikuti kabar terbaru <span aria-hidden="true">↗</span></a>@endif</div>
            @endforelse
        </div>
        @if ($galleries->hasPages())<div class="swe-gallery-pagination">{{ $galleries->links() }}</div>@endif
    </div></section>
    <section class="swe-exhibitor"><div class="swe-wrap swe-exhibitor-inner"><div><span class="swe-eyebrow swe-light">BE PART OF THE NEXT STORY</span><h2>Inspirasi hari ini.<br><em>Cerita bahagia berikutnya.</em></h2><p>Kenali vendor peserta dan temukan inspirasi untuk rencana pernikahan Anda.</p></div><a class="swe-btn swe-btn-cream" href="{{ route('peserta.index') }}">Jelajahi Peserta <span aria-hidden="true">↗</span></a></div></section>
</div>
<dialog class="swe-lightbox" id="gallery-lightbox" aria-labelledby="lightbox-caption">
    <div class="swe-lightbox-top"><span data-lightbox-counter aria-live="polite"></span><button type="button" data-lightbox-close aria-label="Tutup foto">Tutup <span aria-hidden="true">×</span></button></div>
    <div class="swe-lightbox-stage"><button type="button" data-lightbox-prev aria-label="Foto sebelumnya">←</button><div class="swe-lightbox-image-wrap"><img data-lightbox-image alt=""><p data-lightbox-error hidden role="status">Foto tidak dapat dimuat. Coba buka gambar asli melalui tautan di bawah.</p></div><button type="button" data-lightbox-next aria-label="Foto berikutnya">→</button></div>
    <div class="swe-lightbox-bottom"><div><p data-lightbox-edition></p><h2 id="lightbox-caption"></h2></div><a data-lightbox-original target="_blank" rel="noopener noreferrer">Buka gambar asli <span aria-hidden="true">↗</span></a></div>
</dialog>
@endsection
@push('scripts')
<script>
(() => {
    const dialog = document.getElementById('gallery-lightbox');
    const photos = [...document.querySelectorAll('[data-gallery-photo]')];
    if (!dialog || typeof dialog.showModal !== 'function' || !photos.length) return;
    const image = dialog.querySelector('[data-lightbox-image]');
    const error = dialog.querySelector('[data-lightbox-error]');
    const previous = dialog.querySelector('[data-lightbox-prev]');
    const next = dialog.querySelector('[data-lightbox-next]');
    const close = dialog.querySelector('[data-lightbox-close]');
    let index = 0;
    let opener;
    let oldOverflow;
    function showPhoto(position) {
        index = (position + photos.length) % photos.length;
        const photo = photos[index];
        error.hidden = true;
        image.hidden = false;
        image.alt = photo.querySelector('img').alt;
        image.src = photo.href;
        dialog.querySelector('#lightbox-caption').textContent = photo.dataset.caption;
        dialog.querySelector('[data-lightbox-edition]').textContent = photo.dataset.edition;
        dialog.querySelector('[data-lightbox-counter]').textContent = `${index + 1} / ${photos.length} foto pada halaman ini`;
        dialog.querySelector('[data-lightbox-original]').href = photo.href;
        previous.hidden = next.hidden = photos.length < 2;
    }
    photos.forEach((photo, position) => photo.addEventListener('click', event => {
        event.preventDefault(); opener = photo; showPhoto(position);
        oldOverflow = document.body.style.overflow;
        document.body.style.overflow = 'hidden';
        dialog.showModal(); close.focus();
    }));
    image.addEventListener('error', () => { image.hidden = true; error.hidden = false; });
    image.addEventListener('load', () => { image.hidden = false; error.hidden = true; });
    close.addEventListener('click', () => dialog.close());
    previous.addEventListener('click', () => showPhoto(index - 1));
    next.addEventListener('click', () => showPhoto(index + 1));
    dialog.addEventListener('click', event => { if (event.target === dialog) dialog.close(); });
    dialog.addEventListener('keydown', event => {
        if (event.key === 'ArrowLeft') { event.preventDefault(); showPhoto(index - 1); }
        if (event.key === 'ArrowRight') { event.preventDefault(); showPhoto(index + 1); }
    });
    dialog.addEventListener('close', () => { document.body.style.overflow = oldOverflow || ''; image.removeAttribute('src'); opener?.focus(); });
    photos.forEach(photo => photo.querySelector('img').addEventListener('error', () => {
        photo.classList.add('swe-gallery-load-failed');
        if (!photo.querySelector('.swe-photo-failure')) {
            const message = document.createElement('span');
            message.className = 'swe-photo-failure'; message.textContent = 'Foto tidak dapat dimuat'; photo.append(message);
        }
    }));
})();
</script>
@endpush
