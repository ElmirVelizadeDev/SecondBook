<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookRequestControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::create([
            'name' => 'super-admin',
            'display_name' => 'Super Admin',
            'is_system' => true,
        ]);
        $user = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);
        $user->roles()->attach($role);

        $this->actingAs($user);
    }

    public function test_store_creates_book_request(): void
    {
        $response = $this->withSession(['admin_book_requests' => []])
            ->post(route('admin.book.requests.store'), [
                'title' => 'Atomic Habits',
                'requester' => 'Aylin',
                'category' => 'self_development',
                'budget' => '18.50',
                'note' => 'Need this book urgently',
                'status' => 'pending',
            ]);

        $response->assertRedirect(route('admin.book.requests.index'));
        $response->assertSessionHas(
            'error',
            'Book requests are created by sellers from the Sell a Book page.'
        );
    }

    public function test_update_changes_existing_request(): void
    {
        $book = Book::create([
            'title' => 'Pending seller book',
            'status' => 'pending',
        ]);

        $response = $this->put(
            route('admin.book.requests.update', ['book' => $book->id]),
            [
                'status' => 'approved',
                'message' => 'The book has been approved.',
            ]
        );

        $response->assertRedirect(route('admin.book.requests.index'));
        $response->assertSessionHas(
            'success',
            'Book request approved successfully.'
        );
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'status' => 'approved',
        ]);
    }
}
