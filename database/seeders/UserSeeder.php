<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('slug', Role::ADMIN)->first();
        $swimmerRole = Role::where('slug', Role::SWIMMER)->first();

        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@lyonpalme.local')],
            [
                'name' => env('ADMIN_NAME', 'Administrateur Blog'),
                'password' => Hash::make(env('ADMIN_PASSWORD', 'Admin1234!')),
                'role_id' => $adminRole?->id,
                'is_admin' => true,
            ]
        );

        User::factory(2)->create([
            'role_id' => $swimmerRole?->id,
            'is_admin' => false,
        ]);
    }
}
