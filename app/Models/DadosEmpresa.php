<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DadosEmpresa extends Model
{
    protected $table = 'Dados_empresa';

    protected $fillable = [
        'name',
        'nif',
        'telefone',
        'email',
        'website',
        'nomeDoBanco',
        'iban',
        'cidade',
        'rua',
        'edificio',
        'municipio',
        'localizacao',
        'regime',
    ];
}
