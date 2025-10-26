<?php

namespace App\Http\Controllers;

use App\Models\DadosEmpresa;
use Illuminate\Http\Request;
use PDF;

class PdfController extends Controller
{
    public function downloadFatura(Request $request)
    {
        // Recupera os dados da sessão (setados pelo Livewire)
        $dados_fatura = session('dados_fatura');
        $dadosEmpresa = DadosEmpresa::all();
         session(['empresa' => $dadosEmpresa]);

         // carrega a view
        $pdf = PDF::loadView('pdf.fatura', $dados_fatura, $dadosEmpresa);
        /* retorna view */

        return $pdf->stream('meu.pdf');
    }
}
