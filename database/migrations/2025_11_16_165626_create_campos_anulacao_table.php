<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('faturas', function (Blueprint $table) {
            // Status de anulação
            $table->boolean('anulada')->default(false)->after('retificada');
            
            // Data e hora da anulação
            $table->timestamp('data_anulacao')->nullable()->after('anulada');
            
            // Motivo da anulação
            $table->text('motivo_anulacao')->nullable()->after('data_anulacao');
            
            // Usuário que anulou
            $table->foreignId('anulada_por_user_id')
                ->nullable()
                ->after('motivo_anulacao')
                ->constrained('users')
                ->onDelete('set null');
        });

        Schema::table('recibos', function (Blueprint $table) {
            $table->boolean('anulado')->default(false)->after('retificado');
            $table->timestamp('data_anulacao')->nullable()->after('anulado');
            $table->text('motivo_anulacao')->nullable()->after('data_anulacao');
            $table->foreignId('anulado_por_user_id')
                ->nullable()
                ->after('motivo_anulacao')
                ->constrained('users')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('faturas', function (Blueprint $table) {
            $table->dropForeign(['anulada_por_user_id']);
            $table->dropColumn([
                'anulada',
                'data_anulacao',
                'motivo_anulacao',
                'anulada_por_user_id'
            ]);
        });

        Schema::table('recibos', function (Blueprint $table) {
            $table->dropForeign(['anulado_por_user_id']);
            $table->dropColumn([
                'anulado',
                'data_anulacao',
                'motivo_anulacao',
                'anulado_por_user_id'
            ]);
        });
    }
};