<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SellerApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $buyers = DB::table('users')
            ->where('role', 'user')
            ->where('status', 'active')
            ->where('email', 'like', '%@example.com')
            ->orderBy('id')
            ->get();

        if ($buyers->isEmpty()) {
            throw new RuntimeException('No active demo buyers found for seller application seeding.');
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
        $historicalApplications = [
            ['store_name' => 'Old Town Book Nook', 'description' => 'A neighborhood collection of classic and contemporary titles.'],
            ['store_name' => 'Paper Lantern Books', 'description' => 'Carefully selected fiction and poetry for local readers.'],
            ['store_name' => 'Open Shelf Azerbaijan', 'description' => 'Affordable books with a focus on education and lifelong learning.'],
            ['store_name' => 'The Study Shelf', 'description' => 'Academic titles and practical references for students.'],
            ['store_name' => 'Caspian Pages', 'description' => 'A varied selection of regional and international literature.'],
            ['store_name' => 'Quiet Chapter', 'description' => 'A small curated collection for relaxed reading.'],
            ['store_name' => 'Bright Ideas Books', 'description' => 'Business, science and personal development titles.'],
            ['store_name' => 'Blue Door Bookshop', 'description' => 'Pre-owned novels and classics in good condition.'],
            ['store_name' => 'New Leaf Reads', 'description' => 'A fresh selection of books for readers of every age.'],
            ['store_name' => 'Chapter Exchange', 'description' => 'Books that can be enjoyed and shared by another reader.'],
        ];
        $insertIfMissing = static function (array $application): void {
            $exists = DB::table('seller_applications')
                ->where('user_id', $application['user_id'])
                ->where('store_name', $application['store_name'])
                ->exists();

            if (!$exists) {
                DB::table('seller_applications')->insert($application);
            }
        };

        foreach ($buyers as $index => $buyer) {
            $application = $applications[$index % count($applications)];

            $insertIfMissing([
                    'user_id' => $buyer->id,
                    'store_name' => $application['store_name'],
                    'description' => $application['description'],
                    'phone' => $application['phone'],
                    'address' => $application['address'],

                    // Every buyer application starts as pending.
                    'status' => 'pending',

                    'rejection_reason' => null,
                    'reviewed_at' => null,

                    'created_at' => now()->subDays(($index % 20) + 1),
                    'updated_at' => now(),
                ]);

            if (isset($historicalApplications[$index])) {
                $historical = $historicalApplications[$index];
                $insertIfMissing([
                        'user_id' => $buyer->id,
                        'store_name' => $historical['store_name'],
                        'description' => $historical['description'],
                        'phone' => $buyer->phone,
                        'address' => ($buyer->city ?: 'Baku') . ', Azerbaijan',
                        'status' => 'rejected',
                        'rejection_reason' => 'The application was not completed; the account may submit an updated application.',
                        'reviewed_at' => now()->subDays(20),
                        'created_at' => now()->subDays(30 + $index),
                        'updated_at' => now()->subDays(20),
                    ]);
            }
        }

        $this->command->info(
            $buyers->count() . ' current applications and 10 historical application records seeded successfully.'
        );
    }
}