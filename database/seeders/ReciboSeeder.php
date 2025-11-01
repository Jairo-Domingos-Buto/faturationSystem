<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReciboSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('recibos')->insert([
            [
                'numero' => 'RC2025001',
                'fatura_id' => 1,
                'cliente_id' => 1,
                'user_id' => 1,
                'metodo_pagamento' => 'Transferência Bancária',
                'valor' => 342000,
                'data_emissao' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
