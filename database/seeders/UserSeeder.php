<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'cherif@lyonpalm.fr'],
            [
                'name' => 'Cherif',
                'password' => Hash::make('B2lp2026'),
                'role' => 'admin',
            ]
        )->forceFill([
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ])->save();

        if (app()->environment('local') && class_exists(\Faker\Factory::class)) {
            User::factory(5)->create();
        }
    }
}
