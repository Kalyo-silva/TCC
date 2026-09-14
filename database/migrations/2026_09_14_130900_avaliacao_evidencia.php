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
            $table->foreignId('avaliacao_indicador_id')->constrained('avaliacao_indicador');
            $table->foreignId('evidencia_id')->constrained('evidencias');
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