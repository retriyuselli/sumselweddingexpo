@extends('layouts.app')

@section('title', 'Partners — WeddingExpo')
@push('head')
    <meta name="description" content="Daftar partner, media, dan exhibitor WeddingExpo.">
@endpush

@section('content')
    <main class="min-h-screen">

        <!-- Hero Lokasi -->
        <section class="pt-24 md:pt-28 pb-10 bg-rose-50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold">Partners</h1>
                <p class="mt-2 sm:mt-3 text-sm sm:text-base text-neutral-600">Terima kasih kepada mitra strategis, media partner, dan
                    exhibitor yang mendukung kesuksesan Wedding Expo.</p>
            </div>
        </section>

        <!-- Partner Categories -->
        <section class="py-16 bg-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                
                <!-- Strategic Partners -->
                <div class="mb-16">
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-center mb-2 sm:mb-3">Strategic Partners</h2>
                    <p class="text-center text-sm sm:text-base text-neutral-600 mb-8 sm:mb-10">Mitra strategis yang mendukung penyelenggaraan Wedding Expo</p>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        <div class="bg-white border border-gray-200 rounded-lg p-6 flex items-center justify-center hover:shadow-lg transition-shadow duration-300">
                            <img src="https://images.unsplash.com/photo-1599658880436-c61792e70672?w=300&h=150&fit=crop&auto=format&q=80" alt="PT Makna Kreatif Indonesia" class="max-h-20 w-auto object-contain">
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-6 flex items-center justify-center hover:shadow-lg transition-shadow duration-300">
                            <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=300&h=150&fit=crop&auto=format&q=80" alt="Palembang Icon" class="max-h-20 w-auto object-contain">
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-6 flex items-center justify-center hover:shadow-lg transition-shadow duration-300">
                            <img src="https://images.unsplash.com/photo-1497366811353-6870744d04b2?w=300&h=150&fit=crop&auto=format&q=80" alt="Strategic Partner 3" class="max-h-20 w-auto object-contain">
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-6 flex items-center justify-center hover:shadow-lg transition-shadow duration-300">
                            <img src="https://images.unsplash.com/photo-1497215728101-856f4ea42174?w=300&h=150&fit=crop&auto=format&q=80" alt="Strategic Partner 4" class="max-h-20 w-auto object-contain">
                        </div>
                    </div>
                </div>

                <!-- Media Partners -->
                <div class="mb-16">
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-center mb-2 sm:mb-3">Media Partners</h2>
                    <p class="text-center text-sm sm:text-base text-neutral-600 mb-8 sm:mb-10">Media yang meliput dan mempromosikan Wedding Expo</p>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                        <div class="bg-white border border-gray-200 rounded-lg p-4 flex items-center justify-center hover:shadow-lg transition-shadow duration-300">
                            <img src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=200&h=100&fit=crop&auto=format&q=80" alt="Media Partner 1" class="max-h-16 w-auto object-contain">
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-4 flex items-center justify-center hover:shadow-lg transition-shadow duration-300">
                            <img src="https://images.unsplash.com/photo-1487017159836-4e23ece2e4cf?w=200&h=100&fit=crop&auto=format&q=80" alt="Media Partner 2" class="max-h-16 w-auto object-contain">
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-4 flex items-center justify-center hover:shadow-lg transition-shadow duration-300">
                            <img src="https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?w=200&h=100&fit=crop&auto=format&q=80" alt="Media Partner 3" class="max-h-16 w-auto object-contain">
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-4 flex items-center justify-center hover:shadow-lg transition-shadow duration-300">
                            <img src="https://images.unsplash.com/photo-1556761175-b413da4baf72?w=200&h=100&fit=crop&auto=format&q=80" alt="Media Partner 4" class="max-h-16 w-auto object-contain">
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-4 flex items-center justify-center hover:shadow-lg transition-shadow duration-300">
                            <img src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=200&h=100&fit=crop&auto=format&q=80" alt="Media Partner 5" class="max-h-16 w-auto object-contain">
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-4 flex items-center justify-center hover:shadow-lg transition-shadow duration-300">
                            <img src="https://images.unsplash.com/photo-1521737711867-e3b97375f902?w=200&h=100&fit=crop&auto=format&q=80" alt="Media Partner 6" class="max-h-16 w-auto object-contain">
                        </div>
                    </div>
                </div>

                <!-- Exhibitors -->
                <div class="mb-16">
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-center mb-2 sm:mb-3">Featured Exhibitors</h2>
                    <p class="text-center text-sm sm:text-base text-neutral-600 mb-8 sm:mb-10">Vendor dan exhibitor terpilih yang berpartisipasi di Wedding Expo</p>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                        <div class="bg-white border border-gray-200 rounded-lg p-5 flex items-center justify-center hover:shadow-lg transition-shadow duration-300">
                            <img src="https://images.unsplash.com/photo-1606800052052-a08af7148866?w=200&h=120&fit=crop&auto=format&q=80" alt="Wedding Decoration Vendor" class="max-h-16 w-auto object-contain">
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-5 flex items-center justify-center hover:shadow-lg transition-shadow duration-300">
                            <img src="https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=200&h=120&fit=crop&auto=format&q=80" alt="Bridal Fashion Vendor" class="max-h-16 w-auto object-contain">
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-5 flex items-center justify-center hover:shadow-lg transition-shadow duration-300">
                            <img src="https://images.unsplash.com/photo-1492691527719-9d1e07e534b4?w=200&h=120&fit=crop&auto=format&q=80" alt="Photography Vendor" class="max-h-16 w-auto object-contain">
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-5 flex items-center justify-center hover:shadow-lg transition-shadow duration-300">
                            <img src="https://images.unsplash.com/photo-1555244162-803834f70033?w=200&h=120&fit=crop&auto=format&q=80" alt="Catering Vendor" class="max-h-16 w-auto object-contain">
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-5 flex items-center justify-center hover:shadow-lg transition-shadow duration-300">
                            <img src="https://images.unsplash.com/photo-1478146896981-b80fe463b330?w=200&h=120&fit=crop&auto=format&q=80" alt="Venue Vendor" class="max-h-16 w-auto object-contain">
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-5 flex items-center justify-center hover:shadow-lg transition-shadow duration-300">
                            <img src="https://images.unsplash.com/photo-1490750967868-88aa4486c946?w=200&h=120&fit=crop&auto=format&q=80" alt="Florist Vendor" class="max-h-16 w-auto object-contain">
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-5 flex items-center justify-center hover:shadow-lg transition-shadow duration-300">
                            <img src="https://images.unsplash.com/photo-1514320291840-2e0a9bf2a9ae?w=200&h=120&fit=crop&auto=format&q=80" alt="Music & Entertainment" class="max-h-16 w-auto object-contain">
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-5 flex items-center justify-center hover:shadow-lg transition-shadow duration-300">
                            <img src="https://images.unsplash.com/photo-1487412947147-5cebf100ffc2?w=200&h=120&fit=crop&auto=format&q=80" alt="Makeup Artist Vendor" class="max-h-16 w-auto object-contain">
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-5 flex items-center justify-center hover:shadow-lg transition-shadow duration-300">
                            <img src="https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?w=200&h=120&fit=crop&auto=format&q=80" alt="Jewelry Vendor" class="max-h-16 w-auto object-contain">
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-5 flex items-center justify-center hover:shadow-lg transition-shadow duration-300">
                            <img src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=200&h=120&fit=crop&auto=format&q=80" alt="Invitation Vendor" class="max-h-16 w-auto object-contain">
                        </div>
                    </div>
                </div>

                <!-- Supporting Partners -->
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-center mb-3">Supporting Partners</h2>
                    <p class="text-center text-neutral-600 mb-10">Sponsor dan pendukung acara Wedding Expo</p>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        <div class="bg-white border border-gray-200 rounded-lg p-6 flex items-center justify-center hover:shadow-lg transition-shadow duration-300">
                            <img src="https://images.unsplash.com/photo-1560472355-536de3962603?w=300&h=150&fit=crop&auto=format&q=80" alt="Supporting Partner 1" class="max-h-20 w-auto object-contain">
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-6 flex items-center justify-center hover:shadow-lg transition-shadow duration-300">
                            <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=300&h=150&fit=crop&auto=format&q=80" alt="Supporting Partner 2" class="max-h-20 w-auto object-contain">
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-6 flex items-center justify-center hover:shadow-lg transition-shadow duration-300">
                            <img src="https://images.unsplash.com/photo-1497366754035-f200968a6e72?w=300&h=150&fit=crop&auto=format&q=80" alt="Supporting Partner 3" class="max-h-20 w-auto object-contain">
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-6 flex items-center justify-center hover:shadow-lg transition-shadow duration-300">
                            <img src="https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=300&h=150&fit=crop&auto=format&q=80" alt="Supporting Partner 4" class="max-h-20 w-auto object-contain">
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- Call to Action -->
        <section class="py-16 bg-gradient-to-r from-rose-50 to-pink-50">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-2xl sm:text-3xl font-bold mb-4">Tertarik Menjadi Partner Kami?</h2>
                <p class="text-lg text-neutral-600 mb-8">
                    Bergabunglah dengan Wedding Expo sebagai mitra strategis, media partner, atau exhibitor.
                    Mari bersama-sama menciptakan event pernikahan terbaik di Sumatera Selatan.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/contact" class="inline-flex items-center justify-center px-8 py-3 bg-rose-600 text-white font-semibold rounded-lg hover:bg-rose-700 transition-colors duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Hubungi Kami
                    </a>
                    <a href="/register" class="inline-flex items-center justify-center px-8 py-3 bg-white text-rose-600 font-semibold rounded-lg border-2 border-rose-600 hover:bg-rose-50 transition-colors duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        Daftar Sebagai Exhibitor
                    </a>
                </div>
                
                <!-- Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-12">
                    <div class="bg-white rounded-lg p-6 shadow-md">
                        <div class="text-3xl font-bold text-rose-600 mb-2">100+</div>
                        <div class="text-sm text-neutral-600">Exhibitors</div>
                    </div>
                    <div class="bg-white rounded-lg p-6 shadow-md">
                        <div class="text-3xl font-bold text-rose-600 mb-2">50+</div>
                        <div class="text-sm text-neutral-600">Media Partners</div>
                    </div>
                    <div class="bg-white rounded-lg p-6 shadow-md">
                        <div class="text-3xl font-bold text-rose-600 mb-2">20+</div>
                        <div class="text-sm text-neutral-600">Strategic Partners</div>
                    </div>
                    <div class="bg-white rounded-lg p-6 shadow-md">
                        <div class="text-3xl font-bold text-rose-600 mb-2">10K+</div>
                        <div class="text-sm text-neutral-600">Visitors</div>
                    </div>
                </div>
            </div>
        </section>

    </main>

@endsection

@section('footer')
    <footer class="py-8 border-t border-neutral-200">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex flex-wrap items-center justify-between gap-4">
            <div class="text-sm text-neutral-600">&copy; {{ date('Y') }} WeddingExpo. Semua hak cipta.</div>
            <div class="flex items-center gap-4 text-sm text-neutral-600">
                <a href="#" class="hover:text-neutral-900">Kebijakan Privasi</a>
                <a href="#" class="hover:text-neutral-900">Syarat & Ketentuan</a>
                <a href="/admin" class="hover:text-neutral-900">Admin</a>
            </div>
        </div>
    </footer>
@endsection
