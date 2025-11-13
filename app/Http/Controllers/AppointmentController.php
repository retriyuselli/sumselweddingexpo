<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function create()
    {
        $vendors = Vendor::select('id', 'nama_vendor')->orderBy('nama_vendor')->get();
        return view('appointments.create', compact('vendors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_id' => ['required', 'exists:vendors,id'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
            'location_type' => ['required', 'in:in_person,online'],
            'location_detail' => ['nullable', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'attendee_count' => ['nullable', 'integer', 'min:1'],
            'preferred_contact' => ['nullable', 'in:whatsapp,phone,email'],
            'contact_number' => ['nullable', 'string', 'max:255'],
        ]);

        $start = Carbon::parse($validated['starts_at']);
        $end = Carbon::parse($validated['ends_at']);

        $conflict = Appointment::query()
            ->where('vendor_id', $validated['vendor_id'])
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

        $appointment = Appointment::create([
            'customer_id' => Auth::id(),
            'vendor_id' => $validated['vendor_id'],
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at'],
            'location_type' => $validated['location_type'],
            'location_detail' => $validated['location_detail'] ?? null,
            'subject' => $validated['subject'],
            'notes' => $validated['notes'] ?? null,
            'attendee_count' => $validated['attendee_count'] ?? null,
            'preferred_contact' => $validated['preferred_contact'] ?? null,
            'contact_number' => $validated['contact_number'] ?? null,
            'status' => 'requested',
        ]);

        return redirect()->route('appointments.create')->with('success', 'Permintaan janji temu dikirim. Menunggu konfirmasi vendor.');
    }
}