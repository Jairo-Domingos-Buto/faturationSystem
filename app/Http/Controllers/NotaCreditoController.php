<?php

namespace App\Http\Controllers;

use App\Models\DadosEmpresa;
use App\Models\Fatura;
use App\Models\Recibo;
use Barryvdh\DomPDF\Facade\Pdf;

class NotaCreditoController extends Controller
{
    /**
     * =========================================================================
     * VISUALIZAÇÃO E DOWNLOAD (Fatura, Fatura-Recibo, Proforma convertida)
     * =========================================================================
     */
    public function visualizarFatura($id)
    {
        try {
            $faturaOriginal = Fatura::with([
                'cliente', 'user',
                'items.produto', 'items.imposto', 'items.motivoIsencao',
                'faturaRetificacao.items.produto', 'faturaRetificacao.items.imposto', 'faturaRetificacao.items.motivoIsencao',
            ])->findOrFail($id);

            if (! $faturaOriginal->retificada) {
                return redirect()->back()->with('error', 'Este documento não foi retificado.');
            }

            $empresa = DadosEmpresa::first();
            $dados = $this->montarDadosNotaCredito($faturaOriginal, $empresa);

            $pdf = Pdf::loadView('pdf.notaCredito', ['dados' => $dados])
                ->setPaper('a4', 'portrait')
                ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

            return $pdf->stream('NC-'.$faturaOriginal->numero.'.pdf');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao gerar PDF: '.$e->getMessage());
        }
    }

    public function downloadFatura($id)
    {
        try {
            $faturaOriginal = Fatura::with([
                'cliente', 'user', 'items.produto', 'faturaRetificacao',
            ])->findOrFail($id);

            if (! $faturaOriginal->retificada) {
                return redirect()->back()->with('error', 'Este documento não foi retificado.');
            }

            $empresa = DadosEmpresa::first();
            $dados = $this->montarDadosNotaCredito($faturaOriginal, $empresa);

            $pdf = Pdf::loadView('pdf.notaCredito', ['dados' => $dados])
                ->setPaper('a4', 'portrait')
                ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

            return $pdf->download('NC-'.$faturaOriginal->numero.'.pdf');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro: '.$e->getMessage());
        }
    }

    /**
     * =========================================================================
     * VISUALIZAÇÃO E DOWNLOAD (Recibos de Liquidação - Tabela 'recibos')
     * =========================================================================
     */
    public function visualizarRecibo($id)
    {
        try {
            $reciboOriginal = Recibo::with([
                'cliente', 'user', 'items.produto', 'reciboRetificacao.items.produto',
            ])->findOrFail($id);

            if (! $reciboOriginal->retificado) {
                return redirect()->back()->with('error', 'Este recibo não foi retificado.');
            }

            $empresa = DadosEmpresa::first();
            $dados = $this->montarDadosNotaCreditoRecibo($reciboOriginal, $empresa);

            $pdf = Pdf::loadView('pdf.notaCredito', ['dados' => $dados])
                ->setPaper('a4', 'portrait')
                ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

            return $pdf->stream('NC-'.$reciboOriginal->numero.'.pdf');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro: '.$e->getMessage());
        }
    }

    public function downloadRecibo($id)
    {
        try {
            $reciboOriginal = Recibo::with(['cliente', 'reciboRetificacao'])->findOrFail($id);

            if (! $reciboOriginal->retificado) {
                return redirect()->back()->with('error', 'Este recibo não foi retificado.');
            }

            $empresa = DadosEmpresa::first();
            $dados = $this->montarDadosNotaCreditoRecibo($reciboOriginal, $empresa);

            $pdf = Pdf::loadView('pdf.notaCredito', ['dados' => $dados])
                ->setPaper('a4', 'portrait');

            return $pdf->download('NC-'.$reciboOriginal->numero.'.pdf');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro: '.$e->getMessage());
        }
    }

    /**
     * =========================================================================
     * PREPARAÇÃO DE DADOS (Helpers)
     * =========================================================================
     */

    // ✅ Helper para FATURAS e FATURAS-RECIBO
    private function montarDadosNotaCredito($docOriginal, $empresa)
    {
        $docRetificacao = $docOriginal->faturaRetificacao;

        // Determinar o label correto baseado no tipo_documento (FT, FR)
        $tipoLabel = match ($docOriginal->tipo_documento) {
            'FR' => 'NOTA DE CRÉDITO - FATURA RECIBO',
            'FT' => 'NOTA DE CRÉDITO - FATURA',
            'FP' => 'CORREÇÃO DE PROFORMA', // Raro acontecer retificação em FP, mas previne erro
            default => 'NOTA DE CRÉDITO'
        };

        return [
            'tipo_label' => $tipoLabel,
            'numero_nota_credito' => 'NC-'.$docOriginal->numero,
            'data_emissao_nota' => now()->format('Y-m-d'),

            'empresa' => $this->formatarEmpresa($empresa),
            'cliente' => $this->formatarCliente($docOriginal->cliente),

            'retificacao' => [
                'data' => $docOriginal->data_retificacao ? $docOriginal->data_retificacao->format('d/m/Y H:i') : '-',
                'motivo' => $docOriginal->motivo_retificacao ?? 'Correção de valores/itens',
                'usuario' => $docOriginal->user->name ?? 'Sistema',
            ],

            // Padronizado como 'documento_anulado'
            'documento_anulado' => [
                'tipo' => $docOriginal->tipo_legivel ?? 'Documento',
                'numero' => $docOriginal->numero,
                'data_emissao' => $docOriginal->data_emissao->format('d/m/Y'),
                'subtotal' => (float) $docOriginal->subtotal,
                'total_impostos' => (float) $docOriginal->total_impostos,
                'total' => (float) $docOriginal->total,
                'produtos' => $this->formatarProdutos($docOriginal->items),
                'resumo_impostos' => $this->calcularResumoImpostos($docOriginal->items),
            ],

            // Padronizado como 'documento_retificacao' (A nova versão válida)
            'documento_retificacao' => $docRetificacao ? [
                'numero' => $docRetificacao->numero,
                'data_emissao' => $docRetificacao->data_emissao->format('d/m/Y'),
                'subtotal' => (float) $docRetificacao->subtotal,
                'total_impostos' => (float) $docRetificacao->total_impostos,
                'total' => (float) $docRetificacao->total,
                'produtos' => $this->formatarProdutos($docRetificacao->items),
                'resumo_impostos' => $this->calcularResumoImpostos($docRetificacao->items),
            ] : null,
        ];
    }

    // ✅ Helper para RECIBOS (RC - Liquidação)
    private function montarDadosNotaCreditoRecibo($reciboOriginal, $empresa)
    {
        $reciboRetificacao = $reciboOriginal->reciboRetificacao;

        return [
            'tipo_label' => 'NOTA DE CRÉDITO - RECIBO',
            'numero_nota_credito' => 'NC-'.$reciboOriginal->numero,
            'data_emissao_nota' => now()->format('Y-m-d'),

            'empresa' => $this->formatarEmpresa($empresa),
            'cliente' => $this->formatarCliente($reciboOriginal->cliente),

            'retificacao' => [
                'data' => $reciboOriginal->data_retificacao ? $reciboOriginal->data_retificacao->format('d/m/Y H:i') : '-',
                'motivo' => $reciboOriginal->motivo_retificacao ?? 'Correção do recibo',
                'usuario' => $reciboOriginal->user->name ?? 'Sistema',
            ],

            'documento_anulado' => [
                'tipo' => 'Recibo',
                'numero' => $reciboOriginal->numero,
                'data_emissao' => $reciboOriginal->data_emissao->format('d/m/Y'),
                'subtotal' => (float) ($reciboOriginal->subtotal ?? $reciboOriginal->valor),
                'total_impostos' => 0, // Recibos RC geralmente não têm imposto discriminado assim
                'total' => (float) $reciboOriginal->valor,
                'produtos' => $this->formatarProdutos($reciboOriginal->items),
                'resumo_impostos' => [], // Recibo de liquidação não incide IVA novamente
            ],

            'documento_retificacao' => $reciboRetificacao ? [
                'numero' => $reciboRetificacao->numero,
                'data_emissao' => $reciboRetificacao->data_emissao->format('d/m/Y'),
                'total' => (float) $reciboRetificacao->valor,
                'produtos' => $this->formatarProdutos($reciboRetificacao->items),
            ] : null,
        ];
    }

    // --- MÉTODOS DE FORMATAÇÃO E CÁLCULO ---

    private function formatarEmpresa($empresa)
    {
        return [
            'nome' => $empresa->name ?? 'Nome da Empresa',
            'nif' => $empresa->nif ?? '999999999',
            'telefone' => $empresa->telefone ?? '',
            'email' => $empresa->email ?? '',
            'endereco' => $empresa->rua ?? '',
            'edificio' => $empresa->edificio ?? '',
            'cidade' => $empresa->cidade ?? 'Luanda',
            'banco' => $empresa->nomeDoBanco ?? '',
            'iban' => $empresa->iban ?? '',
        ];
    }

    private function formatarCliente($cliente)
    {
        if (! $cliente) {
            return null;
        }

        return [
            'id' => $cliente->id,
            'nome' => $cliente->nome,
            'nif' => $cliente->nif,
            'localizacao' => $cliente->localizacao ?? $cliente->endereco ?? '',
            'cidade' => $cliente->cidade ?? '',
            'provincia' => $cliente->provincia ?? '',
            'telefone' => $cliente->telefone ?? '',
        ];
    }

    private function formatarProdutos($items)
    {
        $produtos = [];
        foreach ($items as $item) {
            // Suporte para ambos os models (FaturaItem e ReciboItem)
            $produtoRelacao = $item->produto;

            // Taxas
            if ($item->motivo_isencaos_id) {
                $taxa = 0;
            } elseif ($item->imposto_id) {
                $taxa = (float) $item->taxa_iva;
            } else {
                $taxa = 14; // Default
            }

            $produtos[] = [
                'codigo' => $item->codigo_barras ?? ($produtoRelacao->codigo_barras ?? '-'),
                'descricao' => $item->descricao ?? ($produtoRelacao->descricao ?? 'Item'),
                'quantidade' => $item->quantidade,
                'preco_unitario' => (float) $item->preco_unitario,
                'taxa_iva' => $taxa,
                'valor_iva' => (float) ($item->valor_iva ?? 0),
                'total' => (float) ($item->total ?? ($item->preco_unitario * $item->quantidade)),
            ];
        }

        return $produtos;
    }

    private function calcularResumoImpostos($items)
    {
        $resumo = [];
        foreach ($items as $item) {
            $taxa = (float) ($item->taxa_iva ?? 14);
            $chave = (string) $taxa;

            if (! isset($resumo[$chave])) {
                $resumo[$chave] = [
                    'descricao' => $taxa == 0 ? 'Isento' : 'IVA '.number_format($taxa, 0).'%',
                    'incidencia' => 0,
                    'valor_imposto' => 0,
                ];
            }
            // Usa ?? 0 para prevenir erros em recibos legados que podem não ter esses campos
            $subtotalItem = $item->subtotal ?? ($item->preco_unitario * $item->quantidade);
            $ivaItem = $item->valor_iva ?? ($taxa > 0 ? $subtotalItem * ($taxa / 100) : 0);

            $resumo[$chave]['incidencia'] += (float) $subtotalItem;
            $resumo[$chave]['valor_imposto'] += (float) $ivaItem;
        }

        return array_values($resumo);
    }

    /**
     * ✅ Analisar Diferença entre Produtos (Original vs Retificação)
     */
    private function analisarDiferencaProdutos($itemsOriginais, $itemsRetificacao)
    {
        $analise = [
            'produtos_removidos' => [],
            'produtos_adicionados' => [],
            'produtos_alterados' => [],
        ];

        // Mapear produtos originais por ID
        $mapaOriginais = [];
        foreach ($itemsOriginais as $item) {
            $mapaOriginais[$item->produto_id] = $item;
        }

        // Mapear produtos retificados por ID
        $mapaRetificacao = [];
        foreach ($itemsRetificacao as $item) {
            $mapaRetificacao[$item->produto_id] = $item;
        }

        // Identificar produtos removidos
        foreach ($mapaOriginais as $produtoId => $itemOriginal) {
            if (! isset($mapaRetificacao[$produtoId])) {
                $analise['produtos_removidos'][] = [
                    'descricao' => $itemOriginal->descricao,
                    'quantidade' => $itemOriginal->quantidade,
                    'total' => (float) $itemOriginal->total,
                ];
            }
        }

        // Identificar produtos adicionados e alterados
        foreach ($mapaRetificacao as $produtoId => $itemRetificacao) {
            if (! isset($mapaOriginais[$produtoId])) {
                // Produto adicionado
                $analise['produtos_adicionados'][] = [
                    'descricao' => $itemRetificacao->descricao,
                    'quantidade' => $itemRetificacao->quantidade,
                    'total' => (float) $itemRetificacao->total,
                ];
            } else {
                // Produto existente - verificar alterações
                $itemOriginal = $mapaOriginais[$produtoId];

                if ($itemOriginal->quantidade != $itemRetificacao->quantidade ||
                    $itemOriginal->preco_unitario != $itemRetificacao->preco_unitario) {

                    $analise['produtos_alterados'][] = [
                        'descricao' => $itemRetificacao->descricao,
                        'quantidade_original' => $itemOriginal->quantidade,
                        'quantidade_nova' => $itemRetificacao->quantidade,
                        'preco_original' => (float) $itemOriginal->preco_unitario,
                        'preco_novo' => (float) $itemRetificacao->preco_unitario,
                        'total_original' => (float) $itemOriginal->total,
                        'total_novo' => (float) $itemRetificacao->total,
                        'diferenca' => (float) $itemRetificacao->total - (float) $itemOriginal->total,
                    ];
                }
            }
        }

        return $analise;
    }

    public function gerarPDF($dados)
    {
        $pdf = PDF::loadView('pdf.notaCredito', ['dados' => $dados]);

        return $pdf->stream('NC-'.$dados['numero_nota_credito'].'.pdf');
    }
}
