<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'WeddingExpo')</title>

    <link rel="icon" type="image/png" href="{{ asset('storage/logo/favicon_swe.png') }}">
    <link rel="shortcut icon" href="{{ asset('storage/logo/favicon_swe.png') }}">

    <!-- Font: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>

<body class="font-[Poppins] text-neutral-800 bg-white">
    @include('layouts.navbar')

    @include('layouts.success-modal')

    @auth
        @if (!auth()->user()->isVerified())
            <div x-data="{ open: true }" x-show="open" x-cloak class="fixed inset-0 z-50">
                <div class="absolute inset-0 bg-black/40" @click="open=false" aria-hidden="true"></div>
                <div role="dialog" aria-modal="true" class="absolute inset-0 flex items-center justify-center px-4">
                    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-neutral-200">
                        <div class="p-6">
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-yellow-100 text-yellow-700 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="w-5 h-5">
                                        <path fill-rule="evenodd"
                                            d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12 8.25a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h2 class="text-lg font-semibold text-neutral-900">Email belum diverifikasi</h2>
                                    <p class="mt-1 text-sm text-neutral-700">Silakan verifikasi email Anda untuk mengakses
                                        fitur utama. Jika belum menerima email, kirim ulang verifikasi.</p>
                                </div>
                                <button type="button" @click="open=false" class="text-neutral-500 hover:text-neutral-700"
                                    aria-label="Tutup">&times;</button>
                            </div>
                            <div class="mt-6 flex items-center justify-end gap-3">
                                <form method="POST" action="{{ route('verification.send') }}">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-rose-600 text-white hover:bg-rose-700">Kirim
                                        ulang verifikasi</button>
                                </form>
                                <button type="button" @click="open=false"
                                    class="px-4 py-2 rounded-lg border border-neutral-200 text-neutral-700 hover:bg-neutral-50">Nanti</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endauth

    <main>
        @yield('content')
    </main>

    @hasSection('footer')
        @yield('footer')
    @else
        @include('layouts.footer')
    @endif

    @stack('scripts')
</body>

</html>
