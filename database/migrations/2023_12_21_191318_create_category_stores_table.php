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
        Schema::create('category_stores', function (Blueprint $table) {
            $table->id();
            $table->integer('category_index')->unique();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('image_path')->default('assets/image/category/default.png');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_stores');
    }
};
