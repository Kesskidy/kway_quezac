<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Participation;
use App\Models\PromoCode;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_the_landing_page_shows_the_jacket(): void
    {
        $this->get('/')->assertOk()->assertSee('Veste RE·SOURCE')->assertSee('300 €')->assertSee('S’inscrire à la précommande');
    }

    public function test_a_pre_order_is_saved(): void
    {
        $this->post('/orders', ['first_name' => 'Camille', 'email' => 'camille@example.com', 'size' => 'M'])
            ->assertRedirect(url('/').'#product')
            ->assertSessionHas('status');

        $this->assertSame('Camille', Order::sole()->first_name);
    }

    public function test_a_pre_order_sent_in_json_does_not_redirect(): void
    {
        $this->postJson('/orders', ['first_name' => 'Camille', 'email' => 'camille@example.com', 'size' => 'L'])
            ->assertCreated()
            ->assertJsonPath('data.message', fn ($message) => str_contains($message, 'taille L'));

        $this->assertSame('L', Order::sole()->size);
    }

    public function test_an_invalid_pre_order_is_refused(): void
    {
        $this->from('/')->post('/orders', ['size' => 'XXXL'])
            ->assertRedirect(url('/').'#product')
            ->assertSessionHasErrors(['first_name', 'email', 'size'], null, 'order');

        $this->postJson('/orders', [])->assertUnprocessable()->assertJsonValidationErrors(['first_name', 'email', 'size']);

        $this->assertSame(0, Order::count());
    }

    public function test_the_admin_pages_need_a_password(): void
    {
        $this->get('/admin/participations')->assertUnauthorized();
        $this->get('/admin/participations/export')->assertUnauthorized();
    }

    public function test_the_admin_sees_and_exports_the_participations(): void
    {
        $admin = User::factory()->create();
        Participation::factory()->create(['first_name' => 'Camille']);

        $this->actingAs($admin)->get('/admin/participations')->assertOk()->assertSee('Camille');

        $csv = $this->actingAs($admin)->get('/admin/participations/export')->assertDownload('participations.csv')->streamedContent();
        $this->assertStringContainsString('Camille', $csv);
    }

    public function test_the_legal_pages_are_marked_as_a_student_project(): void
    {
        foreach (['/reglement', '/mentions-legales', '/confidentialite'] as $url) {
            $this->get($url)->assertOk()->assertSee('Projet étudiant');
        }

        $this->get('/reglement')->assertSee('1 code = 1 tirage')->assertSee(config('game.prizes')[1])->assertSee('10 décembre 2027');
    }

    public function test_the_landing_page_shows_the_game_dates(): void
    {
        $this->get('/')->assertOk()->assertSee('1er octobre au 30 novembre 2027')->assertSee('10 décembre 2027');
    }

    public function test_the_database_seeder_creates_the_demo_data(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertSame(20, PromoCode::count());
    }
}
