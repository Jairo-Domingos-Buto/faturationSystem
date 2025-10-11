<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


/**
 * @OA\Tag(
 *     name="Exportação SAFT",
 *     description="Geração e exportação de ficheiros SAFT"
 * )
 */
class SaftExportController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/saft/exportar",
     *     tags={"Exportação SAFT"},
     *     summary="Exportar dados SAFT",
     *     @OA\Response(response=200, description="Ficheiro SAFT gerado com sucesso")
     * )
     */
    public function exportar()
    {
        // Aqui vai a lógica para gerar o ficheiro SAFT
        return response()->json(['message' => 'Ficheiro SAFT exportado com sucesso!'], 200);
    }
}
