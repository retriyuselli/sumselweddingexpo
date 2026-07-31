<?php

namespace App\Jobs;

use App\Models\Payment;
use App\Models\WebhookEvent;
use App\Services\MidtransService;
use App\Services\PaymentStatusSync;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * Processes Midtrans webhooks after the HTTP response is sent
 * (no queue worker required; still keeps the controller thin).
 */
class ProcessMidtransWebhook
{
    use Dispatchable;

    public function __construct(
        public int $webhookEventId,
        public array $payload,
    ) {}

    public function handle(MidtransService $svc, PaymentStatusSync $sync): void
    {
        $event = WebhookEvent::find($this->webhookEventId);
        if (! $event || $event->processed) {
            return;
        }

        $valid = $svc->verifySignature($this->payload);
        $event->update(['signature_valid' => $valid]);

        if (! $valid) {
            return;
        }

        $orderCode = (string) ($this->payload['order_id'] ?? '');
        $payment = Payment::where('provider', 'midtrans')
            ->where('external_id', $orderCode)
            ->first();

        if (! $payment) {
            return;
        }

        $sync->applyFromMidtransPayload($payment, $this->payload, considerDp: true);
        $event->update(['processed' => true, 'processed_at' => now()]);
    }
}
