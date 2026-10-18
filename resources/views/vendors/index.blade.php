@extends('layouts.app')
@section('title', 'Vendor Saya — Sumsel Wedding Expo')
@section('page-theme', 'swe-unified swe-page-vendors')
@section('content')
@include('layouts.unified-hero', ['pageTitle' => 'Kelola Vendor', 'pageEyebrow' => 'YOUR WEDDING BUSINESS', 'pageDescription' => 'Akses profil vendor dan penawaran pernikahan yang Anda kelola.'])
<section class="py-12"><div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
@forelse ($vendors as $vendor)
<article class="rounded-xl border border-neutral-200 bg-white p-6">
<p class="text-xs text-neutral-600">{{ $vendor->jenisUsaha?->nama_jenis_usaha ?? 'Vendor Pernikahan' }}</p>
<h2 class="mt-3">{{ $vendor->nama_vendor }}</h2>
<p class="mt-3 text-sm text-neutral-600">{{ $vendor->kota ?: 'Lokasi belum dilengkapi' }}</p>
<a href="{{ route('vendors.show', $vendor->slug) }}" class="mt-6 inline-flex items-center px-5 py-3 rounded-lg bg-rose-600 text-white text-sm">Lihat Profil Vendor</a>
</article>
@empty
<div class="col-span-full rounded-xl border border-neutral-200 bg-white p-8 text-center"><h2>Belum ada vendor</h2><p class="mt-3 text-neutral-600">Vendor yang Anda kelola akan ditampilkan di sini.</p><a href="{{ route('exhibitor') }}" class="mt-5 inline-flex px-5 py-3 text-white rounded-lg swe-action-primary">Daftar Exhibitor</a></div>
@endforelse
</div><div class="mt-8">{{ $vendors->links() }}</div>
</div></section>
@endsection
