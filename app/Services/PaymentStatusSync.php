<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PaymentStatusSync
{
    public function findByExternalId(string $code, bool $withItems = false): ?Payment
    {
        $with = $withItems ? ['order.items.vendor'] : ['order'];

        return Payment::with($with)
            ->where('provider', 'midtrans')
            ->where('external_id', $code)
            ->first();
    }

    public function findOwnedOrFail(string $code, ?User $user, bool $withItems = false): Payment
    {
        $payment = $this->findByExternalId($code, $withItems);
        if (! $payment) {
            throw new NotFoundHttpException('Payment not found');
        }

        $this->assertOwnedBy($user, $payment);

        return $payment;
    }

    public function assertOwnedBy(?User $user, Payment $payment): void
    {
        $order = $payment->relationLoaded('order') ? $payment->order : $payment->order()->first();
        if ($user && $order && $order->customer_id && (int) $order->customer_id !== (int) $user->id) {
            throw new AccessDeniedHttpException('Forbidden');
        }
    }

    /**
     * Apply Midtrans payload (webhook or status API) to payment + order.
     *
     * @param  array<string, mixed>  $payload
     */
    public function applyFromMidtransPayload(Payment $payment, array $payload, bool $considerDp = true): void
    {
        $status = (string) ($payload['transaction_status'] ?? '');
        $amount = (float) ($payload['gross_amount'] ?? 0);
        $method = (string) ($payload['payment_type'] ?? '');
        $va = null;
        if (! empty($payload['va_numbers']) && is_array($payload['va_numbers'])) {
            $first = $payload['va_numbers'][0] ?? [];
            $va = $first['va_number'] ?? null;
        }

        $payment->update([
            'transaction_id' => (string) ($payload['transaction_id'] ?? ''),
            'status' => $status,
            'amount' => $amount,
            'method' => $method,
            'va_number' => $va,
            'paid_at' => in_array($status, ['capture', 'settlement'], true) ? now() : null,
            'raw_response' => $payload,
        ]);

        $order = $payment->order;
        if (! $order) {
            return;
        }

        if (in_array($status, ['capture', 'settlement'], true)) {
            if ($considerDp) {
                $paidAmount = (float) $amount;
                $totalAmount = (float) ($order->amount_total ?? 0);
                $isDpPaid = $paidAmount > 0 && $paidAmount < $totalAmount;
                $order->update(['status' => $isDpPaid ? 'dp_paid' : 'paid']);
            } else {
                $order->update(['status' => 'paid']);
            }
        } elseif ($status === 'pending') {
            $order->update(['status' => 'pending']);
        } elseif (in_array($status, ['expire', 'cancel', 'failure'], true)) {
            $order->update(['status' => 'failed']);
        }
    }

    /**
     * @return array{ok: bool, status: string, method: string, amount: float, paid_at: ?string}
     */
    public function toStatusPayload(Payment $payment): array
    {
        return [
            'ok' => true,
            'status' => (string) ($payment->status ?? ''),
            'method' => (string) ($payment->method ?? ''),
            'amount' => (float) ($payment->amount ?? 0),
            'paid_at' => $payment->paid_at ? $payment->paid_at->toIso8601String() : null,
        ];
    }
}
