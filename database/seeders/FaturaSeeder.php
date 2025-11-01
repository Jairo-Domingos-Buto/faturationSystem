<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FaturaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('faturas')->insert([
            [
                'numero' => 'FT2025001',
                'cliente_id' => 1,
                'user_id' => 1,
                'subtotal' => 300000,
                'total_impostos' => 42000,
                'total' => 342000,
                'estado' => 'emitida',
                'data_emissao' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
