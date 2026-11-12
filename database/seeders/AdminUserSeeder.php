<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        if (! env('ADMIN_EMAIL') || ! env('ADMIN_PASSWORD')) {
            $this->command?->warn('Compte administrateur non créé : renseigne ADMIN_EMAIL et ADMIN_PASSWORD dans .env.');

            return;
        }

        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL')],
            ['name' => 'Administrateur', 'password' => env('ADMIN_PASSWORD')],
        );
    }
}
