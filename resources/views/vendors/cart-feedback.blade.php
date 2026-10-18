    <div id="cart-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="w-full max-w-sm bg-white rounded-xl shadow-xl border border-neutral-200">
                <div class="p-4 flex items-center gap-3">
                    <div class="w-12 h-12 rounded-lg overflow-hidden bg-neutral-100">
                        <img id="cart-modal-img" src="" alt=""
                            class="w-full h-full object-cover hidden" />
                    </div>
                    <div class="flex-1">
                        <p id="cart-modal-name" class="text-sm font-semibold text-neutral-900"></p>
                        <p id="cart-modal-vendor" class="text-xs text-neutral-600"></p>
                    </div>
                </div>
                <div class="border-t border-neutral-100 p-3 flex items-center justify-between">
                    <p class="text-sm text-neutral-700">Ditambahkan ke keranjang</p>
                    <div class="flex items-center gap-2">
                        <button id="cart-modal-close" type="button"
                            class="px-3 py-1.5 rounded-lg border border-neutral-300 text-neutral-700 text-xs hover:bg-neutral-50">Lanjut
                            belanja</button>
                        <a href="{{ route('cart') }}"
                            class="px-3 py-1.5 rounded-lg text-white text-xs swe-action-primary">Lihat
                            keranjang</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

