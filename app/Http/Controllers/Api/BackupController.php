<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use App\Models\BackupLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BackupController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/backup/run",
     *     summary="Executa o backup do sistema",
     *     description="Executa o comando de backup do sistema utilizando o Artisan. Retorna uma mensagem de sucesso ou erro.",
     *     tags={"Backup"},
     *     @OA\Response(
     *         response=200,
     *         description="Backup criado com sucesso!",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Backup criado com sucesso!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao criar backup.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Erro ao criar backup.")
     *         )
     *     )
     * )
     *
     * @OA\Get(
     *     path="/api/backup/logs",
     *     summary="Obtém o arquivo de log do sistema",
     *     description="Retorna o arquivo de log principal do sistema (laravel.log) se existir, ou uma mensagem informando que não há logs.",
     *     tags={"Backup"},
     *     @OA\Response(
     *         response=200,
     *         description="Arquivo de log retornado com sucesso.",
     *         @OA\Schema(type="file")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Nenhum log encontrado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Nenhum log encontrado.")
     *         )
     *     )
     * )
     */
    public function run()
    {
        try {
            Artisan::call('backup:run');
            return response()->json(['message' => 'Backup criado com sucesso!']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Erro ao criar backup.'], 500);
        }
    }

    public function logs()
    {
        $logPath = storage_path('logs/laravel.log');
        if (file_exists($logPath)) {
            return response()->file($logPath);
        }

        return response()->json(['message' => 'Nenhum log encontrado.'], 404);
    }
}
