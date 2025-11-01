<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            DadosEmpresaSeeder::class,
            CategoriaSeeder::class,
            FornecedorSeeder::class,
            ClienteSeeder::class,
            ImpostoSeeder::class,
            MotivoIsencaoSeeder::class,
            ProdutoSeeder::class,
            ServicoSeeder::class,
            FaturaSeeder::class,
            ReciboSeeder::class,

        ]);
    }
}
