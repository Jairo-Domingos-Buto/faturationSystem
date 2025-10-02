<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaftExport extends Model
{
    protected $table = 'saft_exports';

    protected $fillable = [
        'periodo_de',
        'periodo_ate',
        'caminho_arquivo',
        'status',
        'created_by',
        'notas',
    ];

    protected $casts = [
        'periodo_de' => 'date',
        'periodo_ate' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
