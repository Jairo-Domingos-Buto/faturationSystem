<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Cria o administrador apenas se não existir
        if (!User::where('email', 'admin@sistema.com')->exists()) {
            User::create([
                'name' => 'Administrador',
                'email' => 'admin@sistema.com',
                'password' => Hash::make('12345678'),
                'typeUser' => 'admin',
            ]);
        }

        // Cria outros usuários apenas se a tabela estiver vazia
        if (User::count() <= 1) {
            User::factory(5)->create(['typeUser' => 'atendente']);
        }
    }
}
