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
     *     @OA\Response(response=200, description="Lista de fornecedores retornada")
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
     *     summary="Criar novo fornecedor",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nome", "nif", "email", "telefone", "provincia", "cidade", "localizacao"},
     *             @OA\Property(property="nome", type="string", example="Fornecedor ABC"),
     *             @OA\Property(property="nif", type="string", example="500100000"),
     *             @OA\Property(property="email", type="string", example="abc@email.com"),
     *             @OA\Property(property="telefone", type="string", example="+244923000000"),
     *             @OA\Property(property="provincia", type="string", example="Luanda"),
     *             @OA\Property(property="cidade", type="string", example="Luanda"),
     *             @OA\Property(property="localizacao", type="string", example="Rua 1, Bairro X")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Fornecedor criado com sucesso")
     * )
     */
    public function store(Request $request)
    {
        $fornecedor = Fornecedor::create($request->all());
        return response()->json($fornecedor, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/fornecedores/{id}",
     *     tags={"Fornecedores"},
     *     summary="Mostrar um fornecedor específico",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Fornecedor retornado com sucesso")
     * )
     */
    public function show(Fornecedor $fornecedor)
    {
        return response()->json($fornecedor, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/fornecedores/{id}",
     *     tags={"Fornecedores"},
     *     summary="Atualizar fornecedor",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Fornecedor atualizado com sucesso")
     * )
     */
    public function update(Request $request, Fornecedor $fornecedor)
    {
        $fornecedor->update($request->all());
        return response()->json($fornecedor, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/fornecedores/{id}",
     *     tags={"Fornecedores"},
     *     summary="Remover fornecedor",
     *     @OA\Response(response=200, description="Fornecedor removido com sucesso")
     * )
     */
    public function destroy(Fornecedor $fornecedor)
    {
        $fornecedor->delete();
        return response()->json(['message' => 'Fornecedor removido com sucesso'], 200);
    }
}
