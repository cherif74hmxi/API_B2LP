<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
        ]);

        if (app()->environment('local') && class_exists(\Faker\Factory::class)) {
            $this->call([
                BilletSeeder::class,
                CommentaireSeeder::class,
            ]);
        }
    }
}
