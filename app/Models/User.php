<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'users';

    
    protected $fillable = [
        'nome_completo',
        'nome_usuario',
        'telefone',
        'funcao',
        'email',
        'password',
        'nivel_acesso'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
