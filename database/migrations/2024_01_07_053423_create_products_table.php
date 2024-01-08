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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('image_path');
            $table->boolean('status');
            $table->decimal('rate', 3,2);
            $table->integer('votes');
            $table->unsignedBigInteger('parent_id')->nullable(); // kolumna parent_id
            $table->foreign('parent_id')->references('id')->on('products')->onDelete('cascade'); // klucz obcy
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');

    }
};
