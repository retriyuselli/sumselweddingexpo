<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MidtransService
{
    protected string $serverKey;
    protected bool $isProduction;

    public function __construct()
    {
        $this->serverKey = (string) config('services.midtrans.server_key');
        $this->isProduction = (bool) config('services.midtrans.is_production');
    }

    public function createSnap(array $payload): array
    {
        $base = $this->isProduction ? 'https://app.midtrans.com' : 'https://app.sandbox.midtrans.com';
        $url = $base.'/snap/v1/transactions';
        $res = Http::withBasicAuth($this->serverKey, '')->post($url, $payload);
        if ($res->failed()) {
            return [
                'error' => true,
                'status' => $res->status(),
                'body' => $res->json(),
            ];
        }
        return $res->json();
    }

    public function verifySignature(array $payload): bool
    {
        $orderId = (string) ($payload['order_id'] ?? '');
        $statusCode = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');
        $signatureKey = (string) ($payload['signature_key'] ?? '');
        $calc = hash('sha512', $orderId.$statusCode.$grossAmount.$this->serverKey);
        return hash_equals($calc, $signatureKey);
    }

    public function handleNotification(array $payload): array
    {
        return [
            'order_id' => (string) ($payload['order_id'] ?? ''),
            'transaction_status' => (string) ($payload['transaction_status'] ?? ''),
            'payment_type' => (string) ($payload['payment_type'] ?? ''),
            'gross_amount' => (string) ($payload['gross_amount'] ?? ''),
            'signature_valid' => $this->verifySignature($payload),
        ];
    }

    public function getStatus(string $orderId): array
    {
        $base = $this->isProduction ? 'https://api.midtrans.com' : 'https://api.sandbox.midtrans.com';
        $url = $base.'/v2/'.$orderId.'/status';
        $res = Http::withBasicAuth($this->serverKey, '')->get($url);
        if ($res->failed()) {
            return [ 'error' => true, 'status' => $res->status(), 'body' => $res->json() ];
        }
        return $res->json();
    }
}
