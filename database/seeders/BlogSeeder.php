<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = DB::table('users')
            ->where('email', 'admin@gmail.com')
            ->value('id');

        if (!$adminId) {
            throw new RuntimeException('The seeded admin account is required before blog records can be created.');
        }

        $blogs = [

            [
                'title' => 'Why Reading Books Still Matters',
                'excerpt' => 'Discover how regular reading can become a valuable part of everyday life.',
                'content' => 'Reading remains one of the simplest ways to explore new ideas, discover different perspectives, and develop a lifelong learning habit. Whether you prefer fiction, biographies, history, or technology books, every book can offer something valuable.',
                'image' => null,
            ],

            [
                'title' => 'How to Build a Personal Library',
                'excerpt' => 'Practical ideas for creating a meaningful and organized book collection.',
                'content' => 'Building a personal library does not require hundreds of books. Start with subjects and genres you genuinely enjoy. Organize your books in a way that makes them easy to find and regularly revisit the titles that matter most to you.',
                'image' => null,
            ],

            [
                'title' => 'The Benefits of Buying Pre-Owned Books',
                'excerpt' => 'Learn why second-hand books can be a great choice for readers.',
                'content' => 'Pre-owned books can make reading more affordable while also giving existing books a second life. Buying used books can help readers discover titles they might otherwise skip and allows books to continue circulating between different readers.',
                'image' => null,
            ],

            [
                'title' => 'Tips for New Book Sellers',
                'excerpt' => 'Simple steps to create better listings and attract more customers.',
                'content' => 'Successful book listings should contain clear titles, accurate descriptions, realistic condition information, and attractive photographs. Keeping stock information accurate and processing orders quickly can also create a better customer experience.',
                'image' => null,
            ],

            [
                'title' => 'Choosing Your Next Book',
                'excerpt' => 'A few simple ways to decide what to read next.',
                'content' => 'When you are unsure what to read next, consider your current interests, explore a new genre, revisit an author you enjoyed, or ask other readers for recommendations. Your next favorite book might be very different from your usual choices.',
                'image' => null,
            ],

            [
                'title' => 'How to Take Care of Your Books',
                'excerpt' => 'Simple habits that can help keep your books in good condition for years.',
                'content' => 'Store books away from direct sunlight and excessive moisture. Keep shelves clean and avoid placing heavy objects on top of books. Proper storage helps protect covers, pages, and bindings while keeping your collection looking great.',
                'image' => null,
            ],

            [
                'title' => 'The Joy of Discovering Used Books',
                'excerpt' => 'Explore why finding an unexpected second-hand book can be a special experience.',
                'content' => 'Used bookstores and marketplaces often contain books that are difficult to find elsewhere. Discovering an unexpected title, an old edition, or a book recommended by another reader can make the reading experience even more memorable.',
                'image' => null,
            ],

            [
                'title' => 'Reading Habits That Last',
                'excerpt' => 'Small changes that can help you develop a consistent reading routine.',
                'content' => 'A sustainable reading habit starts with realistic goals. Choose a comfortable reading time, keep your current book nearby, and focus on consistency rather than the number of pages you finish. Even a few pages every day can become a meaningful routine.',
                'image' => null,
            ],

            [
                'title' => 'Books for Learning New Skills',
                'excerpt' => 'Use books as a practical resource for developing knowledge and new abilities.',
                'content' => 'Books can be valuable tools for learning programming, business, languages, history, design, and many other subjects. Combining reading with practice can help turn information into useful skills.',
                'image' => null,
            ],

            [
                'title' => 'Give Your Books a Second Life',
                'excerpt' => 'Learn how selling or sharing your old books can benefit other readers.',
                'content' => 'Books that are no longer being used can become valuable to someone else. Selling or sharing them keeps books circulating, creates space for new titles, and helps build a community where readers can exchange knowledge and stories.',
                'image' => null,
            ],

            [
                'title' => 'The Art of Reading Slowly',
                'excerpt' => 'Why taking your time with a book can make reading more meaningful.',
                'content' => 'Reading does not always need to be about finishing quickly. Slowing down can help readers notice details, understand ideas more deeply, and enjoy the writing itself. A thoughtful reading pace can turn a simple book into a richer experience.',
                'image' => null,
            ],

            [
                'title' => 'How Books Expand Your Perspective',
                'excerpt' => 'Discover how different stories and ideas can help you see the world differently.',
                'content' => 'Books introduce readers to people, cultures, experiences, and ideas outside their everyday lives. Exploring different perspectives can encourage curiosity and create a deeper understanding of the world around us.',
                'image' => null,
            ],

            [
                'title' => 'Creating a Cozy Reading Corner',
                'excerpt' => 'Simple ideas for creating a comfortable space dedicated to reading.',
                'content' => 'A comfortable chair, good lighting, a small table, and a few favorite books can transform an ordinary corner into a relaxing reading space. The goal is to create an environment where you naturally want to spend time with a book.',
                'image' => null,
            ],

            [
                'title' => 'Why Book Communities Matter',
                'excerpt' => 'Explore how readers can connect through shared interests and recommendations.',
                'content' => 'Reading can be a personal activity, but sharing books creates opportunities for meaningful connections. Discussions, recommendations, reviews, and book exchanges help readers discover new titles and build communities around shared interests.',
                'image' => null,
            ],

            [
                'title' => 'From One Reader to Another',
                'excerpt' => 'Every book can continue its journey when readers share and exchange their collections.',
                'content' => 'A book does not have to stop being useful when its first reader finishes it. Passing books from one reader to another keeps stories moving and gives more people the opportunity to discover valuable ideas and memorable characters.',
                'image' => null,
            ],
            ['title' => 'How to Choose a First Edition', 'excerpt' => 'A practical guide to finding an edition that suits your reading plans.', 'content' => 'Compare publication details, format, condition, and price before choosing an edition. For a second-hand title, read the seller description carefully and ask questions when important details are not clear.', 'image' => null],
            ['title' => 'Organizing a Family Reading Shelf', 'excerpt' => 'Simple ways to keep a shared collection easy to browse.', 'content' => 'Group books by age, topic, or reading interest and keep frequently used titles within easy reach. A simple system helps every family member find a book and return it to the right place.', 'image' => null],
            ['title' => 'Choosing Books for Study', 'excerpt' => 'Use reliable book details to find useful study materials.', 'content' => 'Check the edition, publication year, author, and subject before selecting a study book. Matching the title to the course or learning goal can make reading time more effective.', 'image' => null],
            ['title' => 'Why Book Reviews Help Readers', 'excerpt' => 'Thoughtful reviews make it easier to discover the right next read.', 'content' => 'A useful review explains what stood out, who may enjoy the book, and whether the listing matched expectations. Specific, respectful feedback helps other readers make informed choices.', 'image' => null],
            ['title' => 'Sustainable Habits for Booksellers', 'excerpt' => 'Accurate listings and careful packing support a better marketplace.', 'content' => 'Keep stock information current, describe book condition clearly, and protect books during shipping. Consistent seller practices help readers shop with confidence and keep books circulating.', 'image' => null],
            ['title' => 'The Value of Reading Across Genres', 'excerpt' => 'Exploring different genres can broaden your reading experience.', 'content' => 'Trying a new genre can introduce different writing styles, subjects, and viewpoints. Use recommendations and category browsing to explore a topic beyond your usual choices.', 'image' => null],
            ['title' => 'Keeping a Reading Journal', 'excerpt' => 'A few notes can help you remember the books and ideas that matter.', 'content' => 'Record a title, a memorable passage, or a short reflection after reading. Over time, a journal becomes a personal record of interests, discoveries, and recommendations.', 'image' => null],
            ['title' => 'Sharing Books with Young Readers', 'excerpt' => 'Make reading welcoming by following a young reader’s interests.', 'content' => 'Offer age-appropriate choices, read together when helpful, and let young readers explore topics they enjoy. A relaxed approach can help build curiosity and confidence.', 'image' => null],
            ['title' => 'Finding Reliable Book Descriptions', 'excerpt' => 'Learn which listing details help you buy with confidence.', 'content' => 'Look for clear information about the edition, condition, publication details, and any visible wear. A complete description helps set expectations before an order is placed.', 'image' => null],
            ['title' => 'Building a Local Reading Community', 'excerpt' => 'Readers can connect through recommendations and shared books.', 'content' => 'Share thoughtful recommendations, discuss favorite titles, and pass along books that deserve another reader. Small exchanges can help build a supportive community around reading.', 'image' => null],

        ];

        foreach ($blogs as $index => $blog) {
            $slug = Str::slug($blog['title']);
            $existing = DB::table('blogs')->where('slug', $slug)->first();

            if ($existing) {
                DB::table('blogs')
                    ->where('id', $existing->id)
                    ->update([
                        'image' => null,
                        'author_id' => $adminId,
                        'updated_at' => now(),
                    ]);
                continue;
            }

            DB::table('blogs')->insert([
                    'title' => $blog['title'],
                    'slug' => $slug,
                    'excerpt' => $blog['excerpt'],
                    'content' => $blog['content'],
                    'image' => $blog['image'],
                    'author_id' => $adminId,
                    'status' => 'published',
                    'published_at' => now()->subDays(($index % 30) + 1),
                    'updated_at' => now(),
                    'created_at' => now(),
                ]);
        }
    }
}