@extends('layouts.app')

@section('title', 'Daftar Exhibitor — WeddingExpo')
@push('head')
    <meta name="description"
        content="Informasi pendaftaran exhibitor: paket booth, benefit, jadwal teknis, dan cara mendaftar.">
@endpush

@section('content')
    <!-- Hero -->
    <section class="pt-24 md:pt-28 pb-10 bg-rose-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl sm:text-4xl font-bold">Daftar sebagai Exhibitor</h1>
            <p class="mt-3 text-neutral-600 max-w-2xl">Ikut serta sebagai exhibitor dan tampilkan produk/layanan Anda kepada
                calon pengantin serta mitra industri.</p>
        </div>
    </section>

    <!-- Langkah Pendaftaran -->
    <section class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-8 items-start">
            <div class="p-6 rounded-xl border border-neutral-200 bg-white">
                <h2 class="text-xl font-bold">Langkah Pendaftaran</h2>
                <ol class="mt-3 space-y-2 list-decimal list-inside text-neutral-700 text-sm">
                    <li>Pelajari paket booth dan benefit di bawah.</li>
                    <li>Kirim data perusahaan dan kebutuhan melalui formulir atau email.</li>
                    <li>Konfirmasi ketersediaan booth dan tanda jadi.</li>
                    <li>Ikuti jadwal teknis: load-in, briefing, dan load-out.</li>
                </ol>
                <div class="mt-6">
                    <a href="mailto:exhibitor@weddingexpo.id"
                        class="inline-flex items-center px-4 py-2 rounded-full bg-rose-600 text-white hover:bg-rose-700 text-sm">Hubungi
                        Panitia</a>
                </div>
            </div>

            <!-- Paket Booth -->
            <div class="p-6 rounded-xl border border-neutral-200 bg-linear-to-br from-rose-50 to-pink-50">
                <h2 class="text-xl font-bold">Paket Booth</h2>
                <div class="mt-4 grid sm:grid-cols-2 gap-4">
                    <div class="rounded-lg ring-1 ring-neutral-200 p-4 bg-white">
                        <div class="flex items-baseline justify-between">
                            <h3 class="font-semibold">Silver</h3>
                            <div class="text-rose-600 font-bold">Rp 11.000.000</div>
                        </div>
                        <ul class="mt-3 space-y-1 text-sm text-neutral-700">
                            <li>Ukuran booth 2x3 m</li>
                            <li>Listrik</li>
                            <li>Media promosi</li>
                        </ul>
                    </div>
                    <div class="rounded-lg ring-1 ring-neutral-200 p-4 bg-white">
                        <div class="flex items-baseline justify-between">
                            <h3 class="font-semibold">Gold</h3>
                            <div class="text-rose-600 font-bold">Rp 12.000.000</div>
                        </div>
                        <ul class="mt-3 space-y-1 text-sm text-neutral-700">
                            <li>Ukuran booth 2x3 m</li>
                            <li>Listrik</li>
                            <li>Media promosi</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Form Sederhana -->
    <section class="pb-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="p-6 rounded-xl border border-neutral-200 bg-white">
                <h2 class="text-xl font-bold">Form Pendaftaran</h2>

                @guest
                    <div class="mt-4 p-6 bg-rose-50 rounded-lg border border-rose-200">
                        <p class="text-neutral-700 text-sm mb-4">Anda harus login terlebih dahulu untuk mengisi form pendaftaran
                            exhibitor.</p>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('login') }}"
                                class="inline-flex items-center px-4 py-2 rounded-full bg-rose-600 text-white hover:bg-rose-700 text-sm">
                                Login untuk Mendaftar
                            </a>
                            <a href="mailto:exhibitor@weddingexpo.id"
                                class="inline-flex items-center px-4 py-2 rounded-full border border-neutral-300 hover:border-neutral-400 text-sm">
                                Hubungi via Email
                            </a>
                        </div>
                    </div>
                @else
                    @if (isset($registeredAsExhibitor) && $registeredAsExhibitor)
                        <div class="mt-4 p-6 bg-green-50 rounded-lg border border-green-200">
                            <p class="text-neutral-700 text-sm mb-4">Anda sudah terdaftar sebagai exhibitor dengan akun ini.
                                Satu akun hanya bisa mendaftarkan satu vendor.</p>
                            @if (isset($currentVendor) && $currentVendor)
                                <div class="mb-4 rounded-lg ring-1 ring-neutral-200 bg-white p-4">
                                    <h3 class="font-semibold">Detail Vendor</h3>
                                    <dl class="mt-2 grid sm:grid-cols-2 gap-2 text-sm text-neutral-700">
                                        <div>
                                            <dt class="text-neutral-500">Nama Perusahaan</dt>
                                            <dd class="font-medium">{{ $currentVendor->nama_vendor }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-neutral-500">Jenis Usaha</dt>
                                            <dd class="font-medium">{{ $currentVendor->jenisUsaha->nama_jenis_usaha ?? '-' }}
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="text-neutral-500">Paket</dt>
                                            <dd class="font-medium">
                                                {{ \App\Enums\CategoryTier::tryFrom($currentVendor->paket)?->label() ?? ($currentVendor->paket ?? '-') }}
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="text-neutral-500">Lokasi Booth</dt>
                                            <dd class="font-medium">{{ $currentVendor->lokasi_booth ?? '-' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-neutral-500">Harga Paket</dt>
                                            <dd class="font-medium">
                                                {{ isset($currentVendor->harga_jual) ? 'Rp ' . number_format($currentVendor->harga_jual, 0, ',', '.') : '-' }}
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="text-neutral-500">Kontak</dt>
                                            <dd class="font-medium">{{ $currentVendor->email ?? '-' }} •
                                                {{ $currentVendor->no_telepon ?? '-' }}</dd>
                                        </div>
                                    </dl>
                                </div>
                            @endif
                            <div class="flex items-center gap-3">
                                <a href="{{ route('dashboard') }}"
                                    class="inline-flex items-center px-4 py-2 rounded-full bg-neutral-900 text-white hover:bg-neutral-800 text-sm">
                                    Ke Dashboard
                                </a>
                            </div>
                        </div>
                    @else
                        <form method="POST" action="{{ route('exhibitor.store') }}" class="mt-4 grid sm:grid-cols-2 gap-4">
                            @csrf
                            <div>
                                <label for="nama_pendaftar" class="block text-sm text-neutral-600">Nama Pendaftar</label>
                                <input id="nama_pendaftar" name="nama_pendaftar" type="text"
                                    class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm"
                                    value="{{ old('nama_pendaftar', auth()->user()->name) }}" required>
                                @error('nama_pendaftar')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="nama_vendor" class="block text-sm text-neutral-600">Nama Perusahaan</label>
                                <input id="nama_vendor" name="nama_vendor" type="text"
                                    class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm"
                                    value="{{ old('nama_vendor') }}" required>
                                @error('nama_vendor')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="jenis_usaha_id" class="block text-sm text-neutral-600">Kategori (Jenis
                                    Usaha)</label>
                                <select id="jenis_usaha_id" name="jenis_usaha_id"
                                    class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm" required>
                                    <option value="" disabled {{ old('jenis_usaha_id') ? '' : 'selected' }}>Pilih
                                        kategori</option>
                                    @foreach ($jenisUsahas as $ju)
                                        <option value="{{ $ju->id }}"
                                            {{ old('jenis_usaha_id') == $ju->id ? 'selected' : '' }}>
                                            {{ $ju->nama_jenis_usaha }}</option>
                                    @endforeach
                                </select>
                                @error('jenis_usaha_id')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="no_telepon" class="block text-sm text-neutral-600">Kontak (Telepon)</label>
                                <input id="no_telepon" name="no_telepon" type="text"
                                    class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm"
                                    value="{{ old('no_telepon') }}" required>
                                @error('no_telepon')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="pendamping_tenant" class="block text-sm text-neutral-600">Pendamping Tenant
                                    (Opsional)</label>
                                <input id="pendamping_tenant" name="pendamping_tenant" type="text"
                                    class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm placeholder:text-sm"
                                    placeholder="Nama pendamping (jika ada)" value="{{ old('pendamping_tenant') }}">
                                @error('pendamping_tenant')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="email" class="block text-sm text-neutral-600">Email</label>
                                <input id="email" name="email" type="email"
                                    class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm"
                                    value="{{ old('email') }}" required>
                                @error('email')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="nama_pic" class="block text-sm text-neutral-600">Nama PIC</label>
                                <input id="nama_pic" name="nama_pic" type="text"
                                    class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm"
                                    value="{{ old('nama_pic') }}" required>
                                @error('nama_pic')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="no_wa_pic" class="block text-sm text-neutral-600">No. WhatsApp PIC</label>
                                <input id="no_wa_pic" name="no_wa_pic" type="text"
                                    class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm"
                                    value="{{ old('no_wa_pic') }}" required>
                                @error('no_wa_pic')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="kota" class="block text-sm text-neutral-600">Kota</label>
                                <input id="kota" name="kota" type="text"
                                    class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm"
                                    value="{{ old('kota') }}" required>
                                @error('kota')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="alamat" class="block text-sm text-neutral-600">Alamat</label>
                                <textarea id="alamat" name="alamat"
                                    class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm placeholder:text-sm" rows="3"
                                    placeholder="Alamat lengkap perusahaan" required>{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="paket" class="block text-sm text-neutral-600">Paket yang diminati</label>
                                <select id="paket" name="paket"
                                    class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm">
                                    <option value="" disabled {{ old('paket') ? '' : 'selected' }}>Pilih paket</option>
                                    @if (!empty($paketOptions))
                                        @foreach ($paketOptions as $value => $label)
                                            <option value="{{ $value }}"
                                                {{ old('paket') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @if (empty($paketOptions))
                                    <p class="mt-2 text-xs text-neutral-500">Paket belum tersedia untuk expo aktif. Silakan
                                        hubungi panitia.</p>
                                @endif
                                @error('paket')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="display_harga_jual" class="block text-sm text-neutral-600">Harga Paket</label>
                                <input id="display_harga_jual" type="text"
                                    class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm"
                                    value="{{ old('paket') && isset($paketPrices[old('paket')]) ? 'Rp ' . number_format($paketPrices[old('paket')], 0, ',', '.') : '' }}"
                                    readonly>
                                <input id="harga_jual" name="harga_jual" type="hidden"
                                    value="{{ old('paket') && isset($paketPrices[old('paket')]) ? $paketPrices[old('paket')] : '' }}">
                                @error('harga_jual')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="lokasi_booth" class="block text-sm text-neutral-600">Lokasi Booth</label>
                                <input id="lokasi_booth" name="lokasi_booth" type="text"
                                    class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm placeholder:text-sm"
                                    placeholder="Contoh: Hall A, Blok B12" value="{{ old('lokasi_booth') }}">
                                @error('lokasi_booth')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="sm:col-span-2 flex items-center gap-3">
                                <button
                                    class="inline-flex items-center px-4 py-2 rounded-full bg-neutral-900 text-white hover:bg-neutral-800 text-sm"
                                    type="submit">Kirim</button>
                                <a class="inline-flex items-center px-4 py-2 rounded-full border border-neutral-300 hover:border-neutral-400 text-sm"
                                    href="mailto:exhibitor@weddingexpo.id">Kirim via Email</a>
                            </div>
                        </form>
                        <script>
                            (function() {
                                const paketSelect = document.getElementById('paket');
                                const displayHarga = document.getElementById('display_harga_jual');
                                const hargaInput = document.getElementById('harga_jual');
                                const prices = @json($paketPrices ?? []);

                                function formatRupiah(n) {
                                    if (!n || isNaN(n)) return '';
                                    return 'Rp ' + Number(n).toLocaleString('id-ID');
                                }

                                function updateHarga() {
                                    const val = paketSelect.value;
                                    const price = prices[val] ?? '';
                                    displayHarga.value = formatRupiah(price);
                                    hargaInput.value = price || '';
                                }

                                if (paketSelect) {
                                    paketSelect.addEventListener('change', updateHarga);
                                    // Set initial on load
                                    updateHarga();
                                }
                            })();
                        </script>
                    @endif
                @endguest
            </div>
        </div>
    </section>
@endsection
