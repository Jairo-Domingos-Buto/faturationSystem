<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeckupLog extends Model
{
    protected $table = 'beckup_logs';

    protected $fillable = [
        'nome_arquivo',
        'disco',
        'tamanho',
        'status',
        'criado_por',
        'observacoes',
    ];

    /**
     * Usuário que criou o backup.
     */
    public function criador()
    {
        return $this->belongsTo(User::class, 'criado_por');
    }
}
