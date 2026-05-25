<?php

namespace Database\Factories;

use App\Models\Billet;
use App\Models\User;
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
            'COM_DATE' => now()->toDateString(),
            'COM_CONTENU' => fake()->text(200),
            'billet_id' => Billet::factory(),
            'user_id' => User::factory(),
        ];
    }
}
