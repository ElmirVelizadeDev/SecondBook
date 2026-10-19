<?php

namespace Tests\Feature;

use App\Models\AccountSecurityEvent;
use App\Models\Notification;
use App\Models\Book;
use App\Models\Order;
use App\Models\Otp;
use App\Models\Refund;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AccountSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_account_settings_uses_the_authenticated_profile_and_only_returns_own_security_events(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $this->actingAs($user);

        AccountSecurityEvent::create(['user_id' => $user->id, 'event' => 'password_changed']);
        AccountSecurityEvent::create(['user_id' => $other->id, 'event' => 'email_updated']);

        $this->get(route('frontend.account.settings'))
            ->assertOk()
            ->assertSee('account-settings-page', false)
            ->assertSee('data-profile-completion=', false);

        $this->getJson('/api/account/security-events')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.event', 'password_changed');
    }

    public function test_email_change_is_not_applied_until_new_address_otp_is_verified(): void
    {
        Mail::fake();
        $user = User::factory()->create(['email' => 'reader@example.test']);
        $this->actingAs($user);

        $profileUpdate = [
            'first_name' => 'Casey',
            'last_name' => 'Reader',
            'username' => $user->username,
            'email' => 'new-reader@example.test',
            'phone_country_code' => '+994',
            'phone' => '',
        ];

        $this->putJson('/api/profile', $profileUpdate)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['current_password']);
        $this->assertSame('reader@example.test', $user->fresh()->email);

        $this->putJson('/api/profile', [
            ...$profileUpdate,
            'current_password' => 'password',
        ])->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('redirect', route('frontend.auth.email.verify'));

        $this->assertSame('reader@example.test', $user->fresh()->email);
        $otp = Otp::query()
            ->where('email', 'new-reader@example.test')
            ->where('purpose', 'email_change')
            ->firstOrFail();

        $this->postJson('/api/auth/email/verify', ['otp_code' => $otp->otp_code])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('redirect', route('frontend.account.settings'));

        $this->assertSame('new-reader@example.test', $user->fresh()->email);
        $this->assertNotNull($user->fresh()->email_verified_at);
        $this->assertDatabaseHas('account_security_events', [
            'user_id' => $user->id,
            'event' => 'email_updated',
        ]);
        $this->assertDatabaseMissing('otps', [
            'email' => 'new-reader@example.test',
            'purpose' => 'email_change',
        ]);
    }

    public function test_preferences_reject_invalid_values_and_session_api_does_not_fake_file_sessions(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->putJson('/api/account/preferences', [
            'email_notifications' => 'not-a-boolean',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['email_notifications']);

        $this->getJson('/api/account/sessions')
            ->assertOk()
            ->assertJsonPath('supported', false)
            ->assertJsonCount(0, 'data');
    }

    public function test_notification_preferences_filter_only_the_matching_in_app_categories(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        UserSetting::create([
            'user_id' => $user->id,
            'email_notifications' => true,
            'order_updates' => false,
            'message_updates' => false,
            'promotional_emails' => false,
            'profile_visible' => true,
        ]);

        Notification::create([
            'user_id' => $user->id,
            'type' => 'order_status_updated',
            'title' => 'Order update',
            'message' => 'Order status changed',
        ]);
        Notification::create([
            'user_id' => $user->id,
            'type' => 'seller_message_reply',
            'title' => 'Message reply',
            'message' => 'A seller replied',
        ]);
        Notification::create([
            'user_id' => $user->id,
            'type' => 'account_notice',
            'title' => 'Account notice',
            'message' => 'General account update',
        ]);

        $this->getJson('/api/notifications')
            ->assertOk()
            ->assertJsonCount(1, 'notifications.data')
            ->assertJsonPath('notifications.data.0.type', 'account_notice')
            ->assertJsonPath('unread_count', 1);
    }

    public function test_data_export_contains_only_the_authenticated_users_records_and_no_secrets(): void
    {
        $user = User::factory()->create(['name' => 'Export Owner']);
        $other = User::factory()->create(['name' => 'Other Account Secret']);
        $this->actingAs($user);

        AccountSecurityEvent::create([
            'user_id' => $user->id,
            'event' => 'password_changed',
            'user_agent' => 'private device metadata',
        ]);
        AccountSecurityEvent::create([
            'user_id' => $other->id,
            'event' => 'email_updated',
        ]);
        Otp::create([
            'email' => $user->email,
            'purpose' => 'password_change',
            'otp_code' => '123456',
            'expires_at' => now()->addMinutes(10),
        ]);

        $response = $this->get('/api/account/export')->assertOk();
        $payload = json_decode($response->streamedContent(), true, flags: JSON_THROW_ON_ERROR);

        $this->assertSame('Export Owner', $payload['profile']['name']);
        $this->assertSame(1, count($payload['security_events']));
        $this->assertSame('password_changed', $payload['security_events'][0]['event']);
        $this->assertStringNotContainsString('Other Account Secret', $response->streamedContent());
        $this->assertStringNotContainsString('123456', $response->streamedContent());
        $this->assertStringNotContainsString('private device metadata', $response->streamedContent());
        $response->assertHeader('Cache-Control', 'private, no-store');
    }

    public function test_account_deletion_is_blocked_when_a_refund_is_linked_to_the_user(): void
    {
        Mail::fake();
        $user = User::factory()->create();
        $book = Book::create(['title' => 'Refunded book']);
        $order = Order::create([
            'order_number' => 'TEST-REFUND-' . $user->id,
            'user_id' => $user->id,
            'book_id' => $book->id,
            'book_price' => 10,
            'quantity' => 1,
            'total_price' => 10,
            'payment_method' => 'cash_on_delivery',
            'payment_status' => 'refunded',
            'order_status' => 'delivered',
            'full_name' => $user->name,
            'phone' => '0000000000',
            'country' => 'Azerbaijan',
            'city' => 'Baku',
            'address' => 'Test address',
        ]);
        Refund::create([
            'order_id' => $order->id,
            'user_id' => $user->id,
            'refund_number' => 'TEST-REFUND-REQUEST-' . $user->id,
            'amount' => 10,
            'reason' => 'Test refund',
        ]);

        $this->actingAs($user)
            ->postJson('/api/account/delete/request')
            ->assertStatus(409)
            ->assertJsonPath('success', false);

        $this->assertDatabaseHas('users', ['id' => $user->id]);
        $this->assertDatabaseHas('refunds', ['order_id' => $order->id]);
        Mail::assertNothingSent();
    }
}
