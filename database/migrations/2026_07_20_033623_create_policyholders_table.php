<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('policyholders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('motor_quote_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // person, organization, lender
            $table->string('name');
            $table->string('address')->nullable();
            $table->boolean('use_as_address')->default(false);
            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('policyholders');
    }
};
