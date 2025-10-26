<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;

class PdfController extends Controller
{
    public function downloadFatura(Request $request)
    {
        // Recupera os dados da sessão (setados pelo Livewire)
        $dados_fatura = session('dados_fatura');

         // carrega a view
        $pdf = PDF::loadView('pdf.fatura', $dados_fatura);
        /* retorna view */

        return $pdf->stream('meu.pdf');
    }
}
