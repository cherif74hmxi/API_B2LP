<?php

namespace Database\Seeders;

use App\Models\Billet;
use App\Models\Commentaire;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentaireSeeder extends Seeder
{
    public function run(): void
    {
        $billetIds = Billet::query()->pluck('id');
        $swimmerIds = User::whereHas('role', fn ($query) => $query->where('slug', Role::SWIMMER))->pluck('id');

        if ($billetIds->isEmpty() || $swimmerIds->isEmpty()) {
            return;
        }

        foreach (range(1, 10) as $index) {
            Commentaire::factory()->create([
                'billet_id' => $billetIds->random(),
                'user_id' => $swimmerIds->random(),
            ]);
        }
    }
}
