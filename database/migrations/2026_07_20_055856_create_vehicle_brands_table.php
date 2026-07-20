<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_brands', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_class');
            $table->string('name');
            $table->timestamps();

            $table->unique(['vehicle_class', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_brands');
    }
};
