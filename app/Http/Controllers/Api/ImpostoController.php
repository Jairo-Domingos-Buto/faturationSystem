<?php

namespace App\Http\Controllers\Api;

use App\Models\Imposto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ImpostoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/impostos",
     *     summary="Lista todos os impostos",
     *     tags={"Impostos"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de impostos retornada com sucesso"
     *     )
     * )
     *
     * @OA\Post(
     *     path="/api/impostos",
     *     summary="Cria um novo imposto",
     *     tags={"Impostos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
    *             required={"descricao", "taxa", "codigo"},
    *             @OA\Property(property="descricao", type="string", example="Imposto sobre valor acrescentado"),
    *             @OA\Property(property="taxa", type="number", format="float", minimum=0, maximum=100, example=17),
    *             @OA\Property(property="codigo", type="string", maxLength=50, example="IVA")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Imposto criado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação"
     *     )
     * )
     *
     * @OA\Get(
     *     path="/api/impostos/{id}",
     *     summary="Exibe um imposto específico",
     *     tags={"Impostos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Imposto retornado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Imposto não encontrado"
     *     )
     * )
     *
     * @OA\Put(
     *     path="/api/impostos/{id}",
     *     summary="Atualiza um imposto existente",
     *     tags={"Impostos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="descricao", type="string", maxLength=255, example="IVA"),
     *             @OA\Property(property="taxa", type="number", format="float", minimum=0, maximum=100, example=17),
     *             @OA\Property(property="codigo", type="string", nullable=true, example="Imposto sobre valor acrescentado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Imposto atualizado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Imposto não encontrado"
     *     )
     * )
     *
     * @OA\Delete(
     *     path="/api/impostos/{id}",
     *     summary="Remove um imposto",
     *     tags={"Impostos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Imposto removido com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Imposto não encontrado"
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Imposto::all(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'descricao' => 'required|string|max:255',
            'taxa' => 'required|numeric|min:0|max:100',
            'codigo' => 'nullable|string',
        ]);

        $imposto = Imposto::create($request->all());

        return response()->json([
            'message' => 'Imposto criado com sucesso!',
            'data' => $imposto
        ], 201);
    }

    public function show(Imposto $imposto)
    {
        return response()->json($imposto, 200);
    }

    public function update(Request $request, Imposto $imposto)
    {
        $imposto->update($request->all());

        return response()->json([
            'message' => 'Imposto atualizado com sucesso!',
            'data' => $imposto
        ], 200);
    }

    public function destroy(Imposto $imposto)
    {
        $imposto->delete();

        return response()->json(['message' => 'Imposto removido com sucesso!'], 200);
    }
}