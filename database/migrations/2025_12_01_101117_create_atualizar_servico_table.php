<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up()
    {
        Schema::table('servicos', function (Blueprint $table) {
            // Renomear 'valor' para 'preco_venda' se existir
            if (Schema::hasColumn('servicos', 'valor')) {
                $table->renameColumn('valor', 'preco_venda');
            } else {
                $table->decimal('preco_venda', 10, 2)->default(0);
            }

            // Adicionar campos de imposto
            $table->foreignId('imposto_id')->nullable()->constrained('impostos')->nullOnDelete();
            $table->foreignId('motivo_isencaos_id')->nullable()->constrained('motivo_isencaos')->nullOnDelete();

            // Estoque para controle (opcional)
            $table->integer('estoque')->default(999999); // Valor alto para serviços ilimitados
        });
    }

    public function down()
    {
        Schema::table('servicos', function (Blueprint $table) {
            $table->dropForeign(['imposto_id']);
            $table->dropForeign(['motivo_isencaos_id']);
            $table->dropColumn(['imposto_id', 'motivo_isencaos_id', 'estoque']);

            if (Schema::hasColumn('servicos', 'preco_venda')) {
                $table->renameColumn('preco_venda', 'valor');
            }
        });
    }
};
