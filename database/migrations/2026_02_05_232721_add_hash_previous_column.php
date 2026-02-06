<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Verifica e adiciona em FATURAS
        if (Schema::hasTable('faturas') && !Schema::hasColumn('faturas', 'hash_previous')) {
            Schema::table('faturas', function (Blueprint $table) {
                $table->text('hash_previous')->nullable()->after('hash');
            });
        }

        // Verifica e adiciona em RECIBOS (Importante para o SAF-T também)
        if (Schema::hasTable('recibos') && !Schema::hasColumn('recibos', 'hash_previous')) {
            Schema::table('recibos', function (Blueprint $table) {
                 $table->text('hash_previous')->nullable()->after('hash');
            });
        }
    }

    public function down(): void
    {
        Schema::table('faturas', function (Blueprint $table) {
            $table->dropColumn('hash_previous');
        });
        Schema::table('recibos', function (Blueprint $table) {
            $table->dropColumn('hash_previous');
        });
    }
};
