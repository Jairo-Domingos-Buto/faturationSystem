<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fornecedor extends Model
{
    protected $table = 'fornecedors';

    protected $fillable = [
        'nome',
        'nif',
        'email',
        'telefone',
        'provincia',
        'cidade',
        'localizacao',
    ];
}
