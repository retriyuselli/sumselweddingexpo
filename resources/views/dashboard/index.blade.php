@extends('layouts.app')

@section('title', 'Dashboard — WeddingExpo')

@section('content')
    <main class="min-h-screen bg-gray-50">

        <section class="pt-24 md:pt-28 pb-10 bg-linear-to-r from-blue-50 to-indigo-50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        @if (auth()->user()->avatar_url)
                            <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}"
                                class="w-12 h-12 sm:w-16 sm:h-16 rounded-full object-cover shadow-lg border-2 border-white"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                            <div
                                class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-linear-to-br from-blue-400 to-indigo-600 items-center justify-center text-white shadow-lg font-bold text-lg sm:text-2xl hidden">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @else
                            <div
                                class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-linear-to-br from-blue-400 to-indigo-600 flex items-center justify-center text-white shadow-lg font-bold text-lg sm:text-2xl">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <h1 class="text-xl sm:text-2xl md:text-3xl font-bold mb-1">Welcome back,
                                {{ auth()->user()->name }}!</h1>
                            <p class="text-xs sm:text-sm text-neutral-600">Here's what's happening with your account</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-8 sm:py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                @if ($registeredAsExhibitor ?? false)
                    <div class="mb-6 rounded-xl border border-blue-200 bg-blue-50 text-blue-800 p-4 sm:p-5">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1">
                                <h2 class="text-sm sm:text-base font-semibold">Anda terdaftar sebagai Exhibitor</h2>
                                <p class="text-xs sm:text-sm mt-1">Customer dapat mengajukan janji temu dengan vendor Anda.
                                    <span class="text-red-600 font-bold">({{ $vendorAppointmentsTotalCount ?? 0 }} janji
                                        temu)</span>
                                </p>

                            </div>
                            <div class="shrink-0 flex items-center gap-2">
                                @if (($isCustomer ?? false) && !($registeredAsExhibitor ?? false))
                                    <a href="{{ route('appointments.index') }}"
                                        class="inline-flex items-center px-3 py-2 rounded-full bg-blue-600 text-white text-xs hover:bg-blue-700">Lihat
                                        Janji Temu</a>
                                    <a href="{{ route('appointments.create') }}"
                                        class="inline-flex items-center px-3 py-2 rounded-full border border-blue-600 text-blue-700 text-xs hover:bg-blue-100">Buat
                                        Janji Temu</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">

                    <div class="bg-white rounded-xl border border-neutral-200 p-4 sm:p-6">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <span
                                class="text-xs px-2 py-1 bg-green-50 text-green-600 rounded-full font-medium">Active</span>
                        </div>
                        <h3 class="text-xs text-neutral-600 mb-1">Account Status</h3>
                        <p class="text-lg sm:text-xl font-bold text-neutral-900">{{ ucfirst($user->name ?? 'User') }}</p>
                    </div>

                    <div class="bg-white rounded-xl border border-neutral-200 p-4 sm:p-6">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-xs text-neutral-600 mb-1">Appointments</h3>
                        <p class="text-lg sm:text-xl font-bold text-neutral-900">{{ $appointmentsCount ?? 0 }}</p>
                        <p class="text-xs text-neutral-500 mt-1">Total janji temu</p>
                    </div>

                    @if (($registeredAsExhibitor ?? false) && !empty($currentVendor))
                        <a href="{{ route('vendors.show', $currentVendor->slug) }}"
                            class="bg-white rounded-xl border border-neutral-200 p-4 sm:p-6 hover:border-rose-300 hover:bg-rose-50 transition block">
                        @else
                            <div class="bg-white rounded-xl border border-neutral-200 p-4 sm:p-6">
                    @endif
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 rounded-lg bg-rose-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xs text-neutral-600 mb-1">Kelola Jadwal Vendor</h3>
                    <p class="text-lg sm:text-xl font-bold text-neutral-900">{{ $vendorAppointmentsTotalCount ?? 0 }}</p>
                    <p class="text-xs text-neutral-500 mt-1">Klik untuk melihat detail</p>
                    @if (($registeredAsExhibitor ?? false) && !empty($currentVendor))
                        </a>
                    @else
                </div>
                @endif

                <div class="bg-white rounded-xl border border-neutral-200 p-4 sm:p-6">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xs text-neutral-600 mb-1">Member Since</h3>
                    <p class="text-lg sm:text-xl font-bold text-neutral-900">{{ $user->created_at->format('M Y') }}</p>
                    <p class="text-xs text-neutral-500 mt-1">{{ $user->created_at->diffForHumans() }}</p>
                </div>
            </div>

            <div class="grid lg:grid-cols-3 gap-6">

                <div class="lg:col-span-2 space-y-6">

                    <div class="bg-white rounded-xl border border-neutral-200 p-4 sm:p-6">
                        <h2 class="text-base sm:text-lg font-bold mb-4">Quick Actions</h2>
                        <div class="grid sm:grid-cols-2 gap-3">
                            <a href="/exhibitor"
                                class="flex items-center gap-3 p-4 border border-neutral-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition group">
                                <div
                                    class="w-10 h-10 rounded-lg bg-blue-50 group-hover:bg-blue-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xs sm:text-sm font-semibold text-neutral-900">
                                        {{ $registeredAsExhibitor ?? false ? 'Terdaftar' : 'Register as Exhibitor' }}
                                    </h3>
                                    <p class="text-xs text-neutral-600">
                                        {{ $registeredAsExhibitor ?? false ? 'Terima kasih telah mendaftar sebagai vendor' : 'Submit your application' }}
                                    </p>
                                </div>
                            </a>

                            @if (($registeredAsExhibitor ?? false) && !empty($currentVendor))
                                <a href="{{ route('vendors.show', $currentVendor->slug) }}#add-product-form"
                                    class="flex items-center gap-3 p-4 border border-neutral-200 rounded-lg hover:border-indigo-300 hover:bg-indigo-50 transition group">
                                    <div class="w-10 h-10 rounded-lg bg-indigo-50 group-hover:bg-indigo-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xs sm:text-sm font-semibold text-neutral-900">Tambah Produk Vendor</h3>
                                        <p class="text-xs text-neutral-600">Buat produk baru untuk vendor Anda</p>
                                    </div>
                                </a>
                            @endif

                            @if (($isCustomer ?? false) && !($registeredAsExhibitor ?? false))
                                <a href="{{ route('appointments.create') }}"
                                    class="flex items-center gap-3 p-4 border border-neutral-200 rounded-lg hover:border-rose-300 hover:bg-rose-50 transition group">
                                    <div
                                        class="w-10 h-10 rounded-lg bg-rose-50 group-hover:bg-rose-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xs sm:text-sm font-semibold text-neutral-900">Buat Janji Temu</h3>
                                        <p class="text-xs text-neutral-600">Atur pertemuan dengan vendor</p>
                                    </div>
                                </a>
                            @endif

                            <a href="/jadwal"
                                class="flex items-center gap-3 p-4 border border-neutral-200 rounded-lg hover:border-purple-300 hover:bg-purple-50 transition group">
                                <div
                                    class="w-10 h-10 rounded-lg bg-purple-50 group-hover:bg-purple-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xs sm:text-sm font-semibold text-neutral-900">View Schedule</h3>
                                    <p class="text-xs text-neutral-600">Check event dates</p>
                                </div>
                            </a>

                            <a href="/partners"
                                class="flex items-center gap-3 p-4 border border-neutral-200 rounded-lg hover:border-rose-300 hover:bg-rose-50 transition group">
                                <div
                                    class="w-10 h-10 rounded-lg bg-rose-50 group-hover:bg-rose-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xs sm:text-sm font-semibold text-neutral-900">Browse Partners</h3>
                                    <p class="text-xs text-neutral-600">View all vendors</p>
                                </div>
                            </a>

                            <a href="/blog"
                                class="flex items-center gap-3 p-4 border border-neutral-200 rounded-lg hover:border-amber-300 hover:bg-amber-50 transition group">
                                <div
                                    class="w-10 h-10 rounded-lg bg-amber-50 group-hover:bg-amber-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xs sm:text-sm font-semibold text-neutral-900">Read Blog</h3>
                                    <p class="text-xs text-neutral-600">Wedding tips & ideas</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    @if (!empty($recentOrders) && count($recentOrders) > 0)
                        <div class="bg-white rounded-xl border border-neutral-200 p-4 sm:p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-base sm:text-lg font-bold">Pesanan Terbaru</h2>
                            </div>
                            <div class="space-y-3">
                                @foreach ($recentOrders as $ord)
                                    @php $pay = $ord->payments->last(); @endphp
                                    <div
                                        class="flex items-center justify-between p-3 rounded-lg border border-neutral-200">
                                        <div>
                                            <p class="text-xs text-neutral-600">Order {{ $ord->code }}</p>
                                            <p class="text-sm font-semibold text-neutral-900">Rp
                                                {{ number_format((float) ($ord->amount_total ?? 0), 0, ',', '.') }}</p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="text-[8px] sm:text-xs px-2 py-1 rounded-xs border {{ $ord->status === 'paid' ? 'border-green-300 text-green-700 bg-green-50' : ($ord->status === 'failed' ? 'border-red-300 text-red-700 bg-red-50' : 'border-amber-300 text-amber-700 bg-amber-50') }}">{{ ucfirst($ord->status) }}</span>
                                            @if ($pay && $pay->external_id)
                                                <a href="{{ route('payment.success') }}?code={{ urlencode($pay->external_id) }}"
                                                    class="text-xs sm:text-sm text-blue-600 hover:text-blue-700 font-medium">Detail</a>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if (!empty($appointmentsPreview) && count($appointmentsPreview) > 0)
                        <div class="bg-white rounded-xl border border-neutral-200 p-4 sm:p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-base sm:text-lg font-bold">Janji Temu Terbaru</h2>
                                <a href="{{ route('appointments.index') }}"
                                    class="text-xs sm:text-sm text-blue-600 hover:text-blue-700 font-medium">Lihat
                                    Semua</a>
                            </div>
                            <div class="space-y-3">
                                @foreach ($appointmentsPreview as $appt)
                                    <div
                                        class="flex items-center justify-between p-3 rounded-lg border border-neutral-200">
                                        <div>
                                            <p class="text-xs text-neutral-600">
                                                {{ $appt->vendor->nama_vendor ?? 'Vendor' }}</p>
                                            <p class="text-sm font-semibold text-neutral-900">
                                                {{ $appt->starts_at?->format('d M Y, H:i') }}</p>
                                        </div>
                                        <span
                                            class="text-xs px-2 py-1 rounded-full border {{ $appt->status === 'confirmed' ? 'border-green-300 text-green-700 bg-green-50' : 'border-amber-300 text-amber-700 bg-amber-50' }}">{{ ucfirst($appt->status) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="bg-white rounded-xl border border-neutral-200 p-4 sm:p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-base sm:text-lg font-bold">Upcoming Events</h2>
                            <a href="/jadwal"
                                class="text-xs sm:text-sm text-blue-600 hover:text-blue-700 font-medium">View All</a>
                        </div>

                        <div class="space-y-3">
                            <div class="flex gap-4 p-4 bg-linear-to-r from-rose-50 to-pink-50 rounded-lg">
                                <div
                                    class="shrink-0 w-12 h-12 sm:w-14 sm:h-14 bg-white rounded-lg flex flex-col items-center justify-center shadow-sm">
                                    <span class="text-lg sm:text-xl font-bold text-rose-600">15</span>
                                    <span class="text-xs text-neutral-600">Feb</span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xs sm:text-sm font-semibold text-neutral-900 mb-1">Sumsel Wedding
                                        Expo 2026</h3>
                                    <p class="text-xs text-neutral-600 mb-2">15-17 Februari 2026 • Palembang Icon</p>
                                    <span
                                        class="inline-block text-xs px-2 py-1 bg-rose-100 text-rose-700 rounded-full font-medium">3
                                        Days Event</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="lg:col-span-1 space-y-6">

                    <div class="bg-white rounded-xl border border-neutral-200 p-4 sm:p-6">
                        <h2 class="text-base sm:text-lg font-bold mb-4">Account Overview</h2>
                        <div class="space-y-4">
                            <div class="flex items-center justify-center mb-4">
                                @if ($user->avatar_url)
                                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}"
                                        class="w-20 h-20 rounded-full object-cover shadow-lg border-2 border-white"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                                    <div
                                        class="w-20 h-20 rounded-full bg-linear-to-br from-blue-400 to-indigo-600 items-center justify-center text-white font-bold text-3xl shadow-lg hidden">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @else
                                    <div
                                        class="w-20 h-20 rounded-full bg-linear-to-br from-blue-400 to-indigo-600 flex items-center justify-center text-white font-bold text-3xl shadow-lg">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="text-center pb-4 border-b border-neutral-200">
                                <h3 class="text-sm font-semibold text-neutral-900">{{ $user->name }}</h3>
                                <p class="text-xs text-neutral-600 mt-1">{{ $user->email }}</p>
                            </div>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-neutral-600">Role</span>
                                    <span
                                        class="font-medium text-neutral-900">{{ ucfirst($user->role->value ?? 'User') }}</span>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-neutral-600">Status</span>
                                    <span
                                        class="px-2 py-1 bg-green-50 text-green-600 rounded-full font-medium">Active</span>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-neutral-600">Joined</span>
                                    <span
                                        class="font-medium text-neutral-900">{{ $user->created_at->format('d M Y') }}</span>
                                </div>
                            </div>
                            <div class="pt-4 space-y-2">
                                <a href="/profile"
                                    class="block w-full px-4 py-2 text-center text-xs sm:text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                                    Edit Profile
                                </a>
                                <a href="/settings"
                                    class="block w-full px-4 py-2 text-center text-xs sm:text-sm border border-neutral-300 text-neutral-700 rounded-lg hover:bg-neutral-50 transition font-medium">
                                    Settings
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="bg-linear-to-br from-blue-600 to-indigo-700 rounded-xl p-4 sm:p-6 text-white">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center mb-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-sm sm:text-base font-bold mb-2">Need Help?</h3>
                        <p class="text-xs opacity-90 mb-4">Have questions? Our support team is here to help you.</p>
                        <a href="https://wa.me/6281373183794" target="_blank"
                            class="inline-flex items-center gap-2 text-xs font-medium hover:underline">
                            Contact Support
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>

                </div>
            </div>

            </div>
        </section>

    </main>
@endsection
