<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class SellerBooksSeeder extends Seeder
{
    public function run(): void
    {
        $sellers = User::where('role', 'seller')
            ->where('status', 'active')
            ->where('email', 'like', 'seller.%@secondbook.test')
            ->orderBy('id')
            ->get();

        if ($sellers->isEmpty()) {
            throw new RuntimeException('No active demo sellers found for book assignment verification.');
        }

        $sellerIds = $sellers->modelKeys();
        $unassignedBooks = Book::where('description', 'like', '%seeded book available in the SecondBook marketplace%')
            ->where(function ($query) use ($sellerIds) {
                $query->whereNull('seller_id')
                    ->orWhereNotIn('seller_id', $sellerIds);
            })
            ->count();

        if ($unassignedBooks > 0) {
            throw new RuntimeException("{$unassignedBooks} demo books have no valid seller.");
        }

        $this->command->info(
            'Demo book-to-seller assignments verified without changing existing listings.'
        );
    }
}