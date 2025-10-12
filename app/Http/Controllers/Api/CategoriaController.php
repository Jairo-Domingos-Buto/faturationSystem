<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Categorias",
 *     description="Gestão de categorias no sistema de faturação"
 * )
 */
class CategoriaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/categorias",
     *     tags={"Categorias"},
     *     summary="Listar todas as categorias",
     *     @OA\Response(response=200, description="Lista de categorias retornada com sucesso")
     * )
     */
    public function index()
    {
        return response()->json(Categoria::all(), 200);
    }

    /**
     * @OA\Post(
     *     path="/api/categorias",
     *     tags={"Categorias"},
     *     summary="Criar nova categoria",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nome", "descricao"},
     *             @OA\Property(property="nome", type="string", example="Eletrônicos"),
     *             @OA\Property(property="descricao", type="string", example="Produtos eletrônicos em geral")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Categoria criada com sucesso")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:255',
        ]);

        $categoria = Categoria::create($validated);
        return response()->json($categoria, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/categorias/{id}",
     *     tags={"Categorias"},
     *     summary="Mostrar categoria específica",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Categoria retornada com sucesso")
     * )
     */
    public function show(Categoria $categoria)
    {
        return response()->json($categoria, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/categorias/{id}",
     *     tags={"Categorias"},
     *     summary="Atualizar categoria existente",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nome", type="string", example="Eletrodomésticos"),
     *             @OA\Property(property="descricao", type="string", example="Produtos para casa e cozinha")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Categoria atualizada com sucesso")
     * )
     */
    public function update(Request $request, Categoria $categoria)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:255',
        ]);

        $categoria->update($validated);
        return response()->json($categoria, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/categorias/{id}",
     *     tags={"Categorias"},
     *     summary="Remover categoria",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Categoria removida com sucesso")
     * )
     */
    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        return response()->json(['message' => 'Categoria removida com sucesso'], 200);
    }
}
