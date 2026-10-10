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
                'description' => 'Faster delivery for customers who need their books sooner.',
                'price' => 7.99,
                'delivery_time' => '1-2 business days',
                'status' => true,
            ],
            [
                'name' => 'Free Shipping',
                'description' => 'Free standard delivery for qualifying orders.',
                'price' => 0,
                'delivery_time' => '5-7 business days',
                'status' => true,
            ],
            [
                'name' => 'International Shipping',
                'description' => 'International delivery for selected destinations.',
                'price' => 14.99,
                'delivery_time' => '7-14 business days',
                'status' => true,
            ],
            ['name' => 'Baku Same-Day Delivery', 'description' => 'Same-day delivery for eligible Baku orders.', 'price' => 9.50, 'delivery_time' => 'Same day', 'status' => true],
            ['name' => 'Baku Next-Day Delivery', 'description' => 'Next-business-day delivery within Baku.', 'price' => 5.50, 'delivery_time' => '1 business day', 'status' => true],
            ['name' => 'Regional Azerbaijan Shipping', 'description' => 'Standard delivery to regional destinations in Azerbaijan.', 'price' => 5.00, 'delivery_time' => '4-6 business days', 'status' => true],
            ['name' => 'Nakhchivan Shipping', 'description' => 'Tracked delivery to Nakhchivan.', 'price' => 7.00, 'delivery_time' => '5-8 business days', 'status' => true],
            ['name' => 'Pickup Point Delivery', 'description' => 'Delivery to a participating pickup point.', 'price' => 2.50, 'delivery_time' => '3-5 business days', 'status' => true],
            ['name' => 'Tracked Standard Shipping', 'description' => 'Standard delivery with shipment tracking.', 'price' => 4.50, 'delivery_time' => '3-5 business days', 'status' => true],
            ['name' => 'Economy Shipping', 'description' => 'Lower-cost delivery for non-urgent orders.', 'price' => 2.00, 'delivery_time' => '6-9 business days', 'status' => true],
            ['name' => 'Fragile Book Protection', 'description' => 'Protective packaging for collectible editions.', 'price' => 3.00, 'delivery_time' => '3-6 business days', 'status' => true],
            ['name' => 'Weekend Delivery', 'description' => 'Delivery on an available weekend route.', 'price' => 6.50, 'delivery_time' => 'Next available weekend', 'status' => true],
            ['name' => 'International Tracked Shipping', 'description' => 'Tracked international delivery for eligible destinations.', 'price' => 18.00, 'delivery_time' => '7-14 business days', 'status' => true],
        ];

        foreach ($shippings as $shipping) {
            if (!DB::table('shippings')->where('name', $shipping['name'])->exists()) {
                DB::table('shippings')->insert(array_merge($shipping, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
    }
}