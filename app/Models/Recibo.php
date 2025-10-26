<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recibo extends Model
{
    use SoftDeletes;

    protected $table = 'recibos';

    protected $fillable = [
        'numero',
        'fatura_id',
        'cliente_id',
        'user_id',
        'data_emissao',
        'valor',
        'metodo_pagamento',
        'observacoes',
    ];

    protected $casts = [
        'data_emissao' => 'date',
        'valor' => 'decimal:2',
    ];

    public function fatura()
    {
        return $this->belongsTo(Fatura::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
