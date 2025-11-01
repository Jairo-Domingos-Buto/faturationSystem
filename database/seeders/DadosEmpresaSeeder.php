<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DadosEmpresaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('Dados_empresa')->insert([
            'name' => 'TechGest Lda',
            'nif' => '5001234567',
            'telefone' => '+244912345678',
            'email' => 'contacto@techgest.co.ao',
            'website' => 'https://techgest.co.ao',
            'rua' => 'Rua 12 de Julho',
            'edificio' => 'Ed. Central',
            'cidade' => 'Luanda',
            'municipio' => 'Talatona',
            'iban' => 'AO06004300000012345678901',
            'nomeDoBanco' => 'Banco BAI',
            'regime' => 'Geral',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
