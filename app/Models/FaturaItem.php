<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaturaItem extends Model
{
    protected $fillable = [
        'fatura_id',
        'produto_id',
        'descricao',
        'servico_id',
        'codigo_barras',
        'quantidade',
        'preco_unitario',
        'subtotal',
        'taxa_iva',
        'valor_iva',
        'total',
        'imposto_id',
        'motivo_isencaos_id',
    ];

    protected $casts = [
        'quantidade' => 'integer',
        'preco_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'taxa_iva' => 'decimal:2',
        'valor_iva' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function fatura()
    {
        return $this->belongsTo(Fatura::class);
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    public function imposto()
    {
        return $this->belongsTo(Imposto::class);
    }

    public function motivoIsencao()
    {
        return $this->belongsTo(MotivoIsencao::class, 'motivo_isencaos_id');
    }

    public function servico()
    {
        return $this->belongsTo(Servico::class);
    }
}
