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
        Schema::create('instituicoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mantenedor_id')->constrained('mantenedores');
            $table->string('nome')->unique();
            $table->string('cidade')->nullable();
            $table->string('uf')->nullable();
            $table->string('bairro')->nullable();
            $table->string('logradouro')->nullable();
            $table->string('cep')->nullable();
            $table->string('sigla')->nullable();
            $table->string('logo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instituicoes');
    }
};
