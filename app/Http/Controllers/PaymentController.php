<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ProductVendor;
use App\Models\WebhookEvent;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function createSnap(Request $request)
    {
        $data = $request->validate([
            'items' => ['required','array','min:1'],
            'items.*.product_vendor_id' => ['required','integer','exists:product_vendors,id'],
            'items.*.qty' => ['required','integer','min:1'],
            'vendor_id' => ['nullable','integer','exists:vendors,id'],
            'billing' => ['required','array'],
            'billing.first_name' => ['required','string','max:255'],
            'billing.last_name' => ['required','string','max:255'],
            'billing.company' => ['nullable','string','max:255'],
            'billing.country' => ['required','string','max:255'],
            'billing.street' => ['required','string'],
            'billing.apt' => ['nullable','string','max:255'],
            'billing.city' => ['required','string','max:255'],
            'billing.province' => ['required','string','max:255'],
            'billing.postcode' => ['nullable','string','max:20'],
            'billing.phone' => ['required','string','max:50'],
            'billing.email' => ['required','email','max:255'],
            'billing.notes' => ['nullable','string'],
            'payment_mode' => ['nullable','in:dp,full'],
        ]);

        $user = Auth::user();
        $products = ProductVendor::whereIn('id', collect($data['items'])->pluck('product_vendor_id'))->get()->keyBy('id');
        $selectedVendorId = $data['vendor_id'] ?? null;
        $vendorIds = collect($data['items'])->map(function($it) use ($products) {
            $p = $products[$it['product_vendor_id']];
            return $p->vendor_id;
        })->unique()->values();
        if ($selectedVendorId && ($vendorIds->count() !== 1 || $vendorIds[0] != $selectedVendorId)) {
            return response()->json(['message' => 'Produk harus berasal dari vendor yang sama'], 422);
        }

        $orderCode = 'ORD-'.now()->format('YmdHis').'-'.Str::upper(Str::random(6));
        $subtotal = 0;

        $order = Order::create([
            'customer_id' => $user ? $user->id : null,
            'code' => $orderCode,
            'amount_subtotal' => 0,
            'amount_total' => 0,
            'status' => 'pending',
            'billing_first_name' => $data['billing']['first_name'] ?? null,
            'billing_last_name' => $data['billing']['last_name'] ?? null,
            'billing_company' => $data['billing']['company'] ?? null,
            'billing_country' => $data['billing']['country'] ?? null,
            'billing_street' => $data['billing']['street'] ?? null,
            'billing_apt' => $data['billing']['apt'] ?? null,
            'billing_city' => $data['billing']['city'] ?? null,
            'billing_province' => $data['billing']['province'] ?? null,
            'billing_postcode' => $data['billing']['postcode'] ?? null,
            'billing_phone' => $data['billing']['phone'] ?? null,
            'billing_email' => $data['billing']['email'] ?? null,
            'notes' => $data['billing']['notes'] ?? null,
        ]);

        $itemDetails = [];
        foreach ($data['items'] as $it) {
            $p = $products[$it['product_vendor_id']];
            $price = (int) round((float) ($p->harga ?? 0));
            $qty = (int) $it['qty'];
            $line = $price * $qty;
            $subtotal += $line;
            OrderItem::create([
                'order_id' => $order->id,
                'product_vendor_id' => $p->id,
                'vendor_id' => $p->vendor_id,
                'name_snapshot' => $p->nama_produk,
                'price_snapshot' => (float) $price,
                'qty' => $qty,
                'subtotal' => $line,
            ]);
            $itemDetails[] = [
                'id' => (string) $p->id,
                'price' => (int) $price,
                'quantity' => (int) $qty,
                'name' => $p->nama_produk,
            ];
        }

        if ($subtotal <= 0) {
            return response()->json([
                'message' => 'Total transaksi tidak boleh 0. Pastikan harga produk terisi.',
            ], 422);
        }

        $order->update([
            'amount_subtotal' => $subtotal,
            'amount_total' => $subtotal,
        ]);

        $dpFixedGlobal = (float) (config('services.midtrans.dp_fixed') ?? 0);
        $mode = (string) ($data['payment_mode'] ?? '');
        $hasItemDp = collect($data['items'])->some(function ($it) use ($products) {
            $p = $products[$it['product_vendor_id']];
            return ((int) ($p->dp_fixed ?? 0)) > 0;
        });
        $dpApplicable = ($dpFixedGlobal > 0) || $hasItemDp;
        $useDp = $dpApplicable && ($mode !== 'full');
        $dpAmount = $subtotal;
        if ($useDp) {
            $dpAmount = 0;
            foreach ($data['items'] as $it) {
                $p = $products[$it['product_vendor_id']];
                $price = (int) round((float) ($p->harga ?? 0));
                $qty = (int) $it['qty'];
                $line = $price * $qty;
                $dpFix = (int) ($p->dp_fixed ?? 0);
                if ($dpFix > 0) {
                    $dpAmount += min($line, $dpFix * $qty);
                }
            }
            if ($dpAmount <= 0) {
                if ($dpFixedGlobal > 0) {
                    $dpAmount = min($subtotal, (int) round($dpFixedGlobal));
                }
                if ($dpAmount <= 0) $dpAmount = 1;
            }
        }

        $payment = Payment::create([
            'order_id' => $order->id,
            'provider' => 'midtrans',
            'external_id' => $orderCode,
            'status' => 'pending',
            'amount' => $dpAmount,
        ]);

        $payload = [
            'transaction_details' => [
                'order_id' => $orderCode,
                'gross_amount' => (int) round($dpAmount),
            ],
            'item_details' => (!$useDp) ? $itemDetails : [
                [
                    'id' => 'dp',
                    'price' => (int) round($dpAmount),
                    'quantity' => 1,
                    'name' => 'Downpayment',
                ],
            ],
        ];
        // Do not set merchant_id unless using Midtrans multiple merchants feature

        $countryCode = (string) ($data['billing']['country'] ?? '');
        if (strtolower($countryCode) === 'indonesia') {
            $countryCode = 'IDN';
        }
        $payload['customer_details'] = [
            'first_name' => $data['billing']['first_name'],
            'last_name' => $data['billing']['last_name'],
            'email' => $data['billing']['email'],
            'phone' => $data['billing']['phone'],
            'billing_address' => [
                'first_name' => $data['billing']['first_name'],
                'last_name' => $data['billing']['last_name'],
                'email' => $data['billing']['email'],
                'phone' => $data['billing']['phone'],
                'address' => trim(($data['billing']['street'] ?? '').' '.($data['billing']['apt'] ?? '')),
                'city' => $data['billing']['city'],
                'postal_code' => $data['billing']['postcode'] ?? '',
                'country_code' => $countryCode,
            ],
        ];

        $svc = new MidtransService();
        $res = $svc->createSnap($payload);
        if (!isset($res['token'])) {
            return response()->json([
                'message' => 'Gagal membuat transaksi',
                'midtrans' => $res,
                'payload' => $payload,
            ], 400);
        }

        $payment->update([
            'token' => $res['token'] ?? null,
            'redirect_url' => $res['redirect_url'] ?? null,
            'raw_response' => $res,
        ]);

        return response()->json([
            'order_code' => $orderCode,
            'snap_token' => $payment->token,
            'redirect_url' => $payment->redirect_url,
        ]);
    }

    public function handleWebhook(Request $request)
    {
        $payload = $request->all();
        $svc = new MidtransService();
        $valid = $svc->verifySignature($payload);

        $event = WebhookEvent::create([
            'provider' => 'midtrans',
            'event_type' => (string) ($payload['transaction_status'] ?? ''),
            'external_id' => (string) ($payload['order_id'] ?? ''),
            'signature_valid' => $valid,
            'processed' => false,
            'payload' => $payload,
        ]);

        if (!$valid) {
            return response()->json(['ok' => false], 400);
        }

        $orderCode = (string) ($payload['order_id'] ?? '');
        $payment = Payment::where('provider', 'midtrans')->where('external_id', $orderCode)->first();
        if (!$payment) {
            return response()->json(['ok' => false], 404);
        }

        $status = (string) ($payload['transaction_status'] ?? '');
        $amount = (float) ($payload['gross_amount'] ?? 0);
        $method = (string) ($payload['payment_type'] ?? '');
        $va = null;
        if (!empty($payload['va_numbers']) && is_array($payload['va_numbers'])) {
            $first = $payload['va_numbers'][0] ?? [];
            $va = $first['va_number'] ?? null;
        }

        $payment->update([
            'transaction_id' => (string) ($payload['transaction_id'] ?? ''),
            'status' => $status,
            'amount' => $amount,
            'method' => $method,
            'va_number' => $va,
            'paid_at' => in_array($status, ['capture','settlement']) ? now() : null,
            'raw_response' => $payload,
        ]);

        $order = $payment->order;
        if (in_array($status, ['capture','settlement'])) {
            $paidAmount = (float) $amount;
            $totalAmount = (float) ($order->amount_total ?? 0);
            $isDpPaid = $paidAmount > 0 && $paidAmount < $totalAmount;
            $order->update(['status' => $isDpPaid ? 'dp_paid' : 'paid']);
        } elseif ($status === 'pending') {
            $order->update(['status' => 'pending']);
        } elseif (in_array($status, ['expire','cancel','failure'])) {
            $order->update(['status' => 'failed']);
        }

        $event->update(['processed' => true, 'processed_at' => now()]);

        return response()->json(['ok' => true]);
    }
}