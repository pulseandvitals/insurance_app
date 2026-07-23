<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        Branch::firstOrCreate(
            ['code' => 'MKT-001'],
            ['name' => 'Makati Main Branch', 'status' => 'Active'],
        );
    }
}
