<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->product = Product::factory()->create(['name' => 'Veste RE·SOURCE K-Way × Quézac']);
    }

    private function validData(array $override = []): array
    {
        return array_filter([
            'product_id' => $this->product->id,
            'first_name' => 'Camille',
            'email' => 'camille@example.com',
            'size' => 'M',
            ...$override,
        ], fn ($value) => $value !== null);
    }

    public function test_the_landing_page_receives_the_products(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertViewHas('products', fn ($products) => $products->contains($this->product));
    }

    public function test_the_landing_route_is_named_as_in_the_contract(): void
    {
        $this->assertSame(url('/'), route('landing'));
        $this->assertSame(url('/orders'), route('orders.store'));
    }

    public function test_a_valid_pre_order_is_saved_and_redirects_to_the_product_section(): void
    {
        $this->post('/orders', $this->validData(['email' => '  Camille@Example.com ']))
            ->assertRedirect(url('/').'#product')
            ->assertSessionHas('status', 'Merci Camille ! Ta précommande de la Veste RE·SOURCE K-Way × Quézac est enregistrée. C’est une précommande fictive : aucun paiement n’est demandé.');

        $order = Order::sole();
        $this->assertTrue($order->product->is($this->product));
        $this->assertSame('Camille', $order->first_name);
        $this->assertSame('camille@example.com', $order->email);
        $this->assertSame('M', $order->size);
    }

    public function test_the_size_is_optional(): void
    {
        $this->post('/orders', $this->validData(['size' => null]))->assertSessionHasNoErrors();

        $this->assertNull(Order::sole()->size);
    }

    public static function invalidFields(): array
    {
        return [
            'missing product' => [['product_id' => null], 'product_id', 'Choisis un produit.'],
            'unknown product' => [['product_id' => 999], 'product_id', 'Ce produit n’existe pas.'],
            'missing first name' => [['first_name' => null], 'first_name', 'Indique ton prénom.'],
            'first name too long' => [['first_name' => str_repeat('a', 51)], 'first_name', 'Ton prénom doit faire 50 caractères maximum.'],
            'missing email' => [['email' => null], 'email', 'Indique ton adresse e-mail.'],
            'invalid email' => [['email' => 'camille'], 'email', 'Cette adresse e-mail est invalide.'],
            'email too long' => [['email' => str_repeat('a', 250).'@example.com'], 'email', 'Cette adresse e-mail est trop longue.'],
            'unknown size' => [['size' => 'XXXL'], 'size', 'Choisis une taille entre XS et XXL.'],
        ];
    }

    #[DataProvider('invalidFields')]
    public function test_invalid_fields_go_back_to_the_product_section_with_errors(array $override, string $field, string $message): void
    {
        $this->post('/orders', $this->validData($override))
            ->assertRedirect(url('/').'#product')
            ->assertSessionHasErrors([$field => $message])
            ->assertSessionHasInput($field === 'first_name' ? 'email' : 'first_name'); // typed values are kept

        $this->assertSame(0, Order::count());
    }

    public function test_the_form_requires_a_csrf_token(): void
    {
        $this->app['env'] = 'local'; // the CSRF check is skipped while running unit tests

        $this->post('/orders', $this->validData())->assertStatus(419);

        $this->assertSame(0, Order::count());
    }

    public function test_the_price_is_formatted_from_cents(): void
    {
        $this->assertSame('300 €', Product::factory()->make(['price_cents' => 30000])->formattedPrice());
        $this->assertSame('39,90 €', Product::factory()->make(['price_cents' => 3990])->formattedPrice());
        $this->assertSame("1\u{202F}250 €", Product::factory()->make(['price_cents' => 125000])->formattedPrice());
    }

    public function test_the_product_seeder_creates_the_jacket_once(): void
    {
        $this->seed(ProductSeeder::class);
        $this->seed(ProductSeeder::class);

        $jacket = Product::where('slug', 'veste-resource')->sole();
        $this->assertSame('Veste RE·SOURCE K-Way × Quézac', $jacket->name);
        $this->assertSame(30000, $jacket->price_cents);
        $this->assertFileExists(public_path($jacket->image_path));
    }
}
