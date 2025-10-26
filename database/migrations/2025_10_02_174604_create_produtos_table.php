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
       Schema::create('produtos', function (Blueprint $table) {
        $table->id();
        $table->string('descricao');
        $table->foreignId('categoria_id')->nullable()->constrained('categorias')->nullOnDelete();
        $table->foreignId('fornecedor_id')->nullable()->constrained('fornecedores')->nullOnDelete();
        $table->string('codigo_barras')->nullable();
        $table->decimal('preco_compra', 15, 2)->default(0);
        $table->decimal('preco_venda', 15, 2)->default(0);
        $table->date('data_validade')->nullable();
        $table->integer('estoque')->default(0);
        $table->unsignedBigInteger('motivo_isencaos_id')->nullable();
        $table->foreign('motivo_isencaos_id')->references('id')->on('motivo_isencaos')->onDelete('set null');
        $table->unsignedBigInteger('imposto_id')->nullable();
        $table->foreign('imposto_id')->references('id')->on('impostos')->onDelete('set null');
        $table->timestamps();
        $table->softDeletes();


        $table->index('codigo_barras');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};