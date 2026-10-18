@extends('layouts.app')
@section('page-theme', 'swe-unified swe-page-terms')
@section('title', 'Kebijakan Privasi — Sumsel Wedding Expo')
@push('head')
<meta name="description" content="Informasi penggunaan data akun, pendaftaran expo, janji temu, dan transaksi di Sumsel Wedding Expo.">
<meta name="robots" content="noindex, nofollow">
@endpush
@section('content')
@include('layouts.unified-hero', ['pageTitle' => 'Kebijakan Privasi', 'pageEyebrow' => 'YOUR DATA & PRIVACY', 'pageDescription' => 'Kenali informasi yang digunakan saat Anda mengakses layanan Sumsel Wedding Expo.'])
<section class="py-12"><div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 space-y-8 text-sm text-neutral-700">
<div class="rounded-xl border border-neutral-200 bg-white p-6">
<p class="font-medium" style="color:#641b2a">Draf untuk ditinjau penyelenggara</p>
<p class="mt-2">Halaman ini menjelaskan penggunaan data berdasarkan fitur aplikasi yang tersedia. Rincian masa penyimpanan, prosedur permintaan data, dan pihak penerima data perlu dikonfirmasi oleh penyelenggara sebelum kebijakan ini ditetapkan.</p>
</div>
<div><h2>1. Informasi yang Anda berikan</h2><p class="mt-3">Fitur akun menggunakan nama, alamat email, kata sandi, dan informasi profil yang Anda isi. Pendaftaran exhibitor dapat memuat identitas usaha, kontak penanggung jawab, serta informasi keikutsertaan expo. Janji temu menggunakan jadwal, subjek, catatan, dan informasi kontak yang Anda masukkan.</p><p class="mt-3">Saat checkout, aplikasi meminta rincian pesanan dan informasi penagihan, seperti nama, alamat, email, dan nomor telepon.</p></div>
<div><h2>2. Penggunaan informasi</h2><p class="mt-3">Informasi tersebut mendukung pembuatan dan pengelolaan akun, pendaftaran expo, pengaturan janji temu, pemrosesan pesanan, serta penampilan status transaksi. Data vendor yang ditampilkan pada profil publik membantu pengunjung mengenali usaha dan menghubungi vendor.</p></div>
<div><h2>3. Layanan pihak ketiga</h2><p class="mt-3">Aplikasi menyediakan pilihan masuk melalui Google dan pembayaran melalui Midtrans. Jika Anda menggunakan fitur tersebut, layanan terkait juga memproses informasi sesuai kebijakannya. Tautan WhatsApp membuka layanan WhatsApp untuk menghubungi vendor atau penyelenggara.</p><p class="mt-3">Periksa informasi sebelum mengirim pesan atau melanjutkan transaksi pada layanan pihak ketiga.</p></div>
<div><h2>4. Cookie dan penyimpanan perangkat</h2><p class="mt-3">Aplikasi menggunakan sesi dan cookie untuk mendukung akses akun serta pengamanan formulir. Pilihan produk di keranjang disimpan pada penyimpanan lokal browser Anda. Menghapus data situs pada browser dapat menghapus pilihan keranjang dan mengakhiri sesi masuk.</p></div>
<div><h2>5. Pengelolaan informasi akun</h2><p class="mt-3">Anda dapat memeriksa dan memperbarui informasi yang tersedia melalui halaman profil serta pengaturan akun. Untuk pertanyaan mengenai akses, koreksi, atau penghapusan data, hubungi penyelenggara agar permintaan dapat ditinjau. Ketentuan retensi dan prosedur penyelesaian permintaan akan dilengkapi setelah dikonfirmasi.</p></div>
<div><h2>6. Hubungi penyelenggara</h2><p class="mt-3">Gunakan kontak resmi pada halaman penyelenggara untuk mengajukan pertanyaan mengenai penggunaan data Anda.</p><a href="{{ route('penyelenggara') }}" class="inline-flex mt-5 px-5 py-3 rounded-lg text-white swe-action-primary">Kontak Penyelenggara</a></div>
<div class="border-t border-neutral-200 pt-6"><a href="{{ route('terms') }}" style="color:#641b2a">Baca juga Syarat &amp; Ketentuan &rarr;</a></div>
</div></section>
@endsection
