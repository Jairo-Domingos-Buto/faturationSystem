<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;

class PdfController extends Controller
{
    public function downloadFatura(Request $request)
    {
        // Recupera os dados da sessão
        $dados_fatura = session('dados_fatura');
        
        // Validação
        if (!$dados_fatura) {
            return redirect()->route('admin.pov')
                ->with('error', 'Dados da fatura não encontrados. Por favor, tente novamente.');
        }
        
        // Carrega a view com os dados
        $pdf = PDF::loadView('pdf.fatura', [
            'dados' => $dados_fatura,
        ]);
        
        // Define configurações do PDF
        $pdf->setPaper('A4', 'portrait');
        
        // Nome do arquivo
        $nomeArquivo = 'Fatura_' . $dados_fatura['numero'] . '_' . date('YmdHis') . '.pdf';
        
        // Limpa a sessão após gerar o PDF
        session()->forget('dados_fatura');
        
        // Retorna o PDF para visualização no navegador
        return $pdf->stream($nomeArquivo);
        
        // OU para forçar download:
        // return $pdf->download($nomeArquivo);
    }
}