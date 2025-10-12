<?php

namespace App\Http\Controllers\Api;

use App\Models\MotivoIsencao;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class MotivoIsencaoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/motivo-isencao",
     *     summary="Lista todos os motivos de isenção",
     *     tags={"MotivoIsencao"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de motivos de isenção",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/MotivoIsencao"))
     *     )
     * )
     *
     * @OA\Post(
     *     path="/api/motivo-isencao",
     *     summary="Cria um novo motivo de isenção",
     *     tags={"MotivoIsencao"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
    *             required={"codigo","razao","descricao"},
    *             @OA\Property(property="codigo", type="string", maxLength=10),
    *             @OA\Property(property="razao", type="string", maxLength=255),
    *             @OA\Property(property="descricao", type="string", maxLength=255)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Motivo de isenção criado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", ref="#/components/schemas/MotivoIsencao")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação"
     *     )
     * )
     *
     * @OA\Get(
     *     path="/api/motivo-isencao/{id}",
     *     summary="Exibe um motivo de isenção específico",
     *     tags={"MotivoIsencao"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Dados do motivo de isenção",
     *         @OA\JsonContent(ref="#/components/schemas/MotivoIsencao")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Motivo de isenção não encontrado"
     *     )
     * )
     *
     * @OA\Put(
     *     path="/api/motivo-isencao/{id}",
     *     summary="Atualiza um motivo de isenção existente",
     *     tags={"MotivoIsencao"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="codigo", type="string", maxLength=10),
     *             @OA\Property(property="descricao", type="string", maxLength=255)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Motivo de isenção atualizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", ref="#/components/schemas/MotivoIsencao")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Motivo de isenção não encontrado"
     *     )
     * )
     *
     * @OA\Delete(
     *     path="/api/motivo-isencao/{id}",
     *     summary="Remove um motivo de isenção",
     *     tags={"MotivoIsencao"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Motivo de isenção removido com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Motivo de isenção não encontrado"
     *     )
     * )
     */
   
}
