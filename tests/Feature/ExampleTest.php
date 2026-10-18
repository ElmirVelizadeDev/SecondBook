<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/frontend');
    }

    public function test_frontend_home_route_is_available(): void
    {
        $this->get('/frontend')->assertOk();
    }

    public function test_featured_books_route_redirects_to_the_home_section(): void
    {
        $this->get('/frontend/featured-books')
            ->assertRedirect(route('frontend.home') . '#featured-books');
    }

    public function test_real_vue_pages_mount_on_their_designated_routes(): void
    {
        $this->withoutVite()
            ->get('/frontend')
            ->assertOk()
            ->assertSee('data-vue-page="home"', false);

        $this->get('/frontend/books')
            ->assertOk()
            ->assertSee('data-vue-page="books-page"', false);

        $this->actingAs(User::factory()->create())
            ->get('/frontend/cart')
            ->assertOk()
            ->assertSee('data-vue-page="cart-page"', false);
    }
}
