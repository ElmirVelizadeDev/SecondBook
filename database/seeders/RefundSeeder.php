<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Refund;
use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class RefundSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@gmail.com')->firstOrFail();
        $orders = Order::with('payment')
            ->where('note', 'Seeded marketplace order.')
            ->whereIn('payment_status', ['paid', 'refunded'])
            ->whereHas('payment')
            ->whereHas('user', function ($query) {
                $query->where('email', 'like', '%@example.com');
            })
            ->orderBy('order_number')
            ->take(25)
            ->get();

        if ($orders->count() < 25) {
            throw new RuntimeException('At least 25 paid demo orders with payments are required before seeding refunds.');
        }

        $reasons = [
            'Customer requested a refund',
            'Book arrived damaged',
            'Wrong book received',
            'Book condition was not as described',
            'Customer changed their mind',
            'Book was not as expected',
            'Customer received the wrong edition',
            'Book arrived late',
            'Duplicate order placed',
            'Customer no longer needs the book',
            'Cover was damaged during delivery',
            'Missing pages reported',
            'Incorrect book description',
            'Seller could not fulfill the order',
            'Customer requested cancellation',
        ];
        $statuses = [
            'pending',
            'approved',
            'processed',
            'rejected',
            'processed',
            'pending',
            'approved',
            'processed',
            'rejected',
            'processed',
            'pending',
            'approved',
            'processed',
            'rejected',
            'processed',
        ];

        foreach ($orders as $index => $order) {
            $payment = $order->payment;
            $status = $statuses[$index % count($statuses)];
            $amount = round(
                (float) $payment->amount * [0.25, 0.50, 0.75, 1.00][$index % 4],
                2
            );
            $requestedAt = now()->subDays($index + 2);
            $processedAt = in_array($status, ['approved', 'processed', 'rejected'], true)
                ? $requestedAt->copy()->addHours(12)
                : null;
            $refundNumber = 'REF-SEED-' . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT);
            $attributes = [
                'order_id' => $order->id,
                'payment_id' => $payment->id,
                'user_id' => $order->user_id,
                'processed_by' => $status === 'pending' ? null : $admin->id,
                'amount' => $amount,
                'reason' => $reasons[$index % count($reasons)],
                'note' => 'Seeded refund request.',
                'status' => $status,
                'requested_at' => $requestedAt,
                'processed_at' => $processedAt,
            ];

            $refund = Refund::where('refund_number', $refundNumber)->first()
                ?? Refund::where('order_id', $order->id)
                    ->where('refund_number', 'like', 'REF-SEED-%')
                    ->first();

            if ($refund) {
                $refund->update(array_merge(
                    ['refund_number' => $refundNumber],
                    $attributes
                ));
            } else {
                Refund::create(array_merge(
                    ['refund_number' => $refundNumber],
                    $attributes
                ));
            }

            if ($status === 'processed' && $amount >= (float) $payment->amount) {
                $payment->update(['payment_status' => 'refunded']);
                $order->update(['payment_status' => 'refunded']);
            }
        }

        $this->command->info('25 stable demo refund records seeded successfully.');
    }
}
