<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use RuntimeException;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $sellers = User::where('role', 'seller')
            ->where('status', 'active')
            ->where('email', 'like', 'seller.%@secondbook.test')
            ->orderBy('id')
            ->get();

        if ($sellers->isEmpty()) {
            throw new RuntimeException('No active demo sellers found for store seeding.');
        }

        foreach ($sellers as $index => $seller) {
            $name = $seller->first_name . "'s Bookshop";

            Store::updateOrCreate(
                ['seller_id' => $seller->id],
                [
                    'name' => $name,
                    'slug' => Str::slug($seller->username . '-' . $name),
                    'description' => 'A trusted SecondBook marketplace store.',
                    'logo' => null,
                    'phone' => $seller->phone,
                    'address' => $seller->city
                        ? $seller->city . ', Azerbaijan'
                        : 'Baku, Azerbaijan',
                    'status' => 'active',
                    'accept_orders' => true,
                    'auto_approve_orders' => false,
                    'processing_time' => ($index % 3) + 1,
                    'minimum_order_amount' => 0,
                    'order_note' => 'Books are carefully packed before shipping.',
                ]
            );
        }

        $this->command->info(
            $sellers->count() . ' seller stores seeded successfully.'
        );
    }
}