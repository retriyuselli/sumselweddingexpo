<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

class PasswordResetController extends Controller
{
    public function create()
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = Str::lower(trim((string) $request->input('email')));
        $user = User::query()->where('email', $email)->first();

        $status = Password::sendResetLink(['email' => $email]);

        if ($status === Password::RESET_LINK_SENT) {
            $message = 'Link reset password telah dikirim ke email Anda (cek juga folder spam).';

            if ($user?->google_id) {
                $message .= ' Akun ini juga terhubung dengan Google — Anda bisa login dengan Google, atau membuat password baru lewat link tersebut.';
            }

            return back()->with('status', $message);
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => $this->statusMessage($status)]);
    }

    public function edit(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => (string) $request->query('email', ''),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)->letters()->mixedCase()->numbers()],
        ]);

        $email = Str::lower(trim((string) $request->input('email')));

        $status = Password::reset(
            [
                'email' => $email,
                'password' => $request->input('password'),
                'password_confirmation' => $request->input('password_confirmation'),
                'token' => $request->input('token'),
            ],
            function ($user, string $password) {
                $user->forceFill([
                    'password' => $password,
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            $user = User::query()->where('email', $email)->first();
            $message = 'Password berhasil diubah. Silakan login dengan password baru.';

            if ($user?->google_id) {
                $message = 'Password berhasil diubah. Anda bisa login dengan password baru atau tetap memakai Login dengan Google.';
            }

            return redirect()->route('login')->with('success', $message);
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => $this->statusMessage($status)]);
    }

    private function statusMessage(string $status): string
    {
        return match ($status) {
            Password::INVALID_USER => 'Email tidak ditemukan. Jika Anda daftar dengan Google, gunakan tombol Login dengan Google.',
            Password::INVALID_TOKEN => 'Token reset tidak valid atau sudah kedaluwarsa. Silakan minta link baru.',
            Password::RESET_THROTTLED => 'Terlalu banyak permintaan. Silakan coba lagi beberapa saat lagi.',
            default => __($status),
        };
    }
}
