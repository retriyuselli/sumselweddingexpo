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

                <p class="mt-6 text-center text-sm text-neutral-600">
                    <a href="{{ route('login') }}" class="text-rose-600 hover:text-rose-700 font-medium">Kembali ke Login</a>
                </p>
            </div>
        </div>
    </section>
@endsection
