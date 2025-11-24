<?php

namespace App\Http\Controllers;

use App\Models\DadosEmpresa;
use App\Models\Fatura;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfFaturaController extends Controller
{
    private function calcularResumoImpostos($items)
    {
        $resumo = [];

        foreach ($items as $item) {
            $taxa = (float) $item->taxa_iva;

            if ($item->motivo_isencaos_id) {
                $descricao = 'Isento';
                $motivoIsencao = $item->motivoIsencao->descricao ?? 'N/A';
            } elseif ($item->imposto_id) {
                $descricao = $item->imposto->descricao ?? 'IVA';
                $motivoIsencao = null;
            } else {
                $descricao = 'IVA';
                $motivoIsencao = null;
            }

            $chave = $taxa.'|'.$descricao;

            if (!isset($resumo[$chave])) {
                $resumo[$chave] = [
                    'descricao' => $descricao,
                    'taxa' => $taxa,
                    'incidencia' => 0,
                    'valor_imposto' => 0,
                    'motivo_isencao' => $motivoIsencao,
                ];
            }

            $resumo[$chave]['incidencia'] += (float) $item->subtotal;
            $resumo[$chave]['valor_imposto'] += (float) $item->valor_iva;
        }

        return array_values($resumo);
    }

    private function paginateItems($items, $perPage = 20)
    {
        return array_chunk($items, $perPage);
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
            ];
        }

        $resumoImpostos = $this->calcularResumoImpostos($fatura->items);

        return [
            'numero' => $fatura->numero,
            'tipo_label' => 'Factura',
            'data_emissao' => $fatura->data_emissao->format('Y-m-d'),
            'data_vencimento' => $fatura->data_emissao->addDays(30)->format('Y-m-d'),
            'moeda' => 'AKZ',
            'condicao_pagamento' => 'Pronto Pagamento',
            'estado' => $fatura->estado,
            'empresa' => [
                'nome' => $empresa->name ?? '',
                'nif' => $empresa->nif ?? '',
                'telefone' => $empresa->telefone ?? '',
                'email' => $empresa->email ?? '',
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
            'produtos' => $this->paginateItems($produtosDetalhados, 20), // aqui dividimos em páginas
            'resumo_impostos' => $resumoImpostos,
            'financeiro' => [
                'subtotal' => (float) $fatura->subtotal,
                'iva' => (float) $fatura->total_impostos,
                'desconto' => 0,
                'total' => (float) $fatura->total,
            ],
        ];
    }

    public function gerarPdf($id)
    {
        try {
            $fatura = Fatura::with([
                'cliente',
                'items.produto.motivoIsencao',
                'items.produto.imposto',
            ])->findOrFail($id);

            $empresa = DadosEmpresa::first();
            $dados = $this->montarDadosFatura($fatura, $empresa);

            $pdf = PDF::loadView('pdf.fatura', ['dados' => $dados])
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'defaultFont' => 'sans-serif',
                    'isFontSubsettingEnabled' => true,
                ]);

            // Abre em nova aba
            return $pdf->stream('fatura-'.$fatura->numero.'.pdf');

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao gerar PDF: '.$e->getMessage()], 500);
        }
    }
}