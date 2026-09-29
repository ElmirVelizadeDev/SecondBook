<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $users = DB::table('users')
            ->where('status', true)
            ->where('email', '!=', 'admin@secondbook.com')
            ->pluck('id')
            ->values();

        $books = DB::table('books')
            ->where('status', 'approved')
            ->pluck('id')
            ->values();

        if ($users->isEmpty() || $books->isEmpty()) {
            return;
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

        foreach ($users as $userId) {
            for ($i = 0; $i < 3; $i++) {

                $bookId = $books[$counter % $books->count()];

                $counter++;

                DB::table('reviews')->updateOrInsert(
                    [
                        'user_id' => $userId,
                        'book_id' => $bookId,
                    ],
                    [
                        'rating' => rand(4, 5),
                        'comment' => $comments[array_rand($comments)],
                        'status' => 'approved',
                        'created_at' => now()->subDays(rand(1, 50)),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}