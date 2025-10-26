<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ClienteSeeder::class,
            FornecedorSeeder::class,
            CategoriaSeeder::class,
            ProdutoSeeder::class,
            FaturaSeeder::class,
            ReciboSeeder::class,
        ]);
    }
}
