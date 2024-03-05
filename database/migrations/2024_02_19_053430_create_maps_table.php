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
        Schema::create('maps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores');
            $table->foreignId('place_id')->constrained('places');
            $table->string('address');
            $table->string('slug');
            $table->decimal('lat',10,8)->default(51.4794644);
            $table->decimal('lng', 11,8)->default(15.984267);
            $table->string('weekdays');
            $table->string('saturday');
            $table->string('sunday');
            $table->text('header');
            $table->text('excerpt');
            $table->text('body');
            $table->string('image_path')->default('assets/image/map/default.png');
            $table->decimal('rate', 3,2);
            $table->integer('votes');
            $table->boolean('status');
            $table->boolean('mall_is');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maps');
    }
};
