<?php

namespace Database\Seeders;

use App\Models\PromoCode;
use Illuminate\Database\Seeder;

/**
 * Demo codes only (regle.md, section 10). Real codes and their QR codes are generated outside the site.
 */
class PromoCodeSeeder extends Seeder
{
    public function run(): void
    {
        PromoCode::factory()->count(20)->create();
    }
}
