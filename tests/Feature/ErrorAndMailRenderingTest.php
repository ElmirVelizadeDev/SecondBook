<?php

namespace Tests\Feature;

use App\Mail\MessageReplyMail;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ErrorAndMailRenderingTest extends TestCase
{
    use RefreshDatabase;

    public function test_maintenance_page_keeps_http_503_and_renders_the_vue_mount_element(): void
    {
        Setting::create([
            'key' => 'maintenance_mode',
            'group_name' => 'general',
            'value' => '1',
            'type' => 'boolean',
        ]);

        $this->get('/')
            ->assertStatus(503)
            ->assertSee('data-vue-page="error-503"', false)
            ->assertSee('data-year="'.now()->year.'"', false)
            ->assertDontSee('Temporarily offline')
            ->assertDontSee('A new chapter')
            ->assertDontSee('error-503-fallback', false);
    }

    public function test_message_reply_email_renders_html_without_vue_runtime(): void
    {
        $html = (new MessageReplyMail("Hello <script>alert('x')</script>\nThanks", 'A reply'))
            ->render();

        $this->assertStringContainsString('<title>A reply</title>', $html);
        $this->assertStringContainsString('Hello &lt;script&gt;alert(&#039;x&#039;)&lt;/script&gt;', $html);
        $this->assertStringNotContainsString('<script', $html);
        $this->assertStringNotContainsString('data-vue-page', $html);
        $this->assertStringNotContainsString('/build/assets/', $html);
    }
}
