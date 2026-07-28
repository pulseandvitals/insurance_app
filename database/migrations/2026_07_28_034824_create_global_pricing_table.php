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
        Schema::create('global_pricing', function (Blueprint $table) {
            $table->id();
            $table->decimal('motorcycle_base_rate', 10, 2);
            $table->decimal('pc_suv_base_rate', 10, 2);
            $table->decimal('cv_truck_base_rate', 10, 2);
            $table->decimal('doc_stamp_tax_percent', 5, 2);
            $table->decimal('vat_percent', 5, 2);
            $table->decimal('local_govt_tax_percent', 5, 2);
            $table->decimal('lto_dbp_dci_fee', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('global_pricing');
    }
};
