<?php

namespace App\Http\Controllers;

use App\Models\Fatura;
use App\Models\Recibo;
use App\Models\DadosEmpresa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class NotaCreditoController extends Controller
{
    /**
     * Exibir Nota de Crédito de uma Fatura Retificada
     */
    public function visualizarFatura($id)
    {
        // ✅ PASSO 1: Buscar Fatura Retificada com TODOS os relacionamentos
        $faturaOriginal = Fatura::with([
            'cliente',
            'user',
            'items.produto.categoria',
            'items.produto.imposto',
            'items.produto.motivoIsencao',
            'faturaRetificacao.cliente',
            'faturaRetificacao.user',
            'faturaRetificacao.items.produto.categoria',
            'faturaRetificacao.items.produto.imposto',
            'faturaRetificacao.items.produto.motivoIsencao',
        ])->findOrFail($id);

        // ✅ PASSO 2: Validar se é uma fatura retificada
        if (!$faturaOriginal->retificada) {
            return redirect()->back()->with('error', 'Esta fatura não foi retificada.');
        }

        // ✅ PASSO 3: Buscar dados da empresa
        $empresa = DadosEmpresa::first();

        // ✅ PASSO 4: Montar objeto completo de dados
        $dadosNotaCredito = $this->montarDadosNotaCredito($faturaOriginal, $empresa);

        // ✅ PASSO 5: Retornar view ou PDF
        return view('pdf.notaCredito', [
            'dados' => $dadosNotaCredito
        ]);

        // OU gerar PDF diretamente:
        // return $this->gerarPDF($dadosNotaCredito);
    }

    /**
     * Visualizar Nota de Crédito de um Recibo Retificado
     */
    public function visualizarRecibo($id)
    {
        $reciboOriginal = Recibo::with([
            'cliente',
            'user',
            'items.produto.categoria',
            'items.produto.imposto',
            'items.produto.motivoIsencao',
            'reciboRetificacao.cliente',
            'reciboRetificacao.user',
            'reciboRetificacao.items.produto.categoria',
            'reciboRetificacao.items.produto.imposto',
            'reciboRetificacao.items.produto.motivoIsencao',
        ])->findOrFail($id);

        if (!$reciboOriginal->retificado) {
            return redirect()->back()->with('error', 'Este recibo não foi retificado.');
        }

        $empresa = DadosEmpresa::first();
        $dadosNotaCredito = $this->montarDadosNotaCreditoRecibo($reciboOriginal, $empresa);

        return view('pdf.notaCredito', [
            'dados' => $dadosNotaCredito
        ]);
    }

    /**
     * ✅ MÉTODO PRINCIPAL: Montar Estrutura Completa de Dados
     */
    private function montarDadosNotaCredito($faturaOriginal, $empresa)
    {
        $faturaRetificacao = $faturaOriginal->faturaRetificacao;

        return [
            // ========== INFORMAÇÕES DA NOTA DE CRÉDITO ==========
            'tipo_documento' => 'nota_credito_fatura',
            'numero_nota_credito' => 'NC-' . $faturaOriginal->numero,
            'data_emissao_nota' => now()->format('Y-m-d'),

            // ========== EMPRESA ==========
            'empresa' => [
                'nome' => $empresa->name ?? '',
                'nif' => $empresa->nif ?? '',
                'telefone' => $empresa->telefone ?? '',
                'email' => $empresa->email ?? '',
                'website' => $empresa->website ?? '',
                'endereco' => $empresa->rua ?? '',
                'cidade' => $empresa->cidade ?? '',
                'provincia' => $empresa->municipio ?? '',
                'logo' => $empresa->logo ?? null,
            ],

            // ========== CLIENTE ==========
            'cliente' => [
                'id' => $faturaOriginal->cliente->id,
                'nome' => $faturaOriginal->cliente->nome,
                'nif' => $faturaOriginal->cliente->nif,
                'telefone' => $faturaOriginal->cliente->telefone ?? '',
                'endereco' => $faturaOriginal->cliente->localizacao ?? '',
                'cidade' => $faturaOriginal->cliente->cidade ?? '',
                'provincia' => $faturaOriginal->cliente->provincia ?? '',
            ],

            // ========== DADOS DA RETIFICAÇÃO ==========
            'retificacao' => [
                'data' => $faturaOriginal->data_retificacao->format('d/m/Y H:i'),
                'motivo' => $faturaOriginal->motivo_retificacao ?? 'Não informado',
                'usuario' => $faturaOriginal->user->name ?? 'Sistema',
            ],

            // ========== FATURA ORIGINAL (Anulada) ==========
            'fatura_original' => [
                'numero' => $faturaOriginal->numero,
                'data_emissao' => $faturaOriginal->data_emissao->format('d/m/Y'),
                'estado' => 'RETIFICADA',
                'subtotal' => (float) $faturaOriginal->subtotal,
                'total_impostos' => (float) $faturaOriginal->total_impostos,
                'total' => (float) $faturaOriginal->total,
                'produtos' => $this->formatarProdutos($faturaOriginal->items),
                'resumo_impostos' => $this->calcularResumoImpostos($faturaOriginal->items),
            ],

            // ========== FATURA RETIFICAÇÃO (Nova/Válida) ==========
            'fatura_retificacao' => $faturaRetificacao ? [
                'numero' => $faturaRetificacao->numero,
                'data_emissao' => $faturaRetificacao->data_emissao->format('d/m/Y'),
                'estado' => 'VÁLIDA',
                'subtotal' => (float) $faturaRetificacao->subtotal,
                'total_impostos' => (float) $faturaRetificacao->total_impostos,
                'total' => (float) $faturaRetificacao->total,
                'produtos' => $this->formatarProdutos($faturaRetificacao->items),
                'resumo_impostos' => $this->calcularResumoImpostos($faturaRetificacao->items),
            ] : null,

            // ========== COMPARATIVO (Diferenças) ==========
            'comparativo' => $faturaRetificacao ? [
                'diferenca_subtotal' => (float) $faturaRetificacao->subtotal - (float) $faturaOriginal->subtotal,
                'diferenca_impostos' => (float) $faturaRetificacao->total_impostos - (float) $faturaOriginal->total_impostos,
                'diferenca_total' => (float) $faturaRetificacao->total - (float) $faturaOriginal->total,
                'percentual_variacao' => $this->calcularPercentualVariacao(
                    $faturaOriginal->total,
                    $faturaRetificacao->total
                ),
            ] : null,

            // ========== ANÁLISE DE PRODUTOS ==========
            'analise_produtos' => $faturaRetificacao ? 
                $this->analisarDiferencaProdutos($faturaOriginal->items, $faturaRetificacao->items) 
                : null,
        ];
    }

    /**
     * ✅ Formatar Produtos para Exibição
     */
    private function formatarProdutos($items)
    {
        $produtos = [];

        foreach ($items as $item) {
            $produto = $item->produto;

            // Determinar taxa e imposto
            if ($item->motivo_isencaos_id) {
                $taxaIva = 0;
                $descricaoImposto = 'Isento';
                $motivoIsencao = $item->motivoIsencao->descricao ?? 'N/A';
            } elseif ($item->imposto_id) {
                $taxaIva = (float) $item->taxa_iva;
                $descricaoImposto = $item->imposto->descricao ?? 'IVA';
                $motivoIsencao = null;
            } else {
                $taxaIva = 14;
                $descricaoImposto = 'IVA';
                $motivoIsencao = null;
            }

            $produtos[] = [
                'id' => $produto->id,
                'codigo' => $item->codigo_barras,
                'descricao' => $item->descricao,
                'categoria' => $produto->categoria->nome ?? 'Sem categoria',
                'quantidade' => $item->quantidade,
                'unidade' => 'UN',
                'preco_unitario' => (float) $item->preco_unitario,
                'subtotal' => (float) $item->subtotal,
                'taxa_iva' => $taxaIva,
                'valor_iva' => (float) $item->valor_iva,
                'total' => (float) $item->total,
                'descricao_imposto' => $descricaoImposto,
                'motivo_isencao' => $motivoIsencao,
            ];
        }

        return $produtos;
    }

    /**
     * ✅ Calcular Resumo de Impostos Agrupados
     */
    private function calcularResumoImpostos($items)
    {
        $resumo = [];

        foreach ($items as $item) {
            $taxa = (float) $item->taxa_iva;
            
            // Determinar descrição
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

            // Chave de agrupamento
            $chave = $taxa . '|' . $descricao;

            // Inicializar grupo se não existir
            if (!isset($resumo[$chave])) {
                $resumo[$chave] = [
                    'taxa' => $taxa,
                    'descricao' => $descricao,
                    'motivo_isencao' => $motivoIsencao,
                    'incidencia' => 0,
                    'valor_imposto' => 0,
                ];
            }

            // Acumular valores
            $resumo[$chave]['incidencia'] += (float) $item->subtotal;
            $resumo[$chave]['valor_imposto'] += (float) $item->valor_iva;
        }

        return array_values($resumo);
    }

    /**
     * ✅ Calcular Percentual de Variação
     */
    private function calcularPercentualVariacao($valorOriginal, $valorNovo)
    {
        if ($valorOriginal == 0) {
            return 0;
        }

        return (($valorNovo - $valorOriginal) / $valorOriginal) * 100;
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
            if (!isset($mapaRetificacao[$produtoId])) {
                $analise['produtos_removidos'][] = [
                    'descricao' => $itemOriginal->descricao,
                    'quantidade' => $itemOriginal->quantidade,
                    'total' => (float) $itemOriginal->total,
                ];
            }
        }

        // Identificar produtos adicionados e alterados
        foreach ($mapaRetificacao as $produtoId => $itemRetificacao) {
            if (!isset($mapaOriginais[$produtoId])) {
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

    /**
     * ✅ Montar Dados para Recibo (Estrutura Similar)
     */
    private function montarDadosNotaCreditoRecibo($reciboOriginal, $empresa)
    {
        $reciboRetificacao = $reciboOriginal->reciboRetificacao;

        return [
            'tipo_documento' => 'nota_credito_recibo',
            'numero_nota_credito' => 'NC-' . $reciboOriginal->numero,
            'data_emissao_nota' => now()->format('Y-m-d'),
            'empresa' => [
                'nome' => $empresa->name ?? '',
                'nif' => $empresa->nif ?? '',
                // ... outros dados
            ],
            'cliente' => [
                'nome' => $reciboOriginal->cliente->nome,
                'nif' => $reciboOriginal->cliente->nif,
                // ... outros dados
            ],
            'retificacao' => [
                'data' => $reciboOriginal->data_retificacao->format('d/m/Y H:i'),
                'motivo' => $reciboOriginal->motivo_retificacao ?? 'Não informado',
                'usuario' => $reciboOriginal->user->name ?? 'Sistema',
            ],
            'recibo_original' => [
                'numero' => $reciboOriginal->numero,
                'data_emissao' => $reciboOriginal->data_emissao->format('d/m/Y'),
                'valor' => (float) $reciboOriginal->valor,
                'metodo_pagamento' => $reciboOriginal->metodo_pagamento,
                'produtos' => $this->formatarProdutos($reciboOriginal->items),
            ],
            'recibo_retificacao' => $reciboRetificacao ? [
                'numero' => $reciboRetificacao->numero,
                'data_emissao' => $reciboRetificacao->data_emissao->format('d/m/Y'),
                'valor' => (float) $reciboRetificacao->valor,
                'metodo_pagamento' => $reciboRetificacao->metodo_pagamento,
                'produtos' => $this->formatarProdutos($reciboRetificacao->items),
            ] : null,
            'comparativo' => $reciboRetificacao ? [
                'diferenca_total' => (float) $reciboRetificacao->valor - (float) $reciboOriginal->valor,
                'percentual_variacao' => $this->calcularPercentualVariacao(
                    $reciboOriginal->valor,
                    $reciboRetificacao->valor
                ),
            ] : null,
            'analise_produtos' => $reciboRetificacao ?
                $this->analisarDiferencaProdutos($reciboOriginal->items, $reciboRetificacao->items)
                : null,
        ];
    }

    /**
     * ✅ Gerar PDF da Nota de Crédito
     */
    public function gerarPDF($dados)
    {
        $pdf = PDF::loadView('pdf.nota-credito', ['dados' => $dados]);
        
        return $pdf->download('nota-credito-' . $dados['numero_nota_credito'] . '.pdf');
        
        // OU exibir no navegador:
        // return $pdf->stream('nota-credito.pdf');
    }
}