<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Imposto extends Model
{
    protected $table = 'impostos';

    protected $fillable = [
        'descricao',
        'taxa',
        'codigo',
    ];
}
