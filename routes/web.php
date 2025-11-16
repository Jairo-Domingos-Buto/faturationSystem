<?php

use App\Http\Controllers\ImpressaoController;
use App\Http\Controllers\NotaCreditoController;
use App\Http\Controllers\pdfController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
*/
Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('login');
    Route::view('/register', 'auth.register')->name('register');
    Route::view('/forgetPassword', 'auth.forgetPassword')->name('forgetPassword');
    Route::view('/resetPassword', 'auth.reset-password')->name('resetPassword');
    Route::view('/confirmPassword', 'auth.confirm-password')->name('confirmPassword');
});

// LOGOUT
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login');
})->name('logout');

/*
|--------------------------------------------------------------------------
| Rotas Autenticadas
|--------------------------------------------------------------------------
*/

// Rota dashboard simplificada - apenas para fallback
Route::get('/dashboard', function () {
    $user = auth()->user();
    $tipo = $user->typeUser->value;

    return match ($tipo) {
        'admin' => redirect()->route('admin.dashboard'),
        'atendente' => redirect('/atendente/home'),
        'balconista' => redirect('/balcao/home'),
        default => view('User.dashboard'),
    };
})->name('dashboard');

// Grupo apenas para administradores
Route::middleware(['type:admin'])
    ->prefix('admin')
    ->name('admin.')  // ✅ ADICIONE O PONTO AQUI
    ->group(function () {
        Route::view('/dashboard', 'Admin.dashboard')->name('dashboard');
        Route::view('/clientes', 'Admin.clientes')->name('clientes');
        Route::view('/fornecedores', 'Admin.fornecedores')->name('fornecedores');
        Route::view('/usuarios', 'Admin.gerirUsers')->name('usuarios');
        Route::view('/produtos', 'Admin.produtos')->name('produtos');
        Route::view('/servicos', 'Admin.servicos')->name('servicos');
        Route::view('/impostos', 'Admin.impostos')->name('impostos');
        Route::view('/isencao', 'Admin.isencao')->name('isencao');
        Route::view('/notas-credito', 'Admin.nota-credito')->name('notas-credito');
        Route::view('/categoria', 'Admin.categoria')->name('categoria');
        Route::view('/faturas', 'Admin.fatura')->name('faturas');
        Route::view('/faturas-recibo', 'Admin.fatura-recibo')->name('faturas.recibo');
        Route::view('/recibos', 'Admin.recibo')->name('recibos');
        Route::view('/pov', 'Admin.pov')->name('pov');
        Route::view('/configuracoes', 'Admin.configuracoes')->name('configuracoes');

        Route::get('/impressao/servicos', [ImpressaoController::class, 'servicos'])->name('impressao.servicos');
        Route::get('/impressao/produtos', [ImpressaoController::class, 'produtos'])->name('impressao.produtos');
        /* impressao apartir do ponto de venda */
        Route::get('/fatura/download', [pdfController::class, 'downloadFatura'])->name('fatura.download');

        Route::get('/recibo/download', [pdfController::class, 'downloadRecibo'])->name('recibo.download');

        // Visualizar Nota de Crédito de Fatura
        Route::get('/notas-credito/fatura/{id}', [NotaCreditoController::class, 'visualizarFatura'])
            ->name('notas-credito.fatura');

        // Visualizar Nota de Crédito de Recibo
        Route::get('/notas-credito/recibo/{id}', [NotaCreditoController::class, 'visualizarRecibo'])
            ->name('notas-credito.recibo');

        // Gerar PDF da Nota de Crédito
        Route::get('/notas-credito/pdf/{tipo}/{id}', [NotaCreditoController::class, 'gerarPDF'])
            ->name('notas-credito.pdf');
    });
