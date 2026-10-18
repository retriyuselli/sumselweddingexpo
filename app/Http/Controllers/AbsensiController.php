<?php

namespace App\Http\Controllers;

use App\Enums\TujuanKunjungan;
use App\Models\Absensi;
use App\Models\Expo;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
            'tujuan' => ['required', Rule::enum(TujuanKunjungan::class)],
            'tujuan_lainnya' => ['nullable', 'string', 'max:255', 'required_if:tujuan,'.TujuanKunjungan::Lainnya->value],
        ], [
            'tujuan.required' => 'Pilih tujuan kunjungan.',
            'tujuan_lainnya.required_if' => 'Tuliskan tujuan kunjungan Anda.',
        ]);

        $email = mb_strtolower(trim($data['email']));
        $phone = Absensi::normalizePhone($data['no_tlp']);
        $tujuan = TujuanKunjungan::from($data['tujuan']);
        $tujuanLainnya = $tujuan === TujuanKunjungan::Lainnya
            ? trim((string) ($data['tujuan_lainnya'] ?? ''))
            : null;

        if ($tujuan === TujuanKunjungan::Lainnya && $tujuanLainnya === '') {
            throw ValidationException::withMessages([
                'tujuan_lainnya' => 'Tuliskan tujuan kunjungan Anda.',
            ]);
        }

        if (! preg_match('/^0[0-9]{8,14}$/', $phone)) {
            throw ValidationException::withMessages([
                'no_tlp' => 'Nomor telepon tidak valid. Contoh: 081234567890.',
            ]);
        }

        $forExpo = Absensi::query()->where('expo_id', $expo->id);

        $errors = [];
        if ((clone $forExpo)->where('no_tlp', $phone)->where('email', '!=', $email)->exists()) {
            $errors['no_tlp'] = 'Nomor telepon ini sudah terdaftar dengan email lain. Gunakan email yang sama.';
        }
        if ((clone $forExpo)->where('email', $email)->where('no_tlp', '!=', $phone)->exists()) {
            $errors['email'] = 'Email ini sudah terdaftar dengan nomor telepon lain. Gunakan nomor yang sama.';
        }
        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }

        $takenToday = (clone $forExpo)->whereDate('tanggal', $today->toDateString());

        if ((clone $takenToday)->where('email', $email)->exists()) {
            $errors['email'] = 'Email ini sudah tercatat hari ini. Besok bisa absen lagi dengan nomor telepon yang sama.';
        }
        if ((clone $takenToday)->where('no_tlp', $phone)->exists()) {
            $errors['no_tlp'] = 'Nomor telepon ini sudah tercatat hari ini. Besok bisa absen lagi dengan email yang sama.';
        }
        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }

        try {
            Absensi::create([
                'expo_id' => $expo->id,
                'email' => $email,
                'no_tlp' => $phone,
                'tujuan' => $tujuan,
                'tujuan_lainnya' => $tujuanLainnya,
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
