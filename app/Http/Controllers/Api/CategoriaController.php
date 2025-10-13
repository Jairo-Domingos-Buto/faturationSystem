<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Categoria",
 *     description="Endpoints para gestão de categorias de produtos"
 * )
 */
class CategoriaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/categorias",
     *     summary="Listar todas as categorias",
     *     tags={"Categoria"},
     *     @OA\Response(response=200, description="Lista de categorias")
     * )
     */
    public function index()
    {
        return response()->json(Categoria::all(), 200);
    }

    /**
     * @OA\Post(
     *     path="/api/categorias",
     *     summary="Criar nova categoria",
     *     tags={"Categoria"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nome","descricao"},
     *             @OA\Property(property="nome", type="string", example="Informática"),
     *             @OA\Property(property="descricao", type="string", example="Produtos eletrônicos e acessórios.")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Categoria criada com sucesso")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string|max:255',
        ]);

        $categoria = Categoria::create($validated);
        return response()->json($categoria, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/categorias/{id}",
     *     summary="Mostrar categoria específica",
     *     tags={"Categoria"},
     *     @OA\Response(response=200, description="Categoria retornada com sucesso"),
     *     @OA\Response(response=404, description="Categoria não encontrada")
     * )
     */
    public function show($id)
    {
        $categoria = Categoria::find($id);
        if (!$categoria) {
            return response()->json(['message' => 'Categoria não encontrada'], 404);
        }
        return response()->json($categoria, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/categorias/{id}",
     *     summary="Atualizar categoria existente",
     *     tags={"Categoria"},
     *     @OA\Response(response=200, description="Categoria atualizada com sucesso")
     * )
     */
    public function update(Request $request, $id)
    {
        $categoria = Categoria::find($id);
        if (!$categoria) {
            return response()->json(['message' => 'Categoria não encontrada'], 404);
        }

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
     *     summary="Remover categoria",
     *     tags={"Categoria"},
     *     @OA\Response(response=200, description="Categoria excluída com sucesso")
     * )
     */
    public function destroy($id)
    {
        $categoria = Categoria::find($id);
        if (!$categoria) {
            return response()->json(['message' => 'Categoria não encontrada'], 404);
        }

        $categoria->delete();
        return response()->json(['message' => 'Categoria excluída com sucesso'], 200);
    }
}