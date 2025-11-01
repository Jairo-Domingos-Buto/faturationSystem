<?php

namespace App\Providers;

use App\Enums\UserType;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/dashboard';

    /**
     * Definir redirecionamento dinâmico após login
     */
    protected function redirectTo(): string
    {
        $user = auth()->user();

        if (! $user) {
            return self::HOME;
        }

        // Se for enum PHP 8.1+, acessa via $user->typeUser->value
        $type = is_object($user->typeUser) ? $user->typeUser->value : $user->typeUser;

        return match ($type) {
            UserType::Admin->value => '/admin/dashboard',
            UserType::Atendente->value => '/atendente/home',
            UserType::Balconista->value => '/balcao/home',
            default => self::HOME,
        };
    }

    public function boot(): void
    {
        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });

    }
}
