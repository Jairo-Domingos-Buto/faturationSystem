<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Perfil extends Model
{
     use HasFactory;

         protected $table = 'profiles';


    protected $fillable = [
        'user_id',
        'telefone',
        'bi',
        'data_nascimento',
        'genero',
        'endereco',
        'descricao',
        'foto',
    ];

    // Relacionamento com o usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}