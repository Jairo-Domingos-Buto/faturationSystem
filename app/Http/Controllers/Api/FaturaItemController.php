<?php

namespace App\Http\Controllers\Api;

use App\Models\FaturaItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


/**
 * @OA\Tag(
 *     name="Itens de Fatura",
 *     description="Gestão dos itens dentro de uma fatura"
 * )
 */
class FaturaItemController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/fatura-itens",
     *     tags={"Itens de Fatura"},
     *     summary="Listar itens de faturas",
     *     @OA\Response(response=200, description="Itens listados com sucesso")
     * )
     */
    public function index()
    {
        return response()->json(FaturaItem::all(), 200);
    }

    /**
     * @OA\Post(
     *     path="/api/fatura-itens",
     *     tags={"Itens de Fatura"},
     *     summary="Adicionar item à fatura",
     *     @OA\RequestBody(
     *         @OA\JsonContent(
    *             required={"fatura_id","itemable_id","itemable_type","descricao","quantidade","preco_unit"},
    *             @OA\Property(property="fatura_id", type="integer", example=1),
    *             @OA\Property(property="itemable_id", type="integer", example=10),
    *             @OA\Property(property="itemable_type", type="string", example="App\\Models\\Produto"),
    *             @OA\Property(property="descricao", type="string", example="Produto X"),
    *             @OA\Property(property="quantidade", type="number", example=2),
    *             @OA\Property(property="preco_unit", type="number", example=5000),
    *             @OA\Property(property="desconto", type="number", example=0),
    *             @OA\Property(property="taxa_imposto", type="number", example=0.18),
    *             @OA\Property(property="total_item", type="number", example=10000)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Item adicionado com sucesso")
     * )
     */
    public function store(Request $request)
    {
        $item = FaturaItem::create($request->all());
        return response()->json($item, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/fatura-itens/{id}",
     *     tags={"Itens de Fatura"},
     *     summary="Ver item específico",
     *     @OA\Response(response=200, description="Item retornado com sucesso")
     * )
     */
    public function show(FaturaItem $faturaItem)
    {
        return response()->json($faturaItem, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/fatura-itens/{id}",
     *     tags={"Itens de Fatura"},
     *     summary="Atualizar item da fatura",
     *     @OA\Response(response=200, description="Item atualizado com sucesso")
     * )
     */
    public function update(Request $request, FaturaItem $faturaItem)
    {
        $faturaItem->update($request->all());
        return response()->json($faturaItem, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/fatura-itens/{id}",
     *     tags={"Itens de Fatura"},
     *     summary="Remover item da fatura",
     *     @OA\Response(response=200, description="Item removido com sucesso")
     * )
     */
    public function destroy(FaturaItem $faturaItem)
    {
        $faturaItem->delete();
        return response()->json(['message' => 'Item removido com sucesso'], 200);
    }
}
