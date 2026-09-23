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
        Schema::create('evidencias', function (Blueprint $table) {
            $table->id();
            $table->text('titulo');
            $table->smallInteger('ano');
            $table->smallInteger('tipo'); // 1 - documento | 2 - Imagem | 3 - Vídeo | 4 - áudio | 5 - link | 6 - texto
            $table->text('file_name');
            $table->text('file_path');
            $table->text('link');
            $table->text('text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evidencias');
    }
};
