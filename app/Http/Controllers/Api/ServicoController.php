<?php

namespace App\Http\Controllers\Api;

use App\Models\Servico;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ServicoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/servicos",
     *     summary="Lista todos os serviços",
     *     tags={"Serviços"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de serviços retornada com sucesso"
     *     )
     * )
     *
     * @OA\Post(
     *     path="/api/servicos",
     *     summary="Cria um novo serviço",
     *     tags={"Serviços"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"descricao","preco"},
     *             @OA\Property(property="descricao", type="string", maxLength=255, example="Serviço de limpeza"),
     *             @OA\Property(property="preco", type="number", format="float", example=99.99)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Serviço criado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dados inválidos"
     *     )
     * )
     *
     * @OA\Get(
     *     path="/api/servicos/{id}",
     *     summary="Exibe um serviço específico",
     *     tags={"Serviços"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Serviço retornado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Serviço não encontrado"
     *     )
     * )
     *
     * @OA\Put(
     *     path="/api/servicos/{id}",
     *     summary="Atualiza um serviço existente",
     *     tags={"Serviços"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="descricao", type="string", maxLength=255, example="Serviço atualizado"),
     *             @OA\Property(property="preco", type="number", format="float", example=120.00)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Serviço atualizado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Serviço não encontrado"
     *     )
     * )
     *
     * @OA\Delete(
     *     path="/api/servicos/{id}",
     *     summary="Remove um serviço",
     *     tags={"Serviços"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Serviço removido com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Serviço não encontrado"
     *     )
     * )
     */
}
