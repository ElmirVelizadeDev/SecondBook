<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_user_can_access_dashboard(): void
    {
        $user = User::create([
            'name' => 'Alice Johnson',
            'first_name' => 'Alice',
            'last_name' => 'Johnson',
            'username' => 'alice-admin',
            'email' => 'alice@example.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->actingAs($user);

        $response = $this->get('/admin/dashboard');

        $response->assertStatus(200);
    }

    public function test_dashboard_recent_activity_shows_latest_records(): void
    {
        $user = User::create([
            'name' => 'Alice Johnson',
            'first_name' => 'Alice',
            'last_name' => 'Johnson',
            'username' => 'alice-activity',
            'email' => 'alice@example.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'status' => 'active',
        ]);
        $this->actingAs($user);

        $category = Category::create([
            'name' => 'Fantasy',
            'slug' => 'fantasy',
            'status' => 1,
        ]);

        $book = Book::create([
            'title' => 'Harry Potter',
            'category_id' => $category->id,
            'seller_id' => $user->id,
            'status' => 'approved',
        ]);

        $response = $this->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Recent Activity');
        $response->assertSee('New Book Added');
        $response->assertSee($book->title);
        $response->assertSee('New User');
        $response->assertSee($user->name);
        $response->assertSee('Category Created');
        $response->assertSee($category->name);
    }
}
