<?php

namespace Tests\Feature;

use App\Models\JenisUsaha;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::findOrCreate('customer');
        Role::findOrCreate('admin');
    }

    #[Test]
    public function customer_cannot_edit_another_users_vendor(): void
    {
        $owner = User::factory()->create();
        $owner->assignRole('customer');
        $intruder = User::factory()->create();
        $intruder->assignRole('customer');

        $jenis = JenisUsaha::create(['nama_jenis_usaha' => 'Fotografi']);
        $vendor = Vendor::create([
            'user_id' => $owner->id,
            'nama_pendaftar' => 'Owner',
            'nama_vendor' => 'Vendor A',
            'slug' => 'vendor-a',
            'jenis_usaha_id' => $jenis->id,
            'alamat' => 'Jl. Test',
            'kota' => 'Palembang',
            'no_telepon' => '08123456789',
            'email' => 'vendor-a@example.com',
            'nama_pic' => 'PIC',
            'no_wa_pic' => '08123456789',
        ]);

        $this->actingAs($intruder)
            ->get(route('vendors.edit', $vendor))
            ->assertForbidden();

        $this->actingAs($intruder)
            ->put(route('vendors.update', $vendor), [
                'nama_pendaftar' => 'Hacked',
                'nama_vendor' => 'Hacked Vendor',
                'jenis_usaha_id' => $jenis->id,
                'alamat' => 'Hacked',
                'kota' => 'Hacked',
                'no_telepon' => '08000000000',
                'email' => 'hacked@example.com',
                'nama_pic' => 'Hack',
                'no_wa_pic' => '08000000000',
                'user_id' => $intruder->id,
            ])
            ->assertForbidden();

        $this->assertSame('Vendor A', $vendor->fresh()->nama_vendor);
        $this->assertSame($owner->id, $vendor->fresh()->user_id);
    }

    #[Test]
    public function customer_cannot_delete_another_users_vendor(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $jenis = JenisUsaha::create(['nama_jenis_usaha' => 'Dekorasi']);

        $vendor = Vendor::create([
            'user_id' => $owner->id,
            'nama_pendaftar' => 'Owner',
            'nama_vendor' => 'Vendor B',
            'slug' => 'vendor-b',
            'jenis_usaha_id' => $jenis->id,
            'alamat' => 'Jl. Test',
            'kota' => 'Palembang',
            'no_telepon' => '08111111111',
            'email' => 'vendor-b@example.com',
            'nama_pic' => 'PIC',
            'no_wa_pic' => '08111111111',
        ]);

        $this->actingAs($intruder)
            ->delete(route('vendors.destroy', $vendor))
            ->assertForbidden();

        $this->assertNotNull($vendor->fresh());
    }

    #[Test]
    public function user_cannot_view_another_users_payment_status(): void
    {
        $owner = User::factory()->create(['email_verified_at' => now()]);
        $intruder = User::factory()->create(['email_verified_at' => now()]);

        $order = Order::create([
            'customer_id' => $owner->id,
            'code' => 'ORD-OWNER-1',
            'amount_subtotal' => 50000,
            'amount_total' => 50000,
            'status' => 'pending',
        ]);
        Payment::create([
            'order_id' => $order->id,
            'provider' => 'midtrans',
            'external_id' => 'ORD-OWNER-1',
            'status' => 'pending',
            'amount' => 50000,
        ]);

        $this->actingAs($intruder)
            ->getJson(route('payments.status', ['code' => 'ORD-OWNER-1']))
            ->assertForbidden();
    }

    #[Test]
    public function owner_can_view_own_payment_status(): void
    {
        $owner = User::factory()->create(['email_verified_at' => now()]);

        $order = Order::create([
            'customer_id' => $owner->id,
            'code' => 'ORD-OWNER-2',
            'amount_subtotal' => 75000,
            'amount_total' => 75000,
            'status' => 'pending',
        ]);
        Payment::create([
            'order_id' => $order->id,
            'provider' => 'midtrans',
            'external_id' => 'ORD-OWNER-2',
            'status' => 'pending',
            'amount' => 75000,
        ]);

        $this->actingAs($owner)
            ->getJson(route('payments.status', ['code' => 'ORD-OWNER-2']))
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('status', 'pending');
    }

    #[Test]
    public function midtrans_webhook_rejects_invalid_signature(): void
    {
        config(['services.midtrans.server_key' => 'test-server-key']);

        $this->postJson(route('webhooks.midtrans'), [
            'order_id' => 'ORD-FAKE',
            'status_code' => '200',
            'gross_amount' => '10000.00',
            'signature_key' => 'invalid',
            'transaction_status' => 'settlement',
        ])->assertStatus(400);
    }

    #[Test]
    public function midtrans_webhook_accepts_valid_signature(): void
    {
        config(['services.midtrans.server_key' => 'test-server-key']);

        $owner = User::factory()->create();
        $order = Order::create([
            'customer_id' => $owner->id,
            'code' => 'ORD-WH-1',
            'amount_subtotal' => 10000,
            'amount_total' => 10000,
            'status' => 'pending',
        ]);
        Payment::create([
            'order_id' => $order->id,
            'provider' => 'midtrans',
            'external_id' => 'ORD-WH-1',
            'status' => 'pending',
            'amount' => 10000,
        ]);

        $orderId = 'ORD-WH-1';
        $statusCode = '200';
        $grossAmount = '10000.00';
        $signature = hash('sha512', $orderId.$statusCode.$grossAmount.'test-server-key');

        $this->postJson(route('webhooks.midtrans'), [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'signature_key' => $signature,
            'transaction_status' => 'settlement',
            'payment_type' => 'gopay',
            'transaction_id' => 'tx-1',
        ])->assertOk();
    }
}
