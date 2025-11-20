<?php

namespace App\Http\Controllers;

use App\Models\DadosEmpresa;
use App\Models\Fatura;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfFaturaController extends Controller
{
    public function downloadFatura(Request $request)
    {
        // Recupera os dados da sessão (setados pelo Livewire)
        $dados_fatura = session('dados_fatura');

        // Validação
        if (! $dados_fatura) {
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
        $nomeArquivo = $tipoDocumento.'_'.$numeroDocumento.'_'.date('YmdHis').'.pdf';

        // Limpa a sessão após gerar o PDF
        session()->forget('dados_fatura');

        // Retorna o PDF para visualização no navegador
        return $pdf->stream($nomeArquivo);

        // OU para forçar download:
        // return $pdf->download($nomeArquivo);
    }

    /* imprimir fatura */
    private function calcularResumoImpostos($items)
    {
        $resumo = [];

        foreach ($items as $item) {
            $taxa = (float) $item->taxa_iva;

            if ($item->motivo_isencaos_id) {
                $descricao = 'Isento';
                $motivoIsencao = $item->motivoIsencao->descricao ?? 'N/A';
                $codigoMotivo = $item->motivoIsencao->codigo ?? null;
            } elseif ($item->imposto_id) {
                $descricao = $item->imposto->descricao ?? 'IVA';
                $motivoIsencao = null;
                $codigoMotivo = null;
            } else {
                $descricao = 'IVA';
                $motivoIsencao = null;
                $codigoMotivo = null;
            }

            $chave = $taxa.'|'.$descricao;

            if (! isset($resumo[$chave])) {
                $resumo[$chave] = [
                    'descricao' => $descricao,
                    'taxa' => $taxa,
                    'incidencia' => 0,
                    'valor_imposto' => 0,
                    'motivo_isencao' => $motivoIsencao,
                    'codigo_motivo' => $codigoMotivo,
                ];
            }

            $resumo[$chave]['incidencia'] += (float) $item->subtotal;
            $resumo[$chave]['valor_imposto'] += (float) $item->valor_iva;
        }

        return array_values($resumo);
    }

    private function montarDadosFatura($fatura, $empresa)
    {
        $produtosDetalhados = [];
        foreach ($fatura->items as $item) {
            $produtosDetalhados[] = [
                'id' => $item->produto_id,
                'codigo_barras' => $item->codigo_barras,
                'descricao' => $item->descricao,
                'quantidade' => $item->quantidade,
                'unidade' => 'UN',
                'preco_unitario' => (float) $item->preco_unitario,
                'desconto' => 0,
                'taxa_iva' => (float) $item->taxa_iva,
                'iva_valor' => (float) $item->valor_iva,
                'subtotal' => (float) $item->subtotal,
                'total' => (float) $item->total,
                'motivo_isencao' => $item->motivoIsencao->descricao ?? null,
                'descricao_imposto' => $item->imposto->descricao ?? 'IVA',
            ];
        }

        $resumoImpostos = $this->calcularResumoImpostos($fatura->items);

        return [
            'numero' => $fatura->numero,
            'tipo_documento' => 'fatura',
            'tipo_label' => 'Factura',
            'natureza' => 'produto',
            'data_emissao' => $fatura->data_emissao->format('Y-m-d'),
            'data_vencimento' => $fatura->data_emissao->addDays(30)->format('Y-m-d'),
            'moeda' => 'AKZ',
            'condicao_pagamento' => 'Pronto Pagamento',
            'estado' => $fatura->estado,
            'is_retificacao' => $fatura->is_retificacao,
            'fatura_original_numero' => $fatura->faturaOriginal->numero ?? null,
            'motivo_retificacao' => $fatura->motivo_retificacao ?? null,
            'anulada' => $fatura->anulada,
            'data_anulacao' => $fatura->data_anulacao ? $fatura->data_anulacao->format('Y-m-d') : null,
            'motivo_anulacao' => $fatura->motivo_anulacao ?? null,
            'empresa' => [
                'nome' => $empresa->name ?? '',
                'nif' => $empresa->nif ?? '',
                'telefone' => $empresa->telefone ?? '',
                'email' => $empresa->email ?? '',
                'website' => $empresa->website ?? '',
                'rua' => $empresa->rua ?? '',
                'edificio' => $empresa->edificio ?? '',
                'cidade' => $empresa->cidade ?? '',
                'provincia' => $empresa->provincia ?? '',
                'banco' => $empresa->banco ?? null,
                'iban' => $empresa->iban ?? null,
                'logo' => $empresa->logo ?? null,
            ],
            'cliente' => [
                'id' => $fatura->cliente->id,
                'nome' => $fatura->cliente->nome,
                'nif' => $fatura->cliente->nif,
                'telefone' => $fatura->cliente->telefone ?? '',
                'cidade' => $fatura->cliente->cidade ?? '',
                'provincia' => $fatura->cliente->provincia ?? '',
                'localizacao' => $fatura->cliente->localizacao ?? '',
            ],
            'produtos' => $produtosDetalhados,
            'resumo_impostos' => $resumoImpostos,
            'financeiro' => [
                'subtotal' => (float) $fatura->subtotal,
                'incidencia' => (float) $fatura->subtotal,
                'iva' => (float) $fatura->total_impostos,
                'desconto' => 0,
                'total' => (float) $fatura->total,
                'total_recebido' => 0,
                'troco' => 0,
            ],
        ];
    }

    public function gerarPdf($id)
    {
        try {
            $fatura = Fatura::with([
                'cliente',
                'user',
                'items.produto.categoria',
                'items.produto.imposto',
                'items.produto.motivoIsencao',
            ])->findOrFail($id);

            $empresa = DadosEmpresa::first();
            $dados = $this->montarDadosFatura($fatura, $empresa);

            $pdf = PDF::loadView('pdf.fatura', ['dados' => $dados])
                ->setPaper('a4', 'portrait');

            return $pdf->stream('fatura-'.$fatura->numero.'.pdf');

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao gerar PDF: '.$e->getMessage()], 500);
        }
    }
}
