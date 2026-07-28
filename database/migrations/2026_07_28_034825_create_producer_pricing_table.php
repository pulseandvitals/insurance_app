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
        Schema::create('producer_pricing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producer_id')->unique()->constrained()->cascadeOnDelete();
            $table->decimal('others_fee', 10, 2)->default(10.00);
            $table->decimal('coc_verification_fee', 10, 2)->default(40.40);
            $table->decimal('motorcycle_price', 10, 2)->default(160.00);
            $table->decimal('pc_suv_price', 10, 2)->default(260.00);
            $table->decimal('cv_truck_price', 10, 2)->default(450.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producer_pricing');
    }
};
