<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class WishlistSeeder extends Seeder
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
            ->whereNotNull('seller_id')
            ->where('description', 'like', '%seeded book available in the SecondBook marketplace%')
            ->pluck('id')
            ->values();

        if ($users->isEmpty() || $books->isEmpty()) {
            throw new RuntimeException('Active demo users and approved seller books are required for wishlist seeding.');
        }

        $insertIfMissing = static function (array $row): void {
            $exists = DB::table('wishlists')
                ->where('user_id', $row['user_id'])
                ->where('book_id', $row['book_id'])
                ->exists();

            if (!$exists) {
                DB::table('wishlists')->insert($row);
            }
        };

        foreach ($users as $userIndex => $userId) {
            $wishlistsForUser = $userIndex < 35 ? 3 : 1;

            for ($i = 0; $i < $wishlistsForUser; $i++) {
                $bookId = $books[($userIndex * 3 + $i) % $books->count()];

                $insertIfMissing([
                    'user_id' => $userId,
                    'book_id' => $bookId,
                    'created_at' => now()->subDays((($userIndex * 3 + $i) % 60) + 1),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}