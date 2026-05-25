<?php

use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->timestamps();
        });

        DB::table('roles')->insert([
            [
                'name' => 'Administrateur',
                'slug' => Role::ADMIN,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nageur',
                'slug' => Role::SWIMMER,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->constrained('roles')->nullOnDelete();
        });

        $adminRoleId = DB::table('roles')->where('slug', Role::ADMIN)->value('id');
        $swimmerRoleId = DB::table('roles')->where('slug', Role::SWIMMER)->value('id');

        if (Schema::hasColumn('users', 'is_admin')) {
            DB::table('users')->where('is_admin', true)->update(['role_id' => $adminRoleId]);
            DB::table('users')->where(function ($query) {
                $query->where('is_admin', false)->orWhereNull('is_admin');
            })->update(['role_id' => $swimmerRoleId]);

            return;
        }

        DB::table('users')->update(['role_id' => $swimmerRoleId]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('role_id');
        });

        Schema::dropIfExists('roles');
    }
};
