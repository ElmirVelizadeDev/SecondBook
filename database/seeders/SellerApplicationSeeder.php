<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SellerApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $buyers = DB::table('users')
            ->where('role', 'user')
            ->where('status', 'active')
            ->orderBy('id')
            ->get();

        if ($buyers->isEmpty()) {
            $this->command->warn('No active buyers found.');
            return;
        }

        $applications = [
            [
                'store_name' => 'Readers Corner',
                'description' => 'A small online bookstore specializing in fiction and classic literature.',
                'phone' => '+994501112233',
                'address' => 'Baku, Azerbaijan',
            ],
            [
                'store_name' => 'Classic Pages',
                'description' => 'A collection of classic and historical books for passionate readers.',
                'phone' => '+994502223344',
                'address' => 'Nakhchivan, Azerbaijan',
            ],
            [
                'store_name' => 'Book House',
                'description' => 'Affordable books for students and everyday readers.',
                'phone' => '+994503334455',
                'address' => 'Ganja, Azerbaijan',
            ],
            [
                'store_name' => 'Tech Readers',
                'description' => 'Programming, technology and professional development books.',
                'phone' => '+994504445566',
                'address' => 'Sumqayit, Azerbaijan',
            ],
            [
                'store_name' => 'Knowledge Hub',
                'description' => 'Educational books, university materials and academic resources.',
                'phone' => '+994505556677',
                'address' => 'Baku, Azerbaijan',
            ],
            [
                'store_name' => 'Book World',
                'description' => 'A diverse collection of fiction, non-fiction and contemporary books.',
                'phone' => '+994506667788',
                'address' => 'Shaki, Azerbaijan',
            ],
            [
                'store_name' => 'Readers Point',
                'description' => 'Second-hand books in excellent condition at affordable prices.',
                'phone' => '+994507778899',
                'address' => 'Mingachevir, Azerbaijan',
            ],
            [
                'store_name' => 'Literature House',
                'description' => 'Local and international literature for readers of all ages.',
                'phone' => '+994508889900',
                'address' => 'Baku, Azerbaijan',
            ],
            [
                'store_name' => 'Smart Books',
                'description' => 'Business, finance, programming and personal development books.',
                'phone' => '+994509990011',
                'address' => 'Baku, Azerbaijan',
            ],
            [
                'store_name' => 'Old Pages',
                'description' => 'Rare, vintage and used books collected from different sources.',
                'phone' => '+994501010101',
                'address' => 'Nakhchivan, Azerbaijan',
            ],
            [
                'store_name' => 'Study Corner',
                'description' => 'University textbooks and study materials for students.',
                'phone' => '+994502020202',
                'address' => 'Baku, Azerbaijan',
            ],
            [
                'store_name' => 'Book Station',
                'description' => 'A general bookstore offering affordable second-hand books.',
                'phone' => '+994503030303',
                'address' => 'Ganja, Azerbaijan',
            ],
            [
                'store_name' => 'Page & Story',
                'description' => 'Fiction, romance, mystery and popular literature.',
                'phone' => '+994504040404',
                'address' => 'Baku, Azerbaijan',
            ],
            [
                'store_name' => 'Digital Minds',
                'description' => 'Books focused on technology, software engineering and IT.',
                'phone' => '+994505050505',
                'address' => 'Sumqayit, Azerbaijan',
            ],
            [
                'store_name' => 'The Reading Room',
                'description' => 'A curated collection of books for serious readers.',
                'phone' => '+994506060606',
                'address' => 'Baku, Azerbaijan',
            ],
            [
                'store_name' => 'Book Avenue',
                'description' => 'Modern novels, classics and popular non-fiction titles.',
                'phone' => '+994507070707',
                'address' => 'Baku, Azerbaijan',
            ],
            [
                'store_name' => 'Student Books',
                'description' => 'Affordable academic and educational books for students.',
                'phone' => '+994508080808',
                'address' => 'Nakhchivan, Azerbaijan',
            ],
            [
                'store_name' => 'Book Market',
                'description' => 'A marketplace for buying and selling quality used books.',
                'phone' => '+994509090909',
                'address' => 'Baku, Azerbaijan',
            ],
            [
                'store_name' => 'New Chapter',
                'description' => 'Books for personal growth, business and modern lifestyle.',
                'phone' => '+994501212121',
                'address' => 'Ganja, Azerbaijan',
            ],
            [
                'store_name' => 'Readers Library',
                'description' => 'A broad selection of fiction, history and cultural books.',
                'phone' => '+994502323232',
                'address' => 'Baku, Azerbaijan',
            ],
        ];

        foreach ($buyers as $index => $buyer) {
            $application = $applications[$index % count($applications)];

            DB::table('seller_applications')->updateOrInsert(
                [
                    'user_id' => $buyer->id,
                ],
                [
                    'user_id' => $buyer->id,
                    'store_name' => $application['store_name'],
                    'description' => $application['description'],
                    'phone' => $application['phone'],
                    'address' => $application['address'],

                    // Every buyer application starts as pending.
                    'status' => 'pending',

                    'rejection_reason' => null,
                    'reviewed_at' => null,

                    'created_at' => now()->subDays(rand(1, 20)),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info(
            $buyers->count() . ' pending seller applications seeded successfully.'
        );
    }
}