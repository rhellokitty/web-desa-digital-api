<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    // use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            HeadOfFamilySeeder::class,
            SocialAssistanceSeeder::class,
            EventSeeder::class,
            EventParticipantSeeder::class
        ]);
    }
}
