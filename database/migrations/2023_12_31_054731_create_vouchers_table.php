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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_id');
            $table->foreignId('program_id');
            $table->string('title');
            $table->string('landing_url');
            $table->string('offer_image');
            $table->string('voucher_code');
            $table->foreignId('voucher_category');
            $table->foreignId('voucher_type');
            $table->date('start_offer_date');
            $table->date('end_offer_date');
            $table->string('conditions');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
