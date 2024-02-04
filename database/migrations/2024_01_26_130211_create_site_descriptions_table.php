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
        Schema::create('site_descriptions', function (Blueprint $table) {
            $table->id();
            $table->integer('type_id');
            $table->integer('box_id');
            $table->string('place');
            $table->string('image_path')->default('assets/image/description/default.png');
            $table->text('header');
            $table->text('body');
            $table->string('explanation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_descriptions');
    }
};
