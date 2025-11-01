<?php

namespace App\Enums;

enum UserType: string
{
    case Admin = 'admin';
    case Atendente = 'atendente';
    case Balconista = 'balconista';
}
