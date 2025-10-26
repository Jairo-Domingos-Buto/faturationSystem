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
        $produtos = Produto::with(['categoria', 'fornecedor', 'imposto', 'motivoIsencao'])->get();
        return response()->json($produtos);
    }

    /**
     * @OA\Post(
     *     path="/api/produtos",
     *     tags={"Produtos"},
     *     summary="Criar produto com imposto ou motivo de isenção",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"descricao", "categoria_id", "fornecedor_id", "preco_compra", "preco_venda"},
     *             @OA\Property(property="descricao", type="string", example="Produto exemplo"),
     *             @OA\Property(property="categoria_id", type="integer", example=1),
     *             @OA\Property(property="fornecedor_id", type="integer", example=2),
     *             @OA\Property(property="codigo_barras", type="string", example="1234567890123"),
     *             @OA\Property(property="preco_compra", type="number", format="float", example="100.50"),
     *             @OA\Property(property="preco_venda", type="number", format="float", example="150.75"),
     *             @OA\Property(property="data_validade", type="string", format="date", example="2025-12-31"),
     *             @OA\Property(property="estoque", type="integer", example=50),
     *             @OA\Property(property="imposto_id", type="integer", example=1, description="Obrigatório se o produto tiver imposto"),
     *             @OA\Property(property="motivo_isencaos_id", type="integer", example=2, description="Obrigatório se o produto for isento de imposto")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Produto criado com sucesso"),
     *     @OA\Response(response=422, description="Erro de validação fiscal: imposto ou isenção obrigatória")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'descricao' => 'required|string|max:255',
            'categoria_id' => 'required|integer|exists:categorias,id',
            'fornecedor_id' => 'required|integer|exists:fornecedores,id',
            'codigo_barras' => 'nullable|string|max:50|unique:produtos',
            'preco_compra' => 'required|numeric|min:0',
            'preco_venda' => 'required|numeric|min:0',
            'data_validade' => 'nullable|date',
            'estoque' => 'nullable|integer|min:0',
            'imposto_id' => 'nullable|exists:impostos,id',
            'motivo_isencaos_id' => 'nullable|exists:motivo_isencaos,id',
        ]);

        // --- Lógica Fiscal ---
        if (empty($validated['imposto_id']) && empty($validated['motivo_isencaos_id'])) {
            return response()->json([
                'error' => 'Deve indicar um imposto ou um motivo de isenção.'
            ], 422);
        }

        if (!empty($validated['imposto_id']) && !empty($validated['motivo_isencaos_id'])) {
            return response()->json([
                'error' => 'Não pode indicar imposto e motivo de isenção ao mesmo tempo.'
            ], 422);
        }

        $produto = Produto::create($validated);
        return response()->json($produto->load(['imposto', 'motivoIsencao']), 201);
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
        return response()->json($produto->load(['categoria', 'fornecedor', 'imposto', 'motivoIsencao']));
    }

    /**
     * @OA\Put(
     *     path="/api/produtos/{id}",
     *     tags={"Produtos"},
     *     summary="Atualizar produto com imposto ou motivo de isenção",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="descricao", type="string", example="Produto atualizado"),
     *             @OA\Property(property="preco_venda", type="number", example="250.75"),
     *             @OA\Property(property="imposto_id", type="integer", example=1),
     *             @OA\Property(property="motivo_isencaos_id", type="integer", example=null)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Produto atualizado com sucesso"),
     *     @OA\Response(response=422, description="Erro fiscal: imposto ou isenção obrigatória")
     * )
     */
    public function update(Request $request, Produto $produto)
    {
        $data = $request->validate([
            'descricao' => 'sometimes|string|max:255',
            'categoria_id' => 'sometimes|integer|exists:categorias,id',
            'fornecedor_id' => 'sometimes|integer|exists:fornecedores,id',
            'codigo_barras' => 'nullable|string|max:50|unique:produtos,codigo_barras,' . $produto->id,
            'preco_compra' => 'sometimes|numeric|min:0',
            'preco_venda' => 'sometimes|numeric|min:0',
            'data_validade' => 'nullable|date',
            'estoque' => 'nullable|integer|min:0',
            'imposto_id' => 'nullable|exists:impostos,id',
            'motivo_isencaos_id' => 'nullable|exists:motivo_isencaos,id',
        ]);

        if (empty($data['imposto_id']) && empty($data['motivo_isencaos_id'])) {
            return response()->json([
                'error' => 'Deve indicar um imposto ou um motivo de isenção.'
            ], 422);
        }

        if (!empty($data['imposto_id']) && !empty($data['motivo_isencaos_id'])) {
            return response()->json([
                'error' => 'Não pode indicar imposto e motivo de isenção ao mesmo tempo.'
            ], 422);
        }

        $produto->update($data);
        return response()->json($produto->load(['imposto', 'motivoIsencao']), 200);
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