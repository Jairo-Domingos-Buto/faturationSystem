<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotivoIsencao extends Model
{
    protected $table = 'motivo_isencaos';

    protected $fillable = [
        'codigo',
        'razao',
        'descricao',
    ];
}
