<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Commentaire>
 */
class CommentaireFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'COM_DATE' => now(),
            'COM_CONTENU' => fake()->text(200),
            'billet_id' => fake()->numberBetween(1,10),
            'user_id' => fake()->numberBetween(1,2),
        ];
    }
}
