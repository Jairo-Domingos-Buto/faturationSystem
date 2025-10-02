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
      Schema::create('faturas', function (Blueprint $table) {
        $table->id();
        $table->string('numero')->unique();
        $table->foreignId('cliente_id')->constrained('clientes');
        $table->foreignId('user_id')->constrained('users');
        $table->date('data_emissao');
        $table->enum('estado', ['rascunho','emitida','anulada'])->default('rascunho');
        $table->decimal('subtotal', 15, 2)->default(0);
        $table->decimal('total_impostos', 15, 2)->default(0);
        $table->decimal('total', 15, 2)->default(0);
        $table->text('observacoes')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faturas');
    }
};
