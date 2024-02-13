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
            $table->foreignId('voivodeship_id')->nullable()->constrained('voivodeships')->onDelete('set null');
            $table->foreignId('county_id')->nullable()->constrained('counties')->onDelete('set null');
            $table->foreignId('commune_id')->nullable()->constrained('communes')->onDelete('set null');
            $table->string('name');
            $table->string('name_genitive');
            $table->string('name_locative');
            $table->string('slug');
            $table->integer('population');
            $table->float('surface')->nullable();
            $table->integer('foundation')->nullable();
            $table->decimal('lat',10,8)->default(51.4794644);
            $table->decimal('lng', 11,8)->default(15.984267);
            $table->string('image_path')->default('assets/image/places/default.png');
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
