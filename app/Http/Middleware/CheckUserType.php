<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserType
{
    public function handle(Request $request, Closure $next, string $type): Response
    {
        // Verifica se o usuário está autenticado
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // ✅ CORREÇÃO: Compara com o valor do enum
        $userType = $user->typeUser->value ?? $user->typeUser;

        if ($userType !== $type) {
            abort(403, 'Acesso negado. Você não tem permissão para acessar esta página.');
        }

        return $next($request);
    }
}