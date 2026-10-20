<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingSeeder extends Seeder
{
    public function run(): void
    {
        $shippings = [
            [
                'name' => 'Standard Shipping',
                'description' => 'Reliable standard delivery for regular orders.',
                'price' => 3.99,
                'delivery_time' => '3-5 business days',
                'status' => true,
            ],
            [
                'name' => 'Express Shipping',
                'description' => 'Fast delivery for customers who need their books sooner.',
                'price' => 7.99,
                'delivery_time' => '1-2 business days',
                'status' => true,
            ],
            [
                'name' => 'Free Shipping',
                'description' => 'Free standard delivery for qualifying orders.',
                'price' => 0.00,
                'delivery_time' => '5-7 business days',
                'status' => true,
            ],
            [
                'name' => 'International Shipping',
                'description' => 'International delivery for eligible destinations.',
                'price' => 14.99,
                'delivery_time' => '7-14 business days',
                'status' => true,
            ],
        ];

        foreach ($shippings as $shipping) {
            if (!DB::table('shippings')
                ->where('name', $shipping['name'])
                ->exists()) {
                DB::table('shippings')->insert(array_merge($shipping, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
    }
}
