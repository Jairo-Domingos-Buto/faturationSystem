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
        'retificado',
        'recibo_original_id',
        'recibo_retificacao_id',
        'data_retificacao',
        'motivo_retificacao',
    ];

    protected $casts = [
        'data_emissao' => 'date',
        'data_retificacao' => 'datetime',
        'retificado' => 'boolean',
        'valor' => 'decimal:2',
    ];

    // Relacionamentos
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

    public function items()
    {
        return $this->hasMany(ReciboItem::class);
    }

    // Relações de retificação
    public function reciboOriginal()
    {
        return $this->belongsTo(Recibo::class, 'recibo_original_id');
    }

    public function reciboRetificacao()
    {
        return $this->belongsTo(Recibo::class, 'recibo_retificacao_id');
    }

    // Scopes
    public function scopeAtivos($query)
    {
        return $query->where('retificado', false);
    }

    public function scopeRetificados($query)
    {
        return $query->where('retificado', true);
    }

    // Accessors
    public function getPodeSerRetificadoAttribute()
    {
        return !$this->retificado;
    }

    public function getIsRetificacaoAttribute()
    {
        return !is_null($this->recibo_original_id);
    }

    public function getEmissaoAttribute()
    {
        return $this->data_emissao ? $this->data_emissao->format('d/m/Y') : '-';
    }

    // Métodos
    public function marcarComoRetificado($novoReciboId, $motivo = null)
    {
        $this->update([
            'retificado' => true,
            'recibo_retificacao_id' => $novoReciboId,
            'data_retificacao' => now(),
            'motivo_retificacao' => $motivo,
        ]);
    }

    // Adicionar campos calculados para compatibilidade
    public function getSubtotalAttribute()
    {
        return $this->valor; // Para compatibilidade com a view
    }

    public function getTotalImpostosAttribute()
    {
        return 0; // Recibos geralmente não têm impostos separados
    }

    public function getTotalAttribute()
    {
        return $this->valor;
    }

    public function getEstadoAttribute()
    {
        return 'emitido'; // Estado padrão para recibos
    }
}