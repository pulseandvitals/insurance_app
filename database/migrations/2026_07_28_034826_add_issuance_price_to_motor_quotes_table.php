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
        Schema::table('motor_quotes', function (Blueprint $table) {
            $table->decimal('issuance_price', 12, 2)->default(0)->after('total_premium');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('motor_quotes', function (Blueprint $table) {
            $table->dropColumn('issuance_price');
        });
    }
};
