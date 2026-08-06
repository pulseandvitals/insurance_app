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
        Schema::table('deposits', function (Blueprint $table) {
            $table->string('paymongo_checkout_session_id')->nullable()->after('proof_path');
            $table->string('paymongo_payment_intent_id')->nullable()->after('paymongo_checkout_session_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            $table->dropColumn(['paymongo_checkout_session_id', 'paymongo_payment_intent_id']);
        });
    }
};
