<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImpressaoController;




Route::get('/login',function(){
    return view('auth.login');
});
Route::get('/register', function () {
  return view('auth.register');
});
Route::get('/forgetPassword', function () {
  return view('auth.forgetPassword');
});


Route::get('/', function () {
    return redirect('/login');
});
Route::get('/dashboard', function () {
    return view('Admin/dashboard');
});
Route::get('/clientes', function () {
    return view('Admin/clientes');
});
Route::get('/fornecedores', function () {
    return view('Admin/fornecedores');
});
Route::get('/usuarios', function () {
    return view('Admin/gerirUsers');
});
Route::get('/produtos', function () {
    return view('Admin/produtos');
});
Route::get('/servicos', function () {
    return view('Admin/servicos');
});
Route::get('/impostos', function () {
    return view('Admin/impostos');
});
Route::get('/isencao', function () {
    return view('Admin/isencao');
});
Route::get('/categoria', function () {
    return view('Admin/categoria');
});
/* Faturas e pontos de venda */

Route::get('/faturas', function(){
  return view('Admin/fatura');
});
Route::get('/faturas-recibo', function () {
  return view('Admin/fatura-recibo');
});
Route::get('/recibos', function () {
  return view('Admin/recibo');
});
Route::get('/pov', function () {
  return view('Admin/pov');
});
Route::get('/configuracoes', function () {
  return view('Admin.configuracoes');
});

Route::get('/admin/impressao/servicos', [ImpressaoController::class, 'servicos'])->name('impressao.servicos');
Route::get('/admin/impressao/produtos', [ImpressaoController::class, 'produtos'])->name('imprimir.produtos');

// routes/web.php
use App\Http\Controllers\pdfController;

Route::get('/fatura/download', [pdfController::class, 'downloadFatura'])
    ->name('fatura.download');
