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
        'anulada',              // ✅ NOVO
        'data_anulacao',        // ✅ NOVO
        'motivo_anulacao',      // ✅ NOVO
        'anulada_por_user_id',  // ✅ NOVO
    ];

    protected $casts = [
        'data_emissao' => 'datetime',
        'data_retificacao' => 'datetime',
        'data_anulacao' => 'datetime',  // ✅ NOVO
        'retificada' => 'boolean',
        'anulada' => 'boolean',          // ✅ NOVO
        'subtotal' => 'decimal:2',
        'total_impostos' => 'decimal:2',
        'total' => 'decimal:2',
    ];
    // Relacionamentos existentes
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

    public function faturaOriginal()
    {
        return $this->belongsTo(Fatura::class, 'fatura_original_id');
    }

    public function faturaRetificacao()
    {
        return $this->belongsTo(Fatura::class, 'fatura_retificacao_id');
    }

    // ✅ NOVO: Relacionamento com usuário que anulou
    public function anuladaPor()
    {
        return $this->belongsTo(User::class, 'anulada_por_user_id');
    }

    // Scopes existentes
    public function scopeAtivas($query)
    {
        return $query->where('retificada', false)
            ->where('anulada', false);  // ✅ NOVO
    }

    public function scopeRetificadas($query)
    {
        return $query->where('retificada', true);
    }

    // ✅ NOVO: Scope para anuladas
    public function scopeAnuladas($query)
    {
        return $query->where('anulada', true);
    }

    // ✅ NOVO: Scope para Notas de Crédito (retificadas + anuladas)
    public function scopeNotasCredito($query)
    {
        return $query->where(function ($q) {
            $q->where('retificada', true)
                ->orWhere('anulada', true);
        });
    }

    // Accessors existentes
    public function getPodeSerRetificadaAttribute()
    {
        return ! $this->retificada
            && ! $this->anulada  // ✅ NOVO: Não pode retificar se anulada
            && $this->estado !== 'cancelada';
    }

    public function getIsRetificacaoAttribute()
    {
        return ! is_null($this->fatura_original_id);
    }

    // ✅ NOVO: Accessor - Pode ser anulada?
    public function getPodeSerAnuladaAttribute()
    {
        return ! $this->anulada && ! $this->retificada;
    }

    // ✅ NOVO: Accessor - Status da fatura
    public function getStatusAttribute()
    {
        if ($this->anulada) {
            return 'ANULADA';
        }
        if ($this->retificada) {
            return 'RETIFICADA';
        }

        return strtoupper($this->estado);
    }

    public function getEmissaoAttribute()
    {
        return $this->data_emissao ? $this->data_emissao->format('d/m/Y') : '-';
    }

    // Métodos existentes
    public function marcarComoRetificada($novaFaturaId, $motivo = null)
    {
        $this->update([
            'retificada' => true,
            'fatura_retificacao_id' => $novaFaturaId,
            'data_retificacao' => now(),
            'motivo_retificacao' => $motivo,
        ]);
    }

    // ✅ NOVO: Método para marcar como anulada
    public function marcarComoAnulada($motivo = null)
    {
        $this->update([
            'anulada' => true,
            'data_anulacao' => now(),
            'motivo_anulacao' => $motivo,
            'anulada_por_user_id' => auth()->id(),
            'estado' => 'anulada',
        ]);
    }

    // ✅ NOVO: Método para devolver estoque
    public function devolverEstoque()
    {
        foreach ($this->items as $item) {
            $produto = Produto::find($item->produto_id);
            if ($produto) {
                $produto->increment('estoque', $item->quantidade);
            }
        }
    }
}
