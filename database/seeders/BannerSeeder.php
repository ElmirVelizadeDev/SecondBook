<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [

            [
                'title' => 'Discover Your Next Book',
                'subtitle' => 'Explore thousands of new and pre-owned books.',
                'image' => '',
                'button_text' => 'Shop Books',
                'button_url' => '/books',
                'position' => 1,
            ],

            [
                'title' => 'Give Books a Second Life',
                'subtitle' => 'Sell your books and connect with readers.',
                'image' => '',
                'button_text' => 'Become a Seller',
                'button_url' => '/seller/apply',
                'position' => 2,
            ],

            [
                'title' => 'Build Your Personal Library',
                'subtitle' => 'Find classics, fiction, technology and more.',
                'image' => '',
                'button_text' => 'Explore',
                'button_url' => '/books',
                'position' => 3,
            ],

            [
                'title' => 'Stories Worth Sharing',
                'subtitle' => 'Discover books that deserve another reader.',
                'image' => '',
                'button_text' => 'Browse Collection',
                'button_url' => '/books',
                'position' => 4,
            ],

            [
                'title' => 'Read More, Spend Less',
                'subtitle' => 'Find quality pre-owned books at affordable prices.',
                'image' => '',
                'button_text' => 'Shop Now',
                'button_url' => '/books',
                'position' => 5,
            ],

            [
                'title' => 'Classics Never Get Old',
                'subtitle' => 'Rediscover timeless stories and unforgettable authors.',
                'image' => '',
                'button_text' => 'Explore Classics',
                'button_url' => '/books',
                'position' => 6,
            ],

            [
                'title' => 'Find Your Next Favorite',
                'subtitle' => 'From fiction to technology, there is something for everyone.',
                'image' => '',
                'button_text' => 'Find a Book',
                'button_url' => '/books',
                'position' => 7,
            ],

            [
                'title' => 'Turn Your Books Into Value',
                'subtitle' => 'Sell books you no longer need and reach new readers.',
                'image' => '',
                'button_text' => 'Sell Your Books',
                'button_url' => '/seller/apply',
                'position' => 8,
            ],

            [
                'title' => 'A Library for Every Reader',
                'subtitle' => 'Explore carefully selected books across many categories.',
                'image' => '',
                'button_text' => 'View Categories',
                'button_url' => '/categories',
                'position' => 9,
            ],

            [
                'title' => 'Technology & Knowledge',
                'subtitle' => 'Level up your skills with books about programming and technology.',
                'image' => '',
                'button_text' => 'Explore Technology',
                'button_url' => '/books',
                'position' => 10,
            ],

            [
                'title' => 'Stories for Every Mood',
                'subtitle' => 'Romance, mystery, adventure and more are waiting for you.',
                'image' => '',
                'button_text' => 'Discover Stories',
                'button_url' => '/books',
                'position' => 11,
            ],

            [
                'title' => 'Make Room for New Stories',
                'subtitle' => 'Sell your old books and make space for your next favorites.',
                'image' => '',
                'button_text' => 'Start Selling',
                'button_url' => '/seller/apply',
                'position' => 12,
            ],

            [
                'title' => 'Books That Inspire',
                'subtitle' => 'Discover ideas, perspectives and stories that stay with you.',
                'image' => '',
                'button_text' => 'Explore Books',
                'button_url' => '/books',
                'position' => 13,
            ],

            [
                'title' => 'Your Next Adventure Starts Here',
                'subtitle' => 'Open a book and step into a completely different world.',
                'image' => '',
                'button_text' => 'Start Exploring',
                'button_url' => '/books',
                'position' => 14,
            ],

            [
                'title' => 'SecondBook — Read. Share. Repeat.',
                'subtitle' => 'Give great books another chapter with a new reader.',
                'image' => '',
                'button_text' => 'Explore SecondBook',
                'button_url' => '/books',
                'position' => 15,
            ],
            ['title' => 'A World of New Ideas', 'subtitle' => 'Find thoughtful reads in science, history and culture.', 'image' => '', 'button_text' => 'Explore Ideas', 'button_url' => '/books', 'position' => 16],
            ['title' => 'Stories for the Weekend', 'subtitle' => 'Choose your next page-turner from independent sellers.', 'image' => '', 'button_text' => 'Browse Stories', 'button_url' => '/books', 'position' => 17],
            ['title' => 'Build Better Reading Habits', 'subtitle' => 'Make time for books that inspire and inform.', 'image' => '', 'button_text' => 'Find a Book', 'button_url' => '/books', 'position' => 18],
            ['title' => 'Discover Local Bookshops', 'subtitle' => 'Support sellers and keep good books in circulation.', 'image' => '', 'button_text' => 'View Stores', 'button_url' => '/stores', 'position' => 19],
            ['title' => 'A New Reader for Every Book', 'subtitle' => 'Give pre-owned books another chance to be enjoyed.', 'image' => '', 'button_text' => 'Shop Pre-Owned', 'button_url' => '/books', 'position' => 20],
            ['title' => 'Explore More Than Fiction', 'subtitle' => 'Browse learning, business, science and creative titles.', 'image' => '', 'button_text' => 'Browse Categories', 'button_url' => '/categories', 'position' => 21],
            ['title' => 'Read, Review, Recommend', 'subtitle' => 'Help other readers discover books they will love.', 'image' => '', 'button_text' => 'See Reviews', 'button_url' => '/books', 'position' => 22],
            ['title' => 'Find a Thoughtful Gift', 'subtitle' => 'Choose a memorable book for someone special.', 'image' => '', 'button_text' => 'Explore Books', 'button_url' => '/books', 'position' => 23],
            ['title' => 'Books That Travel Further', 'subtitle' => 'Pass along stories and ideas to another reader.', 'image' => '', 'button_text' => 'Start Browsing', 'button_url' => '/books', 'position' => 24],
            ['title' => 'Your Next Chapter Starts Here', 'subtitle' => 'Discover a new favorite in the SecondBook marketplace.', 'image' => '', 'button_text' => 'Shop Books', 'button_url' => '/books', 'position' => 25],

        ];

        foreach ($banners as $banner) {
            $existing = DB::table('banners')
                ->where('title', $banner['title'])
                ->first();

            if ($existing) {
                DB::table('banners')
                    ->where('id', $existing->id)
                    ->update(['image' => '', 'updated_at' => now()]);
                continue;
            }

            DB::table('banners')->insert(array_merge($banner, [
                    'status' => 'active',
                    'updated_at' => now(),
                    'created_at' => now(),
                ]));
        }
    }
}