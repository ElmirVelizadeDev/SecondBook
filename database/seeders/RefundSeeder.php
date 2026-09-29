<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Refund;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RefundSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            $this->command->error('No admin found.');
            return;
        }

        // Remove previous seeded refunds
        Refund::where('refund_number', 'like', 'REF-SEED-%')->delete();

        $orders = Order::with('payment')
            ->whereIn('payment_status', ['paid', 'refunded'])
            ->whereIn('order_status', ['delivered', 'cancelled'])
            ->orderBy('id')
            ->take(15)
            ->get();

        if ($orders->isEmpty()) {
            $this->command->warn('No suitable orders found for refunds.');
            return;
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

            if (!$payment) {
                continue;
            }

            $paymentAmount = (float) $payment->amount;

            $amount = round(
                $paymentAmount * [0.25, 0.50, 0.75, 1.00][$index % 4],
                2
            );

            $status = $statuses[$index];

            $requestedAt = now()->subDays(
                rand(2, 30)
            )->subHours(
                rand(1, 12)
            );

            $processedAt = in_array($status, [
                'approved',
                'processed',
                'rejected',
            ])
                ? $requestedAt->copy()->addHours(rand(2, 48))
                : null;

            Refund::create([
                'order_id' => $order->id,
                'payment_id' => $payment->id,
                'user_id' => $order->user_id,

                'processed_by' => in_array($status, [
                    'approved',
                    'processed',
                    'rejected',
                ])
                    ? $admin->id
                    : null,

                'refund_number' => 'REF-SEED-' .
                    now()->format('Ymd') . '-' .
                    str_pad(
                        $index + 1,
                        4,
                        '0',
                        STR_PAD_LEFT
                    ),

                'amount' => $amount,

                'reason' => $reasons[$index],

                'note' => 'Seeded refund record.',

                'status' => $status,

                'requested_at' => $requestedAt,

                'processed_at' => $processedAt,
            ]);
        }

        $this->command->info(
            '15 refund records seeded successfully.'
        );
    }
}