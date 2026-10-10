<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Fiction',
            'Classic Literature',
            'Mystery',
            'Thriller',
            'Horror',
            'Romance',
            'Science Fiction',
            'Fantasy',
            'Adventure',
            'Historical Fiction',
            'Biography',
            'Autobiography',
            'History',
            'Psychology',
            'Self-Help',
            'Philosophy',
            'Science',
            'Technology',
            'Programming',
            'Business',
            'Finance',
            'Economics',
            'Education',
            'Health',
            'Travel',
            'Cooking',
            'Children',
            'Young Adult',
            'Poetry',
            'Drama',
            'Art',
            'Comics & Graphic Novels',
            'Religion & Spirituality',
            'Language Learning',
            'Reference',
            'Sports',
            'Music',
            'Nature',
            'Law',
            'Crafts & Hobbies',
        ];

        $images = [
            'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=900&q=85',
            'https://images.unsplash.com/photo-1512820790803-83ca734da794?w=900&q=85',
            'https://images.unsplash.com/photo-1495446815901-a7297e633e8d?w=900&q=85',
            'https://images.unsplash.com/photo-1511108690759-009324a90311?w=900&q=85',
            'https://images.unsplash.com/photo-1516979187457-637abb4f9353?w=900&q=85',
        ];

        foreach ($categories as $index => $name) {
            Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'description' => $name . ' books collection.',
                    'image' => $images[$index % count($images)],
                    'status' => true,
                ]
            );
        }

        $this->command->info(count($categories) . ' categories seeded successfully.');
    }
}