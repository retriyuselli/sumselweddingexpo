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

        $order->update([
            'amount_subtotal' => $subtotal,
            'amount_total' => $subtotal,
        ]);

        $payment = Payment::create([
            'order_id' => $order->id,
            'provider' => 'midtrans',
            'external_id' => $orderCode,
            'status' => 'pending',
            'amount' => $subtotal,
        ]);

        $payload = [
            'transaction_details' => [
                'order_id' => $orderCode,
                'gross_amount' => (int) round($subtotal),
            ],
            'item_details' => $itemDetails,
        ];
        // Do not set merchant_id unless using Midtrans multiple merchants feature

        if ($user) {
            $payload['customer_details'] = [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->no_wa_pic ?? null,
            ];
        }

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
            $order->update(['status' => 'paid']);
        } elseif ($status === 'pending') {
            $order->update(['status' => 'pending']);
        } elseif (in_array($status, ['expire','cancel','failure'])) {
            $order->update(['status' => 'failed']);
        }

        $event->update(['processed' => true, 'processed_at' => now()]);

        return response()->json(['ok' => true]);
    }
}