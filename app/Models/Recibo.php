<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recibo extends Model
{
    use SoftDeletes;

    protected $table = 'recibos';

    // ========================================
    // FILLABLE (CAMPOS PERMITIDOS EM MASSA)
    // ========================================
    protected $fillable = [
        // ===== Campos Originais =====
        'numero',
        'fatura_id',
        'cliente_id',
        'user_id',
        'data_emissao',
        'valor',
        'metodo_pagamento',
        'observacoes',
        
        // ===== Campos de Retificação =====
        'retificado',
        'recibo_original_id',
        'recibo_retificacao_id',
        'data_retificacao',
        'motivo_retificacao',
        
        // ===== Campos de Anulação =====
        'anulado',
        'data_anulacao',
        'motivo_anulacao',
        'anulado_por_user_id',
    ];

    // ========================================
    // CASTS (CONVERSÃO AUTOMÁTICA DE TIPOS)
    // ========================================
    protected $casts = [
        'data_emissao' => 'datetime',
        'data_retificacao' => 'datetime',
        'data_anulacao' => 'datetime',
        'retificado' => 'boolean',
        'anulado' => 'boolean',
        'valor' => 'decimal:2',
    ];

    // ========================================
    // RELACIONAMENTOS BÁSICOS
    // ========================================
    
    /**
     * Fatura associada ao recibo (se houver)
     */
    public function fatura()
    {
        return $this->belongsTo(Fatura::class);
    }

    /**
     * Cliente que recebeu o recibo
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Usuário que emitiu o recibo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Items (produtos) do recibo
     */
    public function items()
    {
        return $this->hasMany(ReciboItem::class);
    }

    // ========================================
    // RELACIONAMENTOS DE RETIFICAÇÃO
    // ========================================
    
    /**
     * Recibo original que foi retificado (quando este é a retificação)
     * 
     * Uso:
     * $reciboRetificacao = Recibo::find(50);
     * $original = $reciboRetificacao->reciboOriginal;
     * echo $original->numero; // RC-0015
     */
    public function reciboOriginal()
    {
        return $this->belongsTo(Recibo::class, 'recibo_original_id');
    }

    /**
     * Novo recibo criado como retificação (quando este foi retificado)
     * 
     * Uso:
     * $reciboOriginal = Recibo::find(15);
     * $novo = $reciboOriginal->reciboRetificacao;
     * echo $novo->numero; // RC-0050
     */
    public function reciboRetificacao()
    {
        return $this->belongsTo(Recibo::class, 'recibo_retificacao_id');
    }

    // ========================================
    // RELACIONAMENTO DE ANULAÇÃO
    // ========================================
    
    /**
     * Usuário que anulou o recibo
     * 
     * Uso:
     * $recibo = Recibo::with('anuladoPor')->find(15);
     * if ($recibo->anulado) {
     *     echo "Anulado por: " . $recibo->anuladoPor->name;
     * }
     */
    public function anuladoPor()
    {
        return $this->belongsTo(User::class, 'anulado_por_user_id');
    }

    // ========================================
    // SCOPES (QUERY HELPERS)
    // ========================================
    
    /**
     * Scope: Recibos Ativos (não retificados nem anulados)
     * 
     * Uso:
     * $recibos = Recibo::ativos()->get();
     * $recibos = Recibo::ativos()->where('cliente_id', 10)->paginate(20);
     */
    public function scopeAtivos($query)
    {
        return $query->where('retificado', false)
                     ->where('anulado', false);
    }

    /**
     * Scope: Recibos Retificados (inválidos, foram substituídos)
     * 
     * Uso:
     * $retificados = Recibo::retificados()->get();
     * $totalRetificados = Recibo::retificados()
     *                          ->whereMonth('data_retificacao', now()->month)
     *                          ->count();
     */
    public function scopeRetificados($query)
    {
        return $query->where('retificado', true);
    }

    /**
     * Scope: Recibos Anulados
     * 
     * Uso:
     * $anulados = Recibo::anulados()->get();
     * $anuladosHoje = Recibo::anulados()
     *                      ->whereDate('data_anulacao', today())
     *                      ->count();
     */
    public function scopeAnulados($query)
    {
        return $query->where('anulado', true);
    }

    /**
     * Scope: Notas de Crédito (retificados OU anulados)
     * 
     * Uso:
     * $notasCredito = Recibo::notasCredito()->get();
     * $notasCredito = Recibo::notasCredito()
     *                      ->with(['cliente', 'reciboRetificacao', 'anuladoPor'])
     *                      ->latest()
     *                      ->get();
     */
    public function scopeNotasCredito($query)
    {
        return $query->where(function($q) {
            $q->where('retificado', true)
              ->orWhere('anulado', true);
        });
    }

    // ========================================
    // ACCESSORS (ATRIBUTOS COMPUTADOS)
    // ========================================
    
    /**
     * Accessor: Pode ser retificado?
     * 
     * Regras:
     * - NÃO pode se já foi retificado
     * - NÃO pode se foi anulado
     * - SÓ pode se estiver ativo
     * 
     * Uso na View:
     * @if($recibo->pode_ser_retificado)
     *     <a href="...">Retificar</a>
     * @endif
     */
    public function getPodeSerRetificadoAttribute()
    {
        return !$this->retificado && !$this->anulado;
    }

    /**
     * Accessor: É uma retificação?
     * 
     * Verifica se este recibo é uma retificação de outro
     * 
     * Uso:
     * if ($recibo->is_retificacao) {
     *     echo "Este recibo corrige: " . $recibo->reciboOriginal->numero;
     * }
     */
    public function getIsRetificacaoAttribute()
    {
        return !is_null($this->recibo_original_id);
    }

    /**
     * Accessor: Pode ser anulado?
     * 
     * Regras:
     * - NÃO pode se já foi anulado
     * - NÃO pode se já foi retificado (deve anular a nova versão)
     * 
     * Uso na View:
     * @if($recibo->pode_ser_anulado)
     *     <button onclick="anular()">Anular</button>
     * @endif
     */
    public function getPodeSerAnuladoAttribute()
    {
        return !$this->anulado && !$this->retificado;
    }

    /**
     * Accessor: Status consolidado
     * 
     * Retorna status em texto maiúsculo para exibição
     * 
     * Hierarquia:
     * 1. ANULADO (prioridade máxima)
     * 2. RETIFICADO
     * 3. EMITIDO (padrão)
     * 
     * Uso:
     * <span class="badge">{{ $recibo->status }}</span>
     */
    public function getStatusAttribute()
    {
        if ($this->anulado) return 'ANULADO';
        if ($this->retificado) return 'RETIFICADO';
        return 'EMITIDO';
    }

    /**
     * Accessor: Data de emissão formatada
     * 
     * Retorna data no formato brasileiro ou '-' se nulo
     * 
     * Uso:
     * {{ $recibo->emissao }} // 14/11/2025
     */
    public function getEmissaoAttribute()
    {
        return $this->data_emissao ? $this->data_emissao->format('d/m/Y') : '-';
    }

    // ========================================
    // CAMPOS CALCULADOS (COMPATIBILIDADE)
    // ========================================
    
    /**
     * Accessor: Subtotal (compatibilidade com Fatura)
     * 
     * Recibos não têm breakdown de impostos, então subtotal = valor
     * Permite usar mesma view para faturas e recibos
     */
    public function getSubtotalAttribute()
    {
        return $this->valor;
    }

    /**
     * Accessor: Total Impostos (compatibilidade com Fatura)
     * 
     * Recibos geralmente não calculam impostos separadamente
     */
    public function getTotalImpostosAttribute()
    {
        return 0;
    }

    /**
     * Accessor: Total (compatibilidade com Fatura)
     * 
     * Para recibo, total = valor
     */
    public function getTotalAttribute()
    {
        return $this->valor;
    }

    /**
     * Accessor: Estado (compatibilidade com Fatura)
     * 
     * Recibos sempre são "emitidos" por padrão
     */
    public function getEstadoAttribute()
    {
        if ($this->anulado) return 'anulado';
        if ($this->retificado) return 'retificado';
        return 'emitido';
    }

    // ========================================
    // MÉTODOS PÚBLICOS
    // ========================================
    
    /**
     * Marcar recibo como retificado
     * 
     * Atualiza todos os campos relacionados à retificação
     * de forma atômica (1 UPDATE no banco)
     * 
     * @param int $novoReciboId ID do novo recibo criado
     * @param string|null $motivo Justificativa da retificação
     * 
     * Uso:
     * $reciboOriginal->marcarComoRetificado($novoRecibo->id, 'Correção de valores');
     */
    public function marcarComoRetificado($novoReciboId, $motivo = null)
    {
        $this->update([
            'retificado' => true,
            'recibo_retificacao_id' => $novoReciboId,
            'data_retificacao' => now(),
            'motivo_retificacao' => $motivo,
        ]);
    }

    /**
     * Marcar recibo como anulado
     * 
     * Registra anulação com usuário responsável
     * 
     * @param string|null $motivo Justificativa da anulação
     * 
     * Uso:
     * $recibo->marcarComoAnulado('Cliente cancelou a compra');
     * 
     * Resultado:
     * - anulado = true
     * - data_anulacao = agora
     * - motivo_anulacao = motivo informado
     * - anulado_por_user_id = usuário logado
     */
    public function marcarComoAnulado($motivo = null)
    {
        $this->update([
            'anulado' => true,
            'data_anulacao' => now(),
            'motivo_anulacao' => $motivo,
            'anulado_por_user_id' => auth()->id(),
        ]);
    }

    /**
     * Devolver estoque de todos os produtos do recibo
     * 
     * Itera sobre todos os items e incrementa o estoque
     * de cada produto pela quantidade do item
     * 
     * Uso:
     * // Ao retificar:
     * $reciboOriginal->devolverEstoque(); // Devolve tudo
     * // ... criar novo recibo ...
     * // ... descontar estoque do novo ...
     * 
     * // Ao anular:
     * $recibo->devolverEstoque(); // Devolve tudo, FIM
     * 
     * Proteção:
     * - Verifica se produto existe (if $produto)
     * - Ignora produtos deletados do cadastro
     */
    public function devolverEstoque()
    {
        foreach ($this->items as $item) {
            $produto = Produto::find($item->produto_id);
            if ($produto) {
                $produto->increment('estoque', $item->quantidade);
            }
        }
    }

    // ========================================
    // MÉTODOS DE VALIDAÇÃO
    // ========================================
    
    /**
     * Validar se pode ser retificado
     * 
     * Lança exceção se não puder
     * 
     * @throws \Exception
     */
    public function validarRetificacao()
    {
        if ($this->retificado) {
            throw new \Exception('Este recibo já foi retificado.');
        }

        if ($this->anulado) {
            throw new \Exception('Não é possível retificar um recibo anulado.');
        }
    }

    /**
     * Validar se pode ser anulado
     * 
     * Lança exceção se não puder
     * 
     * @throws \Exception
     */
    public function validarAnulacao()
    {
        if ($this->anulado) {
            throw new \Exception('Este recibo já está anulado.');
        }

        if ($this->retificado) {
            throw new \Exception('Não é possível anular um recibo retificado. Anule a nova versão.');
        }
    }

    // ========================================
    // MÉTODOS AUXILIARES
    // ========================================
    
    /**
     * Obter histórico de alterações do recibo
     * 
     * Retorna array com todas as versões do recibo
     * 
     * @return array
     */
    public function getHistoricoCompleto()
    {
        $historico = [];

        // Se este é uma retificação, busca a original
        if ($this->is_retificacao) {
            $original = $this->reciboOriginal;
            $historico[] = [
                'tipo' => 'original',
                'recibo' => $original,
                'data' => $original->data_emissao,
            ];
        } else {
            // Este é o original
            $historico[] = [
                'tipo' => 'original',
                'recibo' => $this,
                'data' => $this->data_emissao,
            ];
        }

        // Se foi retificado, adiciona a retificação
        if ($this->retificado && $this->reciboRetificacao) {
            $historico[] = [
                'tipo' => 'retificacao',
                'recibo' => $this->reciboRetificacao,
                'data' => $this->data_retificacao,
                'motivo' => $this->motivo_retificacao,
            ];
        }

        // Se foi anulado
        if ($this->anulado) {
            $historico[] = [
                'tipo' => 'anulacao',
                'recibo' => $this,
                'data' => $this->data_anulacao,
                'motivo' => $this->motivo_anulacao,
                'usuario' => $this->anuladoPor,
            ];
        }

        return $historico;
    }

    /**
     * Obter diferenças entre este recibo e sua retificação
     * 
     * @return array|null
     */
    public function getDiferencasRetificacao()
    {
        if (!$this->retificado || !$this->reciboRetificacao) {
            return null;
        }

        $novo = $this->reciboRetificacao;

        return [
            'valor_original' => $this->valor,
            'valor_novo' => $novo->valor,
            'diferenca' => $novo->valor - $this->valor,
            'percentual' => $this->valor > 0 
                ? (($novo->valor - $this->valor) / $this->valor) * 100 
                : 0,
            'cliente_mudou' => $this->cliente_id !== $novo->cliente_id,
            'metodo_pagamento_mudou' => $this->metodo_pagamento !== $novo->metodo_pagamento,
        ];
    }
}