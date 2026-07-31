@extends('layouts.app')

@section('title', 'Gallery — WeddingExpo')
@push('head')
    <meta name="description" content="Kumpulan foto suasana dan inspirasi pernikahan dari WeddingExpo.">
@endpush

@section('content')
    <section class="pt-24 md:pt-28 pb-10 bg-rose-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold">Gallery</h1>
            <p class="mt-2 sm:mt-3 text-sm sm:text-base text-neutral-600">Kumpulan momen, dekorasi, dan inspirasi pernikahan.</p>
        </div>
    </section>

    <section class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if(isset($galleries) && $galleries->count())
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($galleries as $gallery)
                        @foreach($gallery->image_urls as $url)
                            <button type="button" class="relative overflow-hidden rounded-xl ring-1 ring-neutral-200 bg-white group" data-url="{{ $url }}">
                                <img src="{{ $url }}" alt="{{ $gallery->title }}" loading="lazy" class="w-full h-44 sm:h-48 md:h-52 object-cover group-hover:scale-105 transition-transform">
                            </button>
                        @endforeach
                    @endforeach
                </div>
                @if(method_exists($galleries, 'links'))
                    <div class="mt-8 flex justify-center">
                        {{ $galleries->links() }}
                    </div>
                @endif
            @else
                <div class="rounded-xl border border-neutral-200 bg-white p-6 text-center">
                    <h2 class="text-lg font-semibold">Belum ada foto</h2>
                    <p class="mt-2 text-neutral-600">Silakan kembali lagi nanti untuk melihat update terbaru.</p>
                </div>
            @endif
        </div>
    </section>
    <div id="lightbox" class="fixed inset-0 z-50 hidden">
        <div id="lightboxBackdrop" class="absolute inset-0 bg-black/70"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="relative max-w-screen-xl max-h-screen">
                <img id="lightboxImage" src="" alt="Preview" class="max-w-full max-h-[85vh] rounded-xl shadow-2xl">
                <button id="lightboxClose" type="button" class="absolute -top-3 -right-3 bg-white text-neutral-800 rounded-full shadow p-2">
                    ✕
                </button>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var lightbox = document.getElementById('lightbox');
            var lightboxImage = document.getElementById('lightboxImage');
            var backdrop = document.getElementById('lightboxBackdrop');
            var closeBtn = document.getElementById('lightboxClose');

            function openLightbox(url) {
                lightboxImage.src = url;
                lightbox.classList.remove('hidden');
            }

            function closeLightbox() {
                lightbox.classList.add('hidden');
                lightboxImage.src = '';
            }

            document.querySelectorAll('button[data-url]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var url = this.getAttribute('data-url');
                    openLightbox(url);
                });
            });

            backdrop.addEventListener('click', closeLightbox);
            closeBtn.addEventListener('click', closeLightbox);
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeLightbox();
            });
        });
    </script>
@endsection