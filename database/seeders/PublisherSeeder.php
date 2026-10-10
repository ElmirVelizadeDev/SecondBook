<?php

namespace Database\Seeders;

use App\Models\Publisher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PublisherSeeder extends Seeder
{
    public function run(): void
    {
        $publishers = [
            ['Penguin Random House', 'UK'],
            ['HarperCollins', 'USA'],
            ['Simon & Schuster', 'USA'],
            ['Macmillan Publishers', 'UK'],
            ['Hachette Book Group', 'USA'],
            ['Oxford University Press', 'UK'],
            ['Cambridge University Press', 'UK'],
            ['Scholastic', 'USA'],
            ['Bloomsbury Publishing', 'UK'],
            ["O'Reilly Media", 'USA'],
            ['Wiley', 'USA'],
            ['Pearson', 'UK'],
            ['Springer Nature', 'Germany'],
            ['Elsevier', 'Netherlands'],
            ['Routledge', 'UK'],
            ['W. W. Norton & Company', 'USA'],
            ['Faber & Faber', 'UK'],
            ['Random House', 'USA'],
            ['Vintage Books', 'USA'],
            ['Bantam Books', 'USA'],
            ['Doubleday', 'USA'],
            ['Kensington Publishing', 'USA'],
            ['Grand Central Publishing', 'USA'],
            ['Little, Brown and Company', 'USA'],
            ['Pan Macmillan', 'UK'],
            ['Canongate Books', 'UK'],
            ['Schirmer/Mosel', 'Germany'],
            ['Gallimard', 'France'],
            ['Editorial Planeta', 'Spain'],
            ['Kodansha', 'Japan'],
            ['Crown Publishing Group', 'USA'],
            ['Atria Books', 'USA'],
            ['Tor Books', 'USA'],
            ['Orbit Books', 'UK'],
            ['National Geographic Books', 'USA'],
            ['Workman Publishing', 'USA'],
            ['Chronicle Books', 'USA'],
            ['Penguin Classics', 'UK'],
            ['Bonnier Books', 'UK'],
            ['Houghton Mifflin Harcourt', 'USA'],
        ];

        foreach ($publishers as [$name, $country]) {
            Publisher::firstOrCreate(
                ['name' => $name],
                [
                    'country' => $country,
                    'website' => null,
                    'description' => $name . ' publishing house.',
                    'status' => true,
                ]
            );
        }

        $this->command->info(count($publishers) . ' publishers seeded successfully.');
    }
}