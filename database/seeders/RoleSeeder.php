<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::updateOrCreate(
            ['slug' => Role::ADMIN],
            ['name' => 'Administrateur']
        );

        Role::updateOrCreate(
            ['slug' => Role::SWIMMER],
            ['name' => 'Nageur']
        );
    }
}
