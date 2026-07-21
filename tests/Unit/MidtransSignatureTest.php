<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\Payment;
use App\Services\MidtransService;
use App\Services\PaymentStatusSync;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MidtransSignatureTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_verifies_valid_midtrans_signature(): void
    {
        config(['services.midtrans.server_key' => 'test-server-key']);

        $orderId = 'ORD-TEST-1';
        $statusCode = '200';
        $grossAmount = '100000.00';
        $signature = hash('sha512', $orderId.$statusCode.$grossAmount.'test-server-key');

        $svc = new MidtransService();
        $this->assertTrue($svc->verifySignature([
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'signature_key' => $signature,
        ]));
    }

    #[Test]
    public function it_rejects_tampered_signature(): void
    {
        config(['services.midtrans.server_key' => 'test-server-key']);

        $svc = new MidtransService();
        $this->assertFalse($svc->verifySignature([
            'order_id' => 'ORD-TEST-1',
            'status_code' => '200',
            'gross_amount' => '100000.00',
            'signature_key' => 'not-a-real-signature',
        ]));
    }

    #[Test]
    public function payment_status_sync_marks_order_paid(): void
    {
        $user = \App\Models\User::factory()->create();
        $order = Order::create([
            'customer_id' => $user->id,
            'code' => 'ORD-SYNC-1',
            'amount_subtotal' => 100000,
            'amount_total' => 100000,
            'status' => 'pending',
        ]);
        $payment = Payment::create([
            'order_id' => $order->id,
            'provider' => 'midtrans',
            'external_id' => 'ORD-SYNC-1',
            'status' => 'pending',
            'amount' => 100000,
        ]);

        app(PaymentStatusSync::class)->applyFromMidtransPayload($payment, [
            'transaction_status' => 'settlement',
            'gross_amount' => '100000',
            'payment_type' => 'bank_transfer',
            'transaction_id' => 'mid-1',
        ]);

        $this->assertSame('settlement', $payment->fresh()->status);
        $this->assertSame('paid', $order->fresh()->status);
    }
}
