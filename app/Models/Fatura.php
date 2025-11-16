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
        'retificada',
        'fatura_original_id',
        'fatura_retificacao_id',
        'data_retificacao',
        'motivo_retificacao',
    ];

    protected $casts = [
        'data_emissao' => 'date',
        'data_retificacao' => 'datetime',
        'retificada' => 'boolean',
        'subtotal' => 'decimal:2',
        'total_impostos' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // Relacionamentos
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
        return $this->hasMany(FaturaItem::class);
    }

    // Relações de retificação
    public function faturaOriginal()
    {
        return $this->belongsTo(Fatura::class, 'fatura_original_id');
    }

    public function faturaRetificacao()
    {
        return $this->belongsTo(Fatura::class, 'fatura_retificacao_id');
    }

    // Scopes
    public function scopeAtivas($query)
    {
        return $query->where('retificada', false);
    }

    public function scopeRetificadas($query)
    {
        return $query->where('retificada', true);
    }

    // Accessors
    public function getPodeSerRetificadaAttribute()
    {
        return !$this->retificada && $this->estado !== 'cancelada';
    }

    public function getIsRetificacaoAttribute()
    {
        return !is_null($this->fatura_original_id);
    }

    public function getEmissaoAttribute()
    {
        return $this->data_emissao ? $this->data_emissao->format('d/m/Y') : '-';
    }

    // Métodos
    public function marcarComoRetificada($novaFaturaId, $motivo = null)
    {
        $this->update([
            'retificada' => true,
            'fatura_retificacao_id' => $novaFaturaId,
            'data_retificacao' => now(),
            'motivo_retificacao' => $motivo,
        ]);
    }
}