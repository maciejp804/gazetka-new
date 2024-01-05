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
        Schema::create('voucher_stores', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('program_id');
            $table->string('logo_path');
            $table->foreignId('category_store');
            $table->foreignId('store_id')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voucher_stores');
    }
};
