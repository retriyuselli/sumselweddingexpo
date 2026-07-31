<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::findOrCreate('customer');
    }

    #[Test]
    public function register_requires_strong_password_and_sends_verification(): void
    {
        Notification::fake();

        $this->from(route('register'))
            ->post(route('register.post'), [
                'name' => 'Demo User',
                'email' => 'demo@example.com',
                'password' => 'weak',
                'password_confirmation' => 'weak',
                'terms' => '1',
            ])->assertSessionHasErrors('password');

        $this->from(route('register'))
            ->post(route('register.post'), [
                'name' => 'Demo User',
                'email' => 'demo@example.com',
                'password' => 'Password1',
                'password_confirmation' => 'Password1',
                'terms' => '1',
            ])->assertRedirect(route('verification.notice'));

        $user = User::where('email', 'demo@example.com')->first();
        $this->assertNotNull($user);
        $this->assertNull($user->email_verified_at);
        $this->assertTrue($user->hasRole('customer'));
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    #[Test]
    public function forgot_password_sends_reset_link(): void
    {
        Notification::fake();

        $user = User::factory()->create(['email' => 'resetme@example.com']);

        $this->from(route('password.request'))
            ->post(route('password.email'), [
                'email' => 'resetme@example.com',
            ])
            ->assertSessionHas('status');

        Notification::assertSentTo($user, ResetPassword::class);
    }

    #[Test]
    public function forgot_password_mentions_google_for_google_accounts(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'googleuser@example.com',
            'google_id' => 'google-123',
        ]);

        $this->from(route('password.request'))
            ->post(route('password.email'), [
                'email' => 'googleuser@example.com',
            ])
            ->assertSessionHas('status');

        $status = session('status');
        $this->assertIsString($status);
        $this->assertStringContainsString('Google', $status);
        Notification::assertSentTo($user, ResetPassword::class);
    }

    #[Test]
    public function user_can_reset_password_with_valid_token(): void
    {
        $user = User::factory()->create(['email' => 'resetme2@example.com']);
        $token = Password::createToken($user);

        $this->from(route('password.reset', ['token' => $token]))
            ->post(route('password.update'), [
                'token' => $token,
                'email' => 'resetme2@example.com',
                'password' => 'NewPass12',
                'password_confirmation' => 'NewPass12',
            ])->assertRedirect(route('login'));

        $this->assertTrue(auth()->attempt([
            'email' => 'resetme2@example.com',
            'password' => 'NewPass12',
        ]));
    }

    #[Test]
    public function changing_email_clears_verification(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'old@example.com',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)
            ->from(route('profile'))
            ->put(route('profile.update'), [
                'name' => $user->name,
                'email' => 'new@example.com',
            ])
            ->assertRedirect(route('verification.notice'));

        $user->refresh();
        $this->assertSame('new@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    #[Test]
    public function verification_notice_page_renders_for_unverified_user(): void
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)
            ->get(route('verification.notice'))
            ->assertOk()
            ->assertSee('Verifikasi Email');
    }
}
