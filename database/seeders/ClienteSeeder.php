<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        Cliente::insert([
            [
                'nome' => 'Loja Central',
                'nif' => '500123456',
                'provincia' => 'Luanda',
                'cidade' => 'Viana',
                'localizacao' => 'Rua 12, nº 45',
                'telefone' => '923456789',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Mercado Popular',
                'nif' => '700654321',
                'provincia' => 'Benguela',
                'cidade' => 'Lobito',
                'localizacao' => 'Avenida Principal',
                'telefone' => '926111222',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'SuperMais',
                'nif' => '900112233',
                'provincia' => 'Huíla',
                'cidade' => 'Lubango',
                'localizacao' => 'Bairro Comercial',
                'telefone' => '928333444',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
