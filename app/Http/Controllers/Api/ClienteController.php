<?php

namespace App\Http\Controllers\Api;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


/**
 * @OA\Info(
 *     title="API de Faturação",
 *     version="1.0.0",
 *     description="Documentação automática gerada pelo Swagger para a API de Faturação."
 * )
 *
 * @OA\Tag(
 *     name="Clientes",
 *     description="Gestão de clientes do sistema de faturação"
 * )
 */
class ClienteController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/clientes",
     *     tags={"Clientes"},
     *     summary="Listar todos os clientes",
     *     @OA\Response(response=200, description="Lista de clientes retornada com sucesso")
     * )
     */
    public function index()
    {
        return response()->json(Cliente::all(), 200);
    }

    /**
     * @OA\Post(
     *     path="/api/clientes",
     *     tags={"Clientes"},
     *     summary="Criar um novo cliente",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *              required={"nome"},
 *             @OA\Property(property="nome", type="string", maxLength=255),
 *             @OA\Property(property="nif", type="string", maxLength=50),
 *             @OA\Property(property="provincia", type="string", maxLength=100),
 *             @OA\Property(property="cidade", type="string", maxLength=100),
 *             @OA\Property(property="localizacao", type="string", maxLength=255),
 *             @OA\Property(property="telefone", type="string", maxLength=50)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Cliente criado com sucesso")
     * )
     */
    public function store(Request $request)
    {
        $cliente = Cliente::create($request->all());
        return response()->json($cliente, 201);
    }
    /**
     * @OA\Get(
     *     path="/api/clientes/{id}",
     *     tags={"Clientes"},
     *     summary="Exibir um cliente específico",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cliente retornado com sucesso",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cliente não encontrado"
     *     )
     * )
     */
    public function show(Cliente $cliente)
    {
        return response()->json($cliente, 200);
    }

   
    /**
     * @OA\Put(
     *     path="/api/clientes/{id}",
     *     tags={"Clientes"},
     *     summary="Atualizar um cliente existente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nome", type="string", maxLength=255),
     *             @OA\Property(property="nif", type="string", maxLength=50),
     *             @OA\Property(property="provincia", type="string", maxLength=100),
     *             @OA\Property(property="cidade", type="string", maxLength=100),
     *             @OA\Property(property="localizacao", type="string", maxLength=255),
     *             @OA\Property(property="telefone", type="string", maxLength=50)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cliente atualizado com sucesso",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dados de entrada inválidos"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cliente não encontrado"
     *     )
     * )
     */
    public function update(Request $request, Cliente $cliente)
    {
        $cliente->update($request->all());
        return response()->json($cliente, 200);
    }

 
    /**
     * @OA\Delete(
     *     path="/api/clientes/{id}",
     *     tags={"Clientes"},
     *     summary="Remover um cliente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cliente removido com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cliente não encontrado"
     *     )
     * )
     */
    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return response()->json(['message' => 'Cliente removido com sucesso'], 200);
    }
}
