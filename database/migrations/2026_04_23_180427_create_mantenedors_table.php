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
        Schema::create('mantenedores', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->string('cidade')->nullable();
            $table->string('uf')->nullable();
            $table->string('bairro')->nullable();
            $table->string('logradouro')->nullable();
            $table->string('cep')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mantenedores');
    }
};
