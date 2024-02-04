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
        Schema::create('leaflets_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leaflet_id')->constrained('leaflets');
            $table->foreignId('product_id')->constrained('products');
            $table->integer('page');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaflets_products');
    }
};
