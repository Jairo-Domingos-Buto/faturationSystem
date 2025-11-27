<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('faturas', function (Blueprint $table) {
            // Identifica se é Fatura (FT), Fatura-Recibo (FR) ou Proforma (FP)
            $table->string('tipo_documento', 5)->default('FT')->after('numero')->index();

            // Para Fatura-Recibo: Dinheiro, TPA, etc.
            $table->string('metodo_pagamento')->nullable()->after('total');

            // Para Fatura e Proforma
            $table->date('data_vencimento')->nullable()->after('data_emissao');

            // Para rastrear se uma Proforma já virou Fatura
            $table->boolean('convertida')->default(false)->after('estado');
        });

        // Atualizar o ENUM de estado para incluir 'convertida' (caso use DB::statement para enum nativo)
        // Se o seu enum for string no Laravel, não precisa mexer no banco, apenas na validação.
        // Mas se for ENUM nativo do MySQL:
        DB::statement("ALTER TABLE faturas MODIFY COLUMN estado ENUM('rascunho','emitida','paga','anulada','convertida') DEFAULT 'emitida'");
    }

    public function down()
    {
        Schema::table('faturas', function (Blueprint $table) {
            $table->dropColumn(['tipo_documento', 'metodo_pagamento', 'data_vencimento', 'convertida']);
        });
    }
};
