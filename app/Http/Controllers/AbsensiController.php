<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Expo;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AbsensiController extends Controller
{
    public function qr(Expo $expo)
    {
        $user = auth()->user();
        abort_unless($user && $user->hasAnyRole(['super_admin', 'admin']), 403);

        return response($expo->absensiQrPng(), 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'attachment; filename="'.$expo->absensiQrFilename().'"',
            'Cache-Control' => 'private, max-age=0, must-revalidate',
        ]);
    }

    public function show(Expo $expo)
    {
        $now = now()->timezone('Asia/Jakarta');

        return view('absensi.form', [
            'expo' => $expo,
            'today' => $now,
            'open' => $expo->isAbsensiOpen($now),
            'notice' => $expo->absensiClosedMessage($now),
        ]);
    }

    public function store(Request $request, Expo $expo)
    {
        $today = now()->timezone('Asia/Jakarta');

        if (! $expo->isAbsensiOpen($today)) {
            throw ValidationException::withMessages([
                'email' => $expo->absensiClosedMessage($today) ?? 'Absensi belum dapat digunakan.',
            ]);
        }

        $data = $request->validate([
            'email' => ['required', 'email:rfc', 'max:255'],
            'no_tlp' => ['required', 'string', 'max:30'],
        ]);

        $email = mb_strtolower(trim($data['email']));
        $phone = Absensi::normalizePhone($data['no_tlp']);

        if (! preg_match('/^0[0-9]{8,14}$/', $phone)) {
            throw ValidationException::withMessages([
                'no_tlp' => 'Nomor telepon tidak valid. Contoh: 081234567890.',
            ]);
        }

        $takenToday = Absensi::query()
            ->where('expo_id', $expo->id)
            ->whereDate('tanggal', $today->toDateString());

        $errors = [];
        if ((clone $takenToday)->where('email', $email)->exists()) {
            $errors['email'] = 'Email ini sudah tercatat hari ini. Bisa digunakan lagi besok.';
        }
        if ((clone $takenToday)->where('no_tlp', $phone)->exists()) {
            $errors['no_tlp'] = 'Nomor telepon ini sudah tercatat hari ini. Bisa digunakan lagi besok.';
        }
        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }

        try {
            Absensi::create([
                'expo_id' => $expo->id,
                'email' => $email,
                'no_tlp' => $phone,
                'tanggal' => $today->toDateString(),
            ]);
        } catch (UniqueConstraintViolationException) {
            throw ValidationException::withMessages([
                'email' => 'Email atau nomor telepon ini sudah tercatat hari ini. Bisa digunakan lagi besok.',
            ]);
        }

        return redirect()
            ->route('absensi.show', $expo)
            ->with('status', 'Kehadiran tercatat. Terima kasih.');
    }
}
