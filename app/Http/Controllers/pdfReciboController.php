<?php

namespace App\Http\Controllers;

use App\Models\DadosEmpresa;
use App\Models\Recibo;
use Barryvdh\DomPDF\Facade\Pdf;

class pdfReciboController extends Controller
{
    /**
     * Gera o PDF do Recibo (Stream em nova aba)
     */
    public function gerarPdf($id)
    {
        try {
            $recibo = Recibo::with([
                'cliente',
                'user',
                'items.produto.imposto',
                'items.produto.motivoIsencao',
                'items.servico', // Essencial para carregar nomes dos serviços
                'reciboOriginal', // Caso seja retificação
            ])->findOrFail($id);

            $empresa = DadosEmpresa::first();
            $dados = $this->montarDadosRecibo($recibo, $empresa);

            // Usa a view simplificada de recibo que configuramos
            $pdf = PDF::loadView('pdf.recibo', ['dados' => $dados])
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'defaultFont' => 'DejaVu Sans',
                    'isRemoteEnabled' => true,
                ]);

            // Gera nome amigável: Recibo_RC-20230001.pdf
            $filename = 'Recibo_'.str_replace('/', '-', $recibo->numero).'.pdf';

            return $pdf->stream($filename);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao gerar Recibo em PDF: '.$e->getMessage()], 500);
        }
    }

    /**
     * Prepara o array de dados para a View
     */
    private function montarDadosRecibo($recibo, $empresa)
    {
        // 1. Processamento dos Itens (Distinção Produto/Serviço)
        $produtosDetalhados = [];
        $subtotalGeral = 0;
        $ivaGeral = 0;

        foreach ($recibo->items as $item) {
            $subtotalGeral += (float) $item->subtotal;
            $ivaGeral += (float) $item->valor_iva;

            // Verifica a origem do nome e código
            $isServico = ! empty($item->servico_id);
            $descricao = $item->descricao ?? ($isServico ? $item->servico->descricao : $item->produto->descricao);
            $codigo = $isServico ? 'SERV' : ($item->codigo_barras ?? $item->produto->codigo_barras ?? '-');
            $unidade = $isServico ? 'UN' : 'UN'; // Ajustar se tiver tabela de unidades

            $produtosDetalhados[] = [
                'id' => $item->produto_id ?? $item->servico_id,
                'codigo_barras' => $codigo,
                'descricao' => $descricao,
                'quantidade' => $item->quantidade,
                'unidade' => $unidade,
                'preco_unitario' => (float) $item->preco_unitario,
                'desconto' => 0,
                'taxa_iva' => (float) $item->taxa_iva,
                'iva_valor' => (float) $item->valor_iva,
                'subtotal' => (float) $item->subtotal,
                'total' => (float) $item->total,
                'is_servico' => $isServico,
            ];
        }

        // 2. Cálculo do Resumo de Impostos (Necessário p/ Compliance)
        $resumoImpostos = $this->calcularResumoImpostos($recibo->items);

        // 3. Labels e Textos
        $tipoLabel = $recibo->is_retificacao ? 'RECIBO (RETIFICADO)' : 'RECIBO';
        if ($recibo->anulado) {
            $tipoLabel .= ' [ANULADO]';
        }

        return [
            // Metadados
            'numero' => $recibo->numero,
            'tipo_documento' => 'recibo',
            'tipo_label' => $tipoLabel,
            'data_emissao' => $recibo->data_emissao->format('d/m/Y'),

            // Dados Pagamento
            'moeda' => 'AKZ',
            'metodo_pagamento' => $recibo->metodo_pagamento,
            'condicao_pagamento' => 'Pronto Pagamento',

            // Flags de Estado
            'is_retificacao' => (bool) $recibo->is_retificacao,
            'recibo_original_numero' => $recibo->reciboOriginal->numero ?? null,
            'motivo_retificacao' => $recibo->motivo_retificacao,
            'anulado' => (bool) $recibo->anulado,

            // Empresa
            'empresa' => [
                'nome' => $empresa->name ?? 'Minha Empresa',
                'nif' => $empresa->nif ?? '999999999',
                'telefone' => $empresa->telefone ?? '',
                'email' => $empresa->email ?? '',
                'rua' => $empresa->rua ?? '',
                'edificio' => $empresa->edificio ?? '',
                'cidade' => $empresa->cidade ?? 'Luanda',
                'endereco' => $empresa->rua ?? '',
                'provincia' => $empresa->provincia ?? '',
                'banco' => $empresa->nomeDoBanco ?? $empresa->banco ?? '',
                'iban' => $empresa->iban ?? '',
                'logo' => $empresa->logo ?? null,
            ],

            // Cliente
            'cliente' => [
                'id' => $recibo->cliente->id ?? null,
                'nome' => $recibo->cliente->nome ?? 'Consumidor Final',
                'nif' => $recibo->cliente->nif ?? null,
                'telefone' => $recibo->cliente->telefone ?? null,
                'cidade' => $recibo->cliente->cidade ?? null,
                'localizacao' => $recibo->cliente->endereco ?? $recibo->cliente->localizacao ?? null,
                'provincia' => $recibo->cliente->provincia ?? null,
            ],

            // Listagens
            'produtos' => $produtosDetalhados,
            'resumo_impostos' => $resumoImpostos,

            // Financeiro Final
            'financeiro' => [
                'subtotal' => $subtotalGeral,
                'iva' => $ivaGeral,
                'desconto' => 0,
                'total' => (float) $recibo->valor,
                // Como recibo RC é pagamento total, total_recebido = total
                'total_recebido' => (float) $recibo->valor,
                'troco' => 0,
            ],
        ];
    }

    /**
     * Calcula o quadro de impostos (Base + IVA + Isenções)
     */
    private function calcularResumoImpostos($items)
    {
        $resumo = [];

        foreach ($items as $item) {
            $taxa = (float) $item->taxa_iva;

            // Determinar a descrição e o motivo
            if ($item->motivo_isencaos_id || ($taxa == 0 && $item->servico_id)) {
                $descricao = 'Isento';
                // Pega motivo do DB ou usa um padrão para serviços
                $motivoIsencao = $item->motivoIsencao->descricao ?? ($item->servico_id ? 'Transmissão Isenta (M02)' : 'Regime de Isenção');
                $codigoMotivo = $item->motivoIsencao->codigo ?? 'M02';
            } elseif ($item->imposto_id) {
                $descricao = $item->imposto->descricao ?? 'IVA';
                $motivoIsencao = null;
                $codigoMotivo = null;
            } else {
                $descricao = $taxa > 0 ? "IVA {$taxa}%" : 'Isento';
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

            // Soma valores
            $resumo[$chave]['incidencia'] += (float) $item->subtotal;
            $resumo[$chave]['valor_imposto'] += (float) $item->valor_iva;
        }

        // Ordena por maior taxa para apresentação (IVA 14% antes de Isento)
        usort($resumo, fn ($a, $b) => $b['taxa'] <=> $a['taxa']);

        return array_values($resumo);
    }
}
