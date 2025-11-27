<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fatura extends Model
{
    protected $table = 'faturas';

    // ✅ NOVO: Constantes para evitar erros de digitação
    const TIPO_FATURA = 'FT';

    const TIPO_FATURA_RECIBO = 'FR';

    const TIPO_PROFORMA = 'FP';

    protected $fillable = [
        'numero',
        'tipo_documento',       // ✅ NOVO: 'FT', 'FR', 'FP'
        'cliente_id',
        'user_id',
        'data_emissao',
        'data_vencimento',      // ✅ NOVO: Obrigatório para FT e FP
        'metodo_pagamento',     // ✅ NOVO: Obrigatório para FR (Dinheiro, TPA, etc)
        'estado',               // 'rascunho', 'emitida', 'paga', 'anulada', 'convertida'
        'subtotal',
        'total_impostos',
        'total',
        'observacoes',

        // Controle de Retificação
        'retificada',
        'fatura_original_id',
        'fatura_retificacao_id',
        'data_retificacao',
        'motivo_retificacao',

        // Controle de Anulação
        'anulada',
        'data_anulacao',
        'motivo_anulacao',
        'anulada_por_user_id',

        // Controle de Proforma
        'convertida',           // ✅ NOVO: Se a proforma virou fatura
    ];

    protected $casts = [
        'data_emissao' => 'datetime',
        'data_vencimento' => 'date',     // ✅ NOVO
        'data_retificacao' => 'datetime',
        'data_anulacao' => 'datetime',
        'retificada' => 'boolean',
        'anulada' => 'boolean',
        'convertida' => 'boolean',       // ✅ NOVO
        'subtotal' => 'decimal:2',
        'total_impostos' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // --- Relacionamentos ---

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

    public function anuladaPor()
    {
        return $this->belongsTo(User::class, 'anulada_por_user_id');
    }

    // --- Scopes (Filtros) ---

    public function scopeAtivas($query)
    {
        return $query->where('retificada', false)
            ->where('anulada', false)
            ->where('estado', '!=', 'convertida'); // Proformas convertidas não são "ativas" para venda
    }

    // ✅ NOVO: Filtros por Tipo
    public function scopeFaturas($query)
    {
        return $query->where('tipo_documento', self::TIPO_FATURA);
    }

    public function scopeFaturasRecibo($query)
    {
        return $query->where('tipo_documento', self::TIPO_FATURA_RECIBO);
    }

    public function scopeProformas($query)
    {
        return $query->where('tipo_documento', self::TIPO_PROFORMA);
    }

    public function scopeRetificadas($query)
    {
        return $query->where('retificada', true);
    }

    public function scopeAnuladas($query)
    {
        return $query->where('anulada', true);
    }

    public function scopeNotasCredito($query)
    {
        return $query->where(function ($q) {
            $q->where('retificada', true)
                ->orWhere('anulada', true);
        });
    }

    // --- Accessors (Atributos virtuais) ---

    public function getPodeSerRetificadaAttribute()
    {
        return ! $this->retificada
            && ! $this->anulada
            && ! $this->convertida // Proforma não se retifica, se edita ou converte
            && $this->tipo_documento !== self::TIPO_PROFORMA // Proforma não gera NC
            && $this->estado !== 'cancelada';
    }

    public function getIsRetificacaoAttribute()
    {
        return ! is_null($this->fatura_original_id);
    }

    public function getPodeSerAnuladaAttribute()
    {
        return ! $this->anulada
            && ! $this->retificada
            && ! $this->convertida;
    }

    // ✅ NOVO: Nome legível do tipo
    public function getTipoLegivelAttribute()
    {
        return match ($this->tipo_documento) {
            self::TIPO_FATURA => 'Fatura',
            self::TIPO_FATURA_RECIBO => 'Fatura-Recibo',
            self::TIPO_PROFORMA => 'Proforma',
            default => 'Fatura'
        };
    }

    public function getStatusAttribute()
    {
        if ($this->anulada) {
            return 'ANULADA';
        }
        if ($this->retificada) {
            return 'RETIFICADA';
        }
        if ($this->convertida) {
            return 'CONVERTIDA';
        }

        // Mapeamento visual de status
        return match ($this->estado) {
            'paga' => 'PAGA',
            'emitida' => 'EMITIDA',
            'rascunho' => 'RASCUNHO',
            default => strtoupper($this->estado)
        };
    }

    public function getEmissaoAttribute()
    {
        return $this->data_emissao ? $this->data_emissao->format('d/m/Y') : '-';
    }

    // --- Métodos de Negócio ---

    /**
     * ✅ NOVO: Gera o próximo número sequencial (Ex: FT 2024/1)
     */
    public static function gerarProximoNumero($tipo)
    {
        $ano = date('Y');
        // Busca o último documento deste tipo criado neste ano
        $ultimo = self::where('tipo_documento', $tipo)
            ->whereYear('created_at', $ano)
            ->orderBy('id', 'desc')
            ->first();

        if ($ultimo) {
            // Extrai o número da string (Ex: "FT 2024/15" -> pega 15)
            // Assume formato: TIPO ANO/SEQUENCIA
            $partes = explode('/', $ultimo->numero);
            $sequencia = (int) end($partes) + 1;
        } else {
            $sequencia = 1;
        }

        return "{$tipo} {$ano}/{$sequencia}";
    }

    public function marcarComoRetificada($novaFaturaId, $motivo = null)
    {
        $this->update([
            'retificada' => true,
            'fatura_retificacao_id' => $novaFaturaId,
            'data_retificacao' => now(),
            'motivo_retificacao' => $motivo,
            'estado' => 'anulada', // Fiscalmente conta como anulada/substituída
        ]);
    }

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

    // ✅ NOVO: Marcar proforma como convertida em fatura
    public function marcarComoConvertida()
    {
        $this->update([
            'convertida' => true,
            'estado' => 'convertida',
        ]);
    }

    // ✅ ATUALIZADO: Lógica de devolução de estoque
    public function devolverEstoque()
    {
        // Proforma não baixa estoque ao ser criada, logo não devolve ao ser anulada
        if ($this->tipo_documento === self::TIPO_PROFORMA) {
            return;
        }

        foreach ($this->items as $item) {
            $produto = Produto::find($item->produto_id);
            if ($produto) {
                $produto->increment('estoque', $item->quantidade);
            }
        }
    }
}
