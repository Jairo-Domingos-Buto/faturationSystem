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
        Schema::create('saft_exports', function (Blueprint $table) {
            $table->id();
            $table->date('periodo_de');
            $table->date('periodo_ate');
            $table->string('caminho_arquivo');
            $table->string('status')->default('created');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saft_exports');
    }
};
