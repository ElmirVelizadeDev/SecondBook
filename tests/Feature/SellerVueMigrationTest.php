<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SellerVueMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_seller_dashboard_renders_authenticated_seller_page_data(): void
    {
        $seller = $this->createUser('seller');

        $response = $this->withoutVite()
            ->actingAs($seller)
            ->get(route('seller.dashboard'));

        $response->assertOk()
            ->assertSee('data-vue-page="seller-dashboard-page"', false);

        $pageData = $this->pageData($response->getContent());

        $this->assertSame($seller->full_name, $pageData['sellerName']);
        $this->assertNotEmpty($pageData['greeting']);
    }

    public function test_seller_order_details_renders_status_page_data(): void
    {
        $seller = $this->createUser('seller');
        $buyer = $this->createUser('user');
        $book = Book::create([
            'seller_id' => $seller->id,
            'title' => 'Seller-owned book',
            'status' => 'approved',
        ]);
        $order = $this->createOrder($buyer, $book);

        $response = $this->withoutVite()
            ->actingAs($seller)
            ->get(route('seller.orders.show', $order));

        $response->assertOk()
            ->assertSee('data-vue-page="seller-order-details-page"', false);

        $pageData = $this->pageData($response->getContent());

        $this->assertSame('pending', $pageData['order']['statusClass']);
        $this->assertSame('pending', $pageData['order']['paymentClass']);
    }

    public function test_seller_cannot_view_another_sellers_order(): void
    {
        $seller = $this->createUser('seller');
        $otherSeller = $this->createUser('seller');
        $buyer = $this->createUser('user');
        $book = Book::create([
            'seller_id' => $otherSeller->id,
            'title' => 'Another seller book',
            'status' => 'approved',
        ]);
        $order = $this->createOrder($buyer, $book);

        $this->withoutVite()
            ->actingAs($seller)
            ->get(route('seller.orders.show', $order))
            ->assertForbidden();
    }

    private function createUser(string $role): User
    {
        static $number = 0;
        $number++;

        return User::create([
            'name' => "Test User {$number}",
            'first_name' => 'Test',
            'last_name' => "User {$number}",
            'username' => "test-user-{$number}",
            'email' => "test-user-{$number}@example.com",
            'password' => bcrypt('password'),
            'role' => $role,
            'status' => 'active',
        ]);
    }

    private function createOrder(User $buyer, Book $book): Order
    {
        return Order::create([
            'order_number' => 'TEST-' . uniqid(),
            'user_id' => $buyer->id,
            'book_id' => $book->id,
            'book_price' => 10,
            'quantity' => 1,
            'total_price' => 10,
            'full_name' => $buyer->name,
            'phone' => '5550100',
            'country' => 'Test Country',
            'city' => 'Test City',
            'address' => '1 Test Street',
        ]);
    }

    private function pageData(string $html): array
    {
        $this->assertMatchesRegularExpression('/data-page-data="([^"]+)"/', $html);
        preg_match('/data-page-data="([^"]+)"/', $html, $matches);

        return json_decode(
            base64_decode($matches[1], true),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
    }
}
