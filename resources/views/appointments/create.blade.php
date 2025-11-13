@extends('layouts.app')

@section('title', 'Buat Janji Temu — WeddingExpo')

@section('content')
    <section class="pt-24 md:pt-28 pb-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold mb-6">Buat Janji Temu</h1>

            <div class="bg-white rounded-2xl shadow-lg p-8">
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-sm text-red-600">{{ $errors->first() }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('appointments.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-neutral-700">Vendor</label>
                        <select name="vendor_id" class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2" required>
                            <option value="">Pilih vendor</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" @selected(old('vendor_id') == $vendor->id)>{{ $vendor->nama_vendor }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-neutral-700">Mulai</label>
                            <input type="datetime-local" name="starts_at" value="{{ old('starts_at') }}" required class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-700">Selesai</label>
                            <input type="datetime-local" name="ends_at" value="{{ old('ends_at') }}" required class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2">
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-neutral-700">Jenis Lokasi</label>
                            <select name="location_type" class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2" required>
                                <option value="in_person" @selected(old('location_type') == 'in_person')>Tatap muka</option>
                                <option value="online" @selected(old('location_type') == 'online')>Online</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-700">Detail Lokasi/Link</label>
                            <input type="text" name="location_detail" value="{{ old('location_detail') }}" class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-neutral-700">Subjek</label>
                        <input type="text" name="subject" value="{{ old('subject') }}" required class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-neutral-700">Catatan</label>
                        <textarea name="notes" rows="4" class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2">{{ old('notes') }}</textarea>
                    </div>

                    <div class="grid md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-neutral-700">Jumlah Hadir</label>
                            <input type="number" name="attendee_count" value="{{ old('attendee_count') }}" min="1" class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-700">Kontak Preferensi</label>
                            <select name="preferred_contact" class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2">
                                <option value="">Pilih</option>
                                <option value="whatsapp" @selected(old('preferred_contact') == 'whatsapp')>WhatsApp</option>
                                <option value="phone" @selected(old('preferred_contact') == 'phone')>Telepon</option>
                                <option value="email" @selected(old('preferred_contact') == 'email')>Email</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-700">Nomor Kontak</label>
                            <input type="text" name="contact_number" value="{{ old('contact_number') }}" class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2">
                        </div>
                    </div>

                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-rose-600 text-white hover:bg-rose-700">Kirim Permintaan</button>
                </form>
            </div>
        </div>
    </section>
@endsection