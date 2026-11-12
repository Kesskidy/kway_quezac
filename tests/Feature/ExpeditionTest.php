<?php

namespace Tests\Feature;

use App\Models\ExpeditionRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpeditionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_the_landing_page_shows_the_expedition_after_the_values(): void
    {
        $html = $this->get('/')->assertOk()->assertSee('id="expedition-form"', false)->getContent();

        $this->assertTrue(strpos($html, 'id="valeurs"') < strpos($html, 'id="expedition"'));
        $this->assertTrue(strpos($html, 'id="expedition"') < strpos($html, 'id="game"'));
    }

    public function test_a_visitor_registers_without_reloading(): void
    {
        $this->postJson('/expedition', ['first_name' => ' Camille ', 'email' => 'Camille@Example.com', 'consent' => '1'])
            ->assertCreated()
            ->assertJsonPath('data.message', fn ($message) => str_contains($message, 'Camille'));

        $registration = ExpeditionRegistration::sole();
        $this->assertSame('Camille', $registration->first_name);
        $this->assertSame('camille@example.com', $registration->email);
    }

    public function test_an_address_is_only_registered_once(): void
    {
        ExpeditionRegistration::factory()->create(['email' => 'camille@example.com']);

        $this->postJson('/expedition', ['first_name' => 'Camille', 'email' => 'camille@example.com', 'consent' => '1'])
            ->assertOk()
            ->assertJsonPath('data.message', fn ($message) => str_contains($message, 'déjà inscrite'));

        $this->assertSame(1, ExpeditionRegistration::count());
    }

    public function test_an_invalid_registration_is_refused(): void
    {
        $this->postJson('/expedition', ['email' => 'pas-un-email'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['first_name', 'email', 'consent']);

        $this->from('/')->post('/expedition', [])
            ->assertRedirect(url('/').'#expedition')
            ->assertSessionHasErrors(['first_name', 'email', 'consent'], null, 'expedition');

        $this->assertSame(0, ExpeditionRegistration::count());
    }

    public function test_without_javascript_an_error_only_shows_in_its_own_form(): void
    {
        $this->from('/')->post('/expedition', ['first_name' => 'Camille', 'email' => 'pas-un-email']);

        $html = $this->get('/')->getContent();
        $this->assertSame(1, substr_count($html, 'Cette adresse e-mail est invalide.'));
        $this->assertStringContainsString('value="pas-un-email"', $html);
        $this->assertSame(1, substr_count($html, 'value="pas-un-email"'));
    }

    public function test_the_admin_sees_and_exports_the_registrations(): void
    {
        $this->get('/admin/expedition/export')->assertUnauthorized();

        $admin = User::factory()->create();
        ExpeditionRegistration::factory()->create(['first_name' => 'Camille', 'email' => 'camille@example.com']);

        $this->actingAs($admin)->get('/admin/participations')->assertOk()->assertSee('Inscrits à l’Expédition RE·SOURCE (1)')->assertSee('camille@example.com');

        $csv = $this->actingAs($admin)->get('/admin/expedition/export')->assertDownload('expedition.csv')->streamedContent();
        $this->assertStringContainsString('Camille', $csv);
    }
}
