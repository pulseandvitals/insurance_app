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
        Schema::table('producers', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->after('tier')->constrained()->nullOnDelete();
        });

        Schema::table('producers', function (Blueprint $table) {
            $table->dropColumn(['branch_code', 'branch_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('producers', function (Blueprint $table) {
            $table->string('branch_code')->default('MKT-001');
            $table->string('branch_name')->default('Makati Main Branch');
        });

        Schema::table('producers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('branch_id');
        });
    }
};
