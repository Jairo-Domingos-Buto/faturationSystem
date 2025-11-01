<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FornecedorSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('fornecedores')->insert([
            [
                'nome' => 'Fornecedor Global',
                'nif' => '540987654',
                'telefone' => '+244923456789',
                'email' => 'contato@fornecedorglobal.com',
                'localizacao' => 'Viana',
                'provincia' => 'Luanda',
                'cidade' => 'Viana',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
