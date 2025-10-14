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
     */
    public function index()
    {
        return response()->json(Servico::all(), 200);
    }

    /**
     * @OA\Post(
     *     path="/api/servicos",
     *     summary="Cria um novo serviço",
     *     tags={"Serviços"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"descricao","valor"},
     *             @OA\Property(property="descricao", type="string", maxLength=255, example="Serviço de limpeza"),
     *             @OA\Property(property="valor", type="number", format="float", example=5000.00)
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
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0',
        ]);

        $servico = Servico::create($validated);
        return response()->json($servico, 201);
    }

    /**
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
     */
    public function show($id)
    {
        $servico = Servico::find($id);
        if (!$servico) {
            return response()->json(['message' => 'Serviço não encontrado'], 404);
        }
        return response()->json($servico);
    }

    /**
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
     *             @OA\Property(property="valor", type="number", format="float", example=7500.00)
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
     */
    public function update(Request $request, $id)
    {
        $servico = Servico::find($id);
        if (!$servico) {
            return response()->json(['message' => 'Serviço não encontrado'], 404);
        }

        $validated = $request->validate([
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0',
        ]);

        $servico->update($validated);
        return response()->json($servico);
    }

    /**
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
    public function destroy($id)
    {
        $servico = Servico::find($id);
        if (!$servico) {
            return response()->json(['message' => 'Serviço não encontrado'], 404);
        }
        $servico->delete();
        return response()->json(['message' => 'Serviço removido com sucesso'], 200);
    }
}