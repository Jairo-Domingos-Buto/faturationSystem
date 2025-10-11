<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BackupController;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\FornecedorController;
use App\Http\Controllers\Api\ProdutoController;
use App\Http\Controllers\Api\ServicoController;
use App\Http\Controllers\Api\ImpostoController;
use App\Http\Controllers\Api\MotivoIsencaoController;
use App\Http\Controllers\Api\FaturaController;
use App\Http\Controllers\Api\FaturaItemController;
use App\Http\Controllers\Api\ReciboController;
use App\Http\Controllers\Api\SaftExportController;
use App\Http\Controllers\Api\CategoriaController;



// Gestão de Clientes
Route::apiResource('clientes', ClienteController::class);

// Gestão de Fornecedores
Route::apiResource('fornecedores', FornecedorController::class);

// Gestão de Produtos
Route::apiResource('produtos', ProdutoController::class);

// Gestão de Serviços
Route::apiResource('servicos', ServicoController::class);

// Impostos e Motivos de Isenção
Route::apiResource('impostos', ImpostoController::class);
Route::apiResource('motivos-isencao', MotivoIsencaoController::class);

// Faturação
Route::apiResource('faturas', FaturaController::class);
Route::post('faturas/{fatura}/emitir', [FaturaController::class, 'emitir']);
Route::post('faturas/{fatura}/anular', [FaturaController::class, 'anular']);
Route::get('faturas/{fatura}/pdf', [FaturaController::class, 'gerarPdf']);
Route::get('faturas/saft', [FaturaController::class, 'exportarSaft']);

// Recibos
Route::apiResource('recibos', ReciboController::class);

// Backups
Route::post('backups/run', [BackupController::class, 'run']);
Route::get('backups/logs', [BackupController::class, 'logs']);

// Itens de fatura
Route::apiResource('fatura-itens', FaturaItemController::class);

// Categorias
Route::apiResource('categorias', CategoriaController::class);

// SAFT export
Route::get('saft', [SaftExportController::class, 'index']);
Route::post('saft/export', [SaftExportController::class, 'export']);
Route::get('saft/download/{filename}', [SaftExportController::class, 'download'])->where('filename', '.*');
Route::delete('saft/{filename}', [SaftExportController::class, 'destroy'])->where('filename', '.*');
