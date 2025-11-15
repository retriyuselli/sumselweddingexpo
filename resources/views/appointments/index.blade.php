@extends('layouts.app')

@section('title', 'Janji Temu Saya — WeddingExpo')

@section('content')
    <section class="pt-24 md:pt-28 pb-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold">Janji Temu Saya</h1>
                <a href="{{ route('appointments.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-rose-600 text-white hover:bg-rose-700">Buat Janji Temu</a>
            </div>

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">{{ session('success') }}</div>
            @endif

            @if ($appointments->isEmpty())
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center text-neutral-700">
                    Belum ada janji temu.
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-neutral-200">
                        <thead class="bg-neutral-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-700">Vendor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-700">Mulai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-700">Selesai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-700">Lokasi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-700">Subjek</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-700">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200">
                            @foreach($appointments as $item)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-neutral-900">
                                        <div class="font-medium">{{ optional($item->vendor)->nama_vendor ?? '-' }}</div>
                                        @if($item->expo)
                                            <div class="text-neutral-500 text-xs">Expo: {{ $item->expo->nama_expo }} ({{ $item->expo->periode }})</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-neutral-900">{{ $item->starts_at?->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4 text-sm text-neutral-900">{{ $item->ends_at?->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4 text-sm text-neutral-900">
                                        <div class="inline-flex items-center gap-2">
                                            <span class="px-2 py-1 rounded-full text-xs {{ $item->location_type === 'online' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700' }}">{{ $item->location_type === 'online' ? 'Online' : 'Tatap muka' }}</span>
                                            <span class="text-neutral-600">{{ $item->location_detail }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-neutral-900">{{ $item->subject }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        @php
                                            $statusColors = [
                                                'requested' => 'bg-yellow-100 text-yellow-700',
                                                'confirmed' => 'bg-green-100 text-green-700',
                                                'rescheduled' => 'bg-sky-100 text-sky-700',
                                                'cancelled' => 'bg-red-100 text-red-700',
                                                'completed' => 'bg-neutral-100 text-neutral-700',
                                            ];
                                        @endphp
                                        <span class="px-2 py-1 rounded-full text-xs {{ $statusColors[$item->status] ?? 'bg-neutral-100 text-neutral-700' }}">{{ ucfirst($item->status) }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $appointments->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection