<?php

namespace App\Http\Controllers\Api;

use App\Models\Fatura;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class FaturaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/faturas",
     *     summary="Lista todas as faturas",
     *     tags={"Faturas"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de faturas retornada com sucesso"
     *     )
     * )
     *
     * @OA\Post(
     *     path="/api/faturas",
     *     summary="Cria uma nova fatura",
     *     tags={"Faturas"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"cliente_id","data_emissao","valor_total"},
     *             @OA\Property(property="cliente_id", type="integer", example=1),
     *             @OA\Property(property="data_emissao", type="string", format="date", example="2024-06-01"),
     *             @OA\Property(property="valor_total", type="number", format="float", example=100.50)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Fatura criada com sucesso"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação"
     *     )
     * )
     *
     * @OA\Get(
     *     path="/api/faturas/{id}",
     *     summary="Exibe uma fatura específica",
     *     tags={"Faturas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Fatura retornada com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Fatura não encontrada"
     *     )
     * )
     *
     * @OA\Put(
     *     path="/api/faturas/{id}",
     *     summary="Atualiza uma fatura existente",
     *     tags={"Faturas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="cliente_id", type="integer", example=1),
     *             @OA\Property(property="data_emissao", type="string", format="date", example="2024-06-01"),
     *             @OA\Property(property="valor_total", type="number", format="float", example=100.50)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Fatura atualizada com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Fatura não encontrada"
     *     )
     * )
     *
     * @OA\Delete(
     *     path="/api/faturas/{id}",
     *     summary="Remove uma fatura",
     *     tags={"Faturas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Fatura removida com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Fatura não encontrada"
     *     )
     * )
     *
     * @OA\Post(
     *     path="/api/faturas/{id}/emitir",
     *     summary="Emite uma fatura",
     *     tags={"Faturas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Fatura emitida com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Fatura não encontrada"
     *     )
     * )
     *
     * @OA\Post(
     *     path="/api/faturas/{id}/anular",
     *     summary="Anula uma fatura",
     *     tags={"Faturas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Fatura anulada com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Fatura não encontrada"
     *     )
     * )
     *
     * @OA\Get(
     *     path="/api/faturas/{id}/pdf",
     *     summary="Gera o PDF da fatura",
     *     tags={"Faturas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Gerador de PDF em desenvolvimento"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Fatura não encontrada"
     *     )
     * )
     *
     * @OA\Get(
     *     path="/api/faturas/exportar-saft",
     *     summary="Exporta as faturas em formato SAFT",
     *     tags={"Faturas"},
     *     @OA\Response(
     *         response=200,
     *         description="Exportação SAFT em desenvolvimento"
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Fatura::with('cliente')->get(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'data_emissao' => 'required|date',
            'valor_total' => 'required|numeric|min:0',
        ]);

        $fatura = Fatura::create($request->all());

        return response()->json([
            'message' => 'Fatura criada com sucesso!',
            'data' => $fatura
        ], 201);
    }

    public function show(Fatura $fatura)
    {
        return response()->json($fatura->load('cliente'), 200);
    }

    public function update(Request $request, Fatura $fatura)
    {
        $fatura->update($request->all());

        return response()->json([
            'message' => 'Fatura atualizada com sucesso!',
            'data' => $fatura
        ], 200);
    }

    public function destroy(Fatura $fatura)
    {
        $fatura->delete();

        return response()->json(['message' => 'Fatura removida com sucesso!'], 200);
    }

    // 🔸 Métodos adicionais
    public function emitir(Fatura $fatura)
    {
        $fatura->update(['status' => 'emitida']);

        return response()->json(['message' => 'Fatura emitida com sucesso!']);
    }

    public function anular(Fatura $fatura)
    {
        $fatura->update(['status' => 'anulada']);

        return response()->json(['message' => 'Fatura anulada com sucesso!']);
    }

    public function gerarPdf(Fatura $fatura)
    {
        // Aqui você pode usar dompdf, snappy, etc.
        return response()->json(['message' => 'Gerador de PDF em desenvolvimento.']);
    }

    public function exportarSaft()
    {
        // Aqui você implementará a lógica do SAFT.
        return response()->json(['message' => 'Exportação SAFT em desenvolvimento.']);
    }
}
