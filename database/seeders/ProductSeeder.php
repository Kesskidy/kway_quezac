<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::updateOrCreate(['slug' => 'veste-resource'], [
            'name' => 'Veste RE·SOURCE K-Way × Quézac',
            'description' => 'Veste de pluie bleu nuit en édition limitée, avec le tracé du GR70 brodé sur la manche.',
            'price_cents' => 30000,
            'image_path' => 'images/veste-face.png',
        ]);
    }
}
