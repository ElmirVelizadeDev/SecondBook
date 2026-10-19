<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookConditionControllerTest extends TestCase
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

    public function test_index_displays_conditions_with_action_controls(): void
    {
        $response = $this->get(route('admin.book.conditions.index'));

        $response->assertOk();
        $response->assertViewHas('conditions');
        $response->assertSee('data-vue-page="book-condition-admin"', false);
        $response->assertSee('New');
        $response->assertSee('editPattern', false);
        $response->assertSee('deletePattern', false);
    }

    public function test_index_uses_real_book_counts_for_each_condition(): void
    {
        Book::create([
            'title' => 'Sample Book',
            'condition' => 'good',
            'status' => 'approved',
        ]);

        $response = $this->get(route('admin.book.conditions.index'));

        $response->assertOk();
        $response->assertViewHas('conditions', function ($conditions) {
            return collect($conditions)->contains(function ($condition) {
                return $condition['id'] === 'good' && (int) $condition['books_count'] >= 1;
            });
        });
    }

    public function test_status_toggle_updates_a_condition(): void
    {
        $response = $this->withSession([
            'admin_book_conditions' => [[
                'id' => 'new',
                'name' => 'New',
                'description' => 'Unused copy',
                'status' => 1,
                'books_count' => 0,
                'created_at' => '2026-07-28',
            ]],
        ])->patch(route('admin.book.conditions.status', ['condition' => 'new']));

        $response->assertRedirect(route('admin.book.conditions.index'));
        $response->assertSessionHas('success', 'Condition status updated successfully.');
    }

    public function test_store_creates_a_new_condition(): void
    {
        $response = $this->withSession([
            'admin_book_conditions' => [[
                'id' => 'new',
                'name' => 'New',
                'description' => 'Unused copy',
                'status' => 1,
                'books_count' => 0,
                'created_at' => '2026-07-28',
            ]],
        ])->post(route('admin.book.conditions.store'), [
            'name' => 'Mint',
            'description' => 'Pristine condition',
            'status' => 1,
        ]);

        $response->assertRedirect(route('admin.book.conditions.index'));
        $response->assertSessionHas('success', 'Condition added successfully.');
    }

    public function test_update_changes_existing_condition(): void
    {
        $response = $this->withSession([
            'admin_book_conditions' => [[
                'id' => 'new',
                'name' => 'New',
                'description' => 'Unused copy',
                'status' => 1,
                'books_count' => 0,
                'created_at' => '2026-07-28',
            ]],
        ])->put(route('admin.book.conditions.update', ['condition' => 'new']), [
            'name' => 'Updated Condition',
            'description' => 'Updated description',
            'status' => 0,
        ]);

        $response->assertRedirect(route('admin.book.conditions.index'));
        $response->assertSessionHas('success', 'Condition updated successfully.');
    }
}
