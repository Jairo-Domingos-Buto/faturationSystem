<?php

namespace Database\Seeders;

use App\Models\Fatura;
use Illuminate\Database\Seeder;

class FaturaSeeder extends Seeder
{
    public function run(): void
    {
        Fatura::factory(30)->create(); // Cria 30 faturas aleatórias
    }
}
