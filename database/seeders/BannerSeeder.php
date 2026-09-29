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
                'image' => 'https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&w=1600&q=85',
                'button_text' => 'Shop Books',
                'button_url' => '/books',
                'position' => 1,
            ],

            [
                'title' => 'Give Books a Second Life',
                'subtitle' => 'Sell your books and connect with readers.',
                'image' => 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=1600&q=85',
                'button_text' => 'Become a Seller',
                'button_url' => '/seller/apply',
                'position' => 2,
            ],

            [
                'title' => 'Build Your Personal Library',
                'subtitle' => 'Find classics, fiction, technology and more.',
                'image' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=1600&q=85',
                'button_text' => 'Explore',
                'button_url' => '/books',
                'position' => 3,
            ],

            [
                'title' => 'Stories Worth Sharing',
                'subtitle' => 'Discover books that deserve another reader.',
                'image' => 'https://images.unsplash.com/photo-1521587760476-6c12a4b040da?auto=format&fit=crop&w=1600&q=85',
                'button_text' => 'Browse Collection',
                'button_url' => '/books',
                'position' => 4,
            ],

            [
                'title' => 'Read More, Spend Less',
                'subtitle' => 'Find quality pre-owned books at affordable prices.',
                'image' => 'https://images.unsplash.com/photo-1495446815901-a7297e633e8d?auto=format&fit=crop&w=1600&q=85',
                'button_text' => 'Shop Now',
                'button_url' => '/books',
                'position' => 5,
            ],

            [
                'title' => 'Classics Never Get Old',
                'subtitle' => 'Rediscover timeless stories and unforgettable authors.',
                'image' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&w=1600&q=85',
                'button_text' => 'Explore Classics',
                'button_url' => '/books',
                'position' => 6,
            ],

            [
                'title' => 'Find Your Next Favorite',
                'subtitle' => 'From fiction to technology, there is something for everyone.',
                'image' => 'https://images.unsplash.com/photo-1519682337058-a94d519337bc?auto=format&fit=crop&w=1600&q=85',
                'button_text' => 'Find a Book',
                'button_url' => '/books',
                'position' => 7,
            ],

            [
                'title' => 'Turn Your Books Into Value',
                'subtitle' => 'Sell books you no longer need and reach new readers.',
                'image' => 'https://images.unsplash.com/photo-1550399105-c4db5fb85c18?auto=format&fit=crop&w=1600&q=85',
                'button_text' => 'Sell Your Books',
                'button_url' => '/seller/apply',
                'position' => 8,
            ],

            [
                'title' => 'A Library for Every Reader',
                'subtitle' => 'Explore carefully selected books across many categories.',
                'image' => 'https://images.unsplash.com/photo-1526243741027-444d633d7365?auto=format&fit=crop&w=1600&q=85',
                'button_text' => 'View Categories',
                'button_url' => '/categories',
                'position' => 9,
            ],

            [
                'title' => 'Technology & Knowledge',
                'subtitle' => 'Level up your skills with books about programming and technology.',
                'image' => 'https://images.unsplash.com/photo-1515879218367-8466d910aaa4?auto=format&fit=crop&w=1600&q=85',
                'button_text' => 'Explore Technology',
                'button_url' => '/books',
                'position' => 10,
            ],

            [
                'title' => 'Stories for Every Mood',
                'subtitle' => 'Romance, mystery, adventure and more are waiting for you.',
                'image' => 'https://images.unsplash.com/photo-1511108690759-009324a90311?auto=format&fit=crop&w=1600&q=85',
                'button_text' => 'Discover Stories',
                'button_url' => '/books',
                'position' => 11,
            ],

            [
                'title' => 'Make Room for New Stories',
                'subtitle' => 'Sell your old books and make space for your next favorites.',
                'image' => 'https://images.unsplash.com/photo-1495446815901-a7297e633e8d?auto=format&fit=crop&w=1600&q=85',
                'button_text' => 'Start Selling',
                'button_url' => '/seller/apply',
                'position' => 12,
            ],

            [
                'title' => 'Books That Inspire',
                'subtitle' => 'Discover ideas, perspectives and stories that stay with you.',
                'image' => 'https://images.unsplash.com/photo-1510172951991-856a654b9e6f?auto=format&fit=crop&w=1600&q=85',
                'button_text' => 'Explore Books',
                'button_url' => '/books',
                'position' => 13,
            ],

            [
                'title' => 'Your Next Adventure Starts Here',
                'subtitle' => 'Open a book and step into a completely different world.',
                'image' => 'https://images.unsplash.com/photo-1474932430478-367dbb6832c1?auto=format&fit=crop&w=1600&q=85',
                'button_text' => 'Start Exploring',
                'button_url' => '/books',
                'position' => 14,
            ],

            [
                'title' => 'SecondBook — Read. Share. Repeat.',
                'subtitle' => 'Give great books another chapter with a new reader.',
                'image' => 'https://images.unsplash.com/photo-1524578271613-d550eacf6090?auto=format&fit=crop&w=1600&q=85',
                'button_text' => 'Explore SecondBook',
                'button_url' => '/books',
                'position' => 15,
            ],

        ];

        foreach ($banners as $banner) {
            DB::table('banners')->updateOrInsert(
                ['title' => $banner['title']],
                array_merge($banner, [
                    'status' => 'active',
                    'updated_at' => now(),
                    'created_at' => now(),
                ])
            );
        }
    }
}