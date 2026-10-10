<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $users = DB::table('users')
            ->where('status', 'active')
            ->where(function ($query) {
                $query->where('email', 'like', 'seller.%@secondbook.test')
                    ->orWhere('email', 'like', '%@example.com')
                    ->orWhere('email', 'admin@gmail.com');
            })
            ->orderBy('email')
            ->pluck('id');

        if ($users->isEmpty()) {
            throw new RuntimeException('No active demo users found for notification seeding.');
        }

        $notifications = [
            [
                'type' => 'order',
                'title' => 'Order Confirmed',
                'message' => 'Your order has been successfully confirmed.',
                'read_at' => null,
            ],
            [
                'type' => 'order',
                'title' => 'Order Shipped',
                'message' => 'Your book order has been shipped.',
                'read_at' => now()->subDays(2),
            ],
            [
                'type' => 'general',
                'title' => 'Welcome to SecondBook',
                'message' => 'Welcome to SecondBook. Start exploring our marketplace today.',
                'read_at' => now()->subDays(5),
            ],
            [
                'type' => 'promotion',
                'title' => 'New Discount Available',
                'message' => 'A new discount coupon is available in the marketplace.',
                'read_at' => null,
            ],
        ];
        $extraNotifications = [
            ['general', 'Reading List Tip', 'Use your wishlist to keep track of books you want to read next.'],
            ['order', 'Order Status Updated', 'Your order status can be reviewed in your account order history.'],
            ['general', 'Complete Your Profile', 'Add contact and delivery details to make checkout easier.'],
            ['promotion', 'Weekly Book Picks', 'Explore a fresh selection of books across the marketplace.'],
            ['seller', 'Listing Quality Reminder', 'Accurate book conditions and descriptions help buyers choose confidently.'],
            ['order', 'Delivery Information', 'Keep your delivery address up to date for future orders.'],
            ['general', 'Review Your Purchase', 'Share a helpful review after receiving a book you ordered.'],
            ['promotion', 'Explore New Categories', 'Browse the latest category collections to discover new titles.'],
            ['seller', 'Stock Information Reminder', 'Keep listing stock accurate so buyers see current availability.'],
            ['general', 'SecondBook Community', 'Pass a good book along and help it find another reader.'],
        ];

        foreach ($users as $userIndex => $userId) {
            $userNotifications = $userIndex < 36
                ? $notifications
                : [[
                    'type' => $extraNotifications[$userIndex - 36][0],
                    'title' => $extraNotifications[$userIndex - 36][1],
                    'message' => $extraNotifications[$userIndex - 36][2],
                    'read_at' => null,
                ]];

            foreach ($userNotifications as $notificationIndex => $notification) {
                $exists = DB::table('notifications')
                    ->where('user_id', $userId)
                    ->where('type', $notification['type'])
                    ->where('title', $notification['title'])
                    ->exists();

                if (!$exists) {
                    DB::table('notifications')->insert([
                        'user_id' => $userId,
                        'type' => $notification['type'],
                        'title' => $notification['title'],
                        'message' => $notification['message'],
                        'read_at' => $notification['read_at'],
                        'created_at' => now()->subDays((($userIndex + $notificationIndex) % 15) + 1),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}