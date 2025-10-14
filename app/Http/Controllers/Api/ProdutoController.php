<?php

namespace App\Http\Controllers\Api;

use App\Models\Produto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * @OA\Tag(
 *     name="Produtos",
 *     description="Gestão de produtos no sistema de faturação"
 * )
 */
class ProdutoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/produtos",
     *     tags={"Produtos"},
     *     summary="Listar produtos",
     *     @OA\Response(response=200, description="Lista de produtos retornada")
     * )
     */
    
    public function index()
    {
        $produtos = Produto::with(['categoria', 'fornecedor'])->get();
        return response()->json($produtos);
    }


    /**
     * @OA\Post(
     *     path="/api/produtos",
     *     tags={"Produtos"},
     *     summary="Criar produto",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"descricao", "categoria_id", "fornecedor_id", "codigo_barras", "preco_compra", "preco_venda", "data_validade", "estoque"},
     *             @OA\Property(property="descricao", type="string", example="Produto exemplo"),
     *             @OA\Property(property="categoria_id", type="integer", example=1),
     *             @OA\Property(property="fornecedor_id", type="integer", example=2),
     *             @OA\Property(property="codigo_barras", type="string", example="1234567890123"),
     *             @OA\Property(property="preco_compra", type="number", format="float", example="100.50"),
     *             @OA\Property(property="preco_venda", type="number", format="float", example="150.75"),
     *             @OA\Property(property="data_validade", type="string", format="date", example="2024-12-31"),
     *             @OA\Property(property="estoque", type="integer", example=50)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Produto criado com sucesso")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'descricao' => 'required|string|max:255',
            'categoria_id' => 'required|integer',
            'fornecedor_id' => 'required|integer',
            'codigo_barras' => 'required|string|max:50|unique:produtos',
            'preco_compra' => 'required|numeric',
            'preco_venda' => 'required|numeric',
            'data_validade' => 'required|date',
            'estoque' => 'required|integer',
        ]);

        $produto = Produto::create($validated);

        return response()->json($produto, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/produtos/{id}",
     *     tags={"Produtos"},
     *     summary="Mostrar produto específico",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Produto retornado com sucesso")
     * )
     */
    public function show(Produto $produto)
    {
        return response()->json($produto, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/produtos/{id}",
     *     tags={"Produtos"},
     *     summary="Atualizar produto",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="descricao", type="string", example="Produto atualizado"),
     *             @OA\Property(property="preco_venda", type="number", example="250.75")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Produto atualizado com sucesso")
     * )
     */
    public function update(Request $request, Produto $produto)
    {
        $produto->update($request->all());
        return response()->json($produto, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/produtos/{id}",
     *     tags={"Produtos"},
     *     summary="Remover produto",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Produto removido com sucesso")
     * )
     */
    public function destroy(Produto $produto)
    {
        $produto->delete();
        return response()->json(['message' => 'Produto removido com sucesso'], 200);
    }
}