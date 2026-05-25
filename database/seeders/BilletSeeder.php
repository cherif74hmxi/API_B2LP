<?php

namespace Database\Seeders;

use App\Models\Billet;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class BilletSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::whereHas('role', fn ($query) => $query->where('slug', Role::ADMIN))->first()
            ?? User::factory()->admin()->create();

        Billet::factory(10)->create([
            'user_id' => $admin->id,
        ]);
    }
}
