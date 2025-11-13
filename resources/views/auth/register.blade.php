@extends('layouts.app')

@section('title', 'Daftar Akun — WeddingExpo')

@section('content')
    <section class="pt-24 md:pt-28 pb-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <div class="hidden md:block">
                    <img src="/storage/logo/logoswe.png" alt="WeddingExpo" class="w-48 h-auto">
                    <h1 class="mt-6 text-2xl font-bold">Buat Akun Customer</h1>
                    <p class="mt-2 text-neutral-600">Daftar untuk membuat janji temu dengan vendor dan mendapatkan info terbaru.</p>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <form method="POST" action="{{ route('register.post') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-neutral-700">Nama</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-rose-500">
                            @error('name')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-700">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-rose-500">
                            @error('email')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-700">Password</label>
                            <input type="password" name="password" required
                                   class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-rose-500">
                            @error('password')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-700">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" required
                                   class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-rose-500">
                        </div>

                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-full bg-neutral-900 text-white hover:bg-neutral-800">
                            Daftar
                        </button>

                        <div class="text-center text-sm text-neutral-600">
                            Sudah punya akun?
                            <a href="{{ route('login') }}" class="text-rose-600 hover:text-rose-700 font-medium">Masuk</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection