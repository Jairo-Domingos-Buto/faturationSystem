<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fatura extends Model
{
    protected $table = 'faturas';

    protected $fillable = [
        'numero',
        'cliente_id',
        'user_id',
        'data_emissao',
        'estado',
        'subtotal',
        'total_impostos',
        'total',
        'observacoes',
    ];

    protected $casts = [
        'data_emissao' => 'date',
        'subtotal' => 'decimal:2',
        'total_impostos' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
