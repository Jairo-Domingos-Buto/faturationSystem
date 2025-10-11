<?php

namespace App\Http\Controllers\Api;

use App\Models\Recibo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ReciboController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/recibos",
     *     summary="Lista todos os recibos",
     *     tags={"Recibos"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de recibos retornada com sucesso"
     *     )
     * )
     *
     * @OA\Post(
     *     path="/api/recibos",
     *     summary="Cria um novo recibo",
     *     tags={"Recibos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
    *             required={"numero", "cliente_id", "user_id", "data_emissao", "valor", "metodo_pagamento"},
    *             @OA\Property(property="numero", type="string", example="REC-2024-001"),
    *             @OA\Property(property="fatura_id", type="integer", nullable=true, example=2),
    *             @OA\Property(property="cliente_id", type="integer", example=1),
    *             @OA\Property(property="user_id", type="integer", example=5),
    *             @OA\Property(property="data_emissao", type="string", format="date", example="2024-06-01"),
    *             @OA\Property(property="valor", type="number", format="float", example=150.00),
    *             @OA\Property(property="metodo_pagamento", type="string", example="Dinheiro"),
    *             @OA\Property(property="observacoes", type="string", nullable=true, example="Pagamento antecipado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Recibo criado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dados de entrada inválidos"
     *     )
     * )
     *
     * @OA\Get(
     *     path="/api/recibos/{recibo}",
     *     summary="Exibe um recibo específico",
     *     tags={"Recibos"},
     *     @OA\Parameter(
     *         name="recibo",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Recibo retornado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Recibo não encontrado"
     *     )
     * )
     *
     * @OA\Put(
     *     path="/api/recibos/{recibo}",
     *     summary="Atualiza um recibo existente",
     *     tags={"Recibos"},
     *     @OA\Parameter(
     *         name="recibo",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="cliente_id", type="integer", example=1),
     *             @OA\Property(property="fatura_id", type="integer", nullable=true, example=2),
     *             @OA\Property(property="valor", type="number", format="float", example=200.00),
     *             @OA\Property(property="data_emissao", type="string", format="date", example="2024-06-02")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Recibo atualizado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Recibo não encontrado"
     *     )
     * )
     *
     * @OA\Delete(
     *     path="/api/recibos/{recibo}",
     *     summary="Remove um recibo",
     *     tags={"Recibos"},
     *     @OA\Parameter(
     *         name="recibo",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Recibo removido com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Recibo não encontrado"
     *     )
     * )
     */
}
