<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Creates (or updates) the administrator account used by auth.basic on /admin/*
 * (regle.md, section 3). Email and password come from .env: ADMIN_EMAIL, ADMIN_PASSWORD.
 */
class AdminUserSeeder extends Seeder
{
    private const MIN_PASSWORD_LENGTH = 8;

    public function run(): void
    {
        $email = config('auth.admin.email');
        $password = config('auth.admin.password');

        if (blank($email) || blank($password)) {
            $this->command?->warn('Compte administrateur non créé : renseigne ADMIN_EMAIL et ADMIN_PASSWORD dans .env.');

            return;
        }

        if (mb_strlen($password) < self::MIN_PASSWORD_LENGTH) {
            $this->command?->warn('Compte administrateur non créé : ADMIN_PASSWORD doit faire au moins '.self::MIN_PASSWORD_LENGTH.' caractères.');

            return;
        }

        // The `hashed` cast of User hashes the password.
        User::updateOrCreate(
            ['email' => mb_strtolower(trim($email))],
            ['name' => 'Administrateur', 'password' => $password],
        );

        $this->command?->info("Compte administrateur prêt : {$email}");
    }
}
