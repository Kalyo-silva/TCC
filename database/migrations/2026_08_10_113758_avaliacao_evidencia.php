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
        Schema::create('avaliacao_evidencia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_avaliacao')->constrained('avaliacoes');
            $table->foreignId('id_instrumento')->constrained('avaliacoes');
            $table->foreignId('id_dimensao')->constrained('dimensoes');
            $table->foreignId('id_indicador')->constrained('indicadores');
            $table->foreignId('id_evidencia')->constrained('evidencias');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avaliacao_evidencia');
    }
};