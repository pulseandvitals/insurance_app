<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Seed the application's admin accounts.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', Role::ROLE_ADMIN)->firstOrFail();
        $devRole = Role::where('name', Role::ROLE_DEV)->firstOrFail();

        $admin1 = User::firstOrCreate(
            ['email' => 'admin1@example.com'],
            ['name' => 'Admin 1', 'password' => Hash::make('password'), 'email_verified_at' => now()],
        );
        $admin1->roles()->syncWithoutDetaching([$adminRole->id]);

        $admin2 = User::firstOrCreate(
            ['email' => 'admin2@example.com'],
            ['name' => 'Admin 2', 'password' => Hash::make('password'), 'email_verified_at' => now()],
        );
        $admin2->roles()->syncWithoutDetaching([$adminRole->id]);

        $adminDev = User::firstOrCreate(
            ['email' => 'admindev@example.com'],
            ['name' => 'Admin Dev', 'password' => Hash::make('password'), 'email_verified_at' => now()],
        );
        $adminDev->roles()->syncWithoutDetaching([$adminRole->id, $devRole->id]);
    }
}
