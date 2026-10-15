<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\Order;
use App\Models\Notification;
use App\Models\Otp;
use App\Models\Shipping;
use App\Models\Setting;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ApiWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_verification_login_session_and_logout_work_over_api(): void
    {
        Mail::fake();

        $registration = [
            'first_name' => 'Casey',
            'last_name' => 'Reader',
            'username' => 'casey-reader',
            'email' => 'casey.reader@gmail.com',
            'password' => 'a-secure-password',
            'password_confirmation' => 'a-secure-password',
            'terms' => '1',
        ];

        $this->postJson('/api/auth/register', $registration)
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('users', [
            'email' => $registration['email'],
        ]);

        $otp = Otp::where('email', $registration['email'])
            ->where('purpose', 'email_verification')
            ->firstOrFail();

        $this->postJson('/api/auth/email/verify', [
            'otp_code' => $otp->otp_code,
        ])->assertOk()->assertJsonPath('success', true);

        $user = User::where('email', $registration['email'])->firstOrFail();
        $this->assertAuthenticatedAs($user);

        $this->getJson('/api/me')
            ->assertOk()
            ->assertJsonPath('data.email', $registration['email']);

        auth()->logout();

        $login = $this->postJson('/api/auth/login', [
            'email' => $registration['email'],
            'password' => $registration['password'],
        ]);
        $login->assertOk()->assertJsonPath('success', true);
        $this->assertAuthenticatedAs($user);

        $this->postJson('/api/auth/logout')
            ->assertOk()
            ->assertJsonPath('success', true);
        $this->assertGuest();
        $this->getJson('/api/me')->assertUnauthorized();

        $this->postJson('/api/auth/password/send-otp', [
            'email' => $registration['email'],
        ])->assertOk()->assertJsonPath('success', true);

        $resetOtp = Otp::where('email', $registration['email'])
            ->where('purpose', 'password_reset')
            ->firstOrFail();

        $this->postJson('/api/auth/password/verify', [
            'otp_code' => $resetOtp->otp_code,
            'password' => 'reset-password-123',
            'password_confirmation' => 'reset-password-123',
        ])->assertOk()->assertJsonPath('success', true);

        $this->postJson('/api/auth/login', [
            'email' => $registration['email'],
            'password' => 'reset-password-123',
        ])->assertOk();
        $this->assertAuthenticatedAs($user);
    }

    public function test_disabled_registration_returns_json_for_api_requests(): void
    {
        Setting::put('user_registration_enabled', false, 'auth', 'boolean');

        $this->postJson('/api/auth/register', [])
            ->assertForbidden()
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'User registration is currently disabled.');
    }

    public function test_cart_api_enforces_auth_stock_and_supports_crud(): void
    {
        $user = User::factory()->create();
        $book = $this->makeBook(stock: 3);

        $this->getJson('/api/cart')->assertUnauthorized();

        $this->actingAs($user);
        $this->postJson("/api/cart/items/{$book->id}", ['quantity' => 2])
            ->assertOk()
            ->assertJsonPath('cart_count', 2);

        $this->postJson("/api/cart/items/{$book->id}", ['quantity' => 2])
            ->assertUnprocessable();

        $this->getJson('/api/cart')
            ->assertOk()
            ->assertJsonPath('total_items', 2)
            ->assertJsonPath('data.0.price', 12.5);

        $this->patchJson("/api/cart/items/{$book->id}", ['quantity' => 3])
            ->assertOk();
        $this->patchJson("/api/cart/items/{$book->id}", ['quantity' => 4])
            ->assertUnprocessable();

        $this->deleteJson("/api/cart/items/{$book->id}")
            ->assertOk();
        $this->postJson("/api/cart/items/{$book->id}", ['quantity' => 1])
            ->assertOk();
        $this->deleteJson('/api/cart')->assertOk();
        $this->getJson('/api/cart')->assertJsonPath('total_items', 0);
    }

    public function test_catalog_search_and_pagination_return_only_approved_books(): void
    {
        for ($index = 1; $index <= 13; $index++) {
            Book::create([
                'title' => sprintf('Catalog title %02d', $index),
                'price' => 12.50,
                'stock' => 1,
                'condition' => 'good',
                'status' => 'approved',
            ]);
        }

        Book::create([
            'title' => 'Catalog unpublished title',
            'status' => 'pending',
        ]);

        $this->getJson('/api/books?search=Catalog')
            ->assertOk()
            ->assertJsonPath('meta.total', 13)
            ->assertJsonPath('meta.current_page', 1)
            ->assertJsonCount(12, 'data');

        $this->getJson('/api/books?search=Catalog&page=2')
            ->assertOk()
            ->assertJsonPath('meta.current_page', 2)
            ->assertJsonCount(1, 'data');
    }

    public function test_home_api_returns_all_homepage_sections_and_book_contracts(): void
    {
        $book = $this->makeBook(title: 'Home API title');

        $this->getJson('/api/home')
            ->assertOk()
            ->assertJsonStructure([
                'categories',
                'featured_books' => [
                    ['id', 'title', 'cover_url', 'price', 'discounted_price', 'discount_label', 'author', 'category'],
                ],
                'best_sellers',
                'trending_books',
                'new_arrivals',
                'editor_picks',
                'most_loved_books',
                'budget_deals',
                'special_offers',
            ])
            ->assertJsonPath('featured_books.0.id', $book->id)
            ->assertJsonPath('best_sellers.0.title', 'Home API title');

        $this->getJson('/api/books?search=unpublished')
            ->assertOk()
            ->assertJsonPath('meta.total', 0);

        $category = Category::create([
            'name' => 'Literature',
            'slug' => 'literature',
            'status' => 1,
        ]);
        $this->getJson("/api/categories/{$category->id}")
            ->assertOk()
            ->assertJsonPath('data.category.slug', 'literature');
    }

    public function test_checkout_uses_database_price_creates_pending_payment_and_decrements_stock(): void
    {
        $user = User::factory()->create();
        $book = $this->makeBook(stock: 4);
        $this->configureCheckout();
        $this->actingAs($user);

        $this->postJson("/api/cart/items/{$book->id}", ['quantity' => 2])
            ->assertOk();

        $this->postJson('/api/checkout', [])
            ->assertUnprocessable();

        $cart = session('cart');
        $cart[$book->id]['price'] = 0.01;

        $response = $this->withSession(['cart' => $cart])
            ->postJson('/api/checkout', $this->checkoutPayload());

        $response->assertCreated()
            ->assertJsonPath('orders.0.total_price', '25.00');

        $order = Order::with('payment')->firstOrFail();
        $this->assertSame('25.00', $order->total_price);
        $this->assertSame('12.50', $order->book_price);
        $this->assertSame('pending', $order->payment_status);
        $this->assertSame('pending', $order->payment->payment_status);
        $this->assertSame(2, $book->fresh()->stock);
        $this->assertNull(session('cart'));

        $this->getJson("/api/payments/{$order->id}")
            ->assertOk()
            ->assertJsonPath('data.payment_status', 'pending');

        $this->postJson("/frontend/payment/{$order->id}")
            ->assertStatus(503)
            ->assertJsonPath('payment_status', 'pending');
        $this->assertSame('pending', $order->fresh()->payment_status);
    }

    public function test_checkout_rolls_back_orders_and_stock_when_any_cart_item_is_unavailable(): void
    {
        $user = User::factory()->create();
        $firstBook = $this->makeBook(stock: 4, title: 'First book');
        $secondBook = $this->makeBook(stock: 1, title: 'Second book');
        $this->configureCheckout();
        $this->actingAs($user);

        $this->postJson("/api/cart/items/{$firstBook->id}", ['quantity' => 1])
            ->assertOk();
        $this->postJson("/api/cart/items/{$secondBook->id}", ['quantity' => 1])
            ->assertOk();
        $secondBook->update(['stock' => 0]);

        $this->postJson('/api/checkout', $this->checkoutPayload())
            ->assertUnprocessable();

        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('payments', 0);
        $this->assertSame(4, $firstBook->fresh()->stock);
        $this->assertSame(0, $secondBook->fresh()->stock);
    }

    public function test_checkout_uses_selected_shipping_price_and_ignores_forged_totals(): void
    {
        $user = User::factory()->create();
        $book = $this->makeBook(stock: 4);
        $book->update([
            'discount_type' => 'fixed',
            'discount_value' => 2.50,
        ]);
        $this->configureCheckout();
        $this->configureShipping();
        $this->actingAs($user);

        $economy = Shipping::create([
            'name' => 'Economy',
            'price' => 4.50,
            'status' => true,
        ]);
        $express = Shipping::create([
            'name' => 'Express',
            'price' => 9.75,
            'status' => true,
        ]);

        $this->postJson("/api/cart/items/{$book->id}", ['quantity' => 2])
            ->assertOk();

        $cart = session('cart');
        $cart[$book->id]['price'] = 1000;
        $this->withSession(['cart' => $cart]);

        $this->getJson('/api/checkout')
            ->assertOk()
            ->assertJsonPath('subtotal', 20)
            ->assertJsonPath('shipping_fee', 4.5)
            ->assertJsonPath('grand_total', 24.5);
        $this->assertSame(10.0, (float) session("cart.{$book->id}.price"));
        $this->getJson("/api/checkout?shipping_id={$express->id}")
            ->assertOk()
            ->assertJsonPath('selected_shipping_id', $express->id)
            ->assertJsonPath('shipping_fee', 9.75)
            ->assertJsonPath('grand_total', 29.75);

        $cart = session('cart');
        $cart[$book->id]['price'] = 1000;
        $this->withSession(['cart' => $cart])
            ->postJson('/api/checkout', $this->checkoutPayload() + [
                'shipping_id' => $express->id,
                'shipping_fee' => 0,
                'total' => 0,
                'grand_total' => 0,
            ])->assertCreated()
            ->assertJsonPath('orders.0.total_price', '29.75');

        $order = Order::firstOrFail();
        $this->assertSame($express->id, $order->shipping_id);
        $this->assertSame('9.75', $order->shipping_fee);
        $this->assertSame('29.75', $order->total_price);

        $express->update(['price' => 99.00]);
        $this->assertSame('9.75', $order->fresh()->shipping_fee);
        $this->assertSame('29.75', $order->fresh()->total_price);

        Setting::put('free_shipping_threshold', 10, 'shipping', 'integer');
        $this->postJson("/api/cart/items/{$book->id}", ['quantity' => 1])
            ->assertOk();
        $this->getJson("/api/checkout?shipping_id={$express->id}")
            ->assertOk()
            ->assertJsonPath('shipping_fee', 0)
            ->assertJsonPath('grand_total', 10);
        $this->postJson('/api/checkout', $this->checkoutPayload() + [
            'shipping_id' => $express->id,
        ])->assertCreated()
            ->assertJsonPath('orders.0.total_price', '10.00');
        $this->assertSame('0.00', Order::latest('id')->firstOrFail()->shipping_fee);

        $this->postJson("/api/cart/items/{$book->id}", ['quantity' => 1])
            ->assertOk();
        $express->update(['status' => false]);
        $this->getJson("/api/checkout?shipping_id={$express->id}")
            ->assertUnprocessable();
        $this->postJson('/api/checkout', $this->checkoutPayload() + [
            'shipping_id' => $express->id,
        ])->assertUnprocessable();
    }

    public function test_blade_checkout_displays_and_charges_selected_shipping_price(): void
    {
        $user = User::factory()->create();
        $book = $this->makeBook(stock: 2);
        $this->configureCheckout();
        $this->configureShipping();
        $shipping = Shipping::create([
            'name' => 'Standard',
            'price' => 6.25,
            'status' => true,
        ]);
        $this->actingAs($user);
        $this->postJson("/api/cart/items/{$book->id}", ['quantity' => 1])
            ->assertOk();

        $this->get(route('frontend.checkout'))
            ->assertOk()
            ->assertViewHas('shippingFee', 6.25)
            ->assertViewHas('grandTotal', 18.75);

        $this->post(route('frontend.checkout.store'), $this->checkoutPayload() + [
            'shipping_id' => $shipping->id,
        ])->assertRedirect(route('frontend.orders'));

        $order = Order::firstOrFail();
        $this->assertSame('6.25', $order->shipping_fee);
        $this->assertSame('18.75', $order->total_price);
    }

    public function test_order_ownership_cancellation_and_review_eligibility_are_enforced(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $book = $this->makeBook(stock: 2);
        $this->configureCheckout();
        Setting::put('cancel_order_period', 2, 'orders', 'integer');
        $this->actingAs($owner);

        $this->postJson("/api/cart/items/{$book->id}", ['quantity' => 1])
            ->assertOk();
        $this->postJson('/api/checkout', $this->checkoutPayload())
            ->assertCreated();
        $order = Order::firstOrFail();

        $this->getJson('/api/orders')->assertOk();
        $this->getJson("/api/orders/{$order->id}")->assertOk();
        $this->getJson("/api/orders/{$order->id}/tracking")->assertOk();

        $this->actingAs($otherUser);
        $this->getJson("/api/orders/{$order->id}")->assertForbidden();
        $this->getJson("/api/orders/{$order->id}/tracking")->assertForbidden();
        $this->postJson("/api/orders/{$order->id}/cancel")->assertForbidden();

        $this->actingAs($owner);
        $this->postJson("/api/orders/{$order->id}/review", [
            'rating' => 5,
            'comment' => 'Excellent condition.',
        ])->assertForbidden();

        $order->update(['order_status' => 'delivered']);
        $this->getJson("/api/orders/{$order->id}/review")
            ->assertOk()
            ->assertJsonPath('data.order_id', $order->id);
        $this->postJson("/api/orders/{$order->id}/review", [
            'rating' => 5,
            'comment' => 'Excellent condition.',
        ])->assertCreated();
        $this->postJson("/api/orders/{$order->id}/review", [
            'rating' => 5,
        ])->assertStatus(409);

        $this->postJson("/api/orders/{$order->id}/cancel")
            ->assertUnprocessable();

        $order->update(['order_status' => 'pending']);
        $this->postJson("/api/orders/{$order->id}/cancel")->assertOk();
        $this->assertSame('cancelled', $order->fresh()->order_status);
        $this->assertSame(2, $book->fresh()->stock);
    }

    public function test_wishlist_notifications_and_seller_application_are_scoped_to_authenticated_users(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $book = $this->makeBook();
        $notification = Notification::create([
            'user_id' => $user->id,
            'type' => 'account',
            'title' => 'Test notification',
            'message' => 'A test message.',
        ]);
        $foreignNotification = Notification::create([
            'user_id' => $otherUser->id,
            'type' => 'account',
            'title' => 'Private notification',
            'message' => 'Another test message.',
        ]);

        $this->getJson('/api/wishlist')->assertUnauthorized();
        $this->actingAs($user);

        $this->postJson("/api/wishlist/{$book->id}")
            ->assertOk();
        $this->getJson("/api/wishlist/{$book->id}/state")
            ->assertOk()
            ->assertJsonPath('wishlisted', true);
        $this->deleteJson("/api/wishlist/{$book->id}")
            ->assertOk();

        $this->getJson('/api/notifications')
            ->assertOk()
            ->assertJsonPath('unread_count', 1);
        $this->postJson("/api/notifications/{$foreignNotification->id}/read")
            ->assertForbidden();
        $this->postJson("/api/notifications/{$notification->id}/read")
            ->assertOk();
        $this->postJson('/api/notifications/read-all')->assertOk();
        $this->deleteJson("/api/notifications/{$notification->id}")
            ->assertOk();

        $this->postJson('/api/seller-application', [
            'store_name' => 'Casey Books',
            'description' => 'A small independent bookstore.',
            'phone' => '+994501234567',
            'address' => 'Main Street',
        ])->assertCreated();
        $this->getJson('/api/seller-application')
            ->assertOk()
            ->assertJsonPath('data.status', 'pending');
    }

    public function test_account_deletion_requires_password_and_email_otp_confirmation(): void
    {
        Mail::fake();
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->postJson('/api/account/delete/request')
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->postJson('/api/account/delete/resend')
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->postJson('/api/account/delete/verify-password', [
            'current_password' => 'incorrect-password',
        ])->assertUnprocessable();
        $this->assertDatabaseHas('users', ['id' => $user->id]);

        $this->postJson('/api/account/delete/verify-password', [
            'current_password' => 'password',
        ])->assertOk()->assertJsonPath('step', 'otp');

        $this->postJson('/api/account/delete/verify-otp', [
            'otp_code' => '000000',
        ])->assertUnprocessable();
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    public function test_profile_photo_can_be_updated_through_multipart_api_request(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post('/api/profile', [
            '_method' => 'PUT',
            'first_name' => 'Casey',
            'last_name' => 'Reader',
            'username' => $user->username,
            'email' => $user->email,
            'phone_country_code' => '+994',
            'profile_photo' => UploadedFile::fake()->create('profile.jpg', 20, 'image/jpeg'),
        ], ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJsonPath('success', true);

        $photoPath = $user->fresh()->profile_photo;
        $this->assertNotEmpty($photoPath);
        Storage::disk('public')->assertExists($photoPath);
    }

    public function test_profile_preferences_and_password_change_otp_work_for_authenticated_user(): void
    {
        Mail::fake();
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->putJson('/api/profile', [
            'first_name' => 'Casey',
            'last_name' => 'Reader',
            'username' => $user->username,
            'email' => $user->email,
            'phone_country_code' => '+994',
            'phone' => '501234567',
        ])->assertOk()->assertJsonPath('success', true);

        $this->assertSame('+994501234567', $user->fresh()->phone);

        $this->putJson('/api/account/preferences', [
            'email_notifications' => false,
            'order_updates' => true,
            'promotional_emails' => false,
            'profile_visible' => false,
        ])->assertOk();

        $this->assertDatabaseHas('user_settings', [
            'user_id' => $user->id,
            'email_notifications' => 0,
            'profile_visible' => 0,
        ]);
        $this->getJson('/api/account/preferences')
            ->assertOk()
            ->assertJsonPath('data.email_notifications', false);

        $this->postJson('/api/account/password/change-request', [
            'current_password' => 'password',
        ])->assertOk()->assertJsonPath('success', true);

        $otp = Otp::where('email', $user->email)
            ->where('purpose', 'password_change')
            ->firstOrFail();

        $this->postJson('/api/auth/password/verify', [
            'otp_code' => $otp->otp_code,
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ])->assertOk()->assertJsonPath('success', true);

        $this->assertAuthenticatedAs($user);
        $this->assertTrue(password_verify('new-password-123', $user->fresh()->password));
    }

    public function test_authenticated_user_can_message_an_active_store(): void
    {
        $customer = User::factory()->create();
        $seller = User::factory()->create([
            'role' => 'seller',
            'status' => 'active',
        ]);
        $store = Store::create([
            'seller_id' => $seller->id,
            'name' => 'Seller Books',
            'slug' => 'seller-books',
            'status' => 'active',
            'accept_orders' => true,
        ]);

        $this->getJson("/api/stores/{$store->slug}")
            ->assertOk()
            ->assertJsonPath('store.slug', $store->slug);

        $this->actingAs($customer)
            ->postJson("/api/stores/{$store->slug}/messages", [
                'subject' => 'Question about a book',
                'message' => 'Is this title still available?',
            ])
            ->assertCreated()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('messages', [
            'user_id' => $customer->id,
            'seller_id' => $seller->id,
            'subject' => 'Question about a book',
        ]);
        $this->assertDatabaseHas('notifications', [
            'user_id' => $seller->id,
            'type' => 'new_message',
        ]);

        $this->postJson('/api/contact', [
            'name' => 'Casey Reader',
            'email' => 'casey@example.com',
            'subject' => 'General question',
            'message' => 'I need help finding a specific book.',
        ])->assertCreated()->assertJsonPath('success', true);

        $this->assertDatabaseHas('messages', [
            'subject' => 'General question',
            'email' => 'casey@example.com',
        ]);
    }

    private function makeBook(int $stock = 5, string $title = 'Test book'): Book
    {
        $seller = User::factory()->create([
            'role' => 'seller',
            'status' => 'active',
        ]);

        Store::create([
            'seller_id' => $seller->id,
            'name' => $seller->name . ' Books',
            'slug' => 'store-' . $seller->id,
            'status' => 'active',
            'accept_orders' => true,
            'auto_approve_orders' => false,
            'processing_time' => 2,
            'minimum_order_amount' => 0,
        ]);

        return Book::create([
            'seller_id' => $seller->id,
            'title' => $title,
            'price' => 12.50,
            'stock' => $stock,
            'status' => 'approved',
            'condition' => 'good',
        ]);
    }

    private function configureCheckout(): void
    {
        Setting::put('shipping_enabled', false, 'shipping', 'boolean');
        Setting::put('payments_enabled', true, 'payments', 'boolean');
        Setting::put('payment_methods', ['cash_on_delivery'], 'payments', 'json');
    }

    private function configureShipping(): void
    {
        Setting::put('shipping_enabled', true, 'shipping', 'boolean');
        Setting::put('default_shipping_fee', 99, 'shipping', 'integer');
        Setting::put('free_shipping_threshold', 30, 'shipping', 'integer');
    }

    private function checkoutPayload(): array
    {
        return [
            'full_name' => 'Casey Reader',
            'phone' => '+994501234567',
            'country' => 'Azerbaijan',
            'city' => 'Baku',
            'address' => '1 Main Street',
            'payment_method' => 'cash_on_delivery',
        ];
    }
}
