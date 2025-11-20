<?php

namespace App\Http\Controllers;

use App\Models\DadosEmpresa;
use App\Models\Recibo;
use Barryvdh\DomPDF\Facade\Pdf;

class pdfReciboController extends Controller
{
    public function gerarPdf($id)
    {
        try {
            $recibo = Recibo::with([
                'cliente',
                'user',
                'items.produto.categoria',
                'items.produto.imposto',
                'items.produto.motivoIsencao',
            ])->findOrFail($id);

            $empresa = DadosEmpresa::first();
            $dados = $this->montarDadosRecibo($recibo, $empresa);

            $pdf = PDF::loadView('pdf.recibo', ['dados' => $dados])
                ->setPaper('a4', 'portrait');

            return $pdf->stream('recibo-'.$recibo->numero.'.pdf');

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao gerar PDF: '.$e->getMessage()], 500);
        }
    }

    /**
     * ✅ MONTAR DADOS DO RECIBO PARA PDF
     */
    private function montarDadosRecibo($recibo, $empresa)
    {
        $produtosDetalhados = [];
        $subtotalGeral = 0;
        $ivaGeral = 0;

        foreach ($recibo->items as $item) {
            $subtotalGeral += (float) $item->subtotal;
            $ivaGeral += (float) $item->valor_iva;

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

        $resumoImpostos = $this->calcularResumoImpostos($recibo->items);

        return [
            'numero' => $recibo->numero,
            'tipo_documento' => 'recibo',
            'tipo_label' => 'Recibo',
            'natureza' => 'produto',
            'data_emissao' => $recibo->data_emissao->format('Y-m-d'),
            'data_vencimento' => $recibo->data_emissao->addDays(30)->format('Y-m-d'),
            'moeda' => 'AKZ',
            'condicao_pagamento' => 'Pronto Pagamento',
            'metodo_pagamento' => $recibo->metodo_pagamento,
            'is_retificacao' => $recibo->is_retificacao,
            'recibo_original_numero' => $recibo->reciboOriginal->numero ?? null,
            'motivo_retificacao' => $recibo->motivo_retificacao ?? null,
            'anulado' => $recibo->anulado,
            'data_anulacao' => $recibo->data_anulacao ? $recibo->data_anulacao->format('Y-m-d') : null,
            'motivo_anulacao' => $recibo->motivo_anulacao ?? null,
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
                'id' => $recibo->cliente->id,
                'nome' => $recibo->cliente->nome,
                'nif' => $recibo->cliente->nif,
                'telefone' => $recibo->cliente->telefone ?? '',
                'provincia' => $recibo->cliente->provincia ?? '',
                'cidade' => $recibo->cliente->cidade ?? '',
                'localizacao' => $recibo->cliente->localizacao ?? '',
            ],
            'produtos' => $produtosDetalhados,
            'resumo_impostos' => $resumoImpostos,
            'financeiro' => [
                'subtotal' => $subtotalGeral,
                'incidencia' => $subtotalGeral,
                'iva' => $ivaGeral,
                'desconto' => 0,
                'total' => (float) $recibo->valor,
                'total_recebido' => (float) $recibo->valor,
                'troco' => 0,
            ],
        ];
    }

    /**
     * ✅ CALCULAR RESUMO DE IMPOSTOS
     */
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
}
