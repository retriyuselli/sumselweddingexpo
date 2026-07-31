@extends('layouts.app')

@section('title', 'Gallery — WeddingExpo')
@push('head')
    <meta name="description" content="Kumpulan foto suasana dan inspirasi pernikahan dari WeddingExpo.">
@endpush

@section('content')

    <!-- Hero Gallery -->
    <section class="pt-24 md:pt-28 pb-10 bg-rose-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold">Gallery</h1>
            <p class="mt-2 sm:mt-3 text-sm sm:text-base text-neutral-600">Kumpulan momen, dekorasi, dan inspirasi pernikahan.
            </p>
        </div>
    </section>

    <!-- Grid Foto dari Database Gallery.image_path -->
    <section class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @isset($galleries)
                    @foreach($galleries as $gallery)
                        @foreach($gallery->image_urls as $url)
                            <figure class="relative overflow-hidden rounded-xl ring-1 ring-neutral-200 bg-white">
                                <img src="{{ $url }}" alt="{{ $gallery->title }}" loading="lazy" class="w-full h-44 sm:h-48 md:h-52 object-cover">
                            </figure>
                        @endforeach
                    @endforeach
                @else
                    <p class="col-span-4 text-center text-neutral-600">Belum ada foto yang ditambahkan.</p>
                @endisset
            </div>

            <!-- Ajakan kirim foto -->
            <div class="mt-10 p-6 rounded-xl border border-neutral-200 bg-white">
                <h2 class="text-xl font-bold">Ingin Foto Anda Tampil di Sini?</h2>
                <p class="mt-3 text-neutral-700">Kirimkan foto terbaik acara pernikahan Anda ke email kami: <a
                        href="mailto:gallery@weddingexpo.id"
                        class="text-rose-600 hover:text-rose-700">gallery@weddingexpo.id</a></p>
            </div>
        </div>
    </section>
@endsection
