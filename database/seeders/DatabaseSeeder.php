<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // No test user: any account in `users` can open /admin/* through auth.basic.
        $this->call([
            AdminUserSeeder::class,
            PromoCodeSeeder::class,
            ProductSeeder::class,
        ]);

        // Fake participations for the demo of /admin/participations and lottery:draw.
        if (app()->environment('local')) {
            $this->call(DemoParticipationSeeder::class);
        }
    }
}
