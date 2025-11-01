<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('clientes')->insert([
            [
                'nome' => 'Carlos Manuel',
                'nif' => '900123456',
                'telefone' => '+244912222333',
                'localizacao' => 'Kilamba',
                'cidade' => 'Luanda',
                'provincia' => 'Luanda',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
