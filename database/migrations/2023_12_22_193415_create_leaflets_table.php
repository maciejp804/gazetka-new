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
        Schema::create('leaflets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id');
            $table->integer('leaflet_category_id');
            $table->integer('leaflet_number');
            $table->string('meta_title');
            $table->string('meta_description');
            $table->string('title');
            $table->text('description');
            $table->string('slug');
            $table->date('start_date');
            $table->date('end_date');
            $table->date('start_offer_date');
            $table->date('end_offer_date');
            $table->date('next_offer_date');
            $table->integer('pages');
            $table->string('thumbnail');
            $table->boolean('is_alcohol');
            $table->boolean('is_regions');
            $table->boolean('is_promo_main');
            $table->boolean('is_next_promo');
            $table->integer('liked_users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaflets');
    }
};
