<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categorias')->insert([
            ['nome' => 'Eletrônicos', 'descricao' => 'Produtos eletrônicos em geral', 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Serviços Técnicos', 'descricao' => 'Manutenção e instalação', 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Mobiliário', 'descricao' => 'Itens de escritório', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
