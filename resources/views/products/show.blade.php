@extends('layouts.app')
@section('title', $product->nama_produk . ' — Sumsel Wedding Expo')
@push('head')
    <meta name="description" content="Lihat rincian {{ $product->nama_produk }} dari {{ $product->vendor?->nama_vendor ?: 'vendor Sumsel Wedding Expo' }}, harga, dan informasi paket pernikahan.">
    @include('home-styles')
    @include('jadwal-styles')
    @include('products/detail-styles')
@endpush
@php
    $vendor = $product->vendor;
    $vendorUrl = $vendor?->slug ? route('vendors.show', $vendor->slug) : route('partners');
    $img = null;
    if ($product->foto_url) {
        if (preg_match('~^https?://~i', $product->foto_url)) {
            $img = filter_var($product->foto_url, FILTER_VALIDATE_URL) ? $product->foto_url : null;
        } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($product->foto_url)) {
            $img = asset('storage/' . $product->foto_url);
        }
    }
    $phone = preg_replace('/\D/', '', (string) $vendor?->no_wa_pic);
    if (str_starts_with($phone, '0')) { $phone = '62' . substr($phone, 1); }
    elseif (str_starts_with($phone, '8')) { $phone = '62' . $phone; }
    $waUrl = strlen($phone) >= 8 && strlen($phone) <= 15 ? 'https://wa.me/' . $phone : null;
    $price = (int) ($product->harga ?? 0);
    $dp = (int) ($product->dp_fixed ?? 0);
    $available = (bool) $product->is_active;
    $description = \App\Support\HtmlSanitizer::clean($product->deskripsi);
    $cartPayload = ['product_vendor_id' => (int) $product->id, 'vendor_id' => (int) $product->vendor_id, 'nama_produk' => $product->nama_produk, 'harga' => $price, 'img' => $img, 'slug' => $product->slug, 'vendor_nama' => $vendor?->nama_vendor ?? '', 'dp_fixed' => $dp];
@endphp
@section('content')
<div class="swe-home swe-product-detail">
    <section class="swe-schedule-hero" aria-labelledby="product-heading"><div class="swe-wrap"><nav class="swe-breadcrumb" aria-label="Jejak navigasi"><a href="{{ route('home') }}">Beranda</a><span aria-hidden="true">/</span><a href="{{ route('partners') }}">Vendor</a><span aria-hidden="true">/</span><a href="{{ $vendorUrl }}">{{ $vendor?->nama_vendor ?: 'Profil vendor' }}</a><span aria-hidden="true">/</span><span aria-current="page">Detail Produk</span></nav><div class="swe-product-hero"><span class="swe-eyebrow swe-light">A LITTLE CLOSER TO YOUR PERFECT DAY</span><h1 id="product-heading">{{ $product->nama_produk }}</h1><p>Pilihan dari <a href="{{ $vendorUrl }}">{{ $vendor?->nama_vendor ?: 'vendor Sumsel Wedding Expo' }} <span aria-hidden="true">↗</span></a></p></div></div></section>

    <section class="swe-section" aria-label="Informasi dan pemesanan produk"><div class="swe-wrap swe-product-layout"><div class="swe-product-content"><div class="swe-product-visual">@if ($img)<img src="{{ $img }}" alt="{{ $product->nama_produk }}" fetchpriority="high">@else<div class="swe-product-placeholder"><span class="swe-eyebrow">SUMSEL WEDDING EXPO</span><span class="swe-product-letter" aria-hidden="true">{{ mb_strtoupper(mb_substr($product->nama_produk, 0, 1)) }}</span><p>Foto produk belum tersedia</p><span>{{ $vendor?->nama_vendor }}</span></div>@endif</div><section class="swe-package-description" id="deskripsi" aria-labelledby="description-heading"><span class="swe-eyebrow">THE DETAILS THAT MATTER</span><h2 id="description-heading">Kenali paketnya.<br>Rencanakan <em>momennya.</em></h2>@if (filled(strip_tags($description ?? '')))<div class="swe-rich-description">{!! $description !!}</div>@else<p class="swe-description-empty">Deskripsi produk belum dicantumkan. Hubungi vendor untuk mengetahui rincian layanan, fasilitas, dan ketentuan paket.</p>@endif</section></div>
        <aside class="swe-product-purchase" aria-labelledby="purchase-heading"><div class="swe-purchase-card"><span class="swe-eyebrow">YOUR WEDDING SELECTION</span><h2 id="purchase-heading">Pilihan Anda.</h2><div class="swe-price-block"><span>Harga produk per unit</span><strong>Rp {{ number_format($price, 0, ',', '.') }}</strong>@if (!$available)<p class="swe-product-inactive">Produk sedang tidak aktif.</p>@endif</div>@if ($dp > 0)<div class="swe-dp-block"><span>DP per unit</span><strong>Rp {{ number_format($dp, 0, ',', '.') }}</strong><p>Pilihan pembayaran mengikuti ketentuan saat checkout.</p></div>@endif<div class="swe-quantity-row"><label for="qty">Jumlah</label><input id="qty" type="number" min="1" step="1" value="1" inputmode="numeric" @disabled(!$available) aria-describedby="quantity-hint"></div><p id="quantity-hint" class="swe-quantity-hint">Sesuaikan jumlah produk yang ingin dimasukkan ke keranjang.</p><div class="swe-subtotal"><span>Subtotal produk</span><strong id="product-subtotal">Rp {{ number_format($price, 0, ',', '.') }}</strong></div><button id="add-to-cart" type="button" class="swe-btn swe-btn-burgundy swe-product-add" @disabled(!$available)>{{ $available ? 'Tambah ke Keranjang' : 'Produk Tidak Aktif' }} <span aria-hidden="true">+</span></button><p id="product-cart-status" role="status" aria-live="polite" class="swe-cart-status"></p><a class="swe-btn swe-btn-outline" href="{{ route('cart') }}">Lihat Keranjang <span aria-hidden="true">↗</span></a><p class="swe-purchase-note">Periksa kembali rincian dan ketentuan produk sebelum melanjutkan pemesanan.</p></div><div class="swe-product-vendor-card"><span class="swe-eyebrow">MEET YOUR VENDOR</span><h3>{{ $vendor?->nama_vendor ?: 'Vendor Pernikahan' }}</h3><p>Diskusikan tanggal pelaksanaan, detail layanan, dan kebutuhan khusus Anda langsung dengan vendor.</p>@if ($waUrl)<a class="swe-text-link" href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer">Tanyakan via WhatsApp <span aria-hidden="true">↗</span></a>@endif<a class="swe-text-link" href="{{ $vendorUrl }}">Lihat Profil Vendor <span aria-hidden="true">↗</span></a></div></aside>
    </div></section>
    <section class="swe-product-steps"><div class="swe-wrap"><div class="swe-section-top"><div><span class="swe-eyebrow">MAKE IT YOURS</span><h2>Dari pilihan,<br>menuju <em>kesepakatan.</em></h2></div><p>Luangkan waktu untuk memastikan produk sesuai dengan rencana pernikahan Anda.</p></div><div class="swe-order-steps">@foreach ([['01','Pelajari detail produk','Baca deskripsi dan tanyakan fasilitas atau layanan yang termasuk dalam paket.'],['02','Diskusikan kebutuhan','Pastikan tanggal, ketersediaan layanan, dan ketentuan pembayaran bersama vendor.'],['03','Tinjau pesanan','Periksa produk dan jumlah di keranjang sebelum melanjutkan ke checkout.']] as [$number,$title,$text])<article><span>{{ $number }}</span><h3>{{ $title }}</h3><p>{{ $text }}</p></article>@endforeach</div></div></section>
    <section class="swe-exhibitor"><div class="swe-wrap swe-exhibitor-inner"><div><span class="swe-eyebrow swe-light">EXPLORE MORE POSSIBILITIES</span><h2>Lengkapi rencana.<br><em>Temukan pilihan lainnya.</em></h2><p>Jelajahi produk dan paket lain dari vendor ini untuk hari istimewa Anda.</p></div><a class="swe-btn swe-btn-cream" href="{{ $vendorUrl }}">Produk Vendor Lainnya <span aria-hidden="true">↗</span></a></div></section>
</div>
@endsection
@push('scripts')
<script>
(() => {
    const product = @json($cartPayload);
    const available = @json($available);
    const quantity = document.getElementById('qty');
    const button = document.getElementById('add-to-cart');
    const subtotal = document.getElementById('product-subtotal');
    const status = document.getElementById('product-cart-status');
    const format = value => 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
    function getQuantity(value) {
        const parsed = Math.floor(Number(value));
        return Number.isSafeInteger(parsed) && parsed > 0 ? parsed : 1;
    }
    function readCart() {
        const items = JSON.parse(localStorage.getItem('cartItems') || '[]');
        if (!Array.isArray(items)) throw new Error('Invalid cart');
        return items;
    }
    function updateSubtotal() { subtotal.textContent = format(product.harga * getQuantity(quantity.value)); }
    function updateState() {
        if (!available) return;
        try {
            const exists = readCart().some(item => Number(item.product_vendor_id) === product.product_vendor_id);
            button.disabled = exists;
            button.textContent = exists ? 'Sudah di Keranjang' : 'Tambah ke Keranjang +';
            quantity.disabled = exists;
            status.textContent = exists ? 'Produk ini sudah ada di keranjang. Ubah jumlah melalui halaman keranjang.' : '';
        } catch (error) {
            status.textContent = 'Keranjang belum dapat dibaca. Coba muat ulang halaman.';
        }
    }
    function addToCart(value) {
        if (!available) return false;
        try {
            const items = readCart();
            const qty = getQuantity(value);
            const index = items.findIndex(item => Number(item.product_vendor_id) === product.product_vendor_id);
            if (index >= 0) items[index].qty = getQuantity(items[index].qty) + qty;
            else items.push({...product, qty});
            localStorage.setItem('cartItems', JSON.stringify(items));
            window.dispatchEvent(new Event('storage'));
            updateState();
            return true;
        } catch (error) {
            status.textContent = 'Produk belum dapat ditambahkan. Periksa pengaturan penyimpanan browser dan coba lagi.';
            return false;
        }
    }
    quantity.addEventListener('input', updateSubtotal);
    quantity.addEventListener('change', () => { quantity.value = getQuantity(quantity.value); updateSubtotal(); });
    button.addEventListener('click', () => { if (!button.disabled) addToCart(quantity.value); });
    window.addEventListener('storage', updateState);
    const params = new URLSearchParams(window.location.search);
    if (params.get('auto_add') === '1' && available) {
        const qty = getQuantity(params.get('qty') || 1);
        quantity.value = qty;
        if (addToCart(qty)) {
            if (params.get('redirect') === 'cart') window.location.href = @json(route('cart'));
            else if (params.get('redirect') === 'checkout') window.location.href = @json(route('checkout')) + '?vendor_id=' + encodeURIComponent(product.vendor_id);
        }
    }
    updateSubtotal(); updateState();
})();
</script>
@endpush
