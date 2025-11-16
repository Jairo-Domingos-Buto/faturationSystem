<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tabela de items da fatura
        Schema::create('fatura_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fatura_id')->constrained('faturas')->onDelete('cascade');
            $table->foreignId('produto_id')->constrained('produtos')->onDelete('cascade');
            
            $table->string('descricao');
            $table->string('codigo_barras')->nullable();
            $table->integer('quantidade');
            $table->decimal('preco_unitario', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('taxa_iva', 5, 2)->default(0);
            $table->decimal('valor_iva', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            
            $table->foreignId('imposto_id')->nullable()->constrained('impostos')->onDelete('set null');
            $table->foreignId('motivo_isencaos_id')->nullable()->constrained('motivo_isencaos')->onDelete('set null');
            
            $table->timestamps();
        });

        // Tabela de items do recibo
        Schema::create('recibo_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recibo_id')->constrained('recibos')->onDelete('cascade');
            $table->foreignId('produto_id')->constrained('produtos')->onDelete('cascade');
            
            $table->string('descricao');
            $table->string('codigo_barras')->nullable();
            $table->integer('quantidade');
            $table->decimal('preco_unitario', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('taxa_iva', 5, 2)->default(0);
            $table->decimal('valor_iva', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            
            $table->foreignId('imposto_id')->nullable()->constrained('impostos')->onDelete('set null');
            $table->foreignId('motivo_isencaos_id')->nullable()->constrained('motivo_isencaos')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('recibo_items');
        Schema::dropIfExists('fatura_items');
    }
};