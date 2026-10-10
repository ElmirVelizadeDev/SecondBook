<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    public function run(): void
    {
        $authors = [
            ['Jane Austen', 'https://randomuser.me/api/portraits/women/68.jpg'],
            ['George Orwell', 'https://randomuser.me/api/portraits/men/52.jpg'],
            ['Haruki Murakami', 'https://randomuser.me/api/portraits/men/41.jpg'],
            ['Agatha Christie', 'https://randomuser.me/api/portraits/women/49.jpg'],
            ['Elchin Safarli', 'https://randomuser.me/api/portraits/men/61.jpg'],
            ['Ismail Kadare', 'https://randomuser.me/api/portraits/men/64.jpg'],
            ['Yuval Noah Harari', 'https://randomuser.me/api/portraits/men/76.jpg'],
            ['Paulo Coelho', 'https://randomuser.me/api/portraits/men/70.jpg'],
            ['Malala Yousafzai', 'https://randomuser.me/api/portraits/women/79.jpg'],
            ['Stephen King', 'https://randomuser.me/api/portraits/men/15.jpg'],
            ['Erich Fromm', 'https://randomuser.me/api/portraits/men/21.jpg'],
            ['Leo Tolstoy', 'https://randomuser.me/api/portraits/men/11.jpg'],
            ['Fyodor Dostoevsky', 'https://randomuser.me/api/portraits/men/22.jpg'],
            ['Gabriel Garcia Marquez', 'https://randomuser.me/api/portraits/men/33.jpg'],
            ['Franz Kafka', 'https://randomuser.me/api/portraits/men/36.jpg'],
            ['J.K. Rowling', 'https://randomuser.me/api/portraits/women/12.jpg'],
            ['J.R.R. Tolkien', 'https://randomuser.me/api/portraits/men/44.jpg'],
            ['Antoine de Saint-Exupery', 'https://randomuser.me/api/portraits/men/48.jpg'],
            ['Daniel Kahneman', 'https://randomuser.me/api/portraits/men/55.jpg'],
            ['James Clear', 'https://randomuser.me/api/portraits/men/57.jpg'],
            ['Stephen Hawking', 'https://randomuser.me/api/portraits/men/67.jpg'],
            ['Walter Isaacson', 'https://randomuser.me/api/portraits/men/69.jpg'],
            ['Robert C. Martin', 'https://randomuser.me/api/portraits/men/71.jpg'],
            ['Brian Kernighan', 'https://randomuser.me/api/portraits/men/72.jpg'],
            ['Martin Fowler', 'https://randomuser.me/api/portraits/men/73.jpg'],
            ['Morgan Housel', 'https://randomuser.me/api/portraits/men/74.jpg'],
            ['Markus Zusak', 'https://randomuser.me/api/portraits/men/77.jpg'],
            ['Umberto Eco', 'https://randomuser.me/api/portraits/men/78.jpg'],
            ['Matt Haig', 'https://randomuser.me/api/portraits/men/80.jpg'],
            ['Alex Michaelides', 'https://randomuser.me/api/portraits/men/81.jpg'],
            ['Neil Gaiman', 'https://randomuser.me/api/portraits/men/82.jpg'],
            ['Patrick Rothfuss', 'https://randomuser.me/api/portraits/men/83.jpg'],
            ['Taylor Jenkins Reid', 'https://randomuser.me/api/portraits/women/83.jpg'],
            ['Dan Brown', 'https://randomuser.me/api/portraits/men/84.jpg'],
            ['Tara Westover', 'https://randomuser.me/api/portraits/women/84.jpg'],
            ['Andrew Hunt', 'https://randomuser.me/api/portraits/men/85.jpg'],
            ['Bessel van der Kolk', 'https://randomuser.me/api/portraits/men/86.jpg'],
            ['Chimamanda Ngozi Adichie', 'https://randomuser.me/api/portraits/women/85.jpg'],
            ['Michelle Obama', 'https://randomuser.me/api/portraits/women/86.jpg'],
            ['Bill Bryson', 'https://randomuser.me/api/portraits/men/87.jpg'],
            ['Mary Roach', 'https://randomuser.me/api/portraits/women/87.jpg'],
        ];

        foreach ($authors as [$name, $photo]) {
            Author::firstOrCreate(
                ['name' => $name],
                [
                    'bio' => $name . ' is an author featured in the SecondBook marketplace.',
                    'photo' => $photo,
                    'status' => true,
                ]
            );
        }

        $this->command->info(count($authors) . ' authors seeded successfully.');
    }
}