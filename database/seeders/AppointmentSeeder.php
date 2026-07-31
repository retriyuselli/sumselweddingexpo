<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Expo;
use App\Models\User;
use App\Models\Vendor;
use App\Services\ExpoResolver;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::role('customer')->get();
        $vendors = Vendor::take(8)->get();
        $expo = app(ExpoResolver::class)->nearestActive()
            ?? Expo::where('status', true)->orderByDesc('tanggal_mulai')->first();

        if ($customers->isEmpty()) {
            $this->command?->warn('No customers found. Please run CustomerSeeder first.');

            return;
        }

        if ($vendors->isEmpty()) {
            $this->command?->warn('No vendors found. Please run VendorSeeder first.');

            return;
        }

        $statuses = ['requested', 'confirmed', 'rescheduled', 'cancelled', 'completed'];
        $created = 0;

        foreach ($customers as $index => $customer) {
            $vendor = $vendors[$index % $vendors->count()];
            $status = $statuses[$index % count($statuses)];
            $isOnline = $index % 2 === 1;
            $startsAt = now()->addDays(2 + $index)->setTime(10 + ($index % 4), 0);
            $endsAt = $startsAt->copy()->addMinutes(45);

            $exists = Appointment::where('customer_id', $customer->id)
                ->where('vendor_id', $vendor->id)
                ->where('starts_at', $startsAt)
                ->exists();

            if ($exists) {
                continue;
            }

            Appointment::create([
                'customer_id' => $customer->id,
                'vendor_id' => $vendor->id,
                'expo_id' => $expo?->id,
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
                'duration_minutes' => 45,
                'location_type' => $isOnline ? 'online' : 'in_person',
                'location_detail' => $isOnline
                    ? 'https://meet.google.com/demo-'.($index + 1)
                    : 'Booth '.$vendor->nama_vendor.($expo ? ' — '.$expo->alamat : ''),
                'subject' => 'Konsultasi paket '.$vendor->nama_vendor,
                'notes' => 'Janji temu demo untuk kebutuhan wedding planning.',
                'attendee_count' => 2 + ($index % 3),
                'preferred_contact' => ['whatsapp', 'phone', 'email'][$index % 3],
                'contact_number' => '08129'.str_pad((string) (1000000 + $index), 7, '0', STR_PAD_LEFT),
                'status' => $status,
            ]);

            $created++;
        }

        $this->command?->info("AppointmentSeeder: {$created} appointments created.");
    }
}
