<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdutoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('produtos')->insert([
            [
                'descricao' => 'Computador Portátil HP',
                'codigo_barras' => '7894561230012',
                'estoque' => 15,
                'preco_compra' => 250000,
                'preco_venda' => 300000,
                'fornecedor_id' => 1,
                'categoria_id' => 1,
                'imposto_id' => 1,
                'motivo_isencaos_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
