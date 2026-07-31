<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ProductVendor;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::role('customer')->get();
        $products = ProductVendor::with('vendor')->where('is_active', true)->get();

        if ($customers->isEmpty()) {
            $this->command?->warn('No customers found. Please run CustomerSeeder first.');

            return;
        }

        if ($products->isEmpty()) {
            $this->command?->warn('No products found. Please run ProductVendorSeeder first.');

            return;
        }

        $statuses = ['pending', 'paid', 'dp_paid', 'failed', 'cancelled'];
        $created = 0;

        foreach ($customers as $index => $customer) {
            $status = $statuses[$index % count($statuses)];
            $selectedProducts = $products->shuffle()->take(1 + ($index % 2));
            $subtotal = 0;
            $itemPayloads = [];

            foreach ($selectedProducts as $product) {
                $qty = 1;
                $price = (float) $product->harga;
                $lineSubtotal = $price * $qty;
                $subtotal += $lineSubtotal;

                $itemPayloads[] = [
                    'product_vendor_id' => $product->id,
                    'vendor_id' => $product->vendor_id,
                    'name_snapshot' => $product->nama_produk,
                    'price_snapshot' => $price,
                    'qty' => $qty,
                    'subtotal' => $lineSubtotal,
                ];
            }

            $code = 'ORD-SEED-'.str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT);

            $order = Order::updateOrCreate(
                ['code' => $code],
                [
                    'customer_id' => $customer->id,
                    'amount_subtotal' => $subtotal,
                    'amount_total' => $subtotal,
                    'status' => $status,
                    'billing_first_name' => explode(' ', $customer->name)[0] ?? $customer->name,
                    'billing_last_name' => explode(' ', $customer->name)[1] ?? '',
                    'billing_company' => null,
                    'billing_country' => 'ID',
                    'billing_street' => 'Jl. Demo Order No. '.($index + 1),
                    'billing_apt' => null,
                    'billing_city' => 'Palembang',
                    'billing_province' => 'Sumatera Selatan',
                    'billing_postcode' => '30111',
                    'billing_phone' => '08128'.str_pad((string) (1000000 + $index), 7, '0', STR_PAD_LEFT),
                    'billing_email' => $customer->email,
                    'notes' => 'Order demo dari OrderSeeder',
                ]
            );

            if ($order->items()->exists()) {
                $order->items()->forceDelete();
            }

            foreach ($itemPayloads as $item) {
                OrderItem::create(array_merge($item, ['order_id' => $order->id]));
            }

            if (! $order->payments()->exists()) {
                $isPaid = in_array($status, ['paid', 'dp_paid'], true);
                $payAmount = $status === 'dp_paid'
                    ? round($subtotal * 0.3, 2)
                    : $subtotal;

                Payment::create([
                    'order_id' => $order->id,
                    'provider' => 'midtrans',
                    'external_id' => 'SEED-'.Str::upper(Str::random(10)),
                    'transaction_id' => $isPaid ? 'TRX-'.Str::upper(Str::random(12)) : null,
                    'status' => match ($status) {
                        'paid', 'dp_paid' => 'settlement',
                        'pending' => 'pending',
                        'cancelled' => 'cancel',
                        default => 'deny',
                    },
                    'amount' => $payAmount,
                    'method' => ['qris', 'bank_transfer', 'gopay'][$index % 3],
                    'va_number' => null,
                    'redirect_url' => null,
                    'token' => null,
                    'paid_at' => $isPaid ? now()->subDays($index + 1) : null,
                    'raw_response' => [
                        'seeded' => true,
                        'order_status' => $status,
                    ],
                ]);
            }

            $created++;
        }

        $this->command?->info("OrderSeeder: {$created} orders (with items/payments) ensured.");
    }
}
