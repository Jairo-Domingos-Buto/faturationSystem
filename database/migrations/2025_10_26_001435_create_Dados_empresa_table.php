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
        Schema::create('Dados_empresa', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nif');
            $table->string('telefone');
            $table->string('email');
            $table->text('website')->nullable();
            $table->string('nomeDoBanco');
            $table->string('iban');
            $table->string('cidade');
            $table->string('rua');
            $table->string('edificio');
            $table->string('municipio')->nullable();
            $table->string('regime')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Dados_empresa');
    }
};
