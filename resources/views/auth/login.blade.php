@extends('layouts.app')

@section('title', 'Login — WeddingExpo')
@push('head')
    <meta name="description" content="Login untuk mengakses fitur WeddingExpo.">
@endpush

@section('content')
    <!-- Hero -->
    <section class="pt-24 md:pt-28 pb-10 bg-rose-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl sm:text-4xl font-bold">Login</h1>
            <p class="mt-3 text-neutral-600 max-w-2xl">Masuk ke akun Anda untuk mengakses berbagai fitur WeddingExpo.</p>
        </div>
    </section>

    <!-- Login Form -->
    <section class="py-16">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-8 items-center">
                <!-- Left Side - Image -->
                <div class="hidden lg:block">
                    <div class="rounded-2xl overflow-hidden shadow-2xl">
                        <img 
                            src="https://images.unsplash.com/photo-1519741497674-611481863552?w=800&h=1000&fit=crop" 
                            alt="Wedding Event" 
                            class="w-full h-[600px] object-cover"
                        >
                    </div>
                    <div class="mt-6 text-center">
                        <h3 class="text-2xl font-bold text-neutral-900">Selamat Datang di WeddingExpo</h3>
                        <p class="mt-2 text-neutral-600">Temukan vendor terbaik untuk hari istimewa Anda</p>
                    </div>
                </div>

                <!-- Right Side - Login Form -->
                <div class="w-full">
                    <div class="p-8 rounded-xl border border-neutral-200 bg-white">
                        <h2 class="text-2xl font-bold text-center mb-6">Masuk ke Akun Anda</h2>
                        
                        @if ($errors->any())
                            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-sm text-red-600">{{ $errors->first() }}</p>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                            @csrf
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-neutral-700 mb-1">Email</label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email') }}"
                                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg text-sm" 
                                    placeholder="nama@email.com"
                                    required 
                                    autofocus
                                >
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-neutral-700 mb-1">Password</label>
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg text-sm" 
                                    placeholder="••••••••"
                                    required
                                >
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <input 
                                        type="checkbox" 
                                        id="remember" 
                                        name="remember" 
                                        class="w-4 h-4 border-neutral-300 rounded"
                                    >
                                    <label for="remember" class="ml-2 text-sm text-neutral-700">Ingat saya</label>
                                </div>
                                {{-- <a href="#" class="text-sm text-rose-600 hover:text-rose-700">Lupa password?</a> --}}
                            </div>

                            <button 
                                type="submit" 
                                class="w-full px-4 py-3 bg-rose-600 text-white font-medium rounded-lg hover:bg-rose-700 transition text-sm"
                            >
                                Login
                            </button>
                        </form>

                        <div class="mt-6 text-center">
                            <p class="text-sm text-neutral-600">
                                Belum punya akun? 
                                <a href="/admin" class="text-rose-600 hover:text-rose-700 font-medium">Hubungi Admin</a>
                            </p>
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
                <a href="#" class="hover:text-neutral-900">Syarat & Ketentuan</a>
                <a href="/admin" class="hover:text-neutral-900">Admin</a>
            </div>
        </div>
    </footer>
@endsection
