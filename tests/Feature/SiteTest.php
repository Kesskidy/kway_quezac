<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Participation;
use App\Models\Product;
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

    public function test_the_landing_page_receives_the_products(): void
    {
        $product = Product::factory()->create();

        $this->get('/')->assertOk()->assertViewHas('products', fn ($products) => $products->contains($product));
    }

    public function test_a_pre_order_is_saved(): void
    {
        $product = Product::factory()->create();

        $this->post('/orders', ['product_id' => $product->id, 'first_name' => 'Camille', 'email' => 'camille@example.com', 'size' => 'M'])
            ->assertRedirect(url('/').'#product')
            ->assertSessionHas('status');

        $this->assertSame('Camille', Order::sole()->first_name);
    }

    public function test_an_invalid_pre_order_is_refused(): void
    {
        $this->post('/orders', ['size' => 'XXXL'])
            ->assertSessionHasErrors(['product_id', 'first_name', 'email', 'size']);

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

    public function test_the_legal_pages_are_marked_as_fictitious(): void
    {
        foreach (['/reglement', '/mentions-legales', '/confidentialite'] as $url) {
            $this->get($url)->assertOk()->assertSee('Projet fictif');
        }

        $this->get('/reglement')->assertSee('1 code = 1 tirage')->assertSee(config('game.prizes')[1]);
    }

    public function test_the_database_seeder_creates_the_demo_data(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertSame(20, PromoCode::count());
        $this->assertSame(1, Product::count());
    }
}
