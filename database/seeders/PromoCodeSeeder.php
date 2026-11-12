<?php

namespace Database\Seeders;

use App\Models\PromoCode;
use Illuminate\Database\Seeder;

/**
 * Demo codes only (regle.md, section 10). Real codes and their QR codes are generated outside the site.
 * Codes are random at each run: no real code is ever stored in the repository.
 * No factory here: factories need Faker, which is only installed in development.
 */
class PromoCodeSeeder extends Seeder
{
    public const COUNT = 20;

    /**
     * No 0/O or 1/I/L, so a code read on a bottle cannot be mistyped.
     */
    private const ALPHABET = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';

    public function run(): void
    {
        $existing = PromoCode::pluck('code')->flip();
        $codes = [];

        while (count($codes) < self::COUNT) {
            $code = 'QZK-'.$this->randomPart().'-'.$this->randomPart();

            if (! isset($existing[$code])) {
                $codes[$code] = true;
            }
        }

        $now = now();
        PromoCode::insert(array_map(
            fn (string $code) => ['code' => $code, 'created_at' => $now, 'updated_at' => $now],
            array_keys($codes),
        ));

        $this->command?->info(self::COUNT.' codes de démonstration créés, à saisir dans la section du jeu :');
        $this->command?->table(['Code'], array_map(fn (string $code) => [$code], array_keys($codes)));
    }

    private function randomPart(): string
    {
        $part = '';

        for ($i = 0; $i < 4; $i++) {
            $part .= self::ALPHABET[random_int(0, strlen(self::ALPHABET) - 1)];
        }

        return $part;
    }
}
