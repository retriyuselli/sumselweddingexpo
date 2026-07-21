<?php

namespace Tests\Feature;

use App\Enums\CategoryTier;
use App\Models\CategoryTenant;
use App\Models\Expo;
use App\Models\JenisUsaha;
use App\Models\Partisipasi;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ExhibitorRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::findOrCreate('customer');
    }

    #[Test]
    public function new_exhibitor_creates_vendor_and_partisipasi(): void
    {
        [$user, $expo, $jenis, $category] = $this->seedExpoContext();

        $response = $this->actingAs($user)->post(route('exhibitor.store'), [
            'nama_pendaftar' => $user->name,
            'nama_vendor' => 'Brand Baru',
            'jenis_usaha_id' => $jenis->id,
            'alamat' => 'Jl. Merdeka',
            'kota' => 'Palembang',
            'no_telepon' => '081234567890',
            'email' => 'brandbaru@example.com',
            'nama_pic' => 'PIC Brand',
            'no_wa_pic' => '081234567890',
            'paket' => $category->category->value,
            'lokasi_booth' => 'Hall A',
            'pendamping_tenant' => 'Budi',
            'harga_jual' => 1, // must be ignored
        ]);

        $response->assertRedirect(route('exhibitor'));
        $response->assertSessionHas('success');

        $vendor = Vendor::where('email', 'brandbaru@example.com')->first();
        $this->assertNotNull($vendor);
        $this->assertSame($user->id, (int) $vendor->user_id);

        $partisipasi = Partisipasi::where('vendor_id', $vendor->id)->where('expo_id', $expo->id)->first();
        $this->assertNotNull($partisipasi);
        $this->assertSame($category->id, (int) $partisipasi->category_tenant_id);
        $this->assertSame((int) $category->harga_jual, (int) $partisipasi->harga_jual);
        $this->assertSame('Hall A', $partisipasi->blok_tenant);
        $this->assertStringContainsString('Pendamping: Budi', (string) $partisipasi->keterangan);
    }

    #[Test]
    public function existing_vendor_can_join_another_expo(): void
    {
        [$user, $expo, $jenis, $category] = $this->seedExpoContext();

        $vendor = Vendor::create([
            'user_id' => $user->id,
            'nama_pendaftar' => $user->name,
            'nama_vendor' => 'Brand Lama',
            'slug' => 'brand-lama',
            'jenis_usaha_id' => $jenis->id,
            'alamat' => 'Jl. Lama',
            'kota' => 'Palembang',
            'no_telepon' => '081111111111',
            'email' => 'brandlama@example.com',
            'nama_pic' => 'PIC',
            'no_wa_pic' => '081111111111',
        ]);

        $response = $this->actingAs($user)->post(route('exhibitor.store'), [
            'paket' => $category->category->value,
            'lokasi_booth' => 'Blok B1',
        ]);

        $response->assertRedirect(route('exhibitor'));
        $response->assertSessionHas('success');

        $this->assertSame(1, Vendor::where('user_id', $user->id)->count());
        $this->assertDatabaseHas('partisipasis', [
            'vendor_id' => $vendor->id,
            'expo_id' => $expo->id,
            'category_tenant_id' => $category->id,
            'blok_tenant' => 'Blok B1',
            'harga_jual' => $category->harga_jual,
        ]);
    }

    #[Test]
    public function duplicate_partisipasi_for_same_expo_is_rejected(): void
    {
        [$user, $expo, $jenis, $category] = $this->seedExpoContext();

        $vendor = Vendor::create([
            'user_id' => $user->id,
            'nama_pendaftar' => $user->name,
            'nama_vendor' => 'Brand Dup',
            'slug' => 'brand-dup',
            'jenis_usaha_id' => $jenis->id,
            'alamat' => 'Jl. Dup',
            'kota' => 'Palembang',
            'no_telepon' => '082222222222',
            'email' => 'branddup@example.com',
            'nama_pic' => 'PIC',
            'no_wa_pic' => '082222222222',
        ]);

        Partisipasi::create([
            'expo_id' => $expo->id,
            'vendor_id' => $vendor->id,
            'tanggal_booking' => now()->toDateString(),
            'status_pembayaran' => 'Belum Lunas',
            'category_tenant_id' => $category->id,
            'harga_jual' => $category->harga_jual,
            'diskon' => 0,
        ]);

        $response = $this->actingAs($user)->from(route('exhibitor'))->post(route('exhibitor.store'), [
            'paket' => $category->category->value,
        ]);

        $response->assertRedirect(route('exhibitor'));
        $response->assertSessionHasErrors('paket');
        $this->assertSame(1, Partisipasi::where('vendor_id', $vendor->id)->where('expo_id', $expo->id)->count());
    }

    /**
     * @return array{0: User, 1: Expo, 2: JenisUsaha, 3: CategoryTenant}
     */
    private function seedExpoContext(): array
    {
        $user = User::factory()->create();
        $user->assignRole('customer');

        $expo = Expo::create([
            'nama_expo' => 'Sumsel Wedding Expo 2026',
            'periode' => '2026',
            'lokasi' => 'Palembang',
            'alamat' => 'Jl. Expo',
            'tanggal_mulai' => now()->addMonth()->toDateString(),
            'tanggal_selesai' => now()->addMonth()->addDays(2)->toDateString(),
            'status' => true,
        ]);

        \App\Services\ExpoResolver::forgetNearest();

        $jenis = JenisUsaha::create(['nama_jenis_usaha' => 'WO']);

        $category = CategoryTenant::create([
            'expo_id' => $expo->id,
            'category' => CategoryTier::Gold,
            'harga_jual' => 11000000,
            'harga_modal' => 8000000,
            'jumlah_unit' => 10,
            'status' => 'Aktif',
        ]);

        return [$user, $expo, $jenis, $category];
    }
}
