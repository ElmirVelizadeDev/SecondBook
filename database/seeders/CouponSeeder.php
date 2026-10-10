<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME10',
                'type' => 'percentage',
                'value' => 10,
                'minimum_order_amount' => 20,
                'maximum_discount_amount' => 10,
                'usage_limit' => 100,
                'used_count' => 12,
                'days' => 60,
                'status' => true,
            ],

            [
                'code' => 'BOOK15',
                'type' => 'percentage',
                'value' => 15,
                'minimum_order_amount' => 30,
                'maximum_discount_amount' => 15,
                'usage_limit' => 50,
                'used_count' => 8,
                'days' => 45,
                'status' => true,
            ],

            [
                'code' => 'SAVE5',
                'type' => 'fixed',
                'value' => 5,
                'minimum_order_amount' => 25,
                'maximum_discount_amount' => null,
                'usage_limit' => 200,
                'used_count' => 31,
                'days' => 90,
                'status' => true,
            ],

            [
                'code' => 'READ20',
                'type' => 'percentage',
                'value' => 20,
                'minimum_order_amount' => 50,
                'maximum_discount_amount' => 20,
                'usage_limit' => 30,
                'used_count' => 5,
                'days' => 30,
                'status' => true,
            ],

            [
                'code' => 'SECOND10',
                'type' => 'fixed',
                'value' => 10,
                'minimum_order_amount' => 60,
                'maximum_discount_amount' => null,
                'usage_limit' => 25,
                'used_count' => 3,
                'days' => 75,
                'status' => true,
            ],

            [
                'code' => 'EXPIRED20',
                'type' => 'percentage',
                'value' => 20,
                'minimum_order_amount' => 20,
                'maximum_discount_amount' => 20,
                'usage_limit' => 50,
                'used_count' => 45,
                'days' => -10,
                'status' => false,
            ],
            ['code' => 'SPRING12', 'type' => 'percentage', 'value' => 12, 'minimum_order_amount' => 25, 'maximum_discount_amount' => 12, 'usage_limit' => 120, 'used_count' => 0, 'days' => 45, 'status' => true],
            ['code' => 'STUDENT8', 'type' => 'percentage', 'value' => 8, 'minimum_order_amount' => 15, 'maximum_discount_amount' => 8, 'usage_limit' => 150, 'used_count' => 0, 'days' => 60, 'status' => true],
            ['code' => 'READMORE5', 'type' => 'fixed', 'value' => 5, 'minimum_order_amount' => 20, 'maximum_discount_amount' => null, 'usage_limit' => 100, 'used_count' => 0, 'days' => 75, 'status' => true],
            ['code' => 'FICTION10', 'type' => 'percentage', 'value' => 10, 'minimum_order_amount' => 30, 'maximum_discount_amount' => 10, 'usage_limit' => 80, 'used_count' => 0, 'days' => 50, 'status' => true],
            ['code' => 'CLASSIC7', 'type' => 'fixed', 'value' => 7, 'minimum_order_amount' => 35, 'maximum_discount_amount' => null, 'usage_limit' => 60, 'used_count' => 0, 'days' => 90, 'status' => true],
            ['code' => 'TECH15', 'type' => 'percentage', 'value' => 15, 'minimum_order_amount' => 40, 'maximum_discount_amount' => 15, 'usage_limit' => 50, 'used_count' => 0, 'days' => 40, 'status' => true],
            ['code' => 'WEEKEND6', 'type' => 'fixed', 'value' => 6, 'minimum_order_amount' => 25, 'maximum_discount_amount' => null, 'usage_limit' => 90, 'used_count' => 0, 'days' => 35, 'status' => true],
            ['code' => 'HISTORY9', 'type' => 'percentage', 'value' => 9, 'minimum_order_amount' => 28, 'maximum_discount_amount' => 9, 'usage_limit' => 75, 'used_count' => 0, 'days' => 55, 'status' => true],
            ['code' => 'NEWCHAPTER4', 'type' => 'fixed', 'value' => 4, 'minimum_order_amount' => 18, 'maximum_discount_amount' => null, 'usage_limit' => 110, 'used_count' => 0, 'days' => 65, 'status' => true],
            ['code' => 'MARKET12', 'type' => 'percentage', 'value' => 12, 'minimum_order_amount' => 45, 'maximum_discount_amount' => 12, 'usage_limit' => 40, 'used_count' => 0, 'days' => 30, 'status' => true],
        ];

        foreach ($coupons as $coupon) {
            $days = $coupon['days'];

            unset($coupon['days']);

            if ($days < 0) {
                $startsAt = now()->subDays(40);
                $expiresAt = now()->subDays(abs($days));
            } else {
                $startsAt = now()->subDays(5);
                $expiresAt = now()->addDays($days);
            }

            if (!DB::table('coupons')->where('code', $coupon['code'])->exists()) {
                DB::table('coupons')->insert(array_merge(
                    $coupon,
                    [
                    'starts_at' => $startsAt,
                    'expires_at' => $expiresAt,
                    'updated_at' => now(),
                    'created_at' => now(),
                    ]
                ));
            }
        }
    }
}