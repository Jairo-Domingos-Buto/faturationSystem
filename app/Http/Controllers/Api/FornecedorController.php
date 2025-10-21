<?php

namespace App\Http\Controllers\Api;

use App\Models\Fornecedor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * @OA\Tag(
 *     name="Fornecedores",
 *     description="Gestão de fornecedores no sistema de faturação"
 * )
 */
class FornecedorController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/fornecedores",
     *     tags={"Fornecedores"},
     *     summary="Listar todos os fornecedores",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de fornecedores retornada com sucesso"
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Fornecedor::all(), 200);
    }

    /**
     * @OA\Post(
     *     path="/api/fornecedores",
     *     tags={"Fornecedores"},
     *     summary="Criar um novo fornecedor",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nome"},
     *             @OA\Property(property="nome", type="string", maxLength=255, example="Fornecedor ABC"),
     *             @OA\Property(property="nif", type="string", maxLength=50, example="500100000"),
     *             @OA\Property(property="email", type="string", format="email", example="abc@email.com"),
     *             @OA\Property(property="telefone", type="string", maxLength=50, example="+244923000000"),
     *             @OA\Property(property="provincia", type="string", maxLength=100, example="Luanda"),
     *             @OA\Property(property="cidade", type="string", maxLength=100, example="Luanda"),
     *             @OA\Property(property="localizacao", type="string", maxLength=255, example="Rua 1, Bairro X")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Fornecedor criado com sucesso"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'nif' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:50',
            'provincia' => 'nullable|string|max:100',
            'cidade' => 'nullable|string|max:100',
            'localizacao' => 'nullable|string|max:255',
        ]);

        $fornecedor = Fornecedor::create($validated);

        return response()->json($fornecedor, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/fornecedores/{id}",
     *     tags={"Fornecedores"},
     *     summary="Exibir um fornecedor específico",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do fornecedor",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Fornecedor retornado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Fornecedor não encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        $fornecedor = Fornecedor::find($id);

        if (!$fornecedor) {
            return response()->json(['message' => 'Fornecedor não encontrado'], 404);
        }

        return response()->json($fornecedor, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/fornecedores/{id}",
     *     tags={"Fornecedores"},
     *     summary="Atualizar um fornecedor existente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do fornecedor a ser atualizado",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nome", type="string", maxLength=255, example="Fornecedor Atualizado"),
     *             @OA\Property(property="nif", type="string", maxLength=50, example="500100001"),
     *             @OA\Property(property="email", type="string", format="email", example="novo@email.com"),
     *             @OA\Property(property="telefone", type="string", maxLength=50, example="+244922999999"),
     *             @OA\Property(property="provincia", type="string", maxLength=100, example="Benguela"),
     *             @OA\Property(property="cidade", type="string", maxLength=100, example="Lobito"),
     *             @OA\Property(property="localizacao", type="string", maxLength=255, example="Avenida 4 de Fevereiro")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Fornecedor atualizado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Fornecedor não encontrado"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $fornecedor = Fornecedor::find($id);

        if (!$fornecedor) {
            return response()->json(['message' => 'Fornecedor não encontrado'], 404);
        }

        $validated = $request->validate([
            'nome' => 'sometimes|string|max:255',
            'nif' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:50',
            'provincia' => 'nullable|string|max:100',
            'cidade' => 'nullable|string|max:100',
            'localizacao' => 'nullable|string|max:255',
        ]);

        $fornecedor->update($validated);

        return response()->json([
            'message' => 'Fornecedor atualizado com sucesso',
            'data' => $fornecedor
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/fornecedores/{id}",
     *     tags={"Fornecedores"},
     *     summary="Remover um fornecedor",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do fornecedor a ser removido",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Fornecedor removido com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Fornecedor removido com sucesso")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Fornecedor não encontrado"
     *     )
     * )
     */
    public function destroy($id)
    {
        $fornecedor = Fornecedor::find($id);

        if (!$fornecedor) {
            return response()->json(['message' => 'Fornecedor não encontrado'], 404);
        }

        $fornecedor->delete();

        return response()->json(['message' => 'Fornecedor removido com sucesso'], 200);
    }
}