<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = DB::table('users')
            ->where('email', 'admin@secondbook.com')
            ->value('id');

        $blogs = [

            [
                'title' => 'Why Reading Books Still Matters',
                'excerpt' => 'Discover how regular reading can become a valuable part of everyday life.',
                'content' => 'Reading remains one of the simplest ways to explore new ideas, discover different perspectives, and develop a lifelong learning habit. Whether you prefer fiction, biographies, history, or technology books, every book can offer something valuable.',
                'image' => 'https://images.unsplash.com/photo-1495446815901-a7297e633e8d?auto=format&fit=crop&w=1200&q=85',
            ],

            [
                'title' => 'How to Build a Personal Library',
                'excerpt' => 'Practical ideas for creating a meaningful and organized book collection.',
                'content' => 'Building a personal library does not require hundreds of books. Start with subjects and genres you genuinely enjoy. Organize your books in a way that makes them easy to find and regularly revisit the titles that matter most to you.',
                'image' => 'https://images.unsplash.com/photo-1521587760476-6c12a4b040da?auto=format&fit=crop&w=1200&q=85',
            ],

            [
                'title' => 'The Benefits of Buying Pre-Owned Books',
                'excerpt' => 'Learn why second-hand books can be a great choice for readers.',
                'content' => 'Pre-owned books can make reading more affordable while also giving existing books a second life. Buying used books can help readers discover titles they might otherwise skip and allows books to continue circulating between different readers.',
                'image' => 'https://images.unsplash.com/photo-1526243741027-444d633d7365?auto=format&fit=crop&w=1200&q=85',
            ],

            [
                'title' => 'Tips for New Book Sellers',
                'excerpt' => 'Simple steps to create better listings and attract more customers.',
                'content' => 'Successful book listings should contain clear titles, accurate descriptions, realistic condition information, and attractive photographs. Keeping stock information accurate and processing orders quickly can also create a better customer experience.',
                'image' => 'https://images.unsplash.com/photo-1524578271613-d550eacf6090?auto=format&fit=crop&w=1200&q=85',
            ],

            [
                'title' => 'Choosing Your Next Book',
                'excerpt' => 'A few simple ways to decide what to read next.',
                'content' => 'When you are unsure what to read next, consider your current interests, explore a new genre, revisit an author you enjoyed, or ask other readers for recommendations. Your next favorite book might be very different from your usual choices.',
                'image' => 'https://images.unsplash.com/photo-1519682337058-a94d519337bc?auto=format&fit=crop&w=1200&q=85',
            ],

            [
                'title' => 'How to Take Care of Your Books',
                'excerpt' => 'Simple habits that can help keep your books in good condition for years.',
                'content' => 'Store books away from direct sunlight and excessive moisture. Keep shelves clean and avoid placing heavy objects on top of books. Proper storage helps protect covers, pages, and bindings while keeping your collection looking great.',
                'image' => 'https://images.unsplash.com/photo-1516979187457-637abb4f9353?auto=format&fit=crop&w=1200&q=85',
            ],

            [
                'title' => 'The Joy of Discovering Used Books',
                'excerpt' => 'Explore why finding an unexpected second-hand book can be a special experience.',
                'content' => 'Used bookstores and marketplaces often contain books that are difficult to find elsewhere. Discovering an unexpected title, an old edition, or a book recommended by another reader can make the reading experience even more memorable.',
                'image' => 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=1200&q=85',
            ],

            [
                'title' => 'Reading Habits That Last',
                'excerpt' => 'Small changes that can help you develop a consistent reading routine.',
                'content' => 'A sustainable reading habit starts with realistic goals. Choose a comfortable reading time, keep your current book nearby, and focus on consistency rather than the number of pages you finish. Even a few pages every day can become a meaningful routine.',
                'image' => 'https://images.unsplash.com/photo-1511108690759-009324a90311?auto=format&fit=crop&w=1200&q=85',
            ],

            [
                'title' => 'Books for Learning New Skills',
                'excerpt' => 'Use books as a practical resource for developing knowledge and new abilities.',
                'content' => 'Books can be valuable tools for learning programming, business, languages, history, design, and many other subjects. Combining reading with practice can help turn information into useful skills.',
                'image' => 'https://images.unsplash.com/photo-1515879218367-8466d910aaa4?auto=format&fit=crop&w=1200&q=85',
            ],

            [
                'title' => 'Give Your Books a Second Life',
                'excerpt' => 'Learn how selling or sharing your old books can benefit other readers.',
                'content' => 'Books that are no longer being used can become valuable to someone else. Selling or sharing them keeps books circulating, creates space for new titles, and helps build a community where readers can exchange knowledge and stories.',
                'image' => 'https://images.unsplash.com/photo-1550399105-c4db5fb85c18?auto=format&fit=crop&w=1200&q=85',
            ],

            [
                'title' => 'The Art of Reading Slowly',
                'excerpt' => 'Why taking your time with a book can make reading more meaningful.',
                'content' => 'Reading does not always need to be about finishing quickly. Slowing down can help readers notice details, understand ideas more deeply, and enjoy the writing itself. A thoughtful reading pace can turn a simple book into a richer experience.',
                'image' => 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?auto=format&fit=crop&w=1200&q=85',
            ],

            [
                'title' => 'How Books Expand Your Perspective',
                'excerpt' => 'Discover how different stories and ideas can help you see the world differently.',
                'content' => 'Books introduce readers to people, cultures, experiences, and ideas outside their everyday lives. Exploring different perspectives can encourage curiosity and create a deeper understanding of the world around us.',
                'image' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=1200&q=85',
            ],

            [
                'title' => 'Creating a Cozy Reading Corner',
                'excerpt' => 'Simple ideas for creating a comfortable space dedicated to reading.',
                'content' => 'A comfortable chair, good lighting, a small table, and a few favorite books can transform an ordinary corner into a relaxing reading space. The goal is to create an environment where you naturally want to spend time with a book.',
                'image' => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=1200&q=85',
            ],

            [
                'title' => 'Why Book Communities Matter',
                'excerpt' => 'Explore how readers can connect through shared interests and recommendations.',
                'content' => 'Reading can be a personal activity, but sharing books creates opportunities for meaningful connections. Discussions, recommendations, reviews, and book exchanges help readers discover new titles and build communities around shared interests.',
                'image' => 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=1200&q=85',
            ],

            [
                'title' => 'From One Reader to Another',
                'excerpt' => 'Every book can continue its journey when readers share and exchange their collections.',
                'content' => 'A book does not have to stop being useful when its first reader finishes it. Passing books from one reader to another keeps stories moving and gives more people the opportunity to discover valuable ideas and memorable characters.',
                'image' => 'https://images.unsplash.com/photo-1529590003495-7c7f9fc6f4f8?auto=format&fit=crop&w=1200&q=85',
            ],

        ];

        foreach ($blogs as $blog) {

            DB::table('blogs')->updateOrInsert(
                ['slug' => Str::slug($blog['title'])],
                [
                    'title' => $blog['title'],
                    'slug' => Str::slug($blog['title']),
                    'excerpt' => $blog['excerpt'],
                    'content' => $blog['content'],
                    'image' => $blog['image'],
                    'author_id' => $adminId,
                    'status' => 'published',
                    'published_at' => now()->subDays(rand(1, 30)),
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}