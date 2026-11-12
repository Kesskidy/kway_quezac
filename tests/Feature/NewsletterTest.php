<?php

namespace Tests\Feature;

use App\Models\NewsletterSubscriber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsletterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_the_landing_page_shows_the_newsletter_form(): void
    {
        $this->get('/')->assertOk()->assertSee('id="newsletter-form"', false)->assertSee('Garde le fil.');
    }

    public function test_a_visitor_subscribes_without_reloading(): void
    {
        $this->postJson('/newsletter', ['email' => '  Camille@Example.com ', 'consent' => '1'])
            ->assertCreated()
            ->assertJsonPath('data.message', fn ($message) => str_contains($message, 'camille@example.com'));

        $subscriber = NewsletterSubscriber::sole();
        $this->assertSame('camille@example.com', $subscriber->email);
        $this->assertNotNull($subscriber->consented_at);
    }

    public function test_an_address_is_only_saved_once(): void
    {
        NewsletterSubscriber::factory()->create(['email' => 'camille@example.com']);

        $this->postJson('/newsletter', ['email' => 'camille@example.com', 'consent' => '1'])
            ->assertOk()
            ->assertJsonPath('data.message', fn ($message) => str_contains($message, 'déjà inscrite'));

        $this->assertSame(1, NewsletterSubscriber::count());
    }

    public function test_an_invalid_subscription_is_refused(): void
    {
        $this->postJson('/newsletter', ['email' => 'pas-un-email'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email', 'consent']);

        $this->from('/')->post('/newsletter', [])
            ->assertRedirect(url('/').'#newsletter')
            ->assertSessionHasErrors(['email', 'consent'], null, 'newsletter');

        $this->assertSame(0, NewsletterSubscriber::count());
    }

    public function test_without_javascript_the_visitor_comes_back_to_the_form(): void
    {
        $this->post('/newsletter', ['email' => 'camille@example.com', 'consent' => '1'])
            ->assertRedirect(url('/').'#newsletter')
            ->assertSessionHas('newsletter_status');
    }

    public function test_the_admin_sees_and_exports_the_subscribers(): void
    {
        $this->get('/admin/newsletter/export')->assertUnauthorized();

        $admin = User::factory()->create();
        NewsletterSubscriber::factory()->create(['email' => 'camille@example.com']);

        $this->actingAs($admin)->get('/admin/participations')->assertOk()->assertSee('Inscrits à la newsletter (1)')->assertSee('camille@example.com');

        $csv = $this->actingAs($admin)->get('/admin/newsletter/export')->assertDownload('newsletter.csv')->streamedContent();
        $this->assertStringContainsString('camille@example.com', $csv);
    }
}
