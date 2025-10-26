<?php

namespace Database\Seeders;

use App\Models\Recibo;
use Illuminate\Database\Seeder;

class ReciboSeeder extends Seeder
{
    public function run(): void
    {
        Recibo::factory(15)->create(); // Cria 15 recibos aleatórios
    }
}
