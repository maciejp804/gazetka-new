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
        Schema::create('leaflet_categories', function (Blueprint $table) {
            $table->bigIncrements('id'); // Klucz główny
            $table->unsignedBigInteger('category_index')->unique(); // Unikalny indeks
            $table->foreignId('parent_id')->nullable()->constrained('leaflet_categories')->onDelete('set null');
            $table->string('name')->unique(); // Unikalna nazwa kategorii
            $table->string('slug')->unique(); // Unikalny slug
            $table->string('image_path')->default('assets/image/category/default.png'); // Ścieżka do obrazu
            $table->timestamps(); // Timestamps dla created_at i updated_at


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaflet_categories');
    }
};
