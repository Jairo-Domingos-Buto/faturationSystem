<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Adicionar campos em FATURAS
        Schema::table('faturas', function (Blueprint $table) {
            $table->boolean('retificada')->default(false)->after('estado');
            $table->unsignedBigInteger('fatura_original_id')->nullable()->after('retificada');
            $table->unsignedBigInteger('fatura_retificacao_id')->nullable()->after('fatura_original_id');
            $table->timestamp('data_retificacao')->nullable()->after('fatura_retificacao_id');
            $table->text('motivo_retificacao')->nullable()->after('data_retificacao');
            
            // Foreign keys (sem cascade para preservar histórico)
            $table->foreign('fatura_original_id')->references('id')->on('faturas')->onDelete('set null');
            $table->foreign('fatura_retificacao_id')->references('id')->on('faturas')->onDelete('set null');
        });

        // Adicionar campos em RECIBOS
        Schema::table('recibos', function (Blueprint $table) {
            $table->boolean('retificado')->default(false)->after('metodo_pagamento');
            $table->unsignedBigInteger('recibo_original_id')->nullable()->after('retificado');
            $table->unsignedBigInteger('recibo_retificacao_id')->nullable()->after('recibo_original_id');
            $table->timestamp('data_retificacao')->nullable()->after('recibo_retificacao_id');
            $table->text('motivo_retificacao')->nullable()->after('data_retificacao');
            
            $table->foreign('recibo_original_id')->references('id')->on('recibos')->onDelete('set null');
            $table->foreign('recibo_retificacao_id')->references('id')->on('recibos')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('faturas', function (Blueprint $table) {
            $table->dropForeign(['fatura_original_id']);
            $table->dropForeign(['fatura_retificacao_id']);
            $table->dropColumn([
                'retificada',
                'fatura_original_id',
                'fatura_retificacao_id',
                'data_retificacao',
                'motivo_retificacao'
            ]);
        });

        Schema::table('recibos', function (Blueprint $table) {
            $table->dropForeign(['recibo_original_id']);
            $table->dropForeign(['recibo_retificacao_id']);
            $table->dropColumn([
                'retificado',
                'recibo_original_id',
                'recibo_retificacao_id',
                'data_retificacao',
                'motivo_retificacao'
            ]);
        });
    }
};