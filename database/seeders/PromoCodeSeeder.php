<?php

namespace Database\Seeders;

use App\Models\PromoCode;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PromoCodeSeeder extends Seeder
{
    public function run(): void
    {
        $this->command?->info('Codes de démonstration :');

        for ($i = 0; $i < 20; $i++) {
            $code = 'QZK-'.Str::upper(Str::random(4)).'-'.Str::upper(Str::random(4));
            PromoCode::create(['code' => $code]);
            $this->command?->line($code);
        }
    }
}
