<?php

namespace App\Filament\Resources\Appointments\Pages;

use App\Filament\Resources\Appointments\AppointmentResource;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Filament\Resources\Pages\CreateRecord;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function beforeCreate(): void
    {
        $data = $this->form->getState();

        $start = Carbon::parse($data['starts_at']);
        $end = Carbon::parse($data['ends_at']);

        $conflict = Appointment::query()
            ->where('vendor_id', $data['vendor_id'])
            ->where(function ($q) use ($start, $end) {
                $q->where('starts_at', '<', $end)
                  ->where('ends_at', '>', $start);
            })
            ->exists();

        if ($conflict) {
            throw ValidationException::withMessages([
                'starts_at' => 'Jadwal vendor bentrok pada rentang waktu tersebut.',
                'ends_at' => 'Jadwal vendor bentrok pada rentang waktu tersebut.',
            ]);
        }
    }
}