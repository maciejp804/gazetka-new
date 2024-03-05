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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('excerpt');
            $table->text('body');
            $table->foreignId('author_id')->nullable()->constrained('users','id');
            $table->string('slug');
            $table->string('image_path');
            $table->string('image_thumbnail')->default('assets/image/pro/mujercest.png');
            $table->string('meta_title');
            $table->string('meta_description');
            $table->foreignId('category_article_id')->nullable()->constrained('category_articles','id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
