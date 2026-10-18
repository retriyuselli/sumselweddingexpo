<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\WebhookEvent;
use Illuminate\Database\Seeder;

class WebhookEventSeeder extends Seeder
{
    public function run(): void
    {
        $payments = Payment::query()
            ->where('provider', 'midtrans')
            ->whereNotNull('external_id')
            ->get();

        if ($payments->isEmpty()) {
            $this->command?->warn('No Midtrans payments found. Please run OrderSeeder first.');

            return;
        }

        $created = 0;

        foreach ($payments as $index => $payment) {
            $signatureValid = $index !== $payments->count() - 1 || $payments->count() === 1;
            $processed = $signatureValid && $payment->status !== 'pending';
            $paidAt = $payment->paid_at ?? now()->subDays($index + 1);

            WebhookEvent::updateOrCreate(
                [
                    'provider' => 'midtrans',
                    'external_id' => $payment->external_id,
                    'event_type' => $payment->status,
                ],
                [
                    'signature_valid' => $signatureValid,
                    'processed' => $processed,
                    'processed_at' => $processed ? $paidAt : null,
                    'payload' => [
                        'transaction_time' => $paidAt->format('Y-m-d H:i:s'),
                        'transaction_status' => $payment->status,
                        'transaction_id' => $payment->transaction_id,
                        'status_message' => $signatureValid
                            ? 'midtrans payment notification'
                            : 'invalid signature',
                        'status_code' => $payment->status === 'settlement' ? '200' : '201',
                        'payment_type' => $payment->method,
                        'order_id' => $payment->external_id,
                        'gross_amount' => number_format((float) $payment->amount, 2, '.', ''),
                        'fraud_status' => 'accept',
                        'currency' => 'IDR',
                        'seeded' => true,
                    ],
                ]
            );

            $created++;
        }

        $this->command?->info("Webhook events seeded: {$created}");
    }
}
