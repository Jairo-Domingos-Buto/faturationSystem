<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nome' => 'Eletrônicos'],
            ['nome' => 'Vestuário'],
            ['nome' => 'Alimentos'],
            ['nome' => 'Bebidas'],
            ['nome' => 'Higiene'],
            ['nome' => 'Limpeza'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}
