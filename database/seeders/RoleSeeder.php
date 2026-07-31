<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Seed the application's roles.
     */
    public function run(): void
    {
        foreach ([Role::ROLE_ADMIN, Role::ROLE_DEV, Role::ROLE_PRODUCER] as $name) {
            Role::firstOrCreate(['name' => $name]);
        }
    }
}
