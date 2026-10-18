<?php

namespace Tests\Feature;

use App\Models\Absensi;
use App\Models\Expo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class AbsensiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_check_in_once_per_day_and_again_the_next_day(): void
    {
        Carbon::setTestNow(now()->timezone('Asia/Jakarta')->setTime(10, 0));
        $expo = $this->expo(now()->toDateString(), now()->addDay()->toDateString(), true);

        $this->post(route('absensi.store', $expo), [
            'email' => 'Tamu@Example.com',
            'no_tlp' => '+62 812-3456-7890',
            'tujuan' => 'mencari_vendor',
        ])->assertRedirect(route('absensi.show', $expo));

        $this->assertDatabaseHas('absensis', [
            'expo_id' => $expo->id,
            'email' => 'tamu@example.com',
            'no_tlp' => '081234567890',
            'tujuan' => 'mencari_vendor',
            'tanggal' => now()->toDateString(),
        ]);

        $this->post(route('absensi.store', $expo), [
            'email' => 'tamu@example.com',
            'no_tlp' => '081111111111',
            'tujuan' => 'mencari_venue',
        ])->assertSessionHasErrors('email');

        $this->post(route('absensi.store', $expo), [
            'email' => 'lain@example.com',
            'no_tlp' => '081234567890',
            'tujuan' => 'lainnya',
            'tujuan_lainnya' => 'Mengantar keluarga',
        ])->assertSessionHasErrors('no_tlp');

        $this->travel(1)->days();

        $this->post(route('absensi.store', $expo), [
            'email' => 'tamu@example.com',
            'no_tlp' => '081234567890',
            'tujuan' => 'mencari_vendor',
        ])->assertRedirect(route('absensi.show', $expo));

        $this->assertSame(2, Absensi::query()->count());

        $this->post(route('absensi.store', $expo), [
            'email' => 'lain@example.com',
            'no_tlp' => '081234567890',
            'tujuan' => 'lainnya',
            'tujuan_lainnya' => 'Mengantar keluarga',
        ])->assertSessionHasErrors('no_tlp');

        $this->assertSame(2, Absensi::query()->count());
    }

    public function test_check_in_is_closed_outside_expo_dates_or_when_inactive(): void
    {
        $upcoming = $this->expo(now()->addDay()->toDateString(), now()->addDays(2)->toDateString(), true);

        $this->get(route('absensi.show', $upcoming))
            ->assertOk()
            ->assertSee('Absensi dibuka pada');

        $this->post(route('absensi.store', $upcoming), [
            'email' => 'tamu@example.com',
            'no_tlp' => '081234567890',
            'tujuan' => 'mencari_vendor',
        ])->assertSessionHasErrors('email');

        $inactive = $this->expo(now()->toDateString(), now()->toDateString(), false);

        $this->post(route('absensi.store', $inactive), [
            'email' => 'tamu@example.com',
            'no_tlp' => '081234567890',
            'tujuan' => 'mencari_vendor',
        ])->assertSessionHasErrors('email');

        $this->assertSame(0, Absensi::query()->count());
    }

    public function test_check_in_is_only_open_from_nine_to_ten_at_night(): void
    {
        Carbon::setTestNow(now()->timezone('Asia/Jakarta')->setTime(8, 59));
        $expo = $this->expo(now()->toDateString(), now()->toDateString(), true);

        $this->post(route('absensi.store', $expo), [
            'email' => 'tamu@example.com',
            'no_tlp' => '081234567890',
            'tujuan' => 'mencari_vendor',
        ])->assertSessionHasErrors('email');

        Carbon::setTestNow(now()->timezone('Asia/Jakarta')->setTime(22, 1));

        $this->post(route('absensi.store', $expo), [
            'email' => 'tamu@example.com',
            'no_tlp' => '081234567890',
            'tujuan' => 'mencari_vendor',
        ])->assertSessionHasErrors('email');

        Carbon::setTestNow(now()->timezone('Asia/Jakarta')->setTime(22, 0));

        $this->post(route('absensi.store', $expo), [
            'email' => 'tamu@example.com',
            'no_tlp' => '081234567890',
            'tujuan' => 'mencari_vendor',
        ])->assertRedirect(route('absensi.show', $expo));
    }

    private function expo(string $start, string $end, bool $active): Expo
    {
        return Expo::create([
            'nama_expo' => 'Expo Uji',
            'tanggal_mulai' => $start,
            'tanggal_selesai' => $end,
            'lokasi' => 'Palembang',
            'alamat' => 'Jakabaring',
            'status' => $active,
            'periode' => '2026',
            'jam_buka_absensi' => '09:00:00',
            'jam_tutup_absensi' => '22:00:00',
        ]);
    }
}
