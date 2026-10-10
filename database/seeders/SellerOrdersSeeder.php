<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Shipping;
use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class SellerOrdersSeeder extends Seeder
{
    public function run(): void
    {
        $sellers = User::where('role', 'seller')
            ->where('status', 'active')
            ->where('email', 'like', 'seller.%@secondbook.test')
            ->with('store')
            ->orderBy('id')
            ->get();

        $buyers = User::where('role', 'user')
            ->where('status', 'active')
            ->where('email', 'like', '%@example.com')
            ->orderBy('id')
            ->get();

        if ($sellers->isEmpty() || $buyers->isEmpty()) {
            throw new RuntimeException('Active demo sellers and buyers are required before seeding orders.');
        }

        $books = Book::whereIn('seller_id', $sellers->modelKeys())
            ->where('description', 'like', '%seeded book available in the SecondBook marketplace%')
            ->where('status', 'approved')
            ->where('stock', '>', 0)
            ->with('seller.store')
            ->orderBy('isbn')
            ->get();

        if ($books->count() < 30) {
            throw new RuntimeException('At least 30 approved demo books with stock are required before seeding orders.');
        }

        $shipping = Shipping::where('name', 'Standard Shipping')->first();

        if (!$shipping) {
            throw new RuntimeException('Standard Shipping must be seeded before demo orders.');
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
            ['delivered', 'paid', 'credit_card'],
            ['processing', 'paid', 'debit_card'],
            ['shipped', 'paid', 'paypal'],
            ['delivered', 'paid', 'cash_on_delivery'],
            ['delivered', 'paid', 'credit_card'],
            ['delivered', 'paid', 'debit_card'],
            ['shipped', 'paid', 'paypal'],
            ['delivered', 'paid', 'credit_card'],
            ['processing', 'paid', 'paypal'],
            ['delivered', 'paid', 'cash_on_delivery'],
        ];

        $legacyOrders = Order::where('note', 'Seeded marketplace order.')
            ->orderBy('id')
            ->get()
            ->values();

        foreach ($statuses as $index => [$orderStatus, $paymentStatus, $paymentMethod]) {
            $book = $books[$index];
            $seller = $book->seller;
            $store = $seller?->store;
            $buyer = $buyers[$index % $buyers->count()];

            if (!$seller || !$store) {
                throw new RuntimeException("Seeded book \"{$book->title}\" has no valid seller store.");
            }

            $quantity = min(
                ($index % 5 === 0) ? 2 : 1,
                (int) $book->stock
            );
            $bookPrice = (float) $book->price;
            $shippingFee = (float) $shipping->price;
            $total = round(($bookPrice * $quantity) + $shippingFee, 2);
            $orderNumber = 'SB-SEED-' . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT);
            $attributes = [
                'user_id' => $buyer->id,
                'book_id' => $book->id,
                'book_price' => $bookPrice,
                'quantity' => $quantity,
                'total_price' => $total,
                'shipping_fee' => $shippingFee,
                'shipping_id' => $shipping->id,
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentStatus,
                'order_status' => $orderStatus,
                'processing_deadline' => now()->addDays($store->processing_time ?? 2),
                'order_note' => $store->order_note,
                'full_name' => $buyer->name,
                'phone' => $buyer->phone ?? '+994500000000',
                'country' => $buyer->country ?? 'Azerbaijan',
                'city' => $buyer->city ?? 'Baku',
                'postal_code' => $buyer->postal_code ?? 'AZ1000',
                'address' => $buyer->address ?? 'Baku, Azerbaijan',
                'delivery_estimate' => $shipping->delivery_time,
                'note' => 'Seeded marketplace order.',
                'created_at' => now()->subDays(($index % 25) + 1),
                'updated_at' => now(),
            ];

            $order = Order::where('order_number', $orderNumber)->first()
                ?? $legacyOrders->get($index);

            if ($order) {
                $order->update(array_merge(['order_number' => $orderNumber], $attributes));
            } else {
                $order = Order::create(array_merge(
                    ['order_number' => $orderNumber],
                    $attributes
                ));
            }

            Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'transaction_id' => 'TXN-SEED-' . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                    'amount' => $total,
                    'payment_method' => $paymentMethod,
                    'payment_status' => $paymentStatus,
                    'paid_at' => $paymentStatus === 'paid'
                        ? now()->subDays(($index % 20) + 1)
                        : null,
                    'note' => 'Seeded payment record.',
                ]
            );
        }

        $this->command->info('30 stable demo marketplace orders and payments seeded successfully.');
    }
}
