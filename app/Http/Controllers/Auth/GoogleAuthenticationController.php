<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\AbstractUser;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthenticationController extends Controller
{
    public function redirect(): RedirectResponse
    {
        if (blank(config('services.google.client_id')) || blank(config('services.google.client_secret'))) {
            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Login Google belum dikonfigurasi oleh administrator.']);
        }

        try {
            return Socialite::driver('google')
                ->scopes(['openid', 'email', 'profile'])
                ->redirect();
        } catch (Throwable $exception) {
            report($exception);

            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Login Google sedang tidak tersedia. Silakan coba lagi nanti.']);
        }
    }

    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $exception) {
            report($exception);

            return $this->failed('Autentikasi Google gagal atau dibatalkan. Silakan coba kembali.');
        }

        $email = Str::lower(trim((string) $googleUser->getEmail()));

        if (! filter_var($email, FILTER_VALIDATE_EMAIL) || ! $this->hasVerifiedEmail($googleUser)) {
            return $this->failed('Akun Google harus memiliki alamat email yang terverifikasi.');
        }

        $googleId = (string) $googleUser->getId();

        $user = User::query()
            ->where(function ($query) use ($googleId, $email) {
                $query->where('google_id', $googleId)
                    ->orWhere('email', $email);
            })
            ->first();

        if (! $user) {
            $user = $this->registerFromGoogle($googleUser, $email, $googleId);
        } else {
            $this->linkGoogleAccount($user, $googleId);
        }

        Auth::login($user, true);
        request()->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    private function registerFromGoogle(AbstractUser $googleUser, string $email, string $googleId): User
    {
        $name = trim((string) ($googleUser->getName() ?: $googleUser->getNickname() ?: Str::before($email, '@')));

        $user = User::create([
            'name' => $name !== '' ? $name : $email,
            'email' => $email,
            'google_id' => $googleId,
            'password' => Str::password(32),
        ]);

        $user->forceFill(['email_verified_at' => now()])->save();
        $user->assignRole('customer');

        return $user;
    }

    private function linkGoogleAccount(User $user, string $googleId): void
    {
        $updates = [];

        if (blank($user->google_id)) {
            $updates['google_id'] = $googleId;
        }

        if (blank($user->email_verified_at)) {
            $updates['email_verified_at'] = now();
        }

        if ($updates !== []) {
            $user->forceFill($updates)->save();
        }
    }

    private function hasVerifiedEmail(AbstractUser $googleUser): bool
    {
        $verified = data_get($googleUser->user, 'verified_email')
            ?? data_get($googleUser->user, 'email_verified');

        return filter_var($verified, FILTER_VALIDATE_BOOL) === true;
    }

    private function failed(string $message): RedirectResponse
    {
        return redirect()
            ->route('login')
            ->withErrors(['email' => $message]);
    }
}
