<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Otp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ForgotPasswordRouteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_forgot_password_route_is_registered(): void
    {
        $response = $this->get(route('frontend.auth.password.request'));

        $response->assertStatus(200);
    }

    public function test_password_request_page_shows_email_form_for_sending_otp(): void
    {
        $response = $this->get(route('frontend.auth.password.request'));

        $response->assertStatus(200);
        $response->assertSee('data-vue-page="auth-page"', false);
        $response->assertSee('data-mode="password-request"', false);
        $response->assertSee('data-password-verify-url="' . route('frontend.auth.password.verify') . '"', false);
    }

    public function test_sending_otp_redirects_to_verify_page_without_dropping_csrf_session(): void
    {
        Mail::fake();
        User::factory()->create([
            'email' => 'user@example.com',
        ]);

        $this->get(route('frontend.auth.password.request'));

        $response = $this->post(route('frontend.auth.password.send.otp'), [
            'email' => 'user@example.com',
        ]);

        $response->assertRedirect(route('frontend.auth.password.verify'));
        $response->assertSessionHas('reset_email', 'user@example.com');
    }

    public function test_email_verification_resend_uses_the_active_registration_session(): void
    {
        Mail::fake();

        $response = $this->withSession([
            'pending_registration' => [
                'first_name' => 'Test',
                'last_name' => 'Reader',
                'username' => 'test-reader',
                'email' => 'test.reader@gmail.com',
                'password' => bcrypt('test-password'),
            ],
            'email_verification_email' => 'test.reader@gmail.com',
        ])->postJson(route('frontend.auth.email.verify.resend'));

        $response->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('otps', [
            'email' => 'test.reader@gmail.com',
            'purpose' => 'email_verification',
        ]);
    }
}
