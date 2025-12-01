<?php

namespace App\Http\Controllers;

use App\Models\DadosEmpresa;
use App\Models\Fatura;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfFaturaController extends Controller
{
    /**
     * Rota: /fatura/{id}/gerar-pdf
     */
    public function gerarPdf($id)
    {
        return $this->processarPdf($id, 'stream');
    }

    /**
     * Rota: /fatura/{id}/download
     */
    public function downloadPdf($id)
    {
        return $this->processarPdf($id, 'download');
    }

    /**
     * Lógica centralizada de processamento
     */
    private function processarPdf($id, $modo = 'stream')
    {
        try {
            // Carrega relações (IMPORTANTE: trazer serviços e produtos)
            $fatura = Fatura::with([
                'cliente',
                'items.produto.motivoIsencao',
                'items.produto.imposto',
                'items.servico', // Necessário para exibir descrição de serviços
            ])->findOrFail($id);

            $empresa = DadosEmpresa::first();
            $dados = $this->montarDadosFatura($fatura, $empresa);

            // Carrega a View (ajuste o nome se for diferente)
            $pdf = PDF::loadView('pdf.fatura', ['dados' => $dados])
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'defaultFont' => 'DejaVu Sans',
                    'isFontSubsettingEnabled' => true,
                ]);

            $nomeArquivo = str_replace(' ', '_', $dados['tipo_label']).'_'.str_replace('/', '-', $fatura->numero).'.pdf';

            if ($modo === 'download') {
                return $pdf->download($nomeArquivo);
            }

            return $pdf->stream($nomeArquivo);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao gerar PDF: '.$e->getMessage()], 500);
        }
    }

    /**
     * Prepara array de dados para View
     */
    private function montarDadosFatura($fatura, $empresa)
    {
        // 1. Formata Produtos (e Serviços)
        $produtosDetalhados = [];
        foreach ($fatura->items as $item) {
            // Verifica se é serviço ou produto
            $codigo = $item->servico_id ? 'SERV' : ($item->codigo_barras ?? $item->produto_id ?? '-');

            // Unidade padrão
            $unidade = 'UN'; // Você pode puxar da tabela produtos se tiver campo unidade

            $produtosDetalhados[] = [
                'id' => $item->produto_id ?? $item->servico_id,
                'codigo_barras' => $codigo,
                'descricao' => $item->descricao,
                'quantidade' => $item->quantidade,
                'unidade' => $unidade,
                'preco_unitario' => (float) $item->preco_unitario,
                'desconto' => 0, // Se implementar desconto por linha futuramente
                'taxa_iva' => (float) $item->taxa_iva,
                'iva_valor' => (float) $item->valor_iva,
                'subtotal' => (float) $item->subtotal,
                'total' => (float) $item->total,
            ];
        }

        // 2. Determina Labels
        $tipoLabel = match ($fatura->tipo_documento) {
            'FR' => 'FACTURA-RECIBO',
            'FP' => 'FACTURA PRÓ-FORMA',
            'RC' => 'RECIBO',
            'FT' => 'FACTURA',
            default => 'DOCUMENTO',
        };

        // 3. Condições de Pagamento
        if ($fatura->tipo_documento === 'FR' || $fatura->tipo_documento === 'RC') {
            $condicaoPagamento = 'Pronto Pagamento';
            $dataVencimento = $fatura->data_emissao->format('d/m/Y');
        } else {
            $condicaoPagamento = 'Prazo';
            $dataVencimento = $fatura->data_vencimento
                ? $fatura->data_vencimento->format('d/m/Y')
                : $fatura->data_emissao->addDays(30)->format('d/m/Y');
        }

        // Retorno formatado
        return [
            // Cabeçalho Documento
            'numero' => $fatura->numero,
            'tipo_label' => $tipoLabel,
            'tipo_documento' => $fatura->tipo_documento,
            'is_proforma' => ($fatura->tipo_documento === 'FP'),
            'data_emissao' => $fatura->data_emissao->format('d/m/Y'),
            'data_vencimento' => $dataVencimento,
            'estado' => $fatura->estado,
            'moeda' => 'AKZ',
            'condicao_pagamento' => $condicaoPagamento,
            'metodo_pagamento' => $fatura->metodo_pagamento,

            // Dados da Empresa (Fallback seguro)
            'empresa' => [
                'nome' => $empresa->name ?? 'Minha Empresa',
                'nif' => $empresa->nif ?? '999999999',
                'telefone' => $empresa->telefone ?? '',
                'email' => $empresa->email ?? '',
                'rua' => $empresa->rua ?? '',
                'endereco' => $empresa->rua ?? '',
                'edificio' => $empresa->edificio ?? '',
                'cidade' => $empresa->cidade ?? 'Luanda',
                'provincia' => $empresa->provincia ?? '',
                'banco' => $empresa->nomeDoBanco ?? $empresa->banco ?? '',
                'iban' => $empresa->iban ?? '',
                'logo' => $empresa->logo ?? null,
            ],

            // Dados Cliente
            'cliente' => [
                'nome' => $fatura->cliente->nome ?? 'Consumidor Final',
                'nif' => $fatura->cliente->nif ?? null,
                'telefone' => $fatura->cliente->telefone ?? null,
                'localizacao' => $fatura->cliente->endereco ?? $fatura->cliente->localizacao ?? null,
                'cidade' => $fatura->cliente->cidade ?? null,
                'provincia' => $fatura->cliente->provincia ?? null,
            ],

            // Lista Paginada (Evita quebra de página feia na tabela)
            'produtos' => $this->paginateItems($produtosDetalhados, 22),

            // Totais Calculados
            'resumo_impostos' => $this->calcularResumoImpostos($fatura->items),
            'financeiro' => [
                'subtotal' => (float) $fatura->subtotal,
                'iva' => (float) $fatura->total_impostos,
                'desconto' => 0, // Ajustar se tiver campo global desconto
                'total' => (float) $fatura->total,
            ],

            'observacoes' => $fatura->observacoes,
        ];
    }

    /**
     * Paginação manual para PDF
     */
    private function paginateItems($items, $perPage = 20)
    {
        return array_chunk($items, $perPage);
    }

    /**
     * Cálculo Fiscal (Compatível com Serviços isentos)
     */
    private function calcularResumoImpostos($items)
    {
        $resumo = [];

        foreach ($items as $item) {
            $taxa = (float) $item->taxa_iva;

            // Determinar Descrição e Motivo
            if ($item->motivo_isencaos_id || ($taxa == 0 && $item->servico_id)) {
                $descricao = 'Isento';
                // Pega motivo do relacionamento ou usa fallback "M02" se for serviço sem motivo atrelado
                $motivoIsencao = $item->motivoIsencao->descricao ?? ($item->servico_id ? 'Transmissão Isenta (Serviço)' : 'Regime de Isenção');
            } elseif ($item->imposto_id) {
                $descricao = $item->imposto->descricao ?? 'IVA';
                $motivoIsencao = null;
            } else {
                $descricao = $taxa > 0 ? "IVA {$taxa}%" : 'Isento';
                $motivoIsencao = null;
            }

            // Chave de agrupamento
            $chave = (string) $taxa.'|'.$descricao;

            if (! isset($resumo[$chave])) {
                $resumo[$chave] = [
                    'descricao' => $descricao,
                    'taxa' => $taxa,
                    'incidencia' => 0,
                    'valor_imposto' => 0,
                    'motivo_isencao' => $motivoIsencao,
                ];
            }

            $resumo[$chave]['incidencia'] += (float) $item->subtotal; // Usa subtotal da linha
            $resumo[$chave]['valor_imposto'] += (float) $item->valor_iva;
        }

        // Ordena por taxa (decrescente) para ficar bonito
        usort($resumo, fn ($a, $b) => $b['taxa'] <=> $a['taxa']);

        return array_values($resumo);
    }
}
