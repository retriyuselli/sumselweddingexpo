@extends('layouts.app')

@section('title', 'Lupa Password — WeddingExpo')
@push('head')
    <meta name="description" content="Reset password akun WeddingExpo.">
@endpush

@section('content')
    <section class="pt-24 md:pt-28 pb-10 bg-rose-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl sm:text-4xl font-bold">Lupa Password</h1>
            <p class="mt-3 text-neutral-600 max-w-2xl">Masukkan email akun Anda. Kami akan kirim link untuk mengatur ulang password.</p>
        </div>
    </section>

    <section class="py-16">
        <div class="mx-auto max-w-md px-4 sm:px-6 lg:px-8">
            <div class="p-8 rounded-xl border border-neutral-200 bg-white">
                <h2 class="text-xl font-bold text-center mb-6">Reset Password</h2>

                @if (session('status'))
                    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-3 text-sm text-green-800">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium text-neutral-700 mb-1">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            class="w-full px-4 py-2 border border-neutral-300 rounded-lg text-sm"
                            placeholder="nama@email.com" required autofocus>
                    </div>
                    <button type="submit"
                        class="w-full px-4 py-3 bg-rose-600 text-white font-medium rounded-lg hover:bg-rose-700 transition text-sm">
                        Kirim Link Reset
                    </button>
                </form>

                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="w-full border-t border-neutral-200"></div>
                    </div>
                    <div class="relative flex justify-center text-xs uppercase tracking-wide">
                        <span class="bg-white px-3 text-neutral-500">atau</span>
                    </div>
                </div>

                <a href="{{ route('auth.google.redirect') }}"
                    class="w-full inline-flex items-center justify-center gap-3 px-4 py-3 rounded-lg border border-neutral-300 bg-white text-sm font-medium text-neutral-800 hover:bg-neutral-50 transition">
                    <svg aria-hidden="true" class="h-5 w-5" viewBox="0 0 24 24">
                        <path fill="#4285F4"
                            d="M21.6 12.23c0-.71-.06-1.4-.18-2.07H12v3.91h5.38a4.6 4.6 0 0 1-2 3.02v2.54h3.24c1.9-1.75 2.98-4.33 2.98-7.4Z" />
                        <path fill="#34A853"
                            d="M12 22c2.7 0 4.98-.9 6.63-2.43l-3.24-2.54c-.9.6-2.05.96-3.39.96-2.61 0-4.82-1.76-5.61-4.13H3.04v2.62A10 10 0 0 0 12 22Z" />
                        <path fill="#FBBC05"
                            d="M6.39 13.86A6 6 0 0 1 6.08 12c0-.65.11-1.28.31-1.86V7.52H3.04A10 10 0 0 0 2 12c0 1.61.38 3.14 1.04 4.48l3.35-2.62Z" />
                        <path fill="#EA4335"
                            d="M12 6.01c1.47 0 2.79.51 3.83 1.5l2.87-2.88A9.63 9.63 0 0 0 12 2a10 10 0 0 0-8.96 5.52l3.35 2.62C7.18 7.77 9.39 6.01 12 6.01Z" />
                    </svg>
                    Login dengan Google
                </a>

                <p class="mt-3 text-center text-xs text-neutral-500">
                    Daftar lewat Google? Anda bisa langsung login dengan Google, atau tetap buat password lewat reset email.
                </p>

                <p class="mt-6 text-center text-sm text-neutral-600">
                    <a href="{{ route('login') }}" class="text-rose-600 hover:text-rose-700 font-medium">Kembali ke Login</a>
                </p>
            </div>
        </div>
    </section>
@endsection
