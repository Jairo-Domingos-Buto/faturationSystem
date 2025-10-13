<?php

namespace App\Http\Controllers\Api;

use App\Models\MotivoIsencao;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * @OA\Tag(
 *     name="MotivoIsencao",
 *     description="Endpoints para gestão de Motivos de Isenção"
 * )
 */
class MotivoIsencaoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/motivo_isencaos",
     *     summary="Listar todos os motivos de isenção",
     *     description="Retorna a lista completa dos motivos de isenção cadastrados no sistema.",
     *     tags={"MotivoIsencao"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de motivos de isenção retornada com sucesso",
     *     )
     * )
     */
    public function index()
    {
        return response()->json(MotivoIsencao::all(), 200);
    }

    /**
     * @OA\Post(
     *     path="/api/motivo_isencaos",
     *     summary="Criar novo motivo de isenção",
     *     description="Cadastra um novo motivo de isenção no sistema.",
     *     tags={"MotivoIsencao"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"codigo","razao","descricao"},
     *             @OA\Property(property="codigo", type="string", maxLength=10, example="M02"),
     *             @OA\Property(property="razao", type="string", maxLength=255, example="Isento por doação"),
     *             @OA\Property(property="descricao", type="string", maxLength=255, example="Aplica-se a doações de caridade.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Motivo de isenção criado com sucesso",
     *     ),
     *     @OA\Response(response=422, description="Erro de validação dos campos")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:10|unique:motivo_isencaos,codigo',
            'razao' => 'required|string|max:255',
            'descricao' => 'required|string|max:255',
        ]);

        $motivo = MotivoIsencao::create($validated);
        return response()->json($motivo, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/motivo_isencaos/{id}",
     *     summary="Mostrar motivo de isenção específico",
     *     description="Retorna os detalhes de um motivo de isenção existente através do ID informado.",
     *     tags={"MotivoIsencao"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do motivo de isenção",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(response=404, description="Motivo de isenção não encontrado")
     * )
     */
    public function show($id)
    {
        $motivo = MotivoIsencao::find($id);
        if (!$motivo) {
            return response()->json(['message' => 'Motivo de isenção não encontrado'], 404);
        }
        return response()->json($motivo, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/motivo_isencaos/{id}",
     *     summary="Atualizar motivo de isenção existente",
     *     description="Atualiza os dados de um motivo de isenção já cadastrado.",
     *     tags={"MotivoIsencao"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do motivo de isenção a ser atualizado",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="codigo", type="string", example="M03"),
     *             @OA\Property(property="razao", type="string", example="Isento por exportação"),
     *             @OA\Property(property="descricao", type="string", example="Aplica-se a exportações internacionais.")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Motivo de isenção atualizado com sucesso"),
     *     @OA\Response(response=404, description="Motivo de isenção não encontrado")
     * )
     */
    public function update(Request $request, $id)
    {
        $motivo = MotivoIsencao::find($id);
        if (!$motivo) {
            return response()->json(['message' => 'Motivo de isenção não encontrado'], 404);
        }

        $validated = $request->validate([
            'codigo' => 'sometimes|required|string|max:10|unique:motivo_isencaos,codigo,' . $motivo->id,
            'razao' => 'sometimes|required|string|max:255',
            'descricao' => 'sometimes|required|string|max:255',
        ]);

        $motivo->update($validated);
        return response()->json($motivo, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/motivo_isencaos/{id}",
     *     summary="Remover motivo de isenção",
     *     description="Exclui um motivo de isenção existente através do ID informado.",
     *     tags={"MotivoIsencao"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do motivo de isenção a ser excluído",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(response=200, description="Motivo de isenção removido com sucesso"),
     *     @OA\Response(response=404, description="Motivo de isenção não encontrado")
     * )
     */
    public function destroy($id)
    {
        $motivo = MotivoIsencao::find($id);
        if (!$motivo) {
            return response()->json(['message' => 'Motivo de isenção não encontrado'], 404);
        }

        $motivo->delete();
        return response()->json(['message' => 'Motivo de isenção removido com sucesso'], 200);
    }
}