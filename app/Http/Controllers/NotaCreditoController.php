<?php

namespace App\Http\Controllers;

use App\Models\DadosEmpresa;
use App\Models\Fatura;
use App\Models\Recibo;
use Barryvdh\DomPDF\Facade\Pdf;

class NotaCreditoController extends Controller
{
    /**
     * ✅ VISUALIZAR: Abre a Nota de Crédito da Fatura no navegador (Stream)
     */
    public function visualizarFatura($id)
    {
        try {
            // 1. Buscar dados
            $faturaOriginal = Fatura::with([
                'cliente', 'user',
                'items.produto.categoria', 'items.produto.imposto', 'items.produto.motivoIsencao',
                'faturaRetificacao.cliente', 'faturaRetificacao.user',
                'faturaRetificacao.items.produto.categoria', 'faturaRetificacao.items.produto.imposto', 'faturaRetificacao.items.produto.motivoIsencao',
            ])->findOrFail($id);

            if (! $faturaOriginal->retificada) {
                return redirect()->back()->with('error', 'Esta fatura não foi retificada.');
            }

            $empresa = DadosEmpresa::first();

            // 2. Montar array de dados (usando o método padronizado que criamos antes)
            $dados = $this->montarDadosNotaCredito($faturaOriginal, $empresa);

            // 3. Configurar e Gerar PDF
            $pdf = Pdf::loadView('pdf.notaCredito', ['dados' => $dados])
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'defaultFont' => 'sans-serif',
                    'isFontSubsettingEnabled' => true,
                ]);

            // 4. Retornar Stream (Visualizar na aba)
            return $pdf->stream('NC-Fatura-'.$faturaOriginal->numero.'.pdf');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao gerar PDF: '.$e->getMessage());
        }
    }

    /**
     * ✅ DOWNLOAD: Baixa a Nota de Crédito da Fatura diretamente
     */
    public function downloadFatura($id)
    {
        try {
            $faturaOriginal = Fatura::with([
                'cliente', 'user',
                'items.produto.categoria', 'items.produto.imposto', 'items.produto.motivoIsencao',
                'faturaRetificacao', // Simplificado pois o montarDados busca o resto
            ])->findOrFail($id);

            if (! $faturaOriginal->retificada) {
                return redirect()->back()->with('error', 'Esta fatura não foi retificada.');
            }

            $empresa = DadosEmpresa::first();
            $dados = $this->montarDadosNotaCredito($faturaOriginal, $empresa);

            $pdf = Pdf::loadView('pdf.notaCredito', ['dados' => $dados])
                ->setPaper('a4', 'portrait')
                ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

            // Retornar Download forçado
            return $pdf->download('NC-Fatura-'.$faturaOriginal->numero.'.pdf');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao baixar PDF: '.$e->getMessage());
        }
    }

    /**
     * ✅ VISUALIZAR: Abre a Nota de Crédito do Recibo no navegador (Stream)
     */
    public function visualizarRecibo($id)
    {
        try {
            $reciboOriginal = Recibo::with([
                'cliente', 'user',
                'items.produto.categoria', 'items.produto.imposto', 'items.produto.motivoIsencao',
                'reciboRetificacao.cliente', 'reciboRetificacao.user',
                'reciboRetificacao.items.produto.categoria', 'reciboRetificacao.items.produto.imposto', 'reciboRetificacao.items.produto.motivoIsencao',
            ])->findOrFail($id);

            if (! $reciboOriginal->retificado) {
                return redirect()->back()->with('error', 'Este recibo não foi retificado.');
            }

            $empresa = DadosEmpresa::first();
            $dados = $this->montarDadosNotaCreditoRecibo($reciboOriginal, $empresa);

            $pdf = Pdf::loadView('pdf.notaCredito', ['dados' => $dados])
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'defaultFont' => 'sans-serif',
                ]);

            return $pdf->stream('NC-Recibo-'.$reciboOriginal->numero.'.pdf');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao gerar PDF: '.$e->getMessage());
        }
    }

    /**
     * ✅ DOWNLOAD: Baixa a Nota de Crédito do Recibo diretamente
     */
    public function downloadRecibo($id)
    {
        try {
            $reciboOriginal = Recibo::with([
                'cliente', 'user',
                'items.produto',
                'reciboRetificacao',
            ])->findOrFail($id);

            if (! $reciboOriginal->retificado) {
                return redirect()->back()->with('error', 'Este recibo não foi retificado.');
            }

            $empresa = DadosEmpresa::first();
            $dados = $this->montarDadosNotaCreditoRecibo($reciboOriginal, $empresa);

            $pdf = Pdf::loadView('pdf.notaCredito', ['dados' => $dados])
                ->setPaper('a4', 'portrait')
                ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

            return $pdf->download('NC-Recibo-'.$reciboOriginal->numero.'.pdf');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao baixar PDF: '.$e->getMessage());
        }
    }

    /**
     * ✅ FATURA: Padronizada para usar as chaves genéricas
     */
    private function montarDadosNotaCredito($faturaOriginal, $empresa)
    {
        $faturaRetificacao = $faturaOriginal->faturaRetificacao;

        // Formatar produtos
        $produtos_original = $this->formatarProdutos($faturaOriginal->items);
        $resumo_impostos_original = $this->calcularResumoImpostos($faturaOriginal->items);

        $produtos_retificacao = $faturaRetificacao ? $this->formatarProdutos($faturaRetificacao->items) : [];
        $resumo_impostos_retificacao = $faturaRetificacao ? $this->calcularResumoImpostos($faturaRetificacao->items) : [];

        // Análise de diferenças
        $analise = $faturaRetificacao ? $this->analisarDiferencaProdutos($faturaOriginal->items, $faturaRetificacao->items) : [];

        return [
            'tipo_documento' => 'nota_credito_fatura', // Usado para lógica interna
            'tipo_label' => 'NOTA DE CRÉDITO - FATURA', // Usado na View
            'numero_nota_credito' => 'NC-'.$faturaOriginal->numero,
            'data_emissao_nota' => now()->format('Y-m-d'),

            // Empresa e Cliente (Estrutura Padrão)
            'empresa' => $this->formatarEmpresa($empresa),
            'cliente' => $this->formatarCliente($faturaOriginal->cliente),

            // Informações da Retificação
            'retificacao' => [
                'data' => $faturaOriginal->data_retificacao ? $faturaOriginal->data_retificacao->format('d/m/Y H:i') : now()->format('d/m/Y'),
                'motivo' => $faturaOriginal->motivo_retificacao ?? $faturaOriginal->motivo_anulacao ?? 'Correção de Fatura',
                'usuario' => $faturaOriginal->user->name ?? 'Sistema',
            ],

            // ✅ PADRONIZAÇÃO: Chamei de 'documento_anulado' igual ao recibo
            'documento_anulado' => [
                'tipo' => 'Fatura Original',
                'numero' => $faturaOriginal->numero,
                'data_emissao' => $faturaOriginal->data_emissao->format('d/m/Y'),
                'subtotal' => (float) $faturaOriginal->subtotal,
                'total_impostos' => (float) $faturaOriginal->total_impostos,
                'total' => (float) $faturaOriginal->total,
                'produtos' => $produtos_original,
                'resumo_impostos' => $resumo_impostos_original,
            ],

            // ✅ PADRONIZAÇÃO: Chamei de 'documento_retificacao' igual ao recibo
            'documento_retificacao' => $faturaRetificacao ? [
                'tipo' => 'Fatura Nova',
                'numero' => $faturaRetificacao->numero,
                'data_emissao' => $faturaRetificacao->data_emissao->format('d/m/Y'),
                'subtotal' => (float) $faturaRetificacao->subtotal,
                'total_impostos' => (float) $faturaRetificacao->total_impostos,
                'total' => (float) $faturaRetificacao->total,
                'produtos' => $produtos_retificacao,
                'resumo_impostos' => $resumo_impostos_retificacao,
            ] : null,

            'analise_produtos' => $analise,
        ];
    }

    /**
     * ✅ RECIBO: Padronizada e Limpa
     */
    private function montarDadosNotaCreditoRecibo($reciboOriginal, $empresa)
    {
        $reciboRetificacao = $reciboOriginal->reciboRetificacao;

        $produtos_original = $this->formatarProdutos($reciboOriginal->items);
        $resumo_impostos_original = $this->calcularResumoImpostos($reciboOriginal->items);

        $produtos_retificacao = $reciboRetificacao ? $this->formatarProdutos($reciboRetificacao->items) : [];
        $resumo_impostos_retificacao = $reciboRetificacao ? $this->calcularResumoImpostos($reciboRetificacao->items) : [];

        $analise = $reciboRetificacao ? $this->analisarDiferencaProdutos($reciboOriginal->items, $reciboRetificacao->items) : [];

        return [
            'tipo_documento' => 'nota_credito_recibo',
            'tipo_label' => 'NOTA DE CRÉDITO - RECIBO',
            'numero_nota_credito' => 'NC-'.$reciboOriginal->numero,
            'data_emissao_nota' => now()->format('Y-m-d'),

            'empresa' => $this->formatarEmpresa($empresa),
            'cliente' => $this->formatarCliente($reciboOriginal->cliente),

            'retificacao' => [
                'data' => $reciboOriginal->data_retificacao ? $reciboOriginal->data_retificacao->format('d/m/Y H:i') : now()->format('d/m/Y'),
                'motivo' => $reciboOriginal->motivo_retificacao ?? $reciboOriginal->motivo_anulacao ?? 'Correção de Recibo',
                'usuario' => $reciboOriginal->user->name ?? 'Sistema',
            ],

            'documento_anulado' => [
                'tipo' => 'Recibo Original',
                'numero' => $reciboOriginal->numero,
                'data_emissao' => $reciboOriginal->data_emissao ? $reciboOriginal->data_emissao->format('d/m/Y') : null,
                'subtotal' => (float) ($reciboOriginal->subtotal ?? 0),
                'total_impostos' => (float) ($reciboOriginal->total_impostos ?? 0),
                'total' => (float) ($reciboOriginal->valor ?? 0),
                'produtos' => $produtos_original,
                'resumo_impostos' => $resumo_impostos_original,
            ],

            'documento_retificacao' => $reciboRetificacao ? [
                'tipo' => 'Recibo Novo',
                'numero' => $reciboRetificacao->numero,
                'data_emissao' => $reciboRetificacao->data_emissao ? $reciboRetificacao->data_emissao->format('d/m/Y') : null,
                'subtotal' => (float) ($reciboRetificacao->subtotal ?? 0),
                'total_impostos' => (float) ($reciboRetificacao->total_impostos ?? 0),
                'total' => (float) ($reciboRetificacao->valor ?? 0),
                'produtos' => $produtos_retificacao,
                'resumo_impostos' => $resumo_impostos_retificacao,
            ] : null,

            'analise_produtos' => $analise,
        ];
    }

    // --- Helpers de Formatação para evitar repetição de código ---

    private function formatarEmpresa($empresa)
    {
        return [
            'nome' => $empresa->name ?? '',
            'nif' => $empresa->nif ?? '',
            'telefone' => $empresa->telefone ?? '',
            'email' => $empresa->email ?? '',
            'endereco' => $empresa->rua ?? '',
            'edificio' => $empresa->edificio ?? '', // Adicionado
            'cidade' => $empresa->cidade ?? '',
            'banco' => $empresa->nomeDoBanco ?? $empresa->banco ?? '', // Fallback duplo
            'iban' => $empresa->iban ?? '',
        ];
    }

    private function formatarCliente($cliente)
    {
        if (! $cliente) {
            return [];
        }

        return [
            'id' => $cliente->id,
            'nome' => $cliente->nome,
            'nif' => $cliente->nif,
            'telefone' => $cliente->telefone ?? '',
            'localizacao' => $cliente->localizacao ?? $cliente->endereco ?? '', // Padronizei para localizacao
            'cidade' => $cliente->cidade ?? '',
            'provincia' => $cliente->provincia ?? '',
        ];
    }

    private function formatarProdutos($items)
    {
        $produtos = [];
        foreach ($items as $item) {
            $produto = $item->produto;

            // Lógica Unificada de Imposto
            if ($item->motivo_isencaos_id) {
                $taxa = 0;
                $descImp = 'Isento';
            } elseif ($item->imposto_id) {
                $taxa = (float) $item->taxa_iva;
                $descImp = 'IVA '.number_format($taxa, 0).'%';
            } else {
                $taxa = 14;
                $descImp = 'IVA 14%';
            }

            $produtos[] = [
                'codigo' => $item->codigo_barras ?? $produto->codigo_barras,
                'descricao' => $item->descricao ?? $produto->descricao,
                'quantidade' => $item->quantidade,
                'unidade' => 'UN',
                'preco_unitario' => (float) $item->preco_unitario,
                'desconto' => 0, // Se tiver campo desconto no item, adicione aqui
                'taxa_iva' => $taxa,
                'valor_iva' => (float) $item->valor_iva,
                'total' => (float) $item->total ?? ($item->subtotal + $item->valor_iva),
                'descricao_imposto' => $descImp,
            ];
        }

        return $produtos;
    }

    private function calcularResumoImpostos($items)
    {
        $resumo = [];
        foreach ($items as $item) {
            $taxa = (float) $item->taxa_iva;
            $chave = (string) $taxa;

            if (! isset($resumo[$chave])) {
                $resumo[$chave] = [
                    'taxa' => $taxa,
                    'descricao' => $taxa == 0 ? 'Isento' : 'IVA '.number_format($taxa, 0).'%',
                    'incidencia' => 0,
                    'valor_imposto' => 0,
                ];
            }
            $resumo[$chave]['incidencia'] += (float) $item->subtotal;
            $resumo[$chave]['valor_imposto'] += (float) $item->valor_iva;
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
