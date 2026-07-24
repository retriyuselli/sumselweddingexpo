@extends('layouts.app')

@section('title', 'Atur Ulang Password — WeddingExpo')

@section('content')
    <section class="pt-24 md:pt-28 pb-10 bg-rose-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl sm:text-4xl font-bold">Atur Ulang Password</h1>
            <p class="mt-3 text-neutral-600 max-w-2xl">Buat password baru untuk akun Anda.</p>
        </div>
    </section>

    <section class="py-16">
        <div class="mx-auto max-w-md px-4 sm:px-6 lg:px-8">
            <div class="p-8 rounded-xl border border-neutral-200 bg-white">
                <h2 class="text-xl font-bold text-center mb-6">Password Baru</h2>

                @if ($errors->any())
                    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div>
                        <label for="email" class="block text-sm font-medium text-neutral-700 mb-1">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $email) }}"
                            class="w-full px-4 py-2 border border-neutral-300 rounded-lg text-sm" required>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-neutral-700 mb-1">Password Baru</label>
                        <input type="password" id="password" name="password"
                            class="w-full px-4 py-2 border border-neutral-300 rounded-lg text-sm" required>
                        <p class="mt-1 text-xs text-neutral-500">Min. 8 karakter, huruf besar &amp; kecil, serta angka.</p>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-neutral-700 mb-1">Konfirmasi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="w-full px-4 py-2 border border-neutral-300 rounded-lg text-sm" required>
                    </div>

                    <button type="submit"
                        class="w-full px-4 py-3 bg-rose-600 text-white font-medium rounded-lg hover:bg-rose-700 transition text-sm">
                        Simpan Password
                    </button>
                </form>

                <p class="mt-4 text-center text-xs text-neutral-500">
                    Jika akun Anda terhubung Google, setelah ini Anda bisa login dengan password baru
                    <span class="whitespace-nowrap">atau tetap memakai</span>
                    <a href="{{ route('auth.google.redirect') }}" class="text-rose-600 hover:text-rose-700 font-medium">Login dengan Google</a>.
                </p>
            </div>
        </div>
    </section>
@endsection
