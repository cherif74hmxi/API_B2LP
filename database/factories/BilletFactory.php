<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Billet>
 */
class BilletFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'BIL_DATE' => now()->toDateString(),
            'BIL_TITRE' => fake()->text(50),
            'BIL_CONTENU' => fake()->text(200),
            'user_id' => User::factory()->admin(),
        ];
    }
}
