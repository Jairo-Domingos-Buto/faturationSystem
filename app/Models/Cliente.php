<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
        'nome',
        'nif',
        'provincia',
        'cidade',
        'localizacao',
        'telefone',
    ];
}
