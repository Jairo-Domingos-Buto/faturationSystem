<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use App\Enums\UserType;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        /* AUTENTICAÇÃO */
        Fortify::authenticateUsing(function (Request $request) {
            $user = \App\Models\User::where('email', $request->email)->first();

            if (!$user || !\Hash::check($request->password, $user->password)) {
                session()->flash('error', 'As credenciais fornecidas são inválidas.');
                return null;
            }

            // Normaliza o valor do typeUser
            $tipo = strtolower(trim($user->typeUser->value));

            // Valida se pertence aos tipos permitidos
            if (!in_array($tipo, ['admin', 'atendente', 'balconista'])) {
                session()->flash('error', 'Usuário não autorizado a acessar o sistema.');
                return null;
            }

            return $user;
        });

        // ✅ ADICIONE ESTA PARTE - Redirecionamento após login
        Fortify::redirects('login', function () {
            $user = auth()->user();
            
            if (!$user) {
                return '/dashboard';
            }

            // Pega o valor do enum
            $tipo = $user->typeUser->value;

            return match($tipo) {
                'admin' => '/admin/dashboard',
                'atendente' => '/atendente/home',
                'balconista' => '/balcao/home',
                default => '/dashboard',
            };
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());
            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}