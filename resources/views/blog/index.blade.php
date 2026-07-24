@extends('layouts.app')

@section('title', 'Blog — WeddingExpo')
@push('head')
    <meta name="description" content="Tips, inspirasi, dan artikel seputar pernikahan dari Sumsel Wedding Expo.">
@endpush

@section('content')
    <main class="min-h-screen">

        <!-- Hero Blog -->
        <section class="pt-24 md:pt-28 pb-10 bg-linear-to-r from-rose-50 to-pink-50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-2 sm:mb-3">Blog & Artikel</h1>
                <p class="text-sm sm:text-base text-neutral-600 max-w-3xl">
                    Dapatkan tips, inspirasi, dan panduan lengkap untuk merencanakan pernikahan impian Anda.
                </p>
            </div>
        </section>

        <!-- Featured Article -->
        @if ($featuredBlog ?? null)
            <section class="py-12 bg-white">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="mb-6 sm:mb-8">
                        <h2 class="text-xl sm:text-2xl font-bold mb-2">Artikel Pilihan</h2>
                        <div class="h-1 w-20 bg-rose-600"></div>
                    </div>

                    <div
                        class="grid md:grid-cols-2 gap-8 items-center bg-linear-to-r from-rose-50 to-pink-50 rounded-2xl overflow-hidden">
                        <div class="order-2 md:order-1 p-6 sm:p-8">
                            <span
                                class="inline-block px-3 py-1 bg-rose-600 text-white text-xs font-semibold rounded-full mb-3 sm:mb-4">Featured</span>
                            <h3 class="text-xl sm:text-2xl md:text-3xl font-bold mb-3 sm:mb-4">{{ $featuredBlog->title }}</h3>
                            <p class="text-sm sm:text-base text-gray-600 mb-4 sm:mb-6 leading-relaxed">
                                {{ $featuredBlog->excerpt }}
                            </p>
                            <div class="flex items-center gap-3 sm:gap-4 text-xs sm:text-sm text-gray-500 mb-4 sm:mb-6">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    {{ optional($featuredBlog->date)->translatedFormat('d M Y') ?? optional($featuredBlog->created_at)->translatedFormat('d M Y') }}
                                </span>
                                @if ($featuredBlog->category)
                                    <span class="flex items-center gap-1">
                                        {{ $featuredBlog->category->name }}
                                    </span>
                                @endif
                            </div>
                            <a href="{{ route('blog.show', $featuredBlog->slug) }}"
                                class="inline-flex items-center px-4 py-2 sm:px-6 sm:py-3 bg-rose-600 text-white rounded-lg font-semibold hover:bg-rose-700 transition text-sm sm:text-base">
                                Baca Selengkapnya
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 ml-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                    </path>
                                </svg>
                            </a>
                        </div>
                        <div class="order-1 md:order-2">
                            <img src="{{ $featuredBlog->image_url }}" alt="{{ $featuredBlog->title }}"
                                class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <!-- Categories -->
        <section class="py-12 bg-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-6 sm:mb-8">
                    <h2 class="text-xl sm:text-2xl font-bold mb-2">Kategori Artikel</h2>
                    <div class="h-1 w-20 bg-rose-600"></div>
                </div>

                <!-- Category Cards -->
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                    @foreach ($categories as $category)
                        <a href="{{ route('blog.category', $category->slug) }}"
                            class="block p-6 rounded-xl border border-neutral-200 bg-linear-to-br from-rose-50 to-pink-50 hover:shadow-lg transition">
                            <h3 class="text-lg font-bold mb-2">{{ $category->name }}</h3>
                            <p class="text-sm text-neutral-700">
                                {{ $category->description ?? 'Jelajahi artikel-artikel menarik seputar ' . strtolower($category->name) . '.' }}
                            </p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Blog Grid -->
        <section class="py-12 bg-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" id="blog-grid">
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">

                    @forelse($blogs as $blog)
                        <!-- Article -->
                        <article
                            class="blog-item bg-white rounded-xl overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300"
                            data-category="{{ $blog->category?->slug }}">
                            <div class="relative overflow-hidden">
                                <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}"
                                    class="w-full h-48 object-cover hover:scale-105 transition-transform duration-300">
                                @if ($blog->category)
                                    <span
                                        class="absolute top-4 left-4 px-3 py-1 bg-{{ $blog->category_color ?: 'purple' }}-600 text-white text-xs font-semibold rounded-full">
                                        {{ $blog->category->name }}
                                    </span>
                                @endif
                            </div>
                            <div class="p-4 sm:p-6">
                                <h3
                                    class="text-base sm:text-lg md:text-xl font-bold mb-2 sm:mb-3 hover:text-rose-600 transition">
                                    <a href="{{ route('blog.show', $blog->slug) }}">{{ $blog->title }}</a>
                                </h3>
                                <p class="text-xs sm:text-sm text-gray-600 mb-3 sm:mb-4 line-clamp-3">
                                    {{ $blog->excerpt }}
                                </p>
                                <div class="flex items-center justify-between text-xs sm:text-sm text-gray-500">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        {{ optional($blog->date)->format('d M Y') ?? optional($blog->created_at)->format('d M Y') }}
                                    </span>
                                    @if ($blog->read_time)
                                        <span>{{ $blog->read_time }} menit baca</span>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <p class="text-gray-500 text-lg">Belum ada artikel blog.</p>
                        </div>
                    @endforelse

                </div>

                <!-- Pagination -->
                <div class="mt-8 sm:mt-12 flex justify-center">
                    {{ $blogs->links() }}
                </div>
            </div>
        </section>

        <!-- Call to Action Exhibitor -->
        <section class="py-16 bg-linear-to-r from-rose-600 to-pink-600 text-white">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 text-center">
                <div class="mb-4 sm:mb-6">
                    <svg class="w-16 h-16 sm:w-20 sm:h-20 mx-auto text-white opacity-90" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </div>
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold mb-3 sm:mb-4">Ingin Bergabung Sebagai Vendor?</h2>
                <p class="text-sm sm:text-base md:text-lg mb-6 sm:mb-8 opacity-90 max-w-2xl mx-auto">
                    Daftarkan bisnis pernikahan Anda di Sumsel Wedding Expo 2026 dan jangkau ribuan calon pengantin
                    yang sedang mencari vendor terbaik untuk hari istimewa mereka!
                </p>
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center items-center">
                    <a href="/exhibitor"
                        class="inline-flex items-center px-6 py-3 sm:px-8 bg-white text-rose-600 font-semibold rounded-lg hover:bg-gray-100 transition shadow-lg text-sm sm:text-base">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Daftar Sebagai Exhibitor
                    </a>
                    <a href="https://wa.me/6281234567890?text=Halo,%20saya%20ingin%20bertanya%20tentang%20pendaftaran%20exhibitor"
                        target="_blank"
                        class="inline-flex items-center px-6 py-3 sm:px-8 bg-transparent border-2 border-white text-white font-semibold rounded-lg hover:bg-white hover:text-rose-600 transition text-sm sm:text-base">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                        </svg>
                        Hubungi via WhatsApp
                    </a>
                </div>
                <div class="mt-6 sm:mt-8 grid grid-cols-3 gap-4 sm:gap-8 max-w-2xl mx-auto text-center">
                    <div>
                        <div class="text-2xl sm:text-3xl font-bold mb-1">100+</div>
                        <div class="text-xs sm:text-sm opacity-90">Vendor Terdaftar</div>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl font-bold mb-1">5000+</div>
                        <div class="text-xs sm:text-sm opacity-90">Pengunjung Expected</div>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl font-bold mb-1">3 Hari</div>
                        <div class="text-xs sm:text-sm opacity-90">Acara Penuh</div>
                    </div>
                </div>
            </div>
        </section>

    </main>
@endsection

@push('scripts')
    <script>
        function filterByCategory(categorySlug) {
            // Update button styles
            const buttons = document.querySelectorAll('.category-filter');
            buttons.forEach(btn => {
                if (btn.dataset.category === categorySlug) {
                    btn.classList.remove('bg-white', 'text-gray-700', 'border-gray-200');
                    btn.classList.add('bg-rose-600', 'text-white', 'border-rose-600');
                } else {
                    btn.classList.remove('bg-rose-600', 'text-white', 'border-rose-600');
                    btn.classList.add('bg-white', 'text-gray-700', 'border-gray-200');
                }
            });

            // Filter blog items
            const blogItems = document.querySelectorAll('.blog-item');
            let visibleCount = 0;

            blogItems.forEach(item => {
                if (categorySlug === 'all' || item.dataset.category === categorySlug) {
                    item.style.display = 'block';
                    // Fade in animation
                    item.style.opacity = '0';
                    setTimeout(() => {
                        item.style.transition = 'opacity 0.3s';
                        item.style.opacity = '1';
                    }, 50);
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            // Show empty state if no items
            const blogGrid = document.getElementById('blog-grid');
            let emptyState = blogGrid.querySelector('.empty-state');

            if (visibleCount === 0) {
                if (!emptyState) {
                    emptyState = document.createElement('div');
                    emptyState.className = 'empty-state col-span-full text-center py-12';
                    emptyState.innerHTML = '<p class="text-gray-500 text-lg">Belum ada artikel dalam kategori ini.</p>';
                    blogGrid.querySelector('.grid').appendChild(emptyState);
                }
            } else {
                if (emptyState) {
                    emptyState.remove();
                }
            }

            // Smooth scroll to blog grid
            blogGrid.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    </script>
@endpush
