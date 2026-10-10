<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSettingSeeder extends Seeder
{
    public function run(): void
    {
        $users = DB::table('users')
            ->where('status', true)
            ->where(function ($query) {
                $query->where('email', 'like', 'seller.%@secondbook.test')
                    ->orWhere('email', 'like', '%@example.com')
                    ->orWhere('email', 'admin@gmail.com');
            })
            ->pluck('id');

        foreach ($users as $index => $userId) {
            $exists = DB::table('user_settings')->where('user_id', $userId)->exists();

            if (!$exists) {
                DB::table('user_settings')->insert([
                    'user_id' => $userId,
                    'email_notifications' => true,
                    'order_updates' => true,
                    'promotional_emails' => $index % 3 !== 0,
                    'profile_visible' => true,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]);
            }
        }
    }
}