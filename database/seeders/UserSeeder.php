<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('slug', Role::ADMIN)->first();
        $swimmerRole = Role::where('slug', Role::SWIMMER)->first();

        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'cherif@lyonpalm.fr')],
            [
                'name' => env('ADMIN_NAME', 'Cherif'),
                'password' => Hash::make(env('ADMIN_PASSWORD', 'B2lp2026')),
                'role_id' => $adminRole?->id,
                'is_admin' => true,
            ]
        )->forceFill([
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ])->save();

        if (app()->environment('local')) {
            User::factory(2)->create([
                'role_id' => $swimmerRole?->id,
                'is_admin' => false,
            ]);
        }
    }
}
