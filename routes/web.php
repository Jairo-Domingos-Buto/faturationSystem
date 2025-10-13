<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/categorias', function () {
    return view('Admin/categorias');
});