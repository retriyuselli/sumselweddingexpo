@extends('layouts.app')

@section('title', 'Verifikasi Email — WeddingExpo')

@section('content')
    <section class="pt-24 md:pt-28 pb-16">
        <div class="mx-auto max-w-lg px-4 sm:px-6 lg:px-8">
            <div class="p-8 rounded-xl border border-neutral-200 bg-white text-center">
                <div class="mx-auto w-14 h-14 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-7 h-7">
                        <path d="M1.5 8.67v8.58a3 3 0 003 3h15a3 3 0 003-3V8.67l-8.928 5.493a3 3 0 01-3.144 0L1.5 8.67z" />
                        <path d="M22.5 6.908V6.75a3 3 0 00-3-3h-15a3 3 0 00-3 3v.158l9.714 5.978a1.5 1.5 0 001.572 0L22.5 6.908z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-neutral-900">Verifikasi Email Anda</h1>
                <p class="mt-3 text-sm text-neutral-600">
                    Kami telah mengirim link verifikasi ke
                    <span class="font-medium text-neutral-900">{{ $user->email }}</span>.
                    Buka email tersebut lalu klik link untuk mengaktifkan fitur checkout, appointment, dan dashboard.
                </p>
                <p class="mt-2 text-xs text-neutral-500">Belum menerima? Cek folder spam, atau kirim ulang di bawah.</p>

                <form method="POST" action="{{ route('verification.send') }}" class="mt-6">
                    @csrf
                    <button type="submit"
                        class="w-full px-4 py-3 bg-rose-600 text-white font-medium rounded-lg hover:bg-rose-700 transition text-sm">
                        Kirim Ulang Email Verifikasi
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="text-sm text-neutral-600 hover:text-neutral-900">
                        Logout &amp; ganti akun
                    </button>
                </form>

                <a href="{{ url('/') }}" class="mt-6 inline-block text-sm text-rose-600 hover:text-rose-700 font-medium">
                    Kembali ke beranda
                </a>
            </div>
        </div>
    </section>
@endsection
