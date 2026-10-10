<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $users = DB::table('users')
            ->where('status', 'active')
            ->whereIn('role', ['seller', 'user'])
            ->where(function ($query) {
                $query->where('email', 'like', 'seller.%@secondbook.test')
                    ->orWhere('email', 'like', '%@example.com');
            })
            ->orderBy('id')
            ->pluck('id')
            ->values();

        $books = DB::table('books')
            ->where('status', 'approved')
            ->where('description', 'like', '%seeded book available in the SecondBook marketplace%')
            ->pluck('id')
            ->values();

        if ($users->isEmpty() || $books->isEmpty()) {
            throw new RuntimeException('Active demo users and approved books are required for review seeding.');
        }

        $comments = [
            'Excellent book. I really enjoyed reading it.',
            'Very interesting and well written.',
            'The book arrived in good condition and I enjoyed it.',
            'A great addition to my bookshelf.',
            'The story was engaging from beginning to end.',
            'Very useful and informative.',
            'I would definitely recommend this book.',
            'The quality was better than I expected.',
            'One of the most enjoyable books I have read recently.',
            'A very good book for anyone interested in this topic.',
        ];

        $counter = 0;
        $insertIfMissing = static function (array $review): void {
            $exists = DB::table('reviews')
                ->where('user_id', $review['user_id'])
                ->where('book_id', $review['book_id'])
                ->exists();

            if (!$exists) {
                DB::table('reviews')->insert($review);
            }
        };

        foreach ($users as $userIndex => $userId) {
            $reviewsForUser = $userIndex < 35 ? 3 : 1;

            for ($i = 0; $i < $reviewsForUser; $i++) {
                $bookId = $books[$counter % $books->count()];

                $insertIfMissing([
                        'user_id' => $userId,
                        'book_id' => $bookId,
                        'rating' => 4 + ($counter % 2),
                        'comment' => $comments[$counter % count($comments)],
                        'status' => 'approved',
                        'created_at' => now()->subDays(($counter % 50) + 1),
                        'updated_at' => now(),
                    ]);
                $counter++;
            }
        }
    }
}