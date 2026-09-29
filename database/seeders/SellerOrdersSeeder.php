<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SellerOrdersSeeder extends Seeder
{
    public function run(): void
    {
        $sellers = User::where('role', 'seller')
            ->where('status', 'active')
            ->with('store')
            ->get();

        $buyers = User::where('role', 'user')
            ->where('status', 'active')
            ->get();

        if ($sellers->isEmpty()) {
            $this->command->error('No active sellers found.');
            return;
        }

        if ($buyers->isEmpty()) {
            $this->command->error('No active buyers found.');
            return;
        }

        // Remove previous seeded orders
        Order::where('note', 'Seeded marketplace order.')
            ->get()
            ->each(function ($order) {
                $order->payment?->delete();
                $order->refunds()->delete();
                $order->delete();
            });

        $books = Book::whereNotNull('seller_id')
            ->where('status', 'approved')
            ->where('stock', '>', 0)
            ->with('seller.store')
            ->get();

        if ($books->isEmpty()) {
            $this->command->error(
                'No approved seller books with stock found.'
            );
            return;
        }

        $statuses = [
            ['pending', 'pending', 'cash_on_delivery'],
            ['processing', 'paid', 'credit_card'],
            ['shipped', 'paid', 'debit_card'],
            ['delivered', 'paid', 'paypal'],
            ['delivered', 'paid', 'cash_on_delivery'],
            ['cancelled', 'failed', 'credit_card'],
            ['processing', 'paid', 'paypal'],
            ['shipped', 'paid', 'credit_card'],
            ['delivered', 'paid', 'debit_card'],
            ['delivered', 'paid', 'credit_card'],
            ['pending', 'pending', 'cash_on_delivery'],
            ['processing', 'paid', 'debit_card'],
            ['shipped', 'paid', 'paypal'],
            ['delivered', 'paid', 'credit_card'],
            ['delivered', 'paid', 'cash_on_delivery'],
            ['cancelled', 'failed', 'debit_card'],
            ['processing', 'paid', 'credit_card'],
            ['shipped', 'paid', 'paypal'],
            ['delivered', 'paid', 'debit_card'],
            ['delivered', 'paid', 'credit_card'],
        ];

        foreach ($statuses as $index => [$orderStatus, $paymentStatus, $paymentMethod]) {
            $book = $books[$index % $books->count()];
            $buyer = $buyers[$index % $buyers->count()];
            $seller = $book->seller;
            $store = $seller?->store;

            if (!$seller || !$store) {
                continue;
            }

            $quantity = min(
                rand(1, 2),
                max(1, (int) $book->stock)
            );

            $bookPrice = (float) $book->price;
            $subtotal = round($bookPrice * $quantity, 2);

            $shippingFee = 0;
            $total = round($subtotal + $shippingFee, 2);

            $orderNumber = 'SB-SEED-' .
                now()->format('Ymd') .
                '-' .
                str_pad($index + 1, 4, '0', STR_PAD_LEFT);

            $order = Order::create([
                'order_number' => $orderNumber,

                'user_id' => $buyer->id,
                'book_id' => $book->id,

                'book_price' => $bookPrice,
                'quantity' => $quantity,
                'total_price' => $total,
                'shipping_fee' => $shippingFee,

                'payment_method' => $paymentMethod,
                'payment_status' => $paymentStatus,
                'order_status' => $orderStatus,

                'processing_deadline' => now()->addDays(
                    $store->processing_time ?? 2
                ),

                'order_note' => $store->order_note,

                'full_name' => $buyer->name,
                'phone' => $buyer->phone ?? '+994500000000',
                'country' => $buyer->country ?? 'Azerbaijan',
                'city' => $buyer->city ?? 'Baku',
                'postal_code' => $buyer->postal_code ?? 'AZ1000',
                'address' => $buyer->address ?? 'Baku, Azerbaijan',

                'delivery_estimate' => '3-5 business days',

                'note' => 'Seeded marketplace order.',

                'created_at' => now()->subDays(rand(0, 25)),
                'updated_at' => now(),
            ]);

            Payment::create([
                'transaction_id' => 'TXN-' . strtoupper(Str::random(12)),
                'order_id' => $order->id,
                'amount' => $total,
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentStatus,
                'paid_at' => in_array($paymentStatus, ['paid', 'refunded'])
                    ? now()->subDays(rand(1, 20))
                    : null,
                'note' => 'Seeded payment record.',
            ]);
        }

        $this->command->info(
            '20 seller marketplace orders and payments seeded successfully.'
        );
    }
}