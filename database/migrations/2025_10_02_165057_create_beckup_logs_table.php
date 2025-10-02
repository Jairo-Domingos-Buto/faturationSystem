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
        Schema::create('beckup_logs', function (Blueprint $table) {
            $table->id();
            $table->string('nome_arquivo');
            $table->string('disco')->nullable();
            $table->bigInteger('tamanho')->nullable();
            $table->string('status')->default('pendente');
            $table->foreignId('criado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beckup_logs');
    }
};
