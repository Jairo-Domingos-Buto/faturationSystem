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
        // Altera a tabela fatura_items
        Schema::table('fatura_items', function (Blueprint $table) {
            // Permite que produto_id seja NULL (quando for um serviço)
            $table->unsignedBigInteger('produto_id')->nullable()->change();
        });

        // Altera a tabela recibo_items (onde deu o erro)
        Schema::table('recibo_items', function (Blueprint $table) {
            // Permite que produto_id seja NULL
            $table->unsignedBigInteger('produto_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverte as mudanças (não recomendado executar se já tiver dados nulos)
        Schema::table('fatura_items', function (Blueprint $table) {
            $table->unsignedBigInteger('produto_id')->nullable(false)->change();
        });

        Schema::table('recibo_items', function (Blueprint $table) {
            $table->unsignedBigInteger('produto_id')->nullable(false)->change();
        });
    }
};
