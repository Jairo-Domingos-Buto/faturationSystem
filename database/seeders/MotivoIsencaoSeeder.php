<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MotivoIsencaoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('motivo_isencaos')->insert([
            ['codigo' => 'M01', 'descricao' => 'Isento por exportação', 'razao' => 'Exportação de bens', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
