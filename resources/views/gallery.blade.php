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

    <!-- Grid Foto Unsplash -->
    <section class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                <!-- Foto Wedding dari Unsplash dengan URL yang stabil -->
                <figure class="relative overflow-hidden rounded-xl ring-1 ring-neutral-200 bg-white">
                    <img src="https://images.unsplash.com/photo-1519741497674-611481863552?w=800&h=600&fit=crop&auto=format&q=80"
                        alt="Wedding Ceremony Outdoor" loading="lazy" class="w-full h-44 sm:h-48 md:h-52 object-cover">
                </figure>

                <figure class="relative overflow-hidden rounded-xl ring-1 ring-neutral-200 bg-white">
                    <img src="https://images.unsplash.com/photo-1606800052052-a08af7148866?w=800&h=600&fit=crop&auto=format&q=80"
                        alt="Wedding Decoration" loading="lazy" class="w-full h-44 sm:h-48 md:h-52 object-cover">
                </figure>

                <figure class="relative overflow-hidden rounded-xl ring-1 ring-neutral-200 bg-white">
                    <img src="https://images.unsplash.com/photo-1511285560929-80b456fea0bc?w=800&h=600&fit=crop&auto=format&q=80"
                        alt="Bride Makeup and Fashion" loading="lazy" class="w-full h-44 sm:h-48 md:h-52 object-cover">
                </figure>

                <figure class="relative overflow-hidden rounded-xl ring-1 ring-neutral-200 bg-white">
                    <img src="https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?w=800&h=600&fit=crop&auto=format&q=80"
                        alt="Wedding Reception" loading="lazy" class="w-full h-44 sm:h-48 md:h-52 object-cover">
                </figure>

                <figure class="relative overflow-hidden rounded-xl ring-1 ring-neutral-200 bg-white">
                    <img src="https://images.unsplash.com/photo-1583939003579-730e3918a45a?w=800&h=600&fit=crop&auto=format&q=80"
                        alt="Wedding Bouquet" loading="lazy" class="w-full h-44 sm:h-48 md:h-52 object-cover">
                </figure>

                <figure class="relative overflow-hidden rounded-xl ring-1 ring-neutral-200 bg-white">
                    <img src="https://images.unsplash.com/photo-1591604466107-ec97de577aff?w=800&h=600&fit=crop&auto=format&q=80"
                        alt="Wedding Couple Portrait" loading="lazy" class="w-full h-44 sm:h-48 md:h-52 object-cover">
                </figure>

                <figure class="relative overflow-hidden rounded-xl ring-1 ring-neutral-200 bg-white">
                    <img src="https://images.unsplash.com/photo-1537633552985-df8429e8048b?w=800&h=600&fit=crop&auto=format&q=80"
                        alt="Wedding Rings" loading="lazy" class="w-full h-44 sm:h-48 md:h-52 object-cover">
                </figure>

                <figure class="relative overflow-hidden rounded-xl ring-1 ring-neutral-200 bg-white">
                    <img src="https://images.unsplash.com/photo-1529636798458-92182e662485?w=800&h=600&fit=crop&auto=format&q=80"
                        alt="Bride and Groom" loading="lazy" class="w-full h-44 sm:h-48 md:h-52 object-cover">
                </figure>

                <figure class="relative overflow-hidden rounded-xl ring-1 ring-neutral-200 bg-white">
                    <img src="https://images.unsplash.com/photo-1513002749550-c59d786b8e6c?w=800&h=600&fit=crop&auto=format&q=80"
                        alt="Wedding Venue" loading="lazy" class="w-full h-44 sm:h-48 md:h-52 object-cover">
                </figure>

                <figure class="relative overflow-hidden rounded-xl ring-1 ring-neutral-200 bg-white">
                    <img src="https://images.unsplash.com/photo-1515377905703-c4788e51af15?w=800&h=600&fit=crop&auto=format&q=80"
                        alt="Wedding Dress Details" loading="lazy" class="w-full h-44 sm:h-48 md:h-52 object-cover">
                </figure>

                <figure class="relative overflow-hidden rounded-xl ring-1 ring-neutral-200 bg-white">
                    <img src="https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=800&h=600&fit=crop&auto=format&q=80"
                        alt="Wedding Dance" loading="lazy" class="w-full h-44 sm:h-48 md:h-52 object-cover">
                </figure>

                <figure class="relative overflow-hidden rounded-xl ring-1 ring-neutral-200 bg-white">
                    <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=800&h=600&fit=crop&auto=format&q=80"
                        alt="Wedding Table Setting" loading="lazy" class="w-full h-44 sm:h-48 md:h-52 object-cover">
                </figure>
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
