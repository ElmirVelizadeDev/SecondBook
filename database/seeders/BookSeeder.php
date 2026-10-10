<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Get active seeded sellers dynamically
        |--------------------------------------------------------------------------
        */
        $sellers = User::where('role', 'seller')
            ->where('status', 'active')
            ->where('email', 'like', 'seller.%@secondbook.test')
            ->orderBy('id')
            ->get();

        if ($sellers->isEmpty()) {
            throw new RuntimeException(
                'No active demo sellers found. Books cannot be assigned to sellers.'
            );
        }

        $books = [
            ['Pride and Prejudice', '9780141439518', 'Jane Austen', 'Classic Literature', 'Penguin Random House'],
            ['Emma', '9780141439587', 'Jane Austen', 'Romance', 'Penguin Random House'],
            ['1984', '9780451524935', 'George Orwell', 'Fiction', 'Penguin Random House'],
            ['Animal Farm', '9780451526342', 'George Orwell', 'Classic Literature', 'Penguin Random House'],
            ['Norwegian Wood', '9780375704024', 'Haruki Murakami', 'Romance', 'Vintage Books'],
            ['Kafka on the Shore', '9781400079278', 'Haruki Murakami', 'Fantasy', 'Vintage Books'],
            ['Murder on the Orient Express', '9780062693662', 'Agatha Christie', 'Mystery', 'HarperCollins'],
            ['And Then There Were None', '9780062073488', 'Agatha Christie', 'Mystery', 'HarperCollins'],
            ['The Sweet Salt of the Bosphorus', '9789752638612', 'Elchin Safarli', 'Fiction', 'Canongate Books'],
            ['There Without Back', '9789952385560', 'Elchin Safarli', 'Romance', 'Canongate Books'],
            ['The General of the Dead Army', '9780810116794', 'Ismail Kadare', 'Historical Fiction', 'Faber & Faber'],
            ['Chronicle in Stone', '9781846550074', 'Ismail Kadare', 'History', 'Faber & Faber'],
            ['Sapiens', '9780062316097', 'Yuval Noah Harari', 'History', 'HarperCollins'],
            ['Homo Deus', '9780062464316', 'Yuval Noah Harari', 'Science', 'HarperCollins'],
            ['The Alchemist', '9780062315007', 'Paulo Coelho', 'Fiction', 'HarperCollins'],
            ['Veronika Decides to Die', '9780061124266', 'Paulo Coelho', 'Psychology', 'HarperCollins'],
            ['I Am Malala', '9780316322423', 'Malala Yousafzai', 'Biography', 'Little, Brown and Company'],
            ['We Are Displaced', '9780316523622', 'Malala Yousafzai', 'History', 'Little, Brown and Company'],
            ['The Shining', '9780307743657', 'Stephen King', 'Horror', 'Random House'],
            ['It', '9781501142970', 'Stephen King', 'Horror', 'Simon & Schuster'],
            ['The Art of Loving', '9780061129735', 'Erich Fromm', 'Psychology', 'HarperCollins'],
            ['Escape from Freedom', '9780807059653', 'Erich Fromm', 'Philosophy', 'Macmillan Publishers'],
            ['War and Peace', '9780199232765', 'Leo Tolstoy', 'Historical Fiction', 'Oxford University Press'],
            ['Anna Karenina', '9780143035008', 'Leo Tolstoy', 'Romance', 'Penguin Random House'],
            ['Crime and Punishment', '9780486415871', 'Fyodor Dostoevsky', 'Classic Literature', 'Oxford University Press'],
            ['The Brothers Karamazov', '9780374528379', 'Fyodor Dostoevsky', 'Philosophy', 'Vintage Books'],
            ['One Hundred Years of Solitude', '9780060883287', 'Gabriel Garcia Marquez', 'Fiction', 'HarperCollins'],
            ['Love in the Time of Cholera', '9780307389732', 'Gabriel Garcia Marquez', 'Romance', 'Vintage Books'],
            ['The Metamorphosis', '9780553213690', 'Franz Kafka', 'Classic Literature', 'Bantam Books'],
            ['The Trial', '9780805209990', 'Franz Kafka', 'Mystery', 'Random House'],
            ["Harry Potter and the Philosopher's Stone", '9780747532699', 'J.K. Rowling', 'Fantasy', 'Bloomsbury Publishing'],
            ['Harry Potter and the Chamber of Secrets', '9780747549604', 'J.K. Rowling', 'Fantasy', 'Bloomsbury Publishing'],
            ['The Hobbit', '9780547928227', 'J.R.R. Tolkien', 'Fantasy', 'Hachette Book Group'],
            ['The Lord of the Rings', '9780544003415', 'J.R.R. Tolkien', 'Fantasy', 'Hachette Book Group'],
            ['The Little Prince', '9780156012195', 'Antoine de Saint-Exupery', 'Children', 'Hachette Book Group'],
            ['Wind, Sand and Stars', '9780156027495', 'Antoine de Saint-Exupery', 'Adventure', 'Hachette Book Group'],
            ['Thinking, Fast and Slow', '9780374533557', 'Daniel Kahneman', 'Psychology', 'Macmillan Publishers'],
            ['Noise', '9780062930801', 'Daniel Kahneman', 'Psychology', 'HarperCollins'],
            ['Atomic Habits', '9780735211292', 'James Clear', 'Self-Help', 'Penguin Random House'],
            ['Atomic Habits Workbook', '9780593236789', 'James Clear', 'Self-Help', 'Penguin Random House'],
            ['A Brief History of Time', '9780553380163', 'Stephen Hawking', 'Science', 'Bantam Books'],
            ['The Universe in a Nutshell', '9780553802023', 'Stephen Hawking', 'Science', 'Bantam Books'],
            ['Steve Jobs', '9781451648539', 'Walter Isaacson', 'Biography', 'Simon & Schuster'],
            ['Leonardo da Vinci', '9781501139154', 'Walter Isaacson', 'Biography', 'Simon & Schuster'],
            ['Clean Code', '9780132350884', 'Robert C. Martin', 'Programming', "O'Reilly Media"],
            ['The Clean Coder', '9780137081073', 'Robert C. Martin', 'Programming', 'Pearson'],
            ['The C Programming Language', '9780131103627', 'Brian Kernighan', 'Programming', 'Pearson'],
            ['The Practice of Programming', '9780201615869', 'Brian Kernighan', 'Technology', 'Pearson'],
            ['Refactoring', '9780134757599', 'Martin Fowler', 'Programming', 'Pearson'],
            ['The Psychology of Money', '9780857197689', 'Morgan Housel', 'Finance', 'Wiley'],
            ['The Book Thief', '9780375842207', 'Markus Zusak', 'Historical Fiction', 'Random House'],
            ['The Name of the Wind', '9780756404741', 'Patrick Rothfuss', 'Fantasy', 'Crown Publishing Group'],
            ['The Silent Patient', '9781250301697', 'Alex Michaelides', 'Mystery', 'Macmillan Publishers'],
            ['The Ocean at the End of the Lane', '9780062459367', 'Neil Gaiman', 'Fantasy', 'HarperCollins'],
            ['The Midnight Library', '9780525559474', 'Matt Haig', 'Fiction', 'Penguin Random House'],
            ['The Seven Husbands of Evelyn Hugo', '9781501161933', 'Taylor Jenkins Reid', 'Romance', 'Atria Books'],
            ['The Da Vinci Code', '9780307474278', 'Dan Brown', 'Mystery', 'Doubleday'],
            ['Educated', '9780399590504', 'Tara Westover', 'Biography', 'Random House'],
            ['The Pragmatic Programmer', '9780135957059', 'Andrew Hunt', 'Programming', 'Pearson'],
            ['The Body Keeps the Score', '9780143127741', 'Bessel van der Kolk', 'Psychology', 'Penguin Random House'],
        ];

        foreach ($books as $index => $data) {
            [
                $title,
                $isbn,
                $authorName,
                $categoryName,
                $publisherName
            ] = $data;

            $author = Author::where('name', $authorName)->first();
            $category = Category::where('name', $categoryName)->first();
            $publisher = Publisher::where('name', $publisherName)->first();

            if (!$author || !$category || !$publisher) {
                throw new RuntimeException(
                    "Missing seeded relation for book \"{$title}\" (author: {$authorName}, category: {$categoryName}, publisher: {$publisherName})."
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Dynamically assign seller
            |--------------------------------------------------------------------------
            |
            | Seller is selected from the actual active seller collection.
            | No seller ID is hardcoded.
            |
            */
            $seller = $sellers[$index % $sellers->count()];

            $book = Book::firstOrNew(['isbn' => $isbn]);

            if ($book->exists && !str_contains((string) $book->description, 'seeded book available in the SecondBook marketplace')) {
                $this->command->warn(
                    "Preserving existing non-seed book with ISBN {$isbn}."
                );
                continue;
            }

            $book->fill([
                    'title' => $title,
                    'category_id' => $category->id,
                    'author_id' => $author->id,
                    'publisher_id' => $publisher->id,

                    // Dynamic seller ID
                    'seller_id' => $seller->id,

                    'description' => "{$title} is a seeded book available in the SecondBook marketplace.",

                    'cover' => "https://covers.openlibrary.org/b/isbn/{$isbn}-L.jpg",

                    'publication_year' => 1990 + ($index % 35),
                    'pages' => 180 + (($index * 23) % 471),
                    'language' => 'English',
                    'price' => 8 + ($index % 38) + 0.99,
                    'stock' => 5 + ($index % 26),

                    'condition' => [
                        'new',
                        'like_new',
                        'good',
                        'fair',
                    ][$index % 4],

                    'status' => 'approved',
                ]);
            $book->save();
        }

        $this->command->info(
            count($books) . ' books seeded and assigned dynamically to active sellers.'
        );
    }
}