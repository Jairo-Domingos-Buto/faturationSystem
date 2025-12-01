<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    protected $table = 'servicos';

    protected $fillable = [
        'descricao',
        'preco_venda', // Renomeado de 'valor' para manter consistência
        'imposto_id',
        'motivo_isencaos_id',
        'estoque', // Para controle de disponibilidade (ex: horas disponíveis)
    ];

    protected $casts = [
        'preco_venda' => 'decimal:2',
        'estoque' => 'integer',
    ];

    public function imposto()
    {
        return $this->belongsTo(Imposto::class);
    }

    public function motivoIsencao()
    {
        return $this->belongsTo(MotivoIsencao::class, 'motivo_isencaos_id');
    }
}
