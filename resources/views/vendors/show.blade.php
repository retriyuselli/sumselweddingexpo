@extends('layouts.app')

@section('title', 'Vendor Detail')

@section('content')
    <main class="min-h-screen bg-gray-50">
        <section class="pt-24 md:pt-28 pb-10 bg-linear-to-r from-blue-50 to-indigo-50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h1 class="text-xl sm:text-2xl md:text-3xl font-bold">{{ $vendor->nama_vendor }}</h1>
                <p class="text-sm text-neutral-600 mt-1">{{ $vendor->jenisUsaha->nama_jenis_usaha ?? '—' }} • {{ $vendor->kota }}</p>
            </div>
        </section>

        <section class="py-8 sm:py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 grid lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-xl border border-neutral-200 p-4 sm:p-6">
                        <h2 class="text-base sm:text-lg font-bold mb-4">Informasi Vendor</h2>
                        <div class="grid sm:grid-cols-2 gap-3 text-sm">
                            <div>
                                <p class="text-neutral-600">Nama Vendor</p>
                                <p class="font-semibold text-neutral-900">{{ $vendor->nama_vendor }}</p>
                            </div>
                            <div>
                                <p class="text-neutral-600">Jenis Usaha</p>
                                <p class="font-semibold text-neutral-900">{{ $vendor->jenisUsaha->nama_jenis_usaha ?? '—' }}</p>
                            </div>
                            <div>
                                <p class="text-neutral-600">Kota</p>
                                <p class="font-semibold text-neutral-900">{{ $vendor->kota }}</p>
                            </div>
                            <div>
                                <p class="text-neutral-600">Lokasi Booth</p>
                                <p class="font-semibold text-neutral-900">{{ $vendor->lokasi_booth ?? '—' }}</p>
                            </div>
                            <div>
                                <p class="text-neutral-600">Paket</p>
                                <p class="font-semibold text-neutral-900">{{ $vendor->paket ?? '—' }}</p>
                            </div>
                            <div>
                                <p class="text-neutral-600">Kontak PIC</p>
                                <p class="font-semibold text-neutral-900">{{ $vendor->nama_pic }} • {{ $vendor->no_wa_pic }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-neutral-200 p-4 sm:p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-base sm:text-lg font-bold">Janji Temu Mendatang</h2>
                            <a href="{{ route('appointments.index') }}" class="text-xs sm:text-sm text-blue-600 hover:text-blue-700 font-medium">Lihat Semua</a>
                        </div>
                        @if ($upcomingAppointments->count() === 0)
                            <p class="text-sm text-neutral-600">Belum ada janji temu yang dijadwalkan.</p>
                        @else
                            <div class="space-y-3">
                                @foreach ($upcomingAppointments as $appt)
                                    <div class="p-3 rounded-lg border border-neutral-200">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-xs text-neutral-600">{{ $appt->customer->name ?? 'Customer' }}</p>
                                                <p class="text-sm font-semibold text-neutral-900">{{ $appt->starts_at?->format('d M Y, H:i') }}</p>
                                                <p class="text-xs text-neutral-700">{{ $appt->subject }}</p>
                                            </div>
                                            <span class="text-xs px-2 py-1 rounded-full border {{ ($appt->status === 'confirmed') ? 'border-green-300 text-green-700 bg-green-50' : (($appt->status === 'rejected') ? 'border-red-300 text-red-700 bg-red-50' : 'border-amber-300 text-amber-700 bg-amber-50') }}">{{ ucfirst($appt->status) }}</span>
                                        </div>

                                        @if ($appt->status === 'requested')
                                            <div class="mt-3 flex flex-col sm:flex-row sm:items-center gap-2">
                                                <input type="text" name="notes" form="confirm-appt-{{ $appt->id }}" placeholder="Catatan (opsional)" class="w-full sm:w-auto flex-1 rounded-lg border border-neutral-300 px-3 py-1.5 text-xs" maxlength="500">

                                                <form id="confirm-appt-{{ $appt->id }}" method="POST" action="{{ route('appointments.updateStatus', $appt->id) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="confirmed">
                                                    <button type="submit" class="px-3 py-1.5 text-xs rounded-lg bg-green-600 text-white hover:bg-green-700">Konfirmasi</button>
                                                </form>
                                                <form method="POST" action="{{ route('appointments.updateStatus', $appt->id) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="rejected">
                                                    <input type="hidden" name="notes" value="" id="reject-notes-{{ $appt->id }}">
                                                    <button type="submit" class="px-3 py-1.5 text-xs rounded-lg bg-red-600 text-white hover:bg-red-700" onclick="document.getElementById('reject-notes-{{ $appt->id }}').value = document.querySelector('[form=confirm-appt-{{ $appt->id }}'][name=notes]').value">Tolak</button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4">
                                {{ $upcomingAppointments->links() }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-xl border border-neutral-200 p-4 sm:p-6">
                        <h2 class="text-base sm:text-lg font-bold mb-4">Aksi</h2>
                        <div class="space-y-2">
                            <a href="{{ route('vendors.edit', $vendor->id) }}" class="block w-full px-4 py-2 text-center text-xs sm:text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">Edit Vendor</a>
                            <a href="{{ route('vendors.index') }}" class="block w-full px-4 py-2 text-center text-xs sm:text-sm border border-neutral-300 text-neutral-700 rounded-lg hover:bg-neutral-50 transition font-medium">Kembali ke Daftar</a>
                            <a href="{{ route('checkout') }}?vendor_id={{ $vendor->id }}" class="block w-full px-4 py-2 text-center text-xs sm:text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">Checkout Vendor Ini</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection