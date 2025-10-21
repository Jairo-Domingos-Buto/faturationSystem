<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class ImpressaoController extends Controller
{
    public function servicos()
    {
        try {
            // --- Chama a rota /api/servicos internamente (sem HTTP externo) ---
            $request = Request::create('/api/servicos', 'GET');
            $response = app('router')->dispatch($request);

            // Se a rota devolveu erro, loga e mostra mensagem no novo separador
            if ($response->getStatusCode() >= 400) {
                Log::error('Erro ao obter serviços via dispatch interno', [
                    'status' => $response->getStatusCode(),
                    'body' => $response->getContent(),
                ]);
                return response()->view('admin.pdf_error', [
                    'message' => 'Erro ao buscar dados da API de serviços (interno). Código: ' . $response->getStatusCode()
                ], 500);
            }

            // O conteúdo deve ser JSON — decodifica
            $servicos = json_decode($response->getContent(), true);

            if (!is_array($servicos)) {
                $servicos = [];
            }

            $pdf = Pdf::loadView('admin.impservicos', compact('servicos'));

            return $pdf->stream('lista_servicos.pdf');

        } catch (\Exception $e) {
            Log::error('Exceção em ImpressaoController@servicos', ['error' => $e->getMessage()]);
            return response()->view('admin.pdf_error', [
                'message' => 'Erro interno ao gerar PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    // 📦 Imprimir lista de produtos
    public function produtos()
    {
        try {
            // Busca dados da API interna /api/produtos
            $request = Request::create('/api/produtos', 'GET');
            $response = app('router')->dispatch($request);

            if ($response->getStatusCode() >= 400) {
                Log::error('Erro ao buscar produtos', [
                    'status' => $response->getStatusCode(),
                    'body' => $response->getContent(),
                ]);
                return response()->view('admin.pdf_error', [
                    'message' => 'Erro ao buscar dados da API de produtos.'
                ], 500);
            }

            $produtos = json_decode($response->getContent(), true) ?? [];

            $pdf = Pdf::loadView('admin.impprodutos', compact('produtos'));
            return $pdf->stream('lista_produtos.pdf');
        } catch (\Exception $e) {
            Log::error('Erro ao gerar PDF de produtos', ['error' => $e->getMessage()]);
            return response()->view('admin.pdf_error', [
                'message' => 'Erro interno ao gerar PDF de produtos: ' . $e->getMessage()
            ], 500);
        }
    }
}