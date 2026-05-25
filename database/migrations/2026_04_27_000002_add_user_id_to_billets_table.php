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
        Schema::table('billets', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
        });

        $adminUserId = DB::table('users')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->where('roles.slug', Role::ADMIN)
            ->value('users.id');

        if (!$adminUserId && Schema::hasColumn('users', 'is_admin')) {
            $adminUserId = DB::table('users')->where('is_admin', true)->value('id');
        }

        $fallbackUserId = $adminUserId ?: DB::table('users')->value('id');

        if ($fallbackUserId) {
            DB::table('billets')->whereNull('user_id')->update(['user_id' => $fallbackUserId]);
        }
    }

    public function down(): void
    {
        Schema::table('billets', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
