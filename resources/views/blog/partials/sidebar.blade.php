<aside class="lg:col-span-1">
                        
    <!-- Search Box -->
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-bold mb-4">Cari Artikel</h3>
        <form action="/blog" method="GET" class="relative">
            <input type="text" 
                   name="search" 
                   placeholder="Cari artikel..." 
                   class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500">
            <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-rose-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </button>
        </form>
    </div>

    <!-- Categories -->
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-bold mb-4">Kategori</h3>
        <ul class="space-y-2">
            <li>
                <a href="/blog?category=tips" class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-rose-50 text-gray-700 hover:text-rose-600 transition">
                    <span class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-rose-600"></span>
                        Tips & Trik
                    </span>
                    <span class="text-sm text-gray-400">12</span>
                </a>
            </li>
            <li>
                <a href="/blog?category=inspirasi" class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-green-50 text-gray-700 hover:text-green-600 transition">
                    <span class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-green-600"></span>
                        Inspirasi
                    </span>
                    <span class="text-sm text-gray-400">8</span>
                </a>
            </li>
            <li>
                <a href="/blog?category=budget" class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-amber-50 text-gray-700 hover:text-amber-600 transition">
                    <span class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-amber-600"></span>
                        Budget
                    </span>
                    <span class="text-sm text-gray-400">6</span>
                </a>
            </li>
            <li>
                <a href="/blog?category=dekorasi" class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-purple-50 text-gray-700 hover:text-purple-600 transition">
                    <span class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                        Dekorasi
                    </span>
                    <span class="text-sm text-gray-400">10</span>
                </a>
            </li>
            <li>
                <a href="/blog?category=fashion" class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-pink-50 text-gray-700 hover:text-pink-600 transition">
                    <span class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-pink-600"></span>
                        Fashion
                    </span>
                    <span class="text-sm text-gray-400">15</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Popular Posts -->
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-bold mb-4">Artikel Populer</h3>
        <div class="space-y-4">
            <a href="/blog/panduan-merencanakan-pernikahan-2025" class="flex gap-3 group">
                <img src="https://images.unsplash.com/photo-1519741497674-611481863552?w=80&h=80&fit=crop&auto=format&q=80" 
                     alt="Article" 
                     class="w-20 h-20 rounded-lg object-cover shrink-0">
                <div>
                    <h4 class="font-semibold text-sm mb-1 group-hover:text-rose-600 line-clamp-2">Panduan Lengkap Merencanakan Pernikahan di 2025</h4>
                    <p class="text-xs text-gray-500">5 Nov 2025</p>
                </div>
            </a>
            <a href="/blog/ide-dekorasi-pernikahan-modern" class="flex gap-3 group">
                <img src="https://images.unsplash.com/photo-1606800052052-a08af7148866?w=80&h=80&fit=crop&auto=format&q=80" 
                     alt="Article" 
                     class="w-20 h-20 rounded-lg object-cover shrink-0">
                <div>
                    <h4 class="font-semibold text-sm mb-1 group-hover:text-rose-600 line-clamp-2">10 Ide Dekorasi Pernikahan Modern</h4>
                    <p class="text-xs text-gray-500">3 Nov 2025</p>
                </div>
            </a>
            <a href="/blog/trend-gaun-pengantin-2025" class="flex gap-3 group">
                <img src="https://images.unsplash.com/photo-1515377905703-c4788e51af15?w=80&h=80&fit=crop&auto=format&q=80" 
                     alt="Article" 
                     class="w-20 h-20 rounded-lg object-cover shrink-0">
                <div>
                    <h4 class="font-semibold text-sm mb-1 group-hover:text-rose-600 line-clamp-2">Trend Gaun Pengantin 2025</h4>
                    <p class="text-xs text-gray-500">1 Nov 2025</p>
                </div>
            </a>
            <a href="/blog/tips-memilih-makeup-artist" class="flex gap-3 group">
                <img src="https://images.unsplash.com/photo-1511285560929-80b456fea0bc?w=80&h=80&fit=crop&auto=format&q=80" 
                     alt="Article" 
                     class="w-20 h-20 rounded-lg object-cover shrink-0">
                <div>
                    <h4 class="font-semibold text-sm mb-1 group-hover:text-rose-600 line-clamp-2">Tips Memilih Makeup Artist</h4>
                    <p class="text-xs text-gray-500">28 Okt 2025</p>
                </div>
            </a>
        </div>
    </div>

    <!-- CTA Widget -->
    <div class="bg-gradient-to-br from-rose-600 to-pink-600 rounded-xl p-6 text-white mb-6">
        <h3 class="text-lg font-bold mb-2">Sumsel Wedding Expo 2025</h3>
        <p class="text-sm mb-4 opacity-90">
            Temui vendor terbaik dan dapatkan penawaran eksklusif!
        </p>
        <div class="text-sm mb-4">
            <p class="font-semibold">📅 17-19 Januari 2025</p>
            <p>📍 Palembang Icon</p>
        </div>
        <a href="/jadwal" class="block w-full text-center px-4 py-2 bg-white text-rose-600 rounded-lg font-semibold hover:bg-gray-100 transition">
            Lihat Jadwal
        </a>
    </div>

    <!-- Tags -->
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="text-lg font-bold mb-4">Tags Populer</h3>
        <div class="flex flex-wrap gap-2">
            <a href="/blog?tag=pernikahan" class="px-3 py-1 bg-gray-100 hover:bg-rose-100 text-gray-700 hover:text-rose-600 rounded-full text-sm transition">
                #pernikahan
            </a>
            <a href="/blog?tag=wedding" class="px-3 py-1 bg-gray-100 hover:bg-rose-100 text-gray-700 hover:text-rose-600 rounded-full text-sm transition">
                #wedding
            </a>
            <a href="/blog?tag=budget" class="px-3 py-1 bg-gray-100 hover:bg-rose-100 text-gray-700 hover:text-rose-600 rounded-full text-sm transition">
                #budget
            </a>
            <a href="/blog?tag=dekorasi" class="px-3 py-1 bg-gray-100 hover:bg-rose-100 text-gray-700 hover:text-rose-600 rounded-full text-sm transition">
                #dekorasi
            </a>
            <a href="/blog?tag=vendor" class="px-3 py-1 bg-gray-100 hover:bg-rose-100 text-gray-700 hover:text-rose-600 rounded-full text-sm transition">
                #vendor
            </a>
            <a href="/blog?tag=makeup" class="px-3 py-1 bg-gray-100 hover:bg-rose-100 text-gray-700 hover:text-rose-600 rounded-full text-sm transition">
                #makeup
            </a>
            <a href="/blog?tag=gaun" class="px-3 py-1 bg-gray-100 hover:bg-rose-100 text-gray-700 hover:text-rose-600 rounded-full text-sm transition">
                #gaun
            </a>
            <a href="/blog?tag=tips" class="px-3 py-1 bg-gray-100 hover:bg-rose-100 text-gray-700 hover:text-rose-600 rounded-full text-sm transition">
                #tips
            </a>
        </div>
    </div>

</aside>
