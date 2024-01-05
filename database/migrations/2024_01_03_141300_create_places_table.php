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
        Schema::create('places', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voivodeship_id');
            $table->foreignId('county_id');
            $table->foreignId('commune_id');
            $table->string('name');
            $table->string('name_genitive');
            $table->string('name_locative');
            $table->string('slug');
            $table->integer('population');
            $table->decimal('lat',10,8)->default(51.4794644);
            $table->decimal('lng', 11,8)->default(15.984267);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('places');
    }
};
