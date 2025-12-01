<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('fatura_items', function (Blueprint $table) {
            $table->foreignId('servico_id')->nullable()->after('produto_id')->constrained('servicos')->nullOnDelete();
        });

        Schema::table('recibo_items', function (Blueprint $table) {
            $table->foreignId('servico_id')->nullable()->after('produto_id')->constrained('servicos')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('fatura_items', function (Blueprint $table) {
            $table->dropForeign(['servico_id']);
            $table->dropColumn('servico_id');
        });

        Schema::table('recibo_items', function (Blueprint $table) {
            $table->dropForeign(['servico_id']);
            $table->dropColumn('servico_id');
        });
    }
};
