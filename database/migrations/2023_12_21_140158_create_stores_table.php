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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('name_genitive')->nullable();
            $table->string('name_locative')->nullable();
            $table->string('subdomain')->unique();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('title')->nullable();
            $table->text('excerpt_description')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('category_store_id');
            $table->boolean('status');
            $table->decimal('rate', 3,2);
            $table->integer('votes');
            $table->integer('offers');
            $table->string('logo');
            $table->boolean('is_online');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
