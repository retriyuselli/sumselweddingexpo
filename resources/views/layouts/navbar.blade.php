<!-- Navbar -->
<header class="fixed top-0 inset-x-0 z-50 bg-white backdrop-blur border-b border-neutral-200">
    <div
        class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-16 grid grid-cols-3 items-center md:flex md:items-center md:justify-between">
        <button id="mobile-menu-button" class="md:hidden justify-self-start p-2 rounded-lg hover:bg-neutral-100"
            aria-label="Toggle menu">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>
        <a href="/" class="flex items-center gap-2 justify-self-center md:justify-self-start">
            @if (isset($penyelenggara) && $penyelenggara?->logo)
                <img src="{{ asset('storage/' . $penyelenggara->logo) }}" alt="{{ $penyelenggara->name }}"
                    class="h-10 w-auto">
            @else
                <img src="/storage/logo/logoswe.png" alt="WeddingExpo Logo" class="h-10 w-auto">
            @endif
        </a>
        <nav class="hidden md:flex items-center gap-8 text-sm">
            <a href="/" class="hover:text-rose-600">Home</a>
            <!-- Dropdown Tentang (desktop) dengan hover -->
            <div class="relative dropdown-hover">
                <button class="inline-flex items-center gap-1 cursor-pointer hover:text-rose-600">
                    Tentang
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                        <path fill-rule="evenodd"
                            d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.08 1.04l-4.25 4.25a.75.75 0 01-1.06 0L5.21 8.27a.75.75 0 01.02-1.06z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
                <div
                    class="dropdown-menu absolute left-0 mt-3 w-48 rounded-lg border border-neutral-200 bg-white shadow-lg opacity-0 invisible transition-all duration-200">
                    <a href="/lokasi" class="block px-3 py-2 hover:bg-neutral-50">Lokasi Pameran</a>
                    <a href="/penyelenggara" class="block px-3 py-2 hover:bg-neutral-50">Penyelenggara</a>
                    <a href="/gallery" class="block px-3 py-2 hover:bg-neutral-50">Gallery</a>
                </div>
            </div>
            <a href="/partners" class="hover:text-rose-600">Vendor</a>
            <a href="/jadwal" class="hover:text-rose-600">Jadwal</a>
            <a href="/blog" class="hover:text-rose-600">Blog</a>
            @auth
                <a href="{{ route('appointments.index') }}" class="hover:text-rose-600">Janji Temu</a>
            @endauth
        </nav>
        <div class="flex items-center gap-3 justify-self-end">
            <div class="relative dropdown-hover">
                <a href="{{ route('cart') }}" class="relative p-2 rounded-lg" aria-label="Keranjang">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 3h1.386c.51 0 .955.343 1.089.835l.383 1.437M7.5 14.25h9.563a1.5 1.5 0 001.433-1.089L20.25 6.75H5.108m2.392 7.5l-1.5 5.25m10.5-5.25l-1.5 5.25M9.75 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm8.25 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                    </svg>
                    <span id="cart-badge"
                        class="absolute -top-1 -right-6 sm:top-7 sm:left-0 z-5 bg-red-600 text-white text-[8px] rounded-full w-4 h-4 flex items-center justify-center">0</span>
                </a>
                <div
                    class="dropdown-menu absolute right-0 mt-2 w-80 rounded-lg border border-neutral-200 bg-white shadow-lg opacity-0 invisible transition-all duration-200">
                    <div class="max-h-64 overflow-auto" id="mini-cart-items"></div>
                    <div class="border-t border-neutral-100 p-3 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-neutral-600">Total</p>
                            <p id="mini-cart-total" class="text-sm font-semibold">Rp 0</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('cart') }}"
                                class="px-3 py-1.5 rounded-lg border border-neutral-300 text-neutral-700 text-xs hover:bg-neutral-50">Keranjang</a>
                            <a href="{{ route('checkout') }}"
                                class="px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-xs hover:bg-indigo-700">Checkout</a>
                        </div>
                    </div>
                </div>
            </div>
            @auth
                <div class="relative md:hidden" id="mobile-profile">
                    <button id="mobile-profile-button" class="p-2 rounded-lg hover:bg-neutral-100" aria-label="Profile">
                        @if (auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}"
                                class="w-8 h-8 rounded-full object-cover">
                        @else
                            <div
                                class="w-8 h-8 rounded-full bg-linear-to-br from-rose-400 to-pink-600 flex items-center justify-center text-white font-semibold text-sm">
                                {{ auth()->user()->initials }}
                            </div>
                        @endif
                    </button>
                    <div id="mobile-profile-menu"
                        class="absolute right-0 mt-2 w-56 rounded-lg border border-neutral-200 bg-white shadow-lg py-1 opacity-0 invisible transition-all duration-200">
                        <div class="px-4 py-3 border-b border-neutral-100">
                            <p class="text-xs text-neutral-500">Signed in as</p>
                            <p class="text-sm font-medium text-neutral-900 truncate">{{ auth()->user()->name }}</p>
                        </div>
                        <a href="/dashboard"
                            class="flex items-center gap-2 px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                            </svg>
                            Dashboard
                        </a>
                        <a href="/profile"
                            class="flex items-center gap-2 px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                            Profile
                        </a>
                        <a href="/settings"
                            class="flex items-center gap-2 px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Settings
                        </a>
                        <a href="{{ route('appointments.index') }}"
                            class="flex items-center gap-2 px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6 8V6a2 2 0 012-2h8a2 2 0 012 2v2M3 10h18M7 14h2m3 0h2m3 0h2M5 20h14a2 2 0 002-2v-8H3v8a2 2 0 002 2z" />
                            </svg>
                            Janji Temu
                        </a>
                        <div class="border-t border-neutral-100 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-2 px-4 py-2 text-sm text-rose-600 hover:bg-neutral-50">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="md:hidden p-2 rounded-lg hover:bg-neutral-100" aria-label="Login">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                        <path fill-rule="evenodd"
                            d="M12 2.25a5.25 5.25 0 100 10.5 5.25 5.25 0 000-10.5zm-7.5 18a7.5 7.5 0 0115 0v.75a.75.75 0 01-.75.75h-13.5a.75.75 0 01-.75-.75v-.75z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            @endauth
            @auth
                <!-- User Profile Dropdown (Desktop) dengan hover -->
                <div class="hidden md:block relative dropdown-hover">
                    <button class="flex items-center gap-2 cursor-pointer hover:opacity-80">
                        <div
                            class="flex items-center gap-2 px-3 py-2 rounded-full border border-neutral-200 hover:border-neutral-300 transition">
                            @if (auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}"
                                    class="w-8 h-8 rounded-full object-cover">
                            @else
                                <div
                                    class="w-8 h-8 rounded-full bg-linear-to-br from-rose-400 to-pink-600 flex items-center justify-center text-white font-semibold text-sm">
                                    {{ auth()->user()->initials }}
                                </div>
                            @endif
                            <span class="text-sm font-medium text-neutral-700">{{ auth()->user()->name }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                class="w-4 h-4 text-neutral-400">
                                <path fill-rule="evenodd"
                                    d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.08 1.04l-4.25 4.25a.75.75 0 01-1.06 0L5.21 8.27a.75.75 0 01.02-1.06z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                    <div
                        class="dropdown-menu absolute right-0 mt-2 w-56 rounded-lg border border-neutral-200 bg-white shadow-lg py-1 opacity-0 invisible transition-all duration-200">
                        <div class="px-4 py-3 border-b border-neutral-100">
                            <p class="text-xs text-neutral-500">Signed in as</p>
                            <p class="text-sm font-medium text-neutral-900 truncate">{{ auth()->user()->email }}</p>
                        </div>

                        <a href="/dashboard"
                            class="flex items-center gap-2 px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                            </svg>
                            Dashboard
                        </a>
                        <a href="/profile"
                            class="flex items-center gap-2 px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                            Profile
                        </a>
                        <a href="/settings"
                            class="flex items-center gap-2 px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Settings
                        </a>
                        <a href="{{ route('appointments.index') }}"
                            class="flex items-center gap-2 px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6 8V6a2 2 0 012-2h8a2 2 0 012 2v2M3 10h18M7 14h2m3 0h2m3 0h2M5 20h14a2 2 0 002-2v-8H3v8a2 2 0 002 2z" />
                            </svg>
                            Janji Temu
                        </a>
                        <div class="border-t border-neutral-100 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-2 px-4 py-2 text-sm text-rose-600 hover:bg-neutral-50">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}"
                    class="hidden sm:inline-flex items-center px-4 py-2 rounded-full bg-neutral-900 text-white hover:bg-neutral-800 text-sm">Login</a>
            @endauth

        </div>
    </div>

    <!-- Mobile Menu (Hidden by default) -->
    <div id="mobile-menu" class="hidden md:hidden border-t border-neutral-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-3 space-y-1">
            <a href="/" class="block px-3 py-2 rounded-lg hover:bg-neutral-50 text-sm">Home</a>

            <!-- Dropdown Tentang (mobile) -->
            <details class="group">
                <summary
                    class="px-3 py-2 rounded-lg hover:bg-neutral-50 cursor-pointer text-sm list-none flex items-center justify-between">
                    <span>Tentang</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        class="w-4 h-4 group-open:rotate-180 transition-transform">
                        <path fill-rule="evenodd"
                            d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.08 1.04l-4.25 4.25a.75.75 0 01-1.06 0L5.21 8.27a.75.75 0 01.02-1.06z"
                            clip-rule="evenodd" />
                    </svg>
                </summary>
                <div class="pl-6 py-1 space-y-1">
                    <a href="/lokasi" class="block px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900">Lokasi
                        Pameran</a>
                    <a href="/penyelenggara"
                        class="block px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900">Penyelenggara</a>
                    <a href="/gallery"
                        class="block px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900">Gallery</a>
                </div>
            </details>

            <a href="/partners" class="block px-3 py-2 rounded-lg hover:bg-neutral-50 text-sm">Vendor</a>
            <a href="/jadwal" class="block px-3 py-2 rounded-lg hover:bg-neutral-50 text-sm">Jadwal</a>
            <a href="/blog" class="block px-3 py-2 rounded-lg hover:bg-neutral-50 text-sm">Blog</a>
            @auth
                <a href="{{ route('appointments.index') }}"
                    class="block px-3 py-2 rounded-lg hover:bg-neutral-50 text-sm">Janji Temu</a>
            @endauth

            {{-- <div class="border-t border-neutral-200 pt-3 mt-3">
                @auth
                    <!-- User Profile (Mobile) -->
                    <div class="px-3 py-3 bg-neutral-50 rounded-lg mb-2">
                        <div class="flex items-center gap-3 mb-3">
                            @if (auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}"
                                    class="w-10 h-10 rounded-full object-cover">
                            @else
                                <div
                                    class="w-10 h-10 rounded-full bg-linear-to-br from-rose-400 to-pink-600 flex items-center justify-center text-white font-semibold">
                                    {{ auth()->user()->initials }}
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-medium text-neutral-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-neutral-500">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                        <a href="/dashboard"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-white text-sm text-neutral-700 mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                            </svg>
                            Dashboard1
                        </a>
                        <a href="/profile"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-white text-sm text-neutral-700 mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                            Profile
                        </a>
                        <a href="/settings"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-white text-sm text-neutral-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Settings
                        </a>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-neutral-50 text-sm text-rose-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                            </svg>
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="block px-3 py-2 rounded-lg hover:bg-neutral-50 text-sm font-medium text-rose-600">Login</a>
                @endauth
            </div> --}}
        </div>
    </div>
</header>

<script>
    // Toggle mobile menu
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });

    const mpBtn = document.getElementById('mobile-profile-button');
    const mpMenu = document.getElementById('mobile-profile-menu');
    const mpContainer = document.getElementById('mobile-profile');
    if (mpBtn && mpMenu && mpContainer) {
        mpBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const opened = mpMenu.classList.contains('opacity-100');
            mpMenu.classList.toggle('opacity-0', opened);
            mpMenu.classList.toggle('invisible', opened);
            mpMenu.classList.toggle('opacity-100', !opened);
            mpMenu.classList.toggle('visible', !opened);
        });
        document.addEventListener('click', function(e) {
            if (!mpMenu.classList.contains('opacity-100')) return;
            if (mpContainer.contains(e.target)) return;
            mpMenu.classList.remove('opacity-100', 'visible');
            mpMenu.classList.add('opacity-0', 'invisible');
        });
    }

    // Dropdown hover functionality
    document.querySelectorAll('.dropdown-hover').forEach(dropdown => {
        const menu = dropdown.querySelector('.dropdown-menu');

        dropdown.addEventListener('mouseenter', () => {
            menu.classList.remove('opacity-0', 'invisible');
            menu.classList.add('opacity-100', 'visible');
        });

        dropdown.addEventListener('mouseleave', () => {
            menu.classList.remove('opacity-100', 'visible');
            menu.classList.add('opacity-0', 'invisible');
        });
    });

    (function() {
        const formatRupiah = (n) => new Intl.NumberFormat('id-ID').format(n || 0);
        const readCart = () => JSON.parse(localStorage.getItem('cartItems') || '[]');
        const updateCartBadge = () => {
            const items = readCart();
            const total = items.reduce((sum, item) => sum + (parseInt(item.qty, 10) || 0), 0);
            const el = document.getElementById('cart-badge');
            if (el) el.textContent = total;
        };
        const renderMiniCart = () => {
            const items = readCart();
            const container = document.getElementById('mini-cart-items');
            const totalEl = document.getElementById('mini-cart-total');
            if (!container) return;
            if (items.length === 0) {
                container.innerHTML = '<div class="p-3 text-xs text-neutral-600">Keranjang kosong.</div>';
                if (totalEl) totalEl.textContent = 'Rp 0';
                return;
            }
            container.innerHTML = items.map(it => {
                const img = it.img ?
                    `<img src="${it.img}" alt="${it.nama_produk || ''}" class="w-12 h-12 rounded object-cover">` :
                    `<div class=\"w-12 h-12 rounded bg-linear-to-br from-blue-400 to-indigo-600 flex items-center justify-center text-white text-xs font-semibold\">${(it.nama_produk||'P').substring(0,1).toUpperCase()}</div>`;
                return `
                    <div class=\"flex items-center gap-3 p-3\">
                        ${img}
                        <div class=\"flex-1\">
                            <p class=\"text-xs font-medium text-neutral-900\">${it.nama_produk || 'Produk'}</p>
                            <p class=\"text-[11px] text-neutral-600\">Qty: ${it.qty || 1} • Rp ${formatRupiah(it.harga || 0)}</p>
                        </div>
                        <button data-product-id=\"${it.product_vendor_id}\" class=\"text-[11px] text-rose-600 remove-mini-item\">Hapus</button>
                    </div>
                `;
            }).join('');
            const total = items.reduce((sum, it) => sum + ((it.harga || 0) * (it.qty || 1)), 0);
            if (totalEl) totalEl.textContent = `Rp ${formatRupiah(total)}`;
            document.querySelectorAll('.remove-mini-item').forEach(btn => {
                btn.addEventListener('click', e => {
                    const id = parseInt(e.target.getAttribute('data-product-id'), 10);
                    const items = readCart().filter(i => i.product_vendor_id !== id);
                    localStorage.setItem('cartItems', JSON.stringify(items));
                    window.dispatchEvent(new Event('storage'));
                    renderMiniCart();
                });
            });
        };
        updateCartBadge();
        renderMiniCart();
        window.addEventListener('storage', updateCartBadge);
        window.addEventListener('storage', renderMiniCart);
    })();
</script>
