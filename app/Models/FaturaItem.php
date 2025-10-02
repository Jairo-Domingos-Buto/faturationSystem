<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaturaItem extends Model
{
    protected $table = 'fatura_items';

    protected $fillable = [
        'fatura_id',
        'itemable_id',
        'itemable_type',
        'descricao',
        'quantidade',
        'preco_unit',
        'desconto',
        'taxa_imposto',
        'total_item',
    ];

    public function fatura()
    {
        return $this->belongsTo(Fatura::class);
    }

    public function itemable()
    {
        return $this->morphTo();
    }
}
