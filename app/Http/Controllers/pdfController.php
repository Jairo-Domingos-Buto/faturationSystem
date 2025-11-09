<?php

namespace App\Http\Controllers;

use App\Models\DadosEmpresa;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function downloadFatura(Request $request)
    {
        // Recupera os dados da sessão (setados pelo Livewire)
        $dados_fatura = session('dados_fatura');
        
        // Validação
        if (!$dados_fatura) {
            return redirect()->route('admin.pov')
                ->with('error', 'Dados da fatura não encontrados. Por favor, tente novamente.');
        }
        
        // Carrega a view com os dados usando DomPDF
        $pdf = PDF::loadView('pdf.fatura', [
            'dados' => $dados_fatura,
        ]);
        
        // Define configurações do PDF
        $pdf->setPaper('A4', 'portrait');
        
        // Configurações adicionais do DomPDF
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'sans-serif',
            'isFontSubsettingEnabled' => true,
        ]);
        
        // Nome do arquivo baseado no tipo de documento
        $tipoDocumento = $dados_fatura['tipo_label'] ?? 'Documento';
        $numeroDocumento = str_replace(['/', '\\', ' '], '_', $dados_fatura['numero'] ?? 'SN');
        $nomeArquivo = $tipoDocumento . '_' . $numeroDocumento . '_' . date('YmdHis') . '.pdf';
        
        // Limpa a sessão após gerar o PDF
        session()->forget('dados_fatura');
        
        // Retorna o PDF para visualização no navegador
        return $pdf->stream($nomeArquivo);
        
        // OU para forçar download:
        // return $pdf->download($nomeArquivo);
    }
} 