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
            $table->string('authentication_no')->nullable()->after('lto_message');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('motor_quotes', function (Blueprint $table) {
            $table->dropColumn('authentication_no');
        });
    }
};
