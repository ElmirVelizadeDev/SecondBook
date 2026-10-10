<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class MessageReplySeeder extends Seeder
{
    public function run(): void
    {
        $adminId = DB::table('users')
            ->where('email', 'admin@gmail.com')
            ->value('id');

        if (!$adminId) {
            throw new RuntimeException('The seeded admin account is required before support replies can be created.');
        }

        $messages = DB::table('messages')
            ->where(function ($query) {
                $query->where('email', 'like', 'seller.%@secondbook.test')
                    ->orWhere('email', 'like', '%@example.com')
                    ->orWhere('email', 'admin@gmail.com');
            })
            ->orderBy('email')
            ->orderBy('id')
            ->get();

        if ($messages->isEmpty()) {
            throw new RuntimeException('No demo messages found before support reply seeding.');
        }

        $replies = [
            'Thank you for contacting SecondBook. We are happy to help.',
            'Thank you for your message. We have checked the information and will assist you shortly.',
            'Your request has been received. Please let us know if you need anything else.',
            'We appreciate you contacting our support team.',
        ];
        foreach ($messages as $index => $message) {
            $reply = $replies[$index % count($replies)];
            if (!DB::table('message_replies')
                ->where('message_id', $message->id)
                ->where('reply', $reply)
                ->exists()) {
                DB::table('message_replies')->insert([
                    'message_id' => $message->id,
                    'user_id' => $adminId,
                    'sender_type' => 'admin',
                    'reply' => $reply,
                    'updated_at' => now(),
                    'created_at' => now()->subDays(($index % 10) + 1),
                ]);
            }
        }
    }
}