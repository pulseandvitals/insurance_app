<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('policies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('motor_quote_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('producer_id')->constrained()->cascadeOnDelete();
            $table->string('online_policy_no')->unique();
            $table->string('genweb_code');
            $table->string('coc_no');
            $table->string('authentication_no');
            $table->timestamp('issued_at');
            $table->date('contract_from');
            $table->date('contract_to');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('policies');
    }
};
