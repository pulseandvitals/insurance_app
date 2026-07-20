<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('motor_quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producer_id')->constrained()->cascadeOnDelete();
            $table->string('quote_ref')->unique();
            $table->string('status')->default('quote'); // quote, pre_flight, policy, rejected

            // Step 1 — vehicle info
            $table->string('lto_registration_type');
            $table->string('vehicle_class');
            $table->unsignedTinyInteger('coverage_period'); // 1 or 3 years
            $table->boolean('surplus_vehicle')->default(false);
            $table->unsignedSmallInteger('year_model');
            $table->string('brand');
            $table->string('model');
            $table->string('variant');
            $table->string('plate_no');

            // premium breakdown
            $table->decimal('net_premium', 12, 2)->default(0);
            $table->decimal('doc_stamps_tax', 12, 2)->default(0);
            $table->decimal('vat', 12, 2)->default(0);
            $table->decimal('local_govt_tax', 12, 2)->default(0);
            $table->decimal('lto_dbp_dci_fee', 12, 2)->default(0);
            $table->decimal('coc_verification_fee', 12, 2)->default(0);
            $table->decimal('other_charges', 12, 2)->default(0);
            $table->decimal('total_premium', 12, 2)->default(0);

            // Step 2 of 3 — pre-flight vehicle details
            $table->string('chassis_no')->nullable();
            $table->string('motor_no')->nullable();
            $table->string('mv_file_no')->nullable();
            $table->string('color')->nullable();
            $table->string('other_info')->nullable();
            $table->date('inception_date')->nullable();
            $table->date('expiry_date')->nullable();

            // LTO authentication
            $table->string('lto_status')->nullable(); // pending, verified, failed
            $table->string('lto_message')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('motor_quotes');
    }
};
