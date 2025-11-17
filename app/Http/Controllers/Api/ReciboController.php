<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Recibo;
use Illuminate\Http\Request;

class ReciboController extends Controller
{
    /**
     * Display a listing of the recibos (ativos, com filtros).
     */
    public function index(Request $request)
    {
        // Validações opcionais
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'page' => 'integer|min:1',
        ]);

        // Query base: Apenas recibos ATIVOS (não retificados/anulados)
        $query = Recibo::query()
            ->where('retificado', false)
            ->where('anulado', false)
            ->with(['cliente', 'user']); // Eager load para evitar N+1

        // Filtros por data (similar ao Livewire)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = $request->start_date . ' 00:00:00';
            $end = $request->end_date . ' 23:59:59';
            $query->whereBetween('created_at', [$start, $end]);
        }

        // Paginação (padrão 15, como no Livewire)
        $recibos = $query->orderByDesc('created_at')->paginate(15);

        // Stats (corrigido: datas calculadas dentro das closures para evitar undefined vars)
        $baseQuery = Recibo::where('retificado', false)->where('anulado', false);
        $somaValores = $baseQuery->clone()
            ->when($request->filled('start_date') && $request->filled('end_date'), function ($q) use ($request) {
                $start = $request->start_date . ' 00:00:00';
                $end = $request->end_date . ' 23:59:59';
                $q->whereBetween('created_at', [$start, $end]);
            })->sum('valor');

        $recibosDinheiro = $baseQuery->clone()
            ->where('metodo_pagamento', 'dinheiro')
            ->when($request->filled('start_date') && $request->filled('end_date'), function ($q) use ($request) {
                $start = $request->start_date . ' 00:00:00';
                $end = $request->end_date . ' 23:59:59';
                $q->whereBetween('created_at', [$start, $end]);
            })->count();

        $recibosMulticaixa = $baseQuery->clone()
            ->where('metodo_pagamento', 'multicaixa')
            ->when($request->filled('start_date') && $request->filled('end_date'), function ($q) use ($request) {
                $start = $request->start_date . ' 00:00:00';
                $end = $request->end_date . ' 23:59:59';
                $q->whereBetween('created_at', [$start, $end]);
            })->count();

        $totalRecibos = $recibos->total();

        // Retorno JSON (estrutura similar ao Livewire para compatibilidade)
        return response()->json([
            'data' => $recibos->items(), // Ou $recibos para full pagination
            'current_page' => $recibos->currentPage(),
            'last_page' => $recibos->lastPage(),
            'per_page' => $recibos->perPage(),
            'total' => $totalRecibos,
            'stats' => [
                'total_recibos' => $totalRecibos,
                'soma_valores' => number_format($somaValores, 2, ',', '.'),
                'recibos_dinheiro' => $recibosDinheiro,
                'recibos_multicaixa' => $recibosMulticaixa,
            ],
        ]);
    }

    // Adicione outros métodos se precisar (ex: show, store, update, destroy)
}