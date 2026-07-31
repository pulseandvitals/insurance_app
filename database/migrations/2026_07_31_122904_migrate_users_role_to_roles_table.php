<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $roleIds = [];

        foreach (['admin', 'dev', 'producer'] as $name) {
            $roleIds[$name] = DB::table('roles')->insertGetId([
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $now = now();

        DB::table('users')->orderBy('id')->get(['id', 'role'])->each(function ($user) use ($roleIds, $now) {
            $roleName = $user->role === 'super_admin' ? 'admin' : 'producer';

            DB::table('role_user')->insert([
                'user_id' => $user->id,
                'role_id' => $roleIds[$roleName],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('producer')->after('email');
        });

        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');

        DB::table('users')->update(['role' => 'producer']);

        if ($adminRoleId) {
            $adminUserIds = DB::table('role_user')->where('role_id', $adminRoleId)->pluck('user_id');
            DB::table('users')->whereIn('id', $adminUserIds)->update(['role' => 'super_admin']);
        }

        DB::table('role_user')->delete();
        DB::table('roles')->delete();
    }
};
