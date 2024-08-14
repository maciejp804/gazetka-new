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
        Schema::create('ocr_results', function (Blueprint $table) {
            $table->id();
            $table->text('text'); // Oryginalny tekst OCR
            $table->text('processed_text'); // Przetworzony tekst
            $table->text('keywords'); // Słowa kluczowe
            $table->integer('page'); // Numer strony
            $table->timestamps(); // Data utworzenia i aktualizacji
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ocr_results');
    }
};
