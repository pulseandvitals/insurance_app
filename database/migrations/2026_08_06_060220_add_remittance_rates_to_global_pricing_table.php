<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('global_pricing', function (Blueprint $table) {
            $table->decimal('motorcycle_remittance_rate', 10, 2)->default(145.00)->after('cv_truck_base_rate');
            $table->decimal('pc_suv_remittance_rate', 10, 2)->default(245.00)->after('motorcycle_remittance_rate');
            $table->decimal('cv_truck_remittance_rate', 10, 2)->default(425.00)->after('pc_suv_remittance_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('global_pricing', function (Blueprint $table) {
            $table->dropColumn(['motorcycle_remittance_rate', 'pc_suv_remittance_rate', 'cv_truck_remittance_rate']);
        });
    }
};
