<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

/**
 * The collaboration product shown in #product (regle.md, section 10).
 * Same name, price and image as the landing page.
 */
class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::updateOrCreate(
            ['slug' => 'veste-resource'],
            [
                'name' => 'Veste RE·SOURCE K-Way × Quézac',
                'description' => 'Veste de pluie bleu nuit en édition limitée, avec le tracé du GR70 brodé sur la manche et des griffes cuivrées sur l’épaule. Une ressource minérale et son emballage transformés en un vêtement durable.',
                'price_cents' => 30000,
                'image_path' => 'images/veste-face.png',
            ],
        );
    }
}
