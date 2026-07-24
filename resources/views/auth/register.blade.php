@extends('layouts.app')

@section('title', 'Daftar — WeddingExpo')
@push('head')
    <meta name="description" content="Daftar untuk membuat akun di WeddingExpo.">
@endpush

@section('content')
    <section class="pt-24 md:pt-28 pb-10 bg-rose-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl sm:text-4xl font-bold">Daftar</h1>
            <p class="mt-3 text-neutral-600 max-w-2xl">Buat akun untuk menikmati fitur WeddingExpo.</p>
        </div>
    </section>

    <section class="py-16">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-8 items-center">
                <div class="hidden lg:block">
                    <div class="rounded-2xl overflow-hidden shadow-2xl">
                        @php
                            $penyelenggara = $penyelenggara ?? \App\Models\Penyelenggara::first();
                            $registerBanner =
                                ($penyelenggara->banner_2 ?? null) &&
                                \Illuminate\Support\Facades\Storage::disk('public')->exists($penyelenggara->banner_2)
                                    ? asset('storage/' . $penyelenggara->banner_2)
                                    : 'https://images.unsplash.com/photo-1763235069111-638a143f4eab?q=80&w=1964&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D';
                        @endphp
                        <img src="{{ $registerBanner }}" alt="Register WeddingExpo" class="w-full h-auto object-contain">
                    </div>
                    <div class="mt-6 text-center">
                        <h3 class="text-2xl font-bold text-neutral-900">Gabung bersama kami</h3>
                        <p class="mt-2 text-neutral-600">Temukan inspirasi, vendor, dan promo terbaik</p>
                    </div>
                </div>

                <div class="w-full">
                    <div class="p-8 rounded-xl border border-neutral-200 bg-white">
                        <h2 class="text-2xl font-bold text-center mb-6">Buat Akun Baru</h2>

                        @if ($errors->any())
                            <div id="register-error-toast"
                                class="fixed top-4 right-4 z-50 max-w-sm rounded-lg border border-red-200 bg-red-50 shadow-lg p-4 flex items-start gap-3">
                                <div class="w-8 h-8 rounded-md bg-red-100 text-red-600 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="w-5 h-5">
                                        <path fill-rule="evenodd"
                                            d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12 8.25a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-red-700">{{ $errors->first() }}</p>
                                </div>
                                <button type="button"
                                    onclick="(function(){var el=document.getElementById('register-error-toast'); if(el) el.remove();})();"
                                    class="text-red-500 hover:text-red-700">&times;</button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register.post') }}" class="space-y-4">
                            @csrf

                            <div>
                                <label class="block text-sm font-medium text-neutral-700 mb-1">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg text-sm"
                                    placeholder="Nama lengkap" required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-neutral-700 mb-1">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg text-sm"
                                    placeholder="nama@email.com" required>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-neutral-700 mb-1">Password</label>
                                <div class="relative">
                                    <input type="password" id="reg-password" name="password"
                                        class="w-full pr-10 px-4 py-2 border border-neutral-300 rounded-lg text-sm"
                                        placeholder="Minimal 8 karakter" required>
                                    <button type="button" id="toggle-reg-password" aria-label="Toggle Password"
                                        class="absolute inset-y-0 right-2 my-auto p-1 rounded text-neutral-500 hover:text-neutral-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                            class="w-5 h-5">
                                            <path
                                                d="M12 4.5c-4.58 0-8.54 2.92-10 7.5 1.46 4.58 5.42 7.5 10 7.5s8.54-2.92 10-7.5c-1.46-4.58-5.42-7.5-10-7.5Zm0 12a4.5 4.5 0 1 1 0-9 4.5 4.5 0 0 1 0 9Z" />
                                        </svg>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-neutral-500">Min. 8 karakter, huruf besar &amp; kecil, serta angka.</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-neutral-700 mb-1">Konfirmasi Password</label>
                                <div class="relative">
                                    <input type="password" id="reg-password-confirm" name="password_confirmation"
                                        class="w-full pr-10 px-4 py-2 border border-neutral-300 rounded-lg text-sm"
                                        placeholder="Ulangi password" required>
                                    <button type="button" id="toggle-reg-password-confirm" aria-label="Toggle Password"
                                        class="absolute inset-y-0 right-2 my-auto p-1 rounded text-neutral-500 hover:text-neutral-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                            class="w-5 h-5">
                                            <path
                                                d="M12 4.5c-4.58 0-8.54 2.92-10 7.5 1.46 4.58 5.42 7.5 10 7.5s8.54-2.92 10-7.5c-1.46-4.58-5.42-7.5-10-7.5Zm0 12a4.5 4.5 0 1 1 0-9 4.5 4.5 0 0 1 0 9Z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="flex items-start gap-2">
                                <input type="checkbox" id="terms" name="terms" value="1" required
                                    class="mt-1 w-4 h-4 border-neutral-300 rounded"
                                    {{ old('terms') ? 'checked' : '' }}>
                                <label for="terms" class="text-sm text-neutral-600">
                                    Saya telah membaca dan menyetujui
                                    <a href="{{ route('terms') }}" target="_blank" rel="noopener"
                                        class="text-rose-600 hover:text-rose-700 font-medium">Syarat &amp; Ketentuan</a>
                                    Sumsel Wedding Expo.
                                </label>
                            </div>
                            @error('terms')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-full bg-neutral-900 text-white hover:bg-neutral-800 text-sm">Daftar</button>
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
                            Daftar dengan Google
                        </a>

                        <p class="mt-3 text-center text-xs text-neutral-500">
                            Dengan mendaftar (termasuk melalui Google), Anda menyetujui dan mematuhi
                            <a href="{{ route('terms') }}" class="text-rose-600 hover:text-rose-700 font-medium">Syarat &amp; Ketentuan</a>.
                        </p>

                        <div class="mt-6 text-center text-sm text-neutral-600">
                            Sudah punya akun?
                            <a href="{{ route('login') }}"
                                class="text-rose-600 hover:text-rose-700 font-medium">Masuk</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footer')
    <footer class="py-8 border-t border-neutral-200">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex flex-wrap items-center justify-between gap-4">
            <div class="text-sm text-neutral-600">&copy; {{ date('Y') }} WeddingExpo. Semua hak cipta.</div>
            <div class="flex items-center gap-4 text-sm text-neutral-600">
                <a href="#" class="hover:text-neutral-900">Kebijakan Privasi</a>
                <a href="{{ route('terms') }}" class="hover:text-neutral-900">Syarat & Ketentuan</a>
                @auth
                    @if (auth()->user()->hasRole('super_admin'))
                        <a href="/admin" class="hover:text-neutral-900">Admin</a>
                    @endif
                @endauth
            </div>
        </div>
    </footer>
@endsection

@push('scripts')
    <script>
        setTimeout(function() {
            var el = document.getElementById('register-error-toast');
            if (el) el.remove();
        }, 5000);
        (function() {
            function setupToggle(inputId, btnId) {
                var input = document.getElementById(inputId);
                var btn = document.getElementById(btnId);
                if (!input || !btn) return;
                var shown = false;

                function setIcon() {
                    btn.innerHTML = shown ?
                        '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M3.98 8.223A10.477 10.477 0 0 0 1.5 12c1.46 4.58 5.42 7.5 10 7.5 2.05 0 3.97-.53 5.62-1.46l1.38 1.38a.75.75 0 1 0 1.06-1.06l-17-17a.75.75 0 1 0-1.06 1.06l2.48 2.48Zm5.26 2.64 4.897 4.897A4.5 4.5 0 0 1 9.24 10.864Zm2.76-6.364c-2.05 0-3.97.53-5.62 1.46l1.54 1.54A10.4 10.4 0 0 1 12 6c4.58 0 8.54 2.92 10 7.5-.59 1.84-1.64 3.42-2.98 4.64l1.06 1.06a.75.75 0 1 0 1.06-1.06l-2.02-2.02C20.86 15.41 21.5 14.26 22 13.5c-1.46-4.58-5.42-7.5-10-7.5Z"/></svg>' :
                        '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M12 4.5c-4.58 0-8.54 2.92-10 7.5 1.46 4.58 5.42 7.5 10 7.5s8.54-2.92 10-7.5c-1.46-4.58-5.42-7.5-10-7.5Zm0 12a4.5 4.5 0 1 1 0-9 4.5 4.5 0 0 1 0 9Z"/></svg>';
                }
                btn.addEventListener('click', function() {
                    shown = !shown;
                    input.type = shown ? 'text' : 'password';
                    setIcon();
                });
                setIcon();
            }
            setupToggle('reg-password', 'toggle-reg-password');
            setupToggle('reg-password-confirm', 'toggle-reg-password-confirm');
        })();
    </script>
@endpush
