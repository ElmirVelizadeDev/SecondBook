<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class BooksManagementFiveSeeder extends Seeder
{
    public function run(): void
    {
        $sellers = User::where('role', 'seller')
            ->where('status', 'active')
            ->where('email', 'like', 'seller.%@secondbook.test')
            ->orderBy('email')
            ->get();

        if ($sellers->isEmpty()) {
            throw new RuntimeException('No active demo sellers are available for the books-management records.');
        }

        $books = [
            ['1984', '9780451524935', 'George Orwell', 'Fiction', 'Penguin Random House', 12.50],
            ['Pride and Prejudice', '9780141439518', 'Jane Austen', 'Classic Literature', 'Penguin Random House', 14.99],
            ['Sapiens', '9780062316097', 'Yuval Noah Harari', 'History', 'HarperCollins', 18.90],
            ['Murder on the Orient Express', '9780062693662', 'Agatha Christie', 'Mystery', 'HarperCollins', 11.75],
            ['Clean Code', '9780132350884', 'Robert C. Martin', 'Programming', "O'Reilly Media", 21.00],
            ["The Wise Man's Fear", '9780756407919', 'Patrick Rothfuss', 'Fantasy', 'Crown Publishing Group', 17.50],
            ['Daisy Jones & The Six', '9781524798642', 'Taylor Jenkins Reid', 'Fiction', 'Atria Books', 16.25],
            ['Angels & Demons', '9780671027360', 'Dan Brown', 'Thriller', 'Doubleday', 13.99],
            ['Programming Ruby', '9780201710894', 'Andrew Hunt', 'Programming', 'Pearson', 24.50],
            ['Americanah', '9780307455925', 'Chimamanda Ngozi Adichie', 'Fiction', 'Random House', 15.75],
            ['Becoming', '9781524763138', 'Michelle Obama', 'Biography', 'Crown Publishing Group', 19.99],
            ['A Short History of Nearly Everything', '9780767908184', 'Bill Bryson', 'Science', 'Penguin Random House', 18.25],
            ['Stiff', '9780393324822', 'Mary Roach', 'Science', 'W. W. Norton & Company', 14.50],
            ['Broken April', '9781611453653', 'Ismail Kadare', 'Historical Fiction', 'Canongate Books', 12.99],
            ['21 Lessons for the 21st Century', '9780525512172', 'Yuval Noah Harari', 'History', 'Penguin Random House', 17.99],
        ];

        foreach ($books as $index => [$title, $isbn, $authorName, $categoryName, $publisherName, $price]) {
            $author = Author::where('name', $authorName)->first();
            $category = Category::where('name', $categoryName)->first();
            $publisher = Publisher::where('name', $publisherName)->first();

            if (!$author || !$category || !$publisher) {
                throw new RuntimeException(
                    "Missing seeded relation for book \"{$title}\"."
                );
            }

            $seller = $sellers[$index % $sellers->count()];
            $existingBook = Book::where('isbn', $isbn)->first();

            if (
                $existingBook
                && !str_contains(
                    (string) $existingBook->description,
                    'seeded book available in the SecondBook marketplace'
                )
            ) {
                $this->command->warn(
                    "Preserving existing non-seed book with ISBN {$isbn}."
                );
                continue;
            }

            Book::firstOrCreate(
                ['isbn' => $isbn],
                [
                    'title' => $title,
                    'category_id' => $category->id,
                    'author_id' => $author->id,
                    'publisher_id' => $publisher->id,
                    'seller_id' => $seller->id,
                    'description' => "{$title} is a seeded book available in the SecondBook marketplace.",
                    'cover' => "https://covers.openlibrary.org/b/isbn/{$isbn}-L.jpg",
                    'publication_year' => 2000 + ($index % 25),
                    'pages' => 220 + (($index * 31) % 400),
                    'language' => 'English',
                    'price' => $price,
                    'stock' => 8 + ($index % 12),
                    'condition' => ['new', 'like_new', 'good', 'fair'][$index % 4],
                    'status' => 'approved',
                ]
            );
        }

        $this->command->info('15 books-management records checked; 10 additional books are uniquely keyed by ISBN.');
    }
}
