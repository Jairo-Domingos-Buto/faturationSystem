<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $table = 'produtos';

    protected $fillable = [
        'descricao',
        'categoria_id',
        'fornecedor_id',
        'codigo_barras',
        'preco_compra',
        'preco_venda',
        'data_validade',
        'estoque',
        'imposto_id',
        'motivo_isencaos_id',
    ];

    protected $casts = [
        'preco_compra' => 'decimal:2',
        'preco_venda' => 'decimal:2',
        'data_validade' => 'date',
        'estoque' => 'integer',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class);
    }

    public function imposto()
    {
        return $this->belongsTo(Imposto::class);
    }

    public function motivoIsencao()
    {
        return $this->belongsTo(MotivoIsencao::class,'motivo_isencaos_id');
    }
}