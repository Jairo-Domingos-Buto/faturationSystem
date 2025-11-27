<?php

namespace App\Http\Controllers;

use App\Models\DadosEmpresa;
use App\Models\Fatura;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfFaturaController extends Controller
{
    /**
     * Calcula o resumo dos impostos para o rodapé do PDF.
     */
    private function calcularResumoImpostos($items)
    {
        $resumo = [];

        foreach ($items as $item) {
            // Garante que é float para cálculos
            $taxa = (float) $item->taxa_iva;

            if ($item->motivo_isencaos_id) {
                $descricao = 'Isento';
                $motivoIsencao = $item->motivoIsencao->descricao ?? 'Isenção';
            } elseif ($item->imposto_id) {
                $descricao = $item->imposto->descricao ?? 'IVA';
                $motivoIsencao = null;
            } else {
                $descricao = $taxa > 0 ? "IVA {$taxa}%" : 'Isento';
                $motivoIsencao = $taxa == 0 ? 'Regime de Isenção' : null;
            }

            // Chave única para agrupar
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

            $resumo[$chave]['incidencia'] += (float) $item->subtotal;
            $resumo[$chave]['valor_imposto'] += (float) $item->valor_iva;
        }

        return array_values($resumo);
    }

    /**
     * Divide os itens em páginas para o PDF (Evita quebra de tabela feia).
     */
    private function paginateItems($items, $perPage = 20)
    {
        return array_chunk($items, $perPage);
    }

    /**
     * Prepara o array de dados final que será enviado para a View.
     */
    private function montarDadosFatura($fatura, $empresa)
    {
        // 1. Formata Produtos
        $produtosDetalhados = [];
        foreach ($fatura->items as $item) {
            $produtosDetalhados[] = [
                'id' => $item->produto_id,
                'codigo_barras' => $item->codigo_barras,
                'descricao' => $item->descricao,
                'quantidade' => $item->quantidade,
                'unidade' => 'UN',
                'preco_unitario' => (float) $item->preco_unitario,
                'desconto' => 0, // Implementar coluna se existir no futuro
                'taxa_iva' => (float) $item->taxa_iva,
                'iva_valor' => (float) $item->valor_iva,
                'subtotal' => (float) $item->subtotal,
                'total' => (float) $item->total,
            ];
        }

        // 2. Determina os Labels baseados no Tipo
        $tipoLabel = match ($fatura->tipo_documento) {
            'FR' => 'FACTURA-RECIBO',
            'FP' => 'FACTURA PRÓ-FORMA',
            'RC' => 'RECIBO DE LIQUIDAÇÃO',
            'FT' => 'FACTURA',
            default => 'DOCUMENTO',
        };

        // 3. Define Condição de Pagamento e Datas
        if ($fatura->tipo_documento === 'FR') {
            $condicaoPagamento = 'Pronto Pagamento';
            $dataVencimento = $fatura->data_emissao->format('Y-m-d'); // Vence hoje
        } else {
            // Pega do banco (salvo no POV) ou calcula fallback
            $condicaoPagamento = 'Prazo';
            $dataVencimento = $fatura->data_vencimento
                ? $fatura->data_vencimento->format('Y-m-d')
                : $fatura->data_emissao->addDays(30)->format('Y-m-d');
        }

        return [
            // Cabeçalhos
            'numero' => $fatura->numero,
            'tipo_label' => $tipoLabel,
            'tipo_documento' => $fatura->tipo_documento, // Para lógica condicional na view (Ex: mostrar aviso de Proforma)
            'is_proforma' => ($fatura->tipo_documento === 'FP'),

            // Datas e Status
            'data_emissao' => $fatura->data_emissao->format('Y-m-d'),
            'data_vencimento' => $dataVencimento,
            'estado' => $fatura->estado, // paga, emitida, anulada

            // Pagamento
            'moeda' => 'AKZ',
            'condicao_pagamento' => $condicaoPagamento,
            'metodo_pagamento' => $fatura->metodo_pagamento, // Ex: Numerário, TPA (importante para FR)

            // Dados da Empresa
            'empresa' => [
                'nome' => $empresa->name ?? 'Nome da Empresa',
                'nif' => $empresa->nif ?? '000000000',
                'telefone' => $empresa->telefone ?? '',
                'email' => $empresa->email ?? '',
                'rua' => $empresa->rua ?? '',
                'edificio' => $empresa->edificio ?? '',
                'cidade' => $empresa->cidade ?? '',
                'provincia' => $empresa->provincia ?? '',
                'banco' => $empresa->nomeDoBanco ?? $empresa->banco ?? '',
                'iban' => $empresa->iban ?? '',
                'logo' => $empresa->logo ?? null,
            ],

            // Dados do Cliente
            'cliente' => [
                'id' => $fatura->cliente->id ?? null,
                'nome' => $fatura->cliente->nome ?? 'Consumidor Final',
                'nif' => $fatura->cliente->nif ?? '999999999',
                'telefone' => $fatura->cliente->telefone ?? '',
                'cidade' => $fatura->cliente->cidade ?? '',
                'provincia' => $fatura->cliente->provincia ?? '',
                'localizacao' => $fatura->cliente->localizacao ?? '',
            ],

            // Itens (Paginados)
            'produtos' => $this->paginateItems($produtosDetalhados, 18), // Ajuste 18-20 conforme seu layout CSS

            // Totais
            'resumo_impostos' => $this->calcularResumoImpostos($fatura->items),
            'financeiro' => [
                'subtotal' => (float) $fatura->subtotal,
                'iva' => (float) $fatura->total_impostos,
                'desconto' => 0,
                'total' => (float) $fatura->total,
            ],

            // Extras
            'observacoes' => $fatura->observacoes,
        ];
    }

    /**
     * Rota: /fatura/{id}/gerar-pdf
     */
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

            // Carrega a view única de fatura
            $pdf = PDF::loadView('pdf.fatura', ['dados' => $dados])
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'defaultFont' => 'sans-serif',
                    'isFontSubsettingEnabled' => true,
                ]);

            // Gera o nome do arquivo amigável
            $filename = str_replace([' ', '/'], ['_', '-'], $dados['tipo_label']).'_'.str_replace('/', '-', $fatura->numero).'.pdf';

            // Abre em nova aba (stream)
            return $pdf->stream($filename);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao gerar PDF: '.$e->getMessage()], 500);
        }
    }

    public function downloadPdf($id)
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
