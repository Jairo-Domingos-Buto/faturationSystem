<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Saft\SaftGenerator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class SaftExportController extends Controller
{
    // Método que é chamado pelo botão na view Livewire ou API
    public function export(Request $request)
    {
        try {
            $start = Carbon::parse($request->input('start_date', now()->startOfMonth()));
            $end = Carbon::parse($request->input('end_date', now()));

            // 1. Gera o XML
            $generator = new SaftGenerator();
            $xmlContent = $generator->generate($start, $end);

            // 2. Define o nome do arquivo padrão AGT: SAFT_NIF_DATA_TIPO.xml
            $filename = 'SAFT_AO_' . $start->format('Ymd') . '_' . $end->format('Ymd') . '.xml';

            // 3. Força Download com encoding correto
            return Response::make($xmlContent, 200, [
                'Content-Type' => 'application/xml',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
