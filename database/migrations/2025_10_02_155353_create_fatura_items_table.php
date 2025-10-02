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
        Schema::create('fatura_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('fatura_id')->constrained('faturas')->cascadeOnDelete();
        $table->morphs('itemable'); // permite produto ou servico
        $table->string('descricao');
        $table->integer('quantidade')->default(1);
        $table->decimal('preco_unit', 15, 2);
        $table->decimal('desconto', 15, 2)->default(0);
        $table->decimal('taxa_imposto', 8, 2)->default(0); // percent
        $table->decimal('total_item', 15, 2);
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fatura_items');
    }
};
