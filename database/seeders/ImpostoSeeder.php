<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImpostoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('impostos')->insert([
            ['codigo' => 'IVA', 'descricao' => 'Imposto sobre Valor Acrescentado', 'taxa' => 14, 'created_at' => now(), 'updated_at' => now()],
            ['codigo' => 'IS', 'descricao' => 'Imposto de Selo', 'taxa' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
